<?php

namespace App\Http\Livewire\Cashbook;

use Livewire\Component;

use App\Models\User;
use App\Models\Product;
use App\Models\DocType;
use App\Models\CashDoc;
use App\Models\StoreDoc;
use App\Models\IncomingDoc;
use App\Models\OutgoingDoc;
use App\Models\IncomingOrder;
use App\Models\OutgoingOrder;
use App\Models\PaymentType;

use App\Traits\GenerateDocNo;

class ReturnProducts extends Component
{
    use GenerateDocNo;

    public $search = '';
    public $company;
    public $incomingOrder = [];
    public $products = [];
    public $productsData = [];
    public $productsDataCopy = [];
    public $returnedProducts = [];

    public function mount()
    {
        $this->company = auth()->user()->profile->company;
    }

    public function updated($key, $value)
    {
        $parts = explode('.', $key);

        // Setting Correct Count
        if (count($parts) == 3 && $parts[2] == 'outgoingCount') {
            unset($this->returnedProducts[$parts[1]]);
            $this->setValidCount($parts[1], $value);
        }

        // Setting Discount
        // if (count($parts) == 3 && $parts[2] == 'discount') {
        //     unset($this->returnedProducts[$parts[1]]);
        //     $this->setValidDiscount($parts[1], $value);
        // }
    }

    public function check($orderId)
    {
        // $docNo = $this->generateIncomingStoreDocNo(session()->get('store')->id);
        // $result = IncomingDoc::where('doc_no', $docNo)->first();

        $this->incomingOrder = IncomingOrder::find($orderId);
        $this->productsData = json_decode($this->incomingOrder->products_data, true);
        $this->productsDataCopy = $this->productsData;
        $this->products = Product::whereIn('id', array_keys($this->productsData))->get();
        $this->search = '';
    }

    public function setValidCount($product_id, $value)
    {
        $outgoingCount = $this->productsData[$product_id]['outgoingCount'];

        if ($value < 0 || !is_numeric($value)) {
            $validCount = null;
        } else {
            $validCount = ($outgoingCount <= $value)
                ? $outgoingCount
                : $value;
        }

        $this->productsDataCopy[$product_id]['outgoingCount'] = $validCount;
    }

    public function setValidDiscount($product_id, $value)
    {
        if ($value < 0 || !is_numeric($value)) {
            $validDiscount = null;
        } else {
            $validDiscount = (10 < $value)
                ? 10
                : $value;
        }

        $this->productsData[$product_id]['discount'] = $validDiscount;
    }

    public function switchCountView($id)
    {
        $this->productsData[$id]['inputCount'] = ($this->productsData[$id]['inputCount']) ? false : true;
    }

    public function switchDiscountView($id)
    {
        $this->productsData[$id]['inputDiscount'] = ($this->productsData[$id]['inputDiscount']) ? false : true;
    }

    public function return($id)
    {
        $outgoingCount = $this->productsData[$id]['outgoingCount'];

        $this->returnedProducts[$id]['incomingCount']
            = $outgoingCount == $this->productsDataCopy[$id]['outgoingCount']
                ? $outgoingCount
                : $outgoingCount - $this->productsDataCopy[$id]['outgoingCount'];

        $this->returnedProducts[$id]['discount'] = $this->productsData[$id]['discount'];
    }

    public function cancel($id)
    {
        unset($this->returnedProducts[$id]);
    }

    public function makeReturnDocs()
    {
        $outgoingTotalAmount = 0;
        $incomingTotalCount = 0;

        $store = session()->get('store');
        $cashbook = session()->get('cashbook');
        $workplaceId = session()->get('cashdeskWorkplace');

        foreach($this->returnedProducts as $productId => $returnedProduct) {

            $product = Product::findOrFail($productId);

            $countInStores = json_decode($product->count_in_stores, true) ?? [];
            $countInStore = $countInStores[$store->id] ?? 0;

            $stockCount = $this->productsData[$productId]['stockCount'] + $returnedProduct['incomingCount'];

            // Discount Calculation
            $percentage = $this->productsData[$product->id]['price'] / 100;
            $amount = $this->productsData[$product->id]['price'] - ($percentage * $returnedProduct['discount']);
            $amountDiscounted = $returnedProduct['incomingCount'] * $amount;

            $this->productsData[$productId]['outgoingCount'] -= $returnedProduct['incomingCount'];
            $this->productsData[$productId]['discount'] = $returnedProduct['discount'];
            $this->productsData[$productId]['stockCount'] = $stockCount;
            $this->productsData[$productId]['barcodes'] = json_decode($product->barcodes, true);

            $outgoingTotalAmount = $outgoingTotalAmount + $amountDiscounted;
            $incomingTotalCount = $incomingTotalCount + $returnedProduct['incomingCount'];

            $countInStores[$store->id] = $stockCount;
            $amountCount = collect($countInStores)->sum();

            $product->count_in_stores = json_encode($countInStores);
            $product->count = $amountCount;
            // $product->save();
        }

        $finalAmount = $this->incomingOrder->sum - $outgoingTotalAmount;
        $finalCount = $this->incomingOrder->count - $incomingTotalCount;

        $paymentDetail = json_decode($this->incomingOrder->payment_detail, true) ?? [];
        $paymentDetail['outgoingAmount'] = $outgoingTotalAmount;

        // Outgoing Order & Incoming Doc
        $docTypes = DocType::whereIn('slug', ['forma-ko-2', 'forma-z-1'])->get();

        $cashDocNo = $this->generateIncomingCashDocNo($cashbook->id);
        $storeDocNo = $this->generateOutgoingStoreDocNo($store->id);

        $outgoingOrder = new OutgoingOrder;
        $outgoingOrder->cashbook_id = $cashbook->id;
        $outgoingOrder->company_id = $this->company->id;
        $outgoingOrder->user_id = auth()->user()->id;
        $outgoingOrder->workplaceId = $workplaceId;
        $outgoingOrder->doc_no = $cashDocNo;
        $outgoingOrder->doc_type_id = $docTypes->where('slug', 'forma-ko-2')->first()->id;
        $outgoingOrder->sum = $this->amount;
        $outgoingOrder->currency = $this->company->currency->code;
        $outgoingOrder->count = 0;
        $outgoingOrder->comment = 'Corrected, Return products';
        // $outgoingOrder->save();

        $cashDoc = new CashDoc;
        $cashDoc->cashbook_id = $cashbook->id;
        $cashDoc->company_id = $this->company->id;
        $cashDoc->user_id = auth()->user()->id;
        $cashDoc->order_type = 'App\Models\OutgoingOrder';
        $cashDoc->order_id = $outgoingOrder->id;
        $cashDoc->contractor_type = $this->incomingOrder->cashDoc->contractor_type;
        $cashDoc->contractor_id = $this->incomingOrder->cashDoc->contractor_id;
        $cashDoc->incoming_amount = 0;
        $cashDoc->outgoing_amount = $this->amount;
        $cashDoc->sum = $this->amount;
        $cashDoc->currency = $this->company->currency->code;
        $cashDoc->comment = 'Corrected, Return products';
        // $cashDoc->save();

        dd($this->productsData,  $this->incomingOrder->cashDoc);

        // Cash Docs
        $this->incomingOrder->workplace_id = $workplaceId;
        $this->incomingOrder->user_id = auth()->user()->id;
        $this->incomingOrder->doc_type_id = $docTypes->where('slug', 'forma-ko-2')->first()->id;
        $this->incomingOrder->products_data = json_encode($this->productsData);
        $this->incomingOrder->payment_detail = json_encode($paymentDetail);
        $this->incomingOrder->sum = $finalAmount;
        $this->incomingOrder->count = $finalCount;
        $this->incomingOrder->comment = 'corrected';
        // $this->incomingOrder->save();

        // Store Docs
        $outgoingDoc = OutgoingDoc::find($this->incomingOrder->cashDoc->doc_id);
        $outgoingDoc->user_id = auth()->user()->id;
        $outgoingDoc->doc_type_id = $docTypes->where('slug', 'forma-z-1')->first()->id;
        $outgoingDoc->products_data = json_encode($productsData);
        $outgoingDoc->sum = $finalAmount;
        $outgoingDoc->count = $finalCount;
        $outgoingDoc->comment = 'corrected';
        // $outgoingDoc->save();

        dd($this->productsData, json_decode($this->incomingOrder->products_data, true), $this->incomingOrder);

        // Cash Doc
        $cashDoc = new CashDoc;
        $cashDoc->cashbook_id = $cashbook->id;
        $cashDoc->company_id = $this->company->id;
        $cashDoc->user_id = auth()->user()->id;
        $cashDoc->order_type = 'App\Models\OutgoingOrder';
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
        $storeDoc->store_id = $store->id;
        $storeDoc->company_id = $this->company->id;
        $storeDoc->user_id = auth()->user()->id;
        $storeDoc->doc_type = 'App\Models\IncomingDoc';
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
/*
        $cashDoc = CashDoc::where('order_id', $this->incomingOrder->id)->first();
        $cashDoc->user_id = auth()->user()->id;
        $cashDoc->outgoing_amount = $outgoingTotalAmount;
        $cashDoc->sum = $finalAmount;
        $cashDoc->comment = 'corrected';
        $cashDoc->save();

        $storeDoc = StoreDoc::where('doc_id', $outgoingDoc->id)->first();
        $storeDoc->user_id = auth()->user()->id;
        $storeDoc->products_data = json_encode($productsData);
        $storeDoc->outgoing_amount = $outgoingTotalAmount;
        $storeDoc->count = $incomingTotalCount;
        $storeDoc->sum = $finalAmount;
        $storeDoc->comment = 'corrected';
        $storeDoc->save();*/

        dd($productsData, $this->incomingOrder, $cashDoc, $outgoingDoc, $storeDoc);

        return redirect(app()->getLocale().'/cashdesk/docsprint/incoming-check/'.$this->incomingOrder->id);
    }

    public function render()
    {
        $incomingOrders = [];

        if ($this->search) {
            $incomingOrders = IncomingOrder::where('doc_no', 'like', '%'.$this->search.'%')->paginate(12);
        }

        return view('livewire.cashbook.return-products', ['incomingOrders' => $incomingOrders]);
    }
}
