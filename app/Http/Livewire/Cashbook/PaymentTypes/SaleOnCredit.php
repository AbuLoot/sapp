<?php

namespace App\Http\Livewire\Cashbook\PaymentTypes;

use Livewire\Component;

use App\Http\Livewire\Cashbook\Index as CashbookIndex;
use App\Models\PaymentType;
use App\Models\User;
use App\Models\Profile;
use App\Models\Product;
use App\Models\DocType;
use App\Models\CashDoc;
use App\Models\StoreDoc;
use App\Models\IncomingOrder;
use App\Models\OutgoingDoc;

use App\Traits\GenerateDocNo;

class SaleOnCredit extends Component
{
    use GenerateDocNo;

    public $lang;
    public $company;
    public $search = '';
    public $sumOfCart;
    public $name;
    public $lastname;
    public $tel;
    public $email;
    public $address;

    protected $listeners = ['newUser'];

    protected $rules = [
        'name' => 'required|min:2',
        'lastname' => 'required|min:2',
        'tel' => 'required|min:11',
    ];

    public function mount()
    {
        $this->lang = app()->getLocale();
        $this->company = auth()->user()->profile->company;
        $this->sumOfCart = CashbookIndex::sumOfCart();
        $this->paymentType = PaymentType::where('slug', 'sale-on-credit')->first();
    }

    public function save()
    {
        $this->validate();

        $user = new User;
        $user->name = $this->name;
        $user->lastname = $this->lastname;
        $user->tel = $this->tel;
        $user->email = $this->email ?? null;
        $user->password = '';
        $user->address = $this->address;
        $user->save();

        session()->flash('message', 'Запись добавлена');

        $this->dispatchBrowserEvent('close-modal');
    }

    public function makeDebtDocs($id)
    {
        $user = User::findOrFail($id);

        $paymentDetail['typeId'] = $this->paymentType->id;
        $paymentDetail['userId'] = $user->id;
        $paymentDetail['debtSum'] = $this->sumOfCart['sumDiscounted'];
        $paymentDetail['incomingOrder'] = null;
        $paymentDetail['outgoingDoc'] = null;

        $productsData = [];
        $countInStores = [];
        $outgoingTotalCount = 0;
        $incomingTotalAmount = 0;

        $store = session()->get('store');
        $cashbook = session()->get('cashbook');
        $workplaceId = session()->get('cashdeskWorkplace');
        $cartProducts = session()->get('cartProducts');

        $contractorType = 'App\Models\User';
        $contractorId = $user->id;

        foreach($cartProducts as $productId => $cartProduct) {

            $product = Product::findOrFail($productId);

            $outgoingCount = $cartProduct['countInCart'];

            // If Order Not a Service
            if ($product->type == 1) {

                $countInStores = json_decode($cartProduct->count_in_stores, true) ?? [];
                $countInStore = $countInStores[$store->id] ?? 0;

                /**
                 * Prepare outgoing count & If outgoing count greater, assign $countInStore
                 */
                if ($countInStore >= 1 && $cartProduct['countInCart'] <= $countInStore) {
                    $outgoingCount = $cartProduct['countInCart'];
                } elseif ($countInStore < $cartProduct['countInCart']) {
                    $outgoingCount = $countInStore;
                }

                $stockCount = $countInStore - $outgoingCount;
                $countInStores[$store->id] = $stockCount;
                $amountCount = collect($countInStores)->sum();

                $product->count_in_stores = json_encode($countInStores);
                $product->count = $amountCount;
                $product->save();
            }

            $price = session()->get('priceMode') == 'retail' ? $product->price : $product->wholesale_price;
            $discount = session()->get('totalDiscount');

            if ($cartProduct->discount) {
                $discount = $cartProduct->discount;
            }

            $productsData[$productId]['store'] = $store->id;
            $productsData[$productId]['price'] = $price;
            $productsData[$productId]['outgoingCount'] = $outgoingCount;
            $productsData[$productId]['discount'] = $discount;
            $productsData[$productId]['barcodes'] = json_decode($product->barcodes, true);

            $incomingTotalAmount = $incomingTotalAmount + ($price * $outgoingCount);
            $outgoingTotalCount = $outgoingTotalCount + $outgoingCount;
        }

        // Incoming Order & Outgoing Doc
        $docTypes = DocType::whereIn('slug', ['forma-ko-1', 'forma-z-2'])->get();

        $cashDocNo = $this->generateIncomingCashDocNo($cashbook->id);
        $storeDocNo = $this->generateOutgoingStoreDocNo($store->id);

        // Cash Doc
        $incomingOrder = new IncomingOrder;
        $incomingOrder->cashbook_id = $cashbook->id;
        $incomingOrder->company_id = $this->company->id;
        $incomingOrder->user_id = auth()->user()->id;
        $incomingOrder->workplace_id = $workplaceId;
        $incomingOrder->doc_no = $cashDocNo;
        $incomingOrder->doc_type_id = $docTypes->where('slug', 'forma-ko-1')->first()->id;
        $incomingOrder->products_data = json_encode($productsData);
        $incomingOrder->contractor_type = $contractorType;
        $incomingOrder->contractor_id = $contractorId;
        $incomingOrder->payment_type_id = $paymentDetail['typeId'];
        $incomingOrder->payment_detail = json_encode($paymentDetail);
        $incomingOrder->sum = 0;
        $incomingOrder->currency = $this->company->currency->code;
        $incomingOrder->count = $this->sumOfCart['totalCount'];
        $incomingOrder->save();

        // Store Doc
        $outgoingDoc = new OutgoingDoc;
        $outgoingDoc->store_id = $store->id;
        $outgoingDoc->company_id = $this->company->id;
        $outgoingDoc->user_id = auth()->user()->id;
        $outgoingDoc->doc_no = $storeDocNo;
        $outgoingDoc->doc_type_id = $docTypes->where('slug', 'forma-z-2')->first()->id;
        $outgoingDoc->products_data = json_encode($productsData);
        $outgoingDoc->contractor_type = $contractorType;
        $outgoingDoc->contractor_id = $contractorId;
        $outgoingDoc->sum = 0;
        $outgoingDoc->currency = $this->company->currency->code;
        $outgoingDoc->count = $outgoingTotalCount;
        $outgoingDoc->save();

        if (empty($user->profile)) {

            $profile = new Profile;
            $profile->user_id = $user->id;
            $profile->region_id = 1;
            $profile->is_debtor = true;
            $profile->debt_sum = $this->sumOfCart['sumDiscounted'];

            $debt_order[] = [
                'docNo' => $incomingOrder->doc_no,
                'sum' => $this->sumOfCart['sumDiscounted'],
            ];

            $profile->debt_orders = json_encode($debt_order);
            $profile->save();

        } else {
            $user->profile->is_debtor = true;
            $user->profile->debt_sum = $user->profile->debt_sum + $this->sumOfCart['sumDiscounted'];

            $debt_orders = json_decode($user->profile->debt_orders, true) ?? [];
            $debt_orders[] = [
                'docNo' => $incomingOrder->doc_no,
                'sum' => $this->sumOfCart['sumDiscounted'],
            ];

            $user->profile->debt_orders = json_encode($debt_orders);
            $user->profile->save();
        }

        // Cash Doc
        $cashDoc = new CashDoc;
        $cashDoc->cashbook_id = $cashbook->id;
        $cashDoc->company_id = $this->company->id;
        $cashDoc->user_id = auth()->user()->id;
        $cashDoc->order_type = 'App\Models\IncomingOrder';
        $cashDoc->order_id = $incomingOrder->id;
        $cashDoc->doc_id = $outgoingDoc->id;
        $cashDoc->contractor_type = $contractorType;
        $cashDoc->contractor_id = $contractorId;
        $cashDoc->incoming_amount = 0;
        $cashDoc->outgoing_amount = 0;
        $cashDoc->sum = $this->sumOfCart['sumDiscounted'];
        $cashDoc->currency = $this->company->currency->code;
        $cashDoc->save();

        // Store Doc
        $storeDoc = new StoreDoc;
        $storeDoc->store_id = $store->id;
        $storeDoc->company_id = $this->company->id;
        $storeDoc->user_id = auth()->user()->id;
        $storeDoc->doc_type = 'App\Models\OutgoingDoc';
        $storeDoc->doc_id = $outgoingDoc->id;
        $storeDoc->order_id = $incomingOrder->id;
        $storeDoc->products_data = json_encode($productsData);
        $storeDoc->contractor_type = $contractorType;
        $storeDoc->contractor_id = $contractorId;
        $storeDoc->incoming_amount = 0;
        $storeDoc->outgoing_amount = 0;
        $storeDoc->count = $outgoingTotalCount;
        $storeDoc->sum = $incomingTotalAmount;
        $storeDoc->save();

        session()->put('docs', [
            'incomingOrderDocNo' => $incomingOrder->doc_no,
            'incomingOrderId' => $incomingOrder->id,
            'outgoingDocId' => $outgoingDoc->id,
        ]);

        session()->forget(['cartProducts', 'totalDiscount']);

        return redirect($this->lang.'/cashdesk/payment-type/success');
    }

    public function render()
    {
        $customers = [];

        if (strlen($this->search) >= 2) {
            $customers = User::where('name', 'like', $this->search.'%')
                ->orWhere('lastname', 'like', $this->search.'%')
                ->orWhere('tel', 'like', $this->search.'%')
                ->get()
                ->take(7);
        }

        return view('livewire.cashbook.payment-types.sale-on-credit', ['customers' => $customers])
            ->layout('livewire.cashbook.layout');
    }
}
