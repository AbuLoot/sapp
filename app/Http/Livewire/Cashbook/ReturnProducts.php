<?php

namespace App\Http\Livewire\Cashbook;

use Livewire\Component;

use App\Models\IncomingOrder;
use App\Models\Product;

class ReturnProducts extends Component
{
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
            $this->setValidCount($parts[1], $value);
        }

        // Setting Discount
        if (count($parts) == 3 && $parts[2] == 'discount') {
            $this->setValidDiscount($parts[1], $value);
        }
    }

    public function check($orderId)
    {
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
        dd($this->returnedProducts);
        // $this-> 
    }

    public function makeReturnDocs()
    {
        $productsData = [];
        $countInStores = [];
        $outgoingTotalAmount = 0;
        $incomingTotalCount = 0;

        $store = session()->get('store');
        $cashbook = $this->company->cashbooks->first();

        foreach($this->returnedProducts as $productId => $returnedProduct) {

            $product = Product::findOrFail($productId);

            $countInStores = json_decode($product->count_in_stores, true) ?? [];
            $countInStore = $countInStores[$store->id] ?? 0;

            $incomingCount = $returnedProduct['incomingCount'];
            $stockCount = $countInStore + $incomingCount;

            $percentage = $this->productsData[$product->id]['price'] / 100;
            $amount = $this->productsData[$product->id]['price'] - ($percentage * $returnedProduct['discount']);
            $amountDiscounted = $incomingCount * $amount;

            $productsData[$productId]['price'] = $amountDiscounted;
            $productsData[$productId]['incomingCount'] = $incomingCount;
            $productsData[$productId]['discount'] = $returnedProduct['discount'];
            $productsData[$productId]['stockCount'] = $stockCount;

            $outgoingTotalAmount = $outgoingTotalAmount + ($amount * $incomingCount);
            $incomingTotalCount = $incomingTotalCount + $incomingCount;

            $countInStores[$store->id] = $stockCount;
            $amountCount = collect($countInStores)->sum();

            $product->count_in_stores = json_encode($countInStores);
            $product->count = $amountCount;
            // $product->save();
        }

        dd($incomingTotalCount, $outgoingTotalAmount, $product);

        $paymentDetail['user_id'] = $user->id;
        $paymentDetail['dept_sum'] = $this->sumOfCart['sumDiscounted'];
        $paymentDetail['incoming_order'] = null;
        $paymentDetail['outgoing_doc'] = null;
        $paymentDetail['incoming_count'] = null;

        // Incoming Order & Outgoing Doc
        $docTypes = DocType::whereIn('slug', ['forma-ko-1', 'forma-z-2'])->get();

        $cashDocNo = $this->generateStoreDocNo($cashbook->id);

        $incomingOrder = new IncomingOrder;
        $incomingOrder->cashbook_id = $cashbook->id;
        $incomingOrder->company_id = $this->company->id;
        $incomingOrder->user_id = auth()->user()->id;
        $incomingOrder->cashier_name = auth()->user()->name;
        $incomingOrder->doc_no = $cashDocNo;
        $incomingOrder->doc_type_id = $docTypes->where('slug', 'forma-ko-1')->first()->id;
        $incomingOrder->products_data = json_encode($productsData);
        $incomingOrder->from_contractor = $store->title;
        $incomingOrder->payment_type_id = $paymentDetail['typeId'];
        $incomingOrder->payment_detail = json_encode($paymentDetail);
        $incomingOrder->sum = 0;
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
        $outgoingDoc->sum = 0;
        $outgoingDoc->currency = $this->company->currency->code;
        $outgoingDoc->count = $outgoingTotalCount;
        // $outgoingDoc->unit = $this->unit;
        // $outgoingDoc->comment = $this->comment;
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

        // Cashbook
        $cashDoc = new CashDoc;
        $cashDoc->cashbook_id = $cashbook->id;
        $cashDoc->company_id = $this->company->id;
        $cashDoc->user_id = auth()->user()->id;
        $cashDoc->doc_id = $incomingOrder->id;
        $cashDoc->doc_type_id = $docTypes->where('slug', 'forma-ko-1')->first()->id;
        $cashDoc->from_contractor = $store->title;
        $cashDoc->to_contractor = $cashbook->title; // $this->company->title;
        $cashDoc->incoming_amount = 0;
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
        $storeDoc->incoming_amount = 0;
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

    public function render()
    {
        $incomingOrders = [];

        if (strlen($this->search) >= 1) {
            $incomingOrders = IncomingOrder::where('doc_no', 'like', '%'.$this->search.'%')->paginate(12);
        }

        return view('livewire.cashbook.return-products', ['incomingOrders' => $incomingOrders]);
    }
}
