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

    public $search;
    public $company;
    public $incomingOrder = [];
    public $products = [];
    public $productsData = [];
    public $returnedProducts = [];
    public $modalClass;

    protected $listeners = [
        'returnProductsInput',
        'modalPLacement',
    ];

    public function mount()
    {
        $this->company = auth()->user()->company;
    }

    public function updated($key, $value)
    {
        $parts = explode('.', $key);

        // Setting Correct Count
        if (count($parts) == 3 && $parts[2] == 'returningCount') {
            unset($this->returnedProducts[$parts[1]]);
            $this->setValidCount($parts[1], $value);
        }

        // Setting Discount
        // if (count($parts) == 3 && $parts[2] == 'discount') {
        //     unset($this->returnedProducts[$parts[1]]);
        //     $this->setValidDiscount($parts[1], $value);
        // }
    }

    public function returnProductsInput($value)
    {
        if ($value[1] != 'search') {
            $array = explode('.', $value[1]);
            // $productsData = $array[0];
            // $this->$productsData[$array[1]][$array[2]] = $value[0] ?? null;
            unset($this->returnedProducts[$array[1]]);
            $this->setValidCount($array[1], $value[0]);
            return;
        }

        $property = $value[1];
        $this->$property = $value[0];
    }

    public function modalPLacement($value)
    {
        $this->modalClass = $value;
    }

    public function check($orderId)
    {
        $this->incomingOrder = IncomingOrder::find($orderId);
        $this->productsData = json_decode($this->incomingOrder->products_data, true);

        $this->products = Product::whereIn('id', array_keys($this->productsData))->get();
        $this->search = '';

        foreach($this->products as $product) {
            $this->productsData[$product->id]['returningCount'] = isset($this->productsData[$product->id]['returnedCount'])
                ? $this->productsData[$product->id]['outgoingCount'] - $this->productsData[$product->id]['returnedCount']
                : $this->productsData[$product->id]['outgoingCount'];
        }
    }

    public function setValidCount($id, $value)
    {
        $outgoingCount = $this->productsData[$id]['outgoingCount'];

        if (isset($this->productsData[$id]['returnedCount'])) {
            $outgoingCount = $this->productsData[$id]['returningCount'] = $this->productsData[$id]['outgoingCount'] - $this->productsData[$id]['returnedCount'];
        }

        if ($value <= 0 || !is_numeric($value)) {
            $validCount = null;
        } else {
            $validCount = $outgoingCount <= $value ? $outgoingCount : $value;
        }

        $this->productsData[$id]['returningCount'] = $validCount;
    }

    public function setValidDiscount($id, $value)
    {
        if ($value < 0 || !is_numeric($value)) {
            $validDiscount = null;
        } else {
            $validDiscount = (10 < $value) ? 10 : $value;
        }

        $this->productsData[$id]['discount'] = $validDiscount;
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

        $this->returnedProducts[$id]['incomingCount'] = ($outgoingCount >= $this->productsData[$id]['returningCount'])
            ? $this->productsData[$id]['returningCount']
            : $outgoingCount - $this->productsData[$id]['returnedCount'] ?? 0;

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
        $belongsToStoreId = collect($this->productsData)->pluck('store')->first() ?? null;

        $store = session()->get('store');
        $cashbook = session()->get('cashdesk');
        $workplaceId = session()->get('cashdeskWorkplace');

        $productsData = [];
        $contractorType = null;
        $contractorId = null;

        if ($this->incomingOrder->contractor_type != null) {
            $contractorType = $this->incomingOrder->contractor_type;
            $contractorId = $this->incomingOrder->contractor_id;
        } elseif (session()->has('customer')) {
            $contractorType = 'App\Models\User';
            $contractorId = session()->get('customer')->id;
        }

        foreach($this->returnedProducts as $productId => $returnedProduct) {

            $product = Product::findOrFail($productId);

            $countInStores = json_decode($product->count_in_stores, true) ?? [];
            $countInStore = $countInStores[$store->id] ?? 0;
            $stockCount = $countInStores[$belongsToStoreId] + $returnedProduct['incomingCount'];

            // Discount Calculation
            $percentage = $this->productsData[$product->id]['price'] / 100;
            $amount = $this->productsData[$product->id]['price'] - ($percentage * $returnedProduct['discount']);
            $amountDiscounted = $returnedProduct['incomingCount'] * $amount;

            $incomingCount = isset($this->productsData[$productId]['returnedCount'])
                ? $returnedProduct['incomingCount'] + $this->productsData[$productId]['returnedCount']
                : $returnedProduct['incomingCount'];

            $this->productsData[$productId]['purchase_price'] = $amountDiscounted;
            $this->productsData[$productId]['count'] = $returnedProduct['incomingCount'];
            $this->productsData[$productId]['unit'] = $product->unit;
            $this->productsData[$productId]['barcodes'] = $product->barcodes;
            $this->productsData[$productId]['stockCount'] = $stockCount;
            $this->productsData[$productId]['returnedCount'] = $incomingCount;

            unset($this->productsData[$productId]['returningCount']);

            // For Incoming Document
            $productsData[$productId] = $this->productsData[$productId];

            $outgoingTotalAmount = $outgoingTotalAmount + $amountDiscounted;
            $incomingTotalCount = $incomingTotalCount + $returnedProduct['incomingCount'];

            $countInStores[$belongsToStoreId] = $stockCount;
            $amountCount = collect($countInStores)->sum();

            $product->count_in_stores = json_encode($countInStores);
            $product->count = $amountCount;
            $product->save();
        }

        // Outgoing Order & Incoming Doc
        $docTypes = DocType::whereIn('slug', ['forma-ko-2', 'forma-z-1'])->get();

        $cashDocNo = $this->generateOutgoingCashDocNo($cashbook->id);
        $storeDocNo = $this->generateIncomingStoreDocNo($store->id);

        // Cash Docs
        $outgoingOrder = new OutgoingOrder;
        $outgoingOrder->cashbook_id = $cashbook->id;
        $outgoingOrder->company_id = $this->company->id;
        $outgoingOrder->user_id = auth()->user()->id;
        $outgoingOrder->workplace_id = $workplaceId;
        $outgoingOrder->doc_no = $cashDocNo;
        $outgoingOrder->doc_type_id = $docTypes->where('slug', 'forma-ko-2')->first()->id;
        $outgoingOrder->contractor_type = $contractorType;
        $outgoingOrder->contractor_id = $contractorId;
        $outgoingOrder->operation_code = 'returned-products';
        $outgoingOrder->sum = $outgoingTotalAmount;
        $outgoingOrder->currency = $this->company->currency->code;
        $outgoingOrder->count = $incomingTotalCount;
        $outgoingOrder->comment = 'Возврат продуктов. Приходной Ордер №'.$this->incomingOrder->doc_no.' был изменен.';
        $outgoingOrder->save();

        $cashDoc = new CashDoc;
        $cashDoc->cashbook_id = $cashbook->id;
        $cashDoc->company_id = $this->company->id;
        $cashDoc->user_id = auth()->user()->id;
        $cashDoc->order_type = 'App\Models\OutgoingOrder';
        $cashDoc->order_id = $outgoingOrder->id;
        $cashDoc->contractor_type = $contractorType;
        $cashDoc->contractor_id = $contractorId;
        $cashDoc->incoming_amount = 0;
        $cashDoc->outgoing_amount = $outgoingTotalAmount;
        $cashDoc->sum = $outgoingTotalAmount;
        $cashDoc->currency = $this->company->currency->code;
        $cashDoc->comment = 'Возврат продуктов. Приходной Ордер №'.$this->incomingOrder->doc_no.' был изменен.';
        $cashDoc->save();

        $paymentDetail = json_decode($this->incomingOrder->payment_detail, true) ?? [];
        $paymentDetail['outgoingAmount'] = $outgoingTotalAmount;

        $this->incomingOrder->workplace_id = $workplaceId;
        $this->incomingOrder->user_id = auth()->user()->id;
        $this->incomingOrder->products_data = json_encode($this->productsData);
        $this->incomingOrder->contractor_type = $contractorType;
        $this->incomingOrder->contractor_id = $contractorId;
        $this->incomingOrder->payment_detail = json_encode($paymentDetail);
        $this->incomingOrder->comment = 'Возврат продуктов. Приходной Ордер №'.$this->incomingOrder->doc_no.' был изменен.';
        $this->incomingOrder->save();

        // Store Docs
        $incomingDoc = new IncomingDoc;
        $incomingDoc->store_id = $belongsToStoreId;
        $incomingDoc->company_id = $this->company->id;
        $incomingDoc->workplace_id = $workplaceId;
        $incomingDoc->user_id = auth()->user()->id;
        $incomingDoc->doc_no = $storeDocNo;
        $incomingDoc->doc_type_id = $docTypes->where('slug', 'forma-z-1')->first()->id;
        $incomingDoc->products_data = json_encode($productsData);
        $incomingDoc->contractor_type = $contractorType;
        $incomingDoc->contractor_id = $contractorId;
        $incomingDoc->operation_code = 'returned-products';
        $incomingDoc->sum = $outgoingTotalAmount;
        $incomingDoc->currency = $this->company->currency->code;
        $incomingDoc->count = $incomingTotalCount;
        $incomingDoc->comment = 'corrected';
        $incomingDoc->save();

        $storeDoc = new StoreDoc;
        $storeDoc->store_id = $belongsToStoreId;
        $storeDoc->company_id = $this->company->id;
        $storeDoc->user_id = auth()->user()->id;
        $storeDoc->doc_type = 'App\Models\IncomingDoc';
        $storeDoc->doc_id = $incomingDoc->id;
        $storeDoc->order_id = $this->incomingOrder->id;
        $storeDoc->products_data = json_encode($productsData);
        $storeDoc->contractor_type = $contractorType;
        $storeDoc->contractor_id = $contractorId;
        $storeDoc->incoming_amount = 0;
        $storeDoc->outgoing_amount = $outgoingTotalAmount;
        $storeDoc->count = $incomingTotalCount;
        $storeDoc->sum = $outgoingTotalAmount;
        $storeDoc->save();

        return redirect(app()->getLocale().'/cashdesk/docsprint/incoming-doc/'.$incomingDoc->id);
    }

    public function render()
    {
        $incomingOrders = [];

        if ($this->search) {
            $incomingOrders = IncomingOrder::query()
                ->where('company_id', $this->company->id)
                ->where('doc_no', 'like', '%'.$this->search.'%')
                ->whereIn('operation_code', ['payment-products', 'returned-products']) // , 'sale-on-credit'
                ->orderByDesc('id')
                ->paginate(12);
        }

        return view('livewire.cashbook.return-products', ['incomingOrders' => $incomingOrders]);
    }
}
