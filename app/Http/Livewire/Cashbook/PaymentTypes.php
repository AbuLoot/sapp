<?php

namespace App\Http\Livewire\Cashbook;

use Livewire\Component;

use App\Http\Livewire\Cashbook\Index;

use App\Models\Product;
use App\Models\PaymentType;
use App\Models\DocType;
use App\Models\CashDoc;
use App\Models\StoreDoc;
use App\Models\IncomingOrder;
use App\Models\OutgoingDoc;

class PaymentTypes extends Component
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

    public function mount()
    {
        $this->lang = app()->getLocale();
        $this->cartProducts = session()->get('cartProducts') ?? [];

        if (empty($this->cartProducts)) {
            return redirect($this->lang.'/cashdesk');
        }

        $this->company = auth()->user()->profile->company;
        $this->cashbook = $this->company->cashbooks->first();
        $this->store = session()->get('store');
        $this->docNo = $this->generateCashDocNo($this->cashbook->id);
        $this->sumOfCart = Index::sumOfCart();
        $this->paymentTypes = PaymentType::get();
    }

    public function paymentType($slug)
    {
        $slug = trim(strip_tags($slug));
        $this->view = $slug;

        // Payment Types
        $paymentType = $this->paymentTypes->where('slug', $slug)->first();

        switch($paymentType->slug) {
            case '';
        }

        $this->makeDocs($paymentType);
    }

    public function generateCashDocNo($cashbook_id, $docNo = null)
    {
        if (is_null($docNo)) {

            $lastDoc = IncomingOrder::where('doc_no', 'like', $cashbook_id.'/*')->orderByDesc('id')->first();

            if ($lastDoc) {
                list($first, $second) = explode('/', $lastDoc->doc_no);
                $docNo = $first.'/'.++$second;
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

            $lastDoc = OutgoingDoc::where('doc_no', 'like', $store_id.'/*')->orderByDesc('id')->first();

            if ($lastDoc && is_null($docNo)) {
                list($first, $second) = explode('/', $lastDoc->doc_no);
                $docNo = $first.'/'.++$second;
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

    public function makeDocs($paymentType)
    {
        $products_data = [];
        $countInStores = [];
        $outgoingTotalCount = 0;
        $incomingTotalAmount = 0;

        $cartProducts = session()->get('cartProducts') ?? [];

        foreach($cartProducts as $productId => $cartProduct) {

            $product = Product::findOrFail($productId);

            $countInStores = json_decode($cartProduct->count_in_stores, true) ?? [];
            $countInStore = $countInStores[$this->store->id] ?? 0;

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

            $products_data[$productId]['price'] = $price;
            $products_data[$productId]['outgoing_count'] = $outgoingCount;
            $products_data[$productId]['stockCount'] = $stockCount;
            $products_data[$productId]['barcodes'] = json_decode($product->barcodes, true);

            $incomingTotalAmount = $incomingTotalAmount + ($price * $outgoingCount);
            $outgoingTotalCount = $outgoingTotalCount + $outgoingCount;

            $countInStores[$this->store->id] = $stockCount;
            $amountCount = collect($countInStores)->sum();

            $product->count_in_stores = json_encode($countInStores);
            $product->count = $amountCount;
            $product->save();
        }

        // Incoming Order
        $cashDocType = DocType::where('slug', 'forma-ko-1')->first();

        $incomingOrder = new IncomingOrder;
        $incomingOrder->cashbook_id = $this->cashbook->id;
        $incomingOrder->company_id = $this->company->id;
        $incomingOrder->user_id = auth()->user()->id;
        $incomingOrder->cashier_name = auth()->user()->name;
        $incomingOrder->doc_no = $this->docNo;
        $incomingOrder->doc_type_id = $cashDocType->id;
        $incomingOrder->products_data = json_encode($products_data);
        $incomingOrder->from_contractor = $this->store->title;
        $incomingOrder->payment_type_id = $paymentType->id;
        $incomingOrder->payment_detail = null; // Customer info
        $incomingOrder->sum = $this->sumOfCart['sumDiscounted'];
        $incomingOrder->currency = $this->company->currency->code;
        $incomingOrder->count = $this->sumOfCart['totalCount'];
        // $incomingOrder->comment = $this->comment;
        $incomingOrder->save();

        // Outgoing Doc
        $storeDocType = DocType::where('slug', 'forma-z-2')->first();

        $storeDocNo = $this->generateStoreDocNo($this->store->id);

        $outgoingDoc = new OutgoingDoc;
        $outgoingDoc->store_id = $this->store->id;
        $outgoingDoc->company_id = $this->company->id;
        $outgoingDoc->user_id = auth()->user()->id;
        $outgoingDoc->username = auth()->user()->name;
        $outgoingDoc->doc_no = $storeDocNo;
        $outgoingDoc->doc_type_id = $storeDocType->id;
        $outgoingDoc->products_data = json_encode($products_data);
        $outgoingDoc->to_contractor = $this->cashbook->id;
        $outgoingDoc->sum = $incomingTotalAmount;
        $outgoingDoc->currency = $this->company->currency->code;
        $outgoingDoc->count = $outgoingTotalCount;
        // $outgoingDoc->unit = $this->unit;
        // $outgoingDoc->comment = $this->comment;
        $outgoingDoc->save();

        // Cashbook
        $cashDoc = new CashDoc;
        $cashDoc->cashbook_id = $this->cashbook->id;
        $cashDoc->company_id = $this->company->id;
        $cashDoc->user_id = auth()->user()->id;
        $cashDoc->doc_id = $incomingOrder->id;
        $cashDoc->doc_type_id = $cashDocType->id;
        $cashDoc->from_contractor = $this->store->title;
        $cashDoc->to_contractor = $this->cashbook->title; // $this->company->title;
        $cashDoc->incoming_amount = $this->sumOfCart['sumDiscounted'];
        $cashDoc->outgoing_amount = 0;
        $cashDoc->sum = $this->sumOfCart['sumDiscounted'];
        $cashDoc->currency = $this->company->currency->code;
        // $cashDoc->comment = $this->comment;
        $cashDoc->save();

        // Storage
        $storeDoc = new StoreDoc;
        $storeDoc->store_id = $this->store->id;
        $storeDoc->company_id = $this->company->id;
        $storeDoc->user_id = auth()->user()->id;
        $storeDoc->doc_id = $outgoingDoc->id;
        $storeDoc->doc_type_id = $storeDocType->id;
        $storeDoc->products_data = json_encode($products_data);
        $storeDoc->from_contractor = $this->cashbook->title;
        $storeDoc->to_contractor = $this->store->title;
        $storeDoc->incoming_amount = $incomingTotalAmount;
        $storeDoc->outgoing_amount = 0;
        $storeDoc->sum = $outgoingTotalCount;
        // $storeDoc->unit = $this->unit;
        // $storeDoc->comment = $this->comment;
        $storeDoc->save();

        session()->forget('cartProducts');
        $this->view = 'success';
    }

    public function backToCash()
    {
        session()->forget('cartProducts');

        return redirect($this->lang.'/cashdesk');
    }

    public function render()
    {
        return view('livewire.cashbook.payment-types')
            ->layout('livewire.cashbook.layout');
    }
}
