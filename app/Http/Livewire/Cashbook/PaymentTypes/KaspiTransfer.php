<?php

namespace App\Http\Livewire\Cashbook\PaymentTypes;

use Livewire\Component;

use App\Http\Livewire\Cashbook\Index;
use App\Http\Livewire\Cashbook\PaymentTypes;

use App\Models\Product;
use App\Models\PaymentType;
use App\Models\DocType;
use App\Models\CashDoc;
use App\Models\StoreDoc;
use App\Models\IncomingOrder;
use App\Models\OutgoingDoc;

use App\Traits\GenerateDocNo;

class KaspiTransfer extends Component
{
    use GenerateDocNo;

    public $cash = null;
    public $sumOfCart;
    public $payButton = false;

    public function mount()
    {
        $this->lang = app()->getLocale();
        $this->company = auth()->user()->profile->company;
        $this->sumOfCart = Index::sumOfCart();
        $this->paymentType = PaymentType::where('slug', 'kaspi-transfer')->first();

        $this->makeDocs();
    }

    public function makeDocs()
    {
        $paymentDetail['typeId'] = $this->paymentType->id;
        $paymentDetail['typeSlug'] = $this->paymentType->slug;

        $productsData = [];
        $countInStores = [];
        $outgoingTotalCount = 0;
        $incomingTotalAmount = 0;

        $store = session()->get('store');
        $cashbook = session()->get('cashbook');
        $workplaceId = session()->get('cashdeskWorkplace');
        $cartProducts = session()->get('cartProducts') ?? [];

        $contractorType = null;
        $contractorId = null;

        if (session()->has('customer')) {
            $contractorType = 'App\Models\User';
            $contractorId = session()->get('customer')->id;
        }

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
            $discount = 0;

            if ($cartProduct->discount != 0) {
                $discount = $cartProduct->discount;
            } elseif(session()->get('totalDiscount') != 0) {
                $discount = session()->get('totalDiscount');
                session()->forget('totalDiscount');
            }

            $productsData[$productId]['price'] = $price;
            $productsData[$productId]['outgoingCount'] = $outgoingCount;
            $productsData[$productId]['discount'] = $discount;
            $productsData[$productId]['stockCount'] = $stockCount;
            $productsData[$productId]['barcodes'] = json_decode($product->barcodes, true);

            $incomingTotalAmount = $incomingTotalAmount + ($price * $outgoingCount);
            $outgoingTotalCount = $outgoingTotalCount + $outgoingCount;

            $countInStores[$store->id] = $stockCount;
            $amountCount = collect($countInStores)->sum();

            $product->count_in_stores = json_encode($countInStores);
            $product->count = $amountCount;
            $product->save();
        }

        // Incoming Order & Outgoing Doc
        $docTypes = DocType::whereIn('slug', ['forma-ko-1', 'forma-z-2'])->get();

        $cashDocNo = $this->generateIncomingCashDocNo($cashbook->id);
        $storeDocNo = $this->generateOutgoingStoreDocNo($store->id);

        // Cashbook Docs
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
        $incomingOrder->sum = $this->sumOfCart['sumDiscounted'];
        $incomingOrder->currency = $this->company->currency->code;
        $incomingOrder->count = $this->sumOfCart['totalCount'];
        $incomingOrder->save();

        $cashDoc = new CashDoc;
        $cashDoc->cashbook_id = $cashbook->id;
        $cashDoc->company_id = $this->company->id;
        $cashDoc->user_id = auth()->user()->id;
        $cashDoc->doc_id = $incomingOrder->id;
        $cashDoc->doc_type_id = $docTypes->where('slug', 'forma-ko-1')->first()->id;
        $cashDoc->contractor_type = $contractorType;
        $cashDoc->contractor_id = $contractorId;
        $cashDoc->incoming_amount = $this->sumOfCart['sumDiscounted'];
        $cashDoc->outgoing_amount = 0;
        $cashDoc->sum = $this->sumOfCart['sumDiscounted'];
        $cashDoc->currency = $this->company->currency->code;
        $cashDoc->save();

        // Storage Docs
        $outgoingDoc = new OutgoingDoc;
        $outgoingDoc->store_id = $store->id;
        $outgoingDoc->company_id = $this->company->id;
        $outgoingDoc->user_id = auth()->user()->id;
        $outgoingDoc->doc_no = $storeDocNo;
        $outgoingDoc->doc_type_id = $docTypes->where('slug', 'forma-z-2')->first()->id;
        $outgoingDoc->inc_order_id = $incomingOrder->id;
        $outgoingDoc->products_data = json_encode($productsData);
        $outgoingDoc->contractor_type = $contractorType;
        $outgoingDoc->contractor_id = $contractorId;
        $outgoingDoc->sum = $this->sumOfCart['sumDiscounted'];
        $outgoingDoc->currency = $this->company->currency->code;
        $outgoingDoc->count = $outgoingTotalCount;
        $outgoingDoc->save();

        $storeDoc = new StoreDoc;
        $storeDoc->store_id = $store->id;
        $storeDoc->company_id = $this->company->id;
        $storeDoc->user_id = auth()->user()->id;
        $storeDoc->doc_id = $outgoingDoc->id;
        $storeDoc->doc_type_id = $docTypes->where('slug', 'forma-z-2')->first()->id;
        $storeDoc->products_data = json_encode($productsData);
        $storeDoc->contractor_type = $contractorType;
        $storeDoc->contractor_id = $contractorId;
        $storeDoc->incoming_amount = $this->sumOfCart['sumDiscounted'];
        $storeDoc->outgoing_amount = 0;
        $storeDoc->count = $outgoingTotalCount;
        $storeDoc->sum = $incomingTotalAmount;
        $storeDoc->save();

        session()->forget('client');
        session()->forget('cartProducts');

        session()->put('docs', [
            'incomingOrderDocNo' => $incomingOrder->doc_no,
            'incomingOrderId' => $incomingOrder->id,
            'outgoingDocId' => $outgoingDoc->id,
        ]);

        return redirect($this->lang.'/cashdesk/payment-type/success');
    }

    public function render()
    {
        // return view('livewire.cashbook.payment-types.kaspi-transfer')
            // ->layout('livewire.cashbook.layout');
    }
}