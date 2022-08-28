<?php

namespace App\Http\Livewire\Cashbook;

use Livewire\Component;

use App\Models\Product;
use App\Models\DocType;
use App\Models\CashDoc;
use App\Models\StoreDoc;
use App\Models\OutgoingOrder;
use App\Models\IncomingOrder;
use App\Models\IncomingDoc;

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
        if (count($parts) == 3 && $parts[2] == 'outgoing_count') {
            unset($this->returnedProducts[$parts[1]]);
            $this->setValidCount($parts[1], $value);
        }

        // Setting Discount
        if (count($parts) == 3 && $parts[2] == 'discount') {
            unset($this->returnedProducts[$parts[1]]);
            $this->setValidDiscount($parts[1], $value);
        }
    }

    public function check($orderId)
    {
        $docNo = $this->generateIncomingStoreDocNo(session()->get('store')->id);
        $result = IncomingDoc::where('doc_no', $docNo)->first();

        $this->incomingOrder = IncomingOrder::find($orderId);
        $this->productsData = json_decode($this->incomingOrder->products_data, true);
        $this->productsDataCopy = $this->productsData;
        $this->products = Product::whereIn('id', array_keys($this->productsData))->get();
        $this->search = '';
    }

    public function setValidCount($product_id, $value)
    {
        $outgoingCount = $this->productsData[$product_id]['outgoing_count'];

        if ($value < 0 || !is_numeric($value)) {
            $validCount = null;
        } else {
            $validCount = ($outgoingCount <= $value)
                ? $outgoingCount
                : $value;
        }

        $this->productsDataCopy[$product_id]['outgoing_count'] = $validCount;
    }

    public function setValidDiscount($product_id, $value)
    {
        if ($value < 0 || !is_numeric($value)) {
            $validDiscount = 0;
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
        $outgoingCount = $this->productsData[$id]['outgoing_count'];

        $this->returnedProducts[$id]['incomingCount']
            = $outgoingCount == $this->productsDataCopy[$id]['outgoing_count']
                ? $outgoingCount
                : $outgoingCount - $this->productsDataCopy[$id]['outgoing_count'];

        $this->returnedProducts[$id]['discount'] = $this->productsData[$id]['discount'];
    }

    public function cancel($id)
    {
        unset($this->returnedProducts[$id]);
    }

    public function makeReturnDocs()
    {
        $productsData = [];
        $outgoingTotalAmount = 0;
        $incomingTotalCount = 0;

        $store = session()->get('store');
        $cashbook = $this->company->cashbooks->first();

        foreach($this->returnedProducts as $productId => $returnedProduct) {

            $product = Product::findOrFail($productId);

            $countInStores = json_decode($product->count_in_stores, true) ?? [];
            $countInStore = $countInStores[$store->id] ?? 0;

            $stockCount = $countInStore + $returnedProduct['incomingCount'];

            // Discount Calculation
            $percentage = $this->productsData[$product->id]['price'] / 100;
            $amount = $this->productsData[$product->id]['price'] - ($percentage * $returnedProduct['discount']);
            $amountDiscounted = $returnedProduct['incomingCount'] * $amount;

            $productsData[$productId]['price'] = $amountDiscounted;
            $productsData[$productId]['incomingCount'] = $returnedProduct['incomingCount'];
            $productsData[$productId]['discount'] = $returnedProduct['discount'];
            $productsData[$productId]['stockCount'] = $stockCount;

            $outgoingTotalAmount = $outgoingTotalAmount + $amountDiscounted;
            $incomingTotalCount = $incomingTotalCount + $returnedProduct['incomingCount'];

            $countInStores[$store->id] = $stockCount;
            $amountCount = collect($countInStores)->sum();

            $product->count_in_stores = json_encode($countInStores);
            $product->count = $amountCount;
            $product->save();
        }

        $paymentDetail['outgoing_amount'] = $outgoingTotalAmount;
        $paymentDetail['count'] = $incomingTotalCount;

        // Outgoing Order & Incoming Doc
        $docTypes = DocType::whereIn('slug', ['forma-ko-2', 'forma-z-1'])->get();

        $cashDocNo = $this->generateIncomingCashDocNo($cashbook->id);
        $storeDocNo = $this->generateOutgoingStoreDocNo($store->id);

        $outgoingOrder = new OutgoingOrder;
        $outgoingOrder->cashbook_id = $cashbook->id;
        $outgoingOrder->company_id = $this->company->id;
        $outgoingOrder->user_id = auth()->user()->id;
        $outgoingOrder->cashier_name = auth()->user()->name;
        $outgoingOrder->doc_no = $cashDocNo;
        $outgoingOrder->doc_type_id = $docTypes->where('slug', 'forma-ko-2')->first()->id;
        $outgoingOrder->to_contractors = $store->id;
        $outgoingOrder->sum = $outgoingTotalAmount;
        $outgoingOrder->currency = $this->company->currency->code;
        $outgoingOrder->count = 0;
        // $outgoingOrder->comment = $this->comment;
        $outgoingOrder->save();

        $incomingDoc = new IncomingDoc;
        $incomingDoc->store_id = $store->id;
        $incomingDoc->company_id = $this->company->id;
        $incomingDoc->user_id = auth()->user()->id;
        $incomingDoc->username = auth()->user()->name;
        $incomingDoc->doc_no = $storeDocNo;
        $incomingDoc->doc_type_id = $docTypes->where('slug', 'forma-z-1')->first()->id;
        $incomingDoc->products_data = json_encode($productsData);
        $incomingDoc->from_contractor = $cashbook->id;
        $incomingDoc->sum = $outgoingTotalAmount;
        $incomingDoc->currency = $this->company->currency->code;
        $incomingDoc->count = $incomingTotalCount;
        // $incomingDoc->unit = $this->unit;
        // $incomingDoc->comment = '';
        $incomingDoc->save();

        // Cashbook
        $cashDoc = new CashDoc;
        $cashDoc->cashbook_id = $cashbook->id;
        $cashDoc->company_id = $this->company->id;
        $cashDoc->user_id = auth()->user()->id;
        $cashDoc->doc_id = $outgoingOrder->id;
        $cashDoc->doc_type_id = $docTypes->where('slug', 'forma-ko-2')->first()->id;
        $cashDoc->from_contractor = $store->title;
        $cashDoc->to_contractor = $cashbook->title; // $this->company->title;
        $cashDoc->incoming_amount = 0;
        $cashDoc->outgoing_amount = $outgoingTotalAmount;
        $cashDoc->sum = $outgoingTotalAmount;
        $cashDoc->currency = $this->company->currency->code;
        // $cashDoc->comment = $this->comment;
        $cashDoc->save();

        // Storage
        $storeDoc = new StoreDoc;
        $storeDoc->store_id = $store->id;
        $storeDoc->company_id = $this->company->id;
        $storeDoc->user_id = auth()->user()->id;
        $storeDoc->doc_id = $incomingDoc->id;
        $storeDoc->doc_type_id = $docTypes->where('slug', 'forma-z-1')->first()->id;
        $storeDoc->products_data = json_encode($productsData);
        $storeDoc->from_contractor = $cashbook->title;
        $storeDoc->to_contractor = $store->title;
        $storeDoc->incoming_amount = 0;
        $storeDoc->outgoing_amount = $outgoingTotalAmount;
        $storeDoc->sum = $outgoingTotalAmount;
        // $storeDoc->unit = $this->unit;
        // $storeDoc->comment = $this->comment;
        $storeDoc->save();

        $this->emitUp('newData');
    }

    public function render()
    {
        $incomingOrders = [];

        if (strlen($this->search) >= 1) {
            $incomingOrders = IncomingOrder::where('doc_no', 'like', '%'.$this->search.'%')->paginate(12);
        }

        return view('livewire.cashbook.return-products', ['incomingOrders' => $incomingOrders]);
    }
}
