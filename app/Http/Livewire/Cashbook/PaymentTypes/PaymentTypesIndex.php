<?php

namespace App\Http\Livewire\Cashbook\PaymentTypes;

use Livewire\Component;

use App\Http\Livewire\Cashbook\Index as CashbookIndex;

use App\Models\Product;
use App\Models\PaymentType;
use App\Models\DocType;
use App\Models\CashDoc;
use App\Models\StoreDoc;
use App\Models\IncomingOrder;
use App\Models\OutgoingDoc;

class PaymentTypesIndex extends Component
{
    public $lang;
    public $view = false;
    public $company;
    public $cashbook;
    public $store;
    public $docNo;
    public $sumOfCart;
    public $cartProducts;
    public $paymentTypes;

    protected $listeners = ['makeDocs' => 'makeDocs'];

    public function mount()
    {
        $this->lang = app()->getLocale();
        $this->company = auth()->user()->profile->company;

        if (empty(session()->get('cartProducts'))) {
            return redirect($this->lang.'/cashdesk');
        }

        if (empty(session()->get('cashbook'))) {
            session()->put('cashbook', $this->company->cashbooks->first());
        }

        $this->docNo = $this->generateCashDocNo(session()->get('cashbook')->id);
        $this->sumOfCart = CashbookIndex::sumOfCart();
        $this->paymentTypes = PaymentType::get();
    }

    public function paymentType($slug)
    {
        $this->view = trim(strip_tags($slug));
    }

    public function makeDocs($paymentDetail = [])
    {
        $productsData = [];
        $countInStores = [];
        $outgoingTotalCount = 0;
        $incomingTotalAmount = 0;

        $store = session()->get('store');
        $cashbook = session()->get('cashbook');
        $cartProducts = session()->get('cartProducts') ?? [];

        foreach($cartProducts as $productId => $cartProduct) {

            $product = Product::findOrFail($productId);

            $countInStores = json_decode($cartProduct->count_in_stores, true) ?? [];
            $countInStore = $countInStores[$store->id] ?? 0;

            $outgoingCount = 0;

            /**
             * Prepare outgoing count & If outgoing count greater, assign $countInStore
             */
            if ($countInStore >= 1 && $cartProduct['countInCart'] <= $countInStore) {
                $outgoingCount = $cartProduct['countInCart'];
            } elseif ($countInStore < $cartProduct['countInCart']) {
                $outgoingCount = $countInStore;
            }

            $stockCount = $countInStore - $outgoingCount;

            $price = (session()->get('priceMode') == 'retail') ? $product->price : $product->wholesale_price;

            $productsData[$productId]['price'] = $price;
            $productsData[$productId]['outgoing_count'] = $outgoingCount;
            $productsData[$productId]['stockCount'] = $stockCount;
            $productsData[$productId]['barcodes'] = json_decode($product->barcodes, true);

            $incomingTotalAmount = $incomingTotalAmount + ($price * $outgoingCount);
            $outgoingTotalCount = $outgoingTotalCount + $outgoingCount;

            $countInStores[$store->id] = $stockCount;
            $amountCount = collect($countInStores)->sum();

            $product->count_in_stores = json_encode($countInStores);
            $product->count = $amountCount;
            // $product->save();
        }

        // Incoming Order & Outgoing Doc
        $docTypes = DocType::whereIn('slug', ['forma-ko-1', 'forma-z-2'])->get();

        $incomingOrder = new IncomingOrder;
        $incomingOrder->cashbook_id = $cashbook->id;
        $incomingOrder->company_id = $this->company->id;
        $incomingOrder->user_id = auth()->user()->id;
        $incomingOrder->cashier_name = auth()->user()->name;
        $incomingOrder->doc_no = $this->docNo;
        $incomingOrder->doc_type_id = $docTypes->where('slug', 'forma-ko-1')->first()->id;
        $incomingOrder->products_data = json_encode($productsData);
        $incomingOrder->from_contractor = $store->title;
        $incomingOrder->payment_type_id = $paymentDetail['typeId'];
        $incomingOrder->payment_detail = json_encode($paymentDetail);
        $incomingOrder->sum = $this->sumOfCart['sumDiscounted'];
        $incomingOrder->currency = $this->company->currency->code;
        $incomingOrder->count = $this->sumOfCart['totalCount'];
        // $incomingOrder->comment = $this->comment;
        $incomingOrder->save();

        $storeDocNo = $this->generateStoreDocNo($store->id);

        $outgoingDoc = new OutgoingDoc;
        $outgoingDoc->store_id = $store->id;
        $outgoingDoc->company_id = $this->company->id;
        $outgoingDoc->user_id = auth()->user()->id;
        $outgoingDoc->username = auth()->user()->name;
        $outgoingDoc->doc_no = $storeDocNo;
        $outgoingDoc->doc_type_id = $docTypes->where('slug', 'forma-z-2')->first()->id;
        $outgoingDoc->products_data = json_encode($productsData);
        $outgoingDoc->to_contractor = $cashbook->id;
        $outgoingDoc->sum = $incomingTotalAmount;
        $outgoingDoc->currency = $this->company->currency->code;
        $outgoingDoc->count = $outgoingTotalCount;
        // $outgoingDoc->unit = $this->unit;
        // $outgoingDoc->comment = $this->comment;
        $outgoingDoc->save();

        // Cashbook
        $cashDoc = new CashDoc;
        $cashDoc->cashbook_id = $cashbook->id;
        $cashDoc->company_id = $this->company->id;
        $cashDoc->user_id = auth()->user()->id;
        $cashDoc->doc_id = $incomingOrder->id;
        $cashDoc->doc_type_id = $docTypes->where('slug', 'forma-ko-1')->first()->id;
        $cashDoc->from_contractor = $store->title;
        $cashDoc->to_contractor = $cashbook->title; // $this->company->title;
        $cashDoc->incoming_amount = $this->sumOfCart['sumDiscounted'];
        $cashDoc->outgoing_amount = 0;
        $cashDoc->sum = $this->sumOfCart['sumDiscounted'];
        $cashDoc->currency = $this->company->currency->code;
        // $cashDoc->comment = $this->comment;
        $cashDoc->save();

        // Storage
        $storeDoc = new StoreDoc;
        $storeDoc->store_id = $store->id;
        $storeDoc->company_id = $this->company->id;
        $storeDoc->user_id = auth()->user()->id;
        $storeDoc->doc_id = $outgoingDoc->id;
        $storeDoc->doc_type_id = $docTypes->where('slug', 'forma-z-2')->first()->id;
        $storeDoc->products_data = json_encode($productsData);
        $storeDoc->from_contractor = $cashbook->title;
        $storeDoc->to_contractor = $store->title;
        $storeDoc->incoming_amount = $incomingTotalAmount;
        $storeDoc->outgoing_amount = 0;
        $storeDoc->sum = $outgoingTotalCount;
        // $storeDoc->unit = $this->unit;
        // $storeDoc->comment = $this->comment;
        $storeDoc->save();

        session()->forget('cartProducts');
        return redirect($this->lang.'/cashdesk/payment-type/success');
    }

    public function generateCashDocNo($cashbook_id, $docNo = null)
    {
        if (is_null($docNo)) {

            $lastDoc = IncomingOrder::orderByDesc('id')->first();

            if ($lastDoc) {
                list($first, $second) = explode('/', $lastDoc->doc_no);
                $docNo = $first.'/'.$second++;
            } elseif (is_null($docNo)) {
                $docNo = $cashbook_id.'/1';
            }
        }

        $existDoc = IncomingOrder::where('doc_no', $docNo)->first();

        if ($existDoc) {
            list($first, $second) = explode('/', $docNo);
            $docNo = $first.'/'.++$second;
            $this->generateCashDocNo($cashbook_id, $docNo);
        }

        return $docNo;
    }

    public function generateStoreDocNo($store_id, $docNo = null)
    {
        if (is_null($docNo)) {

            $lastDoc = OutgoingDoc::orderByDesc('id')->first();

            if ($lastDoc && is_null($docNo)) {
                list($first, $second) = explode('/', $lastDoc->doc_no);
                $docNo = $first.'/'.$second++;
            } elseif (is_null($docNo)) {
                $docNo = $store_id.'/1';
            }
        }

        $existDoc = OutgoingDoc::where('doc_no', $docNo)->first();

        if ($existDoc) {
            list($first, $second) = explode('/', $docNo);
            $docNo = $first.'/'.++$second;
            $this->generateStoreDocNo($store_id, $docNo);
        }

        return $docNo;
    }

    public function backToCash()
    {
        // session()->forget('cartProducts');

        return redirect($this->lang.'/cashdesk');
    }

    public function render()
    {
        $this->cartProducts = session()->get('cartProducts') ?? [];

        return view('livewire.cashbook.payment-types.payment-types-index')
            ->layout('livewire.cashbook.layout');
    }
}
