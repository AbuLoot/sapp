<?php

namespace App\Http\Livewire\Cashbook\PaymentTypes;

use Livewire\Component;

use App\Http\Livewire\Cashbook\Index;

use App\Models\Product;
use App\Models\PaymentType;
use App\Models\DocType;
use App\Models\CashDoc;
use App\Models\StoreDoc;
use App\Models\IncomingOrder;
use App\Models\OutgoingDoc;

use App\Traits\GenerateDocNo;

class CashPayment extends Component
{
    use GenerateDocNo;

    public $lang;
    public $cash = null;
    public $change = 0;
    public $sumOfCart;
    public $payButton = false;

    public $cashbook;
    public $workplaceId;

    public function mount()
    {
        $this->lang = app()->getLocale();
        $this->company = auth()->user()->company;
        $this->sumOfCart = Index::sumOfCart();
        $this->paymentType = PaymentType::where('slug', 'cash-payment')->first();

        $this->cashbook = session()->get('cashdesk');
        $this->workplaceId = session()->get('cashdeskWorkplace');

        if (empty(session()->get('cartProducts'))) {
            return redirect($this->lang.'/cashdesk');
        }
    }

    public function updated($key, $value)
    {
        if ($this->sumOfCart['sumDiscounted'] <= $this->cash) {
            $this->change = (int) $this->cash - $this->sumOfCart['sumDiscounted'];
            $this->payButton = true;
        } else {
            $this->payButton = false;
        }
    }

    public function makeDocs()
    {
        $paymentDetail['typeId'] = $this->paymentType->id;
        $paymentDetail['cash'] = $this->cash;
        $paymentDetail['change'] = $this->change;

        $productsData = [];
        $countInStores = [];
        $outgoingTotalCount = 0;
        $incomingTotalAmount = 0;

        // $store = session()->get('storage');
        // $cashbook = session()->get('cashdesk');
        // $workplaceId = session()->get('cashdeskWorkplace');
        // $cartProducts = session()->get('cartProducts');

        $contractorType = null;
        $contractorId = null;

        if (session()->has('customer')) {
            $contractorType = 'App\Models\User';
            $contractorId = session()->get('customer')->id;
        }

        foreach(session()->get('cartProducts') as $productId => $cartProduct) {

            $product = Product::findOrFail($productId);

            $outgoingCount = $cartProduct['countInCart'];

            $countInStores = json_decode($cartProduct->count_in_stores, true) ?? [];
            $countInStore = $countInStores[session('storage')->num_id] ?? 0;

            // Prepare outgoing count & If outgoing count greater, assign $countInStore
            if ($countInStore >= 1 && $cartProduct['countInCart'] <= $countInStore) {
                $outgoingCount = $cartProduct['countInCart'];
            } elseif ($countInStore < $cartProduct['countInCart']) {
                $outgoingCount = $countInStore;
            }

            $stockCount = $countInStore - $outgoingCount;
            $countInStores[session('storage')->num_id] = $stockCount;
            $amountCount = collect($countInStores)->sum();

            $product->count_in_stores = json_encode($countInStores);
            $product->count = $amountCount;
            $product->save();

            $price = session()->get('priceMode') == 'retail' ? $product->price : $product->wholesale_price;
            $discount = session()->get('totalDiscount');

            if ($cartProduct->discount) {
                $discount = $cartProduct->discount;
            }

            $productsData[$productId]['store'] = session('storage')->id;
            $productsData[$productId]['price'] = $price;
            $productsData[$productId]['outgoingCount'] = $outgoingCount;
            $productsData[$productId]['discount'] = $discount;
            $productsData[$productId]['barcodes'] = json_decode($product->barcodes, true);

            $incomingTotalAmount = $incomingTotalAmount + ($price * $outgoingCount);
            $outgoingTotalCount = $outgoingTotalCount + $outgoingCount;
        }

        // Incoming Order & Outgoing Doc
        $docTypes = DocType::whereIn('slug', ['forma-ko-1', 'forma-z-2'])->get();

        $cashDocNo = $this->generateIncomingCashDocNo($this->cashbook->num_id);
        $storeDocNo = $this->generateOutgoingStoreDocNo(session('storage')->num_id);

        // Cash Doc
        $incomingOrder = new IncomingOrder;
        $incomingOrder->cashbook_id = $this->cashbook->id;
        $incomingOrder->company_id = $this->company->id;
        $incomingOrder->user_id = auth()->user()->id;
        $incomingOrder->workplace_id = $this->workplaceId;
        $incomingOrder->doc_no = $cashDocNo;
        $incomingOrder->doc_type_id = $docTypes->where('slug', 'forma-ko-1')->first()->id;
        $incomingOrder->products_data = json_encode($productsData);
        $incomingOrder->contractor_type = $contractorType;
        $incomingOrder->contractor_id = $contractorId;
        $incomingOrder->operation_code = 'payment-products';
        $incomingOrder->payment_type_id = $paymentDetail['typeId'];
        $incomingOrder->payment_detail = json_encode($paymentDetail);
        $incomingOrder->sum = $this->sumOfCart['sumDiscounted'];
        $incomingOrder->currency = $this->company->currency->code;
        $incomingOrder->count = $this->sumOfCart['totalCount'];
        $incomingOrder->save();

        // Store Doc
        $outgoingDoc = new OutgoingDoc;
        $outgoingDoc->store_id = session('storage')->id;
        $outgoingDoc->company_id = $this->company->id;
        $outgoingDoc->user_id = auth()->user()->id;
        $outgoingDoc->doc_no = $storeDocNo;
        $outgoingDoc->doc_type_id = $docTypes->where('slug', 'forma-z-2')->first()->id;
        $outgoingDoc->products_data = json_encode($productsData);
        $outgoingDoc->contractor_type = $contractorType;
        $outgoingDoc->contractor_id = $contractorId;
        $outgoingDoc->operation_code = 'payment-products';
        $outgoingDoc->sum = $this->sumOfCart['sumDiscounted'];
        $outgoingDoc->currency = $this->company->currency->code;
        $outgoingDoc->count = $outgoingTotalCount;
        $outgoingDoc->save();

        // Cash Doc
        $cashDoc = new CashDoc;
        $cashDoc->cashbook_id = $this->cashbook->id;
        $cashDoc->company_id = $this->company->id;
        $cashDoc->user_id = auth()->user()->id;
        $cashDoc->order_type = 'App\Models\IncomingOrder';
        $cashDoc->order_id = $incomingOrder->id;
        $cashDoc->doc_id = $outgoingDoc->id;
        $cashDoc->contractor_type = $contractorType;
        $cashDoc->contractor_id = $contractorId;
        $cashDoc->incoming_amount = $this->sumOfCart['sumDiscounted'];
        $cashDoc->outgoing_amount = 0;
        $cashDoc->sum = $this->sumOfCart['sumDiscounted'];
        $cashDoc->currency = $this->company->currency->code;
        $cashDoc->save();

        // Store Doc
        $storeDoc = new StoreDoc;
        $storeDoc->store_id = session('storage')->id;
        $storeDoc->company_id = $this->company->id;
        $storeDoc->user_id = auth()->user()->id;
        $storeDoc->doc_type = 'App\Models\OutgoingDoc';
        $storeDoc->doc_id = $outgoingDoc->id;
        $storeDoc->order_id = $incomingOrder->id;
        $storeDoc->products_data = json_encode($productsData);
        $storeDoc->contractor_type = $contractorType;
        $storeDoc->contractor_id = $contractorId;
        $storeDoc->incoming_amount = $this->sumOfCart['sumDiscounted'];
        $storeDoc->outgoing_amount = 0;
        $storeDoc->count = $outgoingTotalCount;
        $storeDoc->sum = $incomingTotalAmount;
        $storeDoc->save();

        session()->put('docs', [
            'incomingOrderDocNo' => $incomingOrder->doc_no,
            'incomingOrderId' => $incomingOrder->id,
            'outgoingDocId' => $outgoingDoc->id,
        ]);

        session()->forget(['customer', 'cartProducts', 'totalDiscount']);

        return redirect($this->lang.'/cashdesk/payment-type/success');
    }

    public function render()
    {
        return view('livewire.cashbook.payment-types.cash-payment')
            ->layout('livewire.cashbook.layout');
    }
}
