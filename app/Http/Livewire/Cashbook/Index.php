<?php

namespace App\Http\Livewire\Cashbook;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;

use Livewire\Component;

use App\Models\Store;
use App\Models\Cashbook;
use App\Models\Unit;
use App\Models\User;
use App\Models\Product;

class Index extends Component
{
    public $lang;
    public $company;
    public $cashbook;
    public $store;
    public $storeId;
    public $storeNum;
    public $search = '';
    public $searchCustomer = '';
    public $cartProducts = [];
    public $customer;
    public $discounts = [];
    public $totalDiscount = null;
    public $totalDiscountView;
    public $priceMode = 'retail';

    protected $listeners = [
        'addToCart',
        'removeFromCart',
        'returnDeferredCheck',
    ];

    protected $rules = [
        'store' => 'required|numeric',
        'cartProducts.*.countInCart' => 'required|numeric',
        'discounts.*' => 'required|numeric',
    ];

    public function mount()
    {
        $this->lang = app()->getLocale();
        $this->company = auth()->user()->company;

        if (!session()->has('storage')) {
            session()->put('storage', Store::where('company_id', $this->company->id)->first());
        }

        $this->cashbook = session()->get('cashdesk');
        $this->store = session()->get('storage');
        $this->storeId = $this->store->id;
        $this->storeNum = $this->store->num_id;

        session()->put('priceMode', $this->priceMode);
    }

    public function updated($key, $value)
    {
        // Stores Switching
        if ($key == 'storeId') {
            $this->store = Store::where('id', $value)->first();
            session()->put('storage', $this->store);
            session()->forget('cartProducts');
            $this->discounts = [];
        }

        // Setting Total Discount
        if ($key == 'totalDiscount') {

            if ($value <= 0 || !is_numeric($value) || empty($value)) {
                $totalDiscount = null;
            } else {
                $totalDiscount = (10 < $value) ? 10 : $value;
            }

            session()->put('totalDiscount', $totalDiscount);
        }

        $parts = explode('.', $key);

        // Setting Discount
        if (count($parts) == 3 && $parts[2] == 'discount') {
            $this->setValidDiscount($parts[1], $value);
        }

        // Setting Correct Count
        if (count($parts) == 3 && $parts[2] == 'countInCart') {
            $this->setValidCount($parts[1], $value);
        }
    }

    public static function sumOfCart()
    {
        // Count The Order
        $percent = 0;
        $totalCount = 0;
        $countProduct = 0;
        $sumDiscounted = 0;
        $sumUndiscounted = 0;

        $cartProducts = session()->get('cartProducts') ?? [];

        foreach($cartProducts as $index => $cartProduct) {

            if ($cartProduct->countInCart == 0) {
                continue;
            }

            $countProduct++;
            $totalCount += $cartProduct->countInCart;

            $price = session()->get('priceMode') == 'retail' ? $cartProduct->price : $cartProduct->wholesale_price ?? 0;

            if ($cartProduct->discount) {
                $percent = $cartProduct->discount;
            }
            elseif (session()->get('totalDiscount')) {
                $percent = session()->get('totalDiscount');
            }

            $percentage = $price / 100;
            $amount = $price - ($percentage * $percent);

            $sumDiscounted += $cartProduct->countInCart * $amount;
            $sumUndiscounted += $cartProduct->countInCart * $price;
        }

        $data['totalCount'] = $totalCount;
        $data['countProduct'] = $countProduct;
        $data['sumDiscounted'] = round($sumDiscounted, -1); // number_format(round($sumDiscounted, -1), 0, '.', ' ');
        $data['sumUndiscounted'] = round($sumUndiscounted, -1);

        return $data;
    }

    public function setValidCount($productId, $value)
    {
        $cartProducts = session()->get('cartProducts');
        $countInStores = json_decode($cartProducts[$productId]->count_in_stores, true) ?? [];
        $countInStore = $countInStores[$this->storeNum] ?? 0;

        if ($value <= 0 || !is_numeric($value)) {
            $validCount = ($countInStore == 0) ? 0 : 1;
        } else {
            $validCount = ($countInStore < $value) ? $countInStore : $value;
        }

        $cartProducts[$productId]['countInCart'] = $validCount;
        session()->put('cartProducts', $cartProducts);
    }

    public function setValidDiscount($productId, $value)
    {
        $cartProducts = session()->get('cartProducts');

        if ($value <= 0 || !is_numeric($value) || empty($value)) {
            $validDiscount = null;
        } else {
            $validDiscount = (10 < $value) ? 10 : $value;
        }

        $cartProducts[$productId]['discount'] = $validDiscount;
        session()->put('cartProducts', $cartProducts);
    }

    public function switchDiscountView($id)
    {
        $cartProducts = session()->get('cartProducts');
        $cartProducts[$id]['input'] = ($cartProducts[$id]['input']) ? false : true;
        session()->put('cartProducts', $cartProducts);
    }

    public function switchTotalDiscountView()
    {
        $this->totalDiscountView = ($this->totalDiscountView) ? false : true;
    }

    public function switchPriceMode()
    {
        if (! Gate::allows('switch-price-mode', auth()->user())) {
            abort(403);
        }

        if (session()->get('priceMode') == 'retail') {
            session()->put('priceMode', 'wholesale');
        } else {
            session()->put('priceMode', 'retail');
        }
    }

    public function addToCart($id)
    {
        if (in_array($id, array_keys($this->cartProducts))) {
            $this->search = '';
            return $this->dispatchBrowserEvent('show-toast', ['message' => 'Item already added in cart']);
        }

        $product = Product::findOrFail($id);

        $countInStores = json_decode($product->count_in_stores, true) ?? [];
        $countInStore = $countInStores[$this->storeNum] ?? 0;

        if (session()->has('cartProducts')) {
            $cartProducts = session()->get('cartProducts');
        }

        $cartProducts[$id] = $product;
        $cartProducts[$id]['countInCart'] = ($countInStore == 0) ? 0 : 1;
        $cartProducts[$id]['discount'] = null;
        $cartProducts[$id]['input'] = false;
        $cartProducts[$id]['time'] = time();

        session()->put('cartProducts', $cartProducts);
        $this->search = '';
    }

    public function checkCustomer($id)
    {
        $this->customer = User::find($id);

        if (! $this->customer->is_customer) {
            $this->customer->is_customer = 1;
            $this->customer->save();
        }

        session()->put('customer', $this->customer);
        session()->put('totalDiscount', $this->customer->profile->discount ?? 0);

        $this->searchCustomer = '';
    }

    public function removeCustomer()
    {
        session()->forget('customer');
        session()->put('totalDiscount', 0);
        $this->customer = null;
    }

    public function removeFromCart($id)
    {
       $cartProducts = session()->get('cartProducts');

        if (count($cartProducts) == 0 OR $id == '0') {
            session()->forget('cartProducts');
            return;
        }

        unset($cartProducts[$id], $this->cartProducts[$id]);
        session()->put('cartProducts', $cartProducts);
    }

    public function clearCart()
    {
        session()->forget('cartProducts');
    }

    public function deferCheck()
    {
        if (!session()->has('cartProducts')) {
            $this->dispatchBrowserEvent('show-toast', ['message' => 'No data']);
            return;
        }

        // Getting Sum Of Cart
        $sumOfCart = $this->sumOfCart();
        $orderName = $this->storeNum.'/'.$sumOfCart['totalCount'].'/'.date("Y-m-d/H:i");

        $ccid = $this->company->id.$this->cashbook->id;

        if (Cache::has('deferredChecks'.$ccid)) {
            $deferredChecks = Cache::get('deferredChecks'.$ccid);
        }

        $deferredChecks[$orderName]['totalDiscount'] = $this->totalDiscount;
        $deferredChecks[$orderName]['sumDiscounted'] = number_format($sumOfCart['sumDiscounted'], 0, '.', ' ').$this->company->currency->symbol;
        $deferredChecks[$orderName]['sumUndiscounted'] = number_format($sumOfCart['sumUndiscounted'], 0, '.', ' ').$this->company->currency->symbol;
        $deferredChecks[$orderName]['cart'] = $this->cartProducts;

        Cache::put('deferredChecks'.$ccid, $deferredChecks);

        $this->totalDiscount = null;
        session()->forget('cartProducts');

        $this->dispatchBrowserEvent('show-toast', ['reload' => true]);
    }

    public function returnDeferredCheck($orderName)
    {
        if (session()->has('cartProducts')) {
            session()->forget('cartProducts');
        }

        $ccid = $this->company->id.$this->cashbook->id;

        if (Cache::has('deferredChecks'.$ccid)) {
            $deferredChecks = Cache::get('deferredChecks'.$ccid);
        }

        $deferredCheck = $deferredChecks[$orderName];
        $orderArrName = explode('/', $orderName);
        $i = 0;

        session()->put('storage', Store::where('company_id', $this->company->id)->find($orderArrName[0]));

        foreach($deferredCheck['cart'] as $id => $check) {

            $product = Product::findOrFail($id);

            $countInStores = json_decode($product->count_in_stores, true) ?? [];
            $countInStore = $countInStores[$this->storeNum] ?? 0;

            $validCount = ($countInStore <= $check['countInCart']) ? $countInStore : $check['countInCart'];

            $cartProducts[$id] = $product;
            $cartProducts[$id]['countInCart'] = $validCount;
            $cartProducts[$id]['discount'] = $check['discount'];
            $cartProducts[$id]['input'] = false;
            $cartProducts[$id]['time'] = time() + $i++;
        }

        $this->totalDiscount = $deferredCheck['totalDiscount'];

        session()->put('cartProducts', $cartProducts);

        $this->dispatchBrowserEvent('show-toast', [
            'message' => 'Операция выполнена', 'selector' => 'closeDefferedChecks'
        ]);
    }

    public function getByBarcode()
    {
        $product = Product::select('id')
            ->where('in_company_id', $this->company->id)
            ->where('barcodes', 'like', '%'.$this->search.'%')
            ->first();

        $this->addToCart($product->id);

        $this->dispatchBrowserEvent('area-focus');
    }

    public function render()
    {
        $products = [];
        $customers = [];
        $sumOfCart = $this->sumOfCart();

        if (strlen($this->search) >= 2) {
            $products = Product::orderBy('id', 'desc')
                ->where('in_company_id', $this->company->id)
                ->where('title', 'like', '%'.$this->search.'%')
                ->orWhere('barcodes', 'like', '%'.$this->search.'%')
                ->orWhere('id_code', 'like', '%'.$this->search.'%')
                ->get()
                ->take(7);
        }

        if (strlen($this->searchCustomer) >= 2) {
            $customers = User::where('company_id', $this->company->id)
                ->where('name', 'like', $this->searchCustomer.'%')
                ->orWhere('lastname', 'like', $this->searchCustomer.'%')
                ->orWhere('tel', 'like', $this->searchCustomer.'%')
                ->get()
                ->take(7);
        }

        $this->priceMode = session()->get('priceMode');
        $this->cartProducts = session()->get('cartProducts') ?? [];
        $this->totalDiscount = session()->get('totalDiscount');

        return view('livewire.cashbook.index', [
                'products' => $products,
                'customers' => $customers,
                'sumOfCart' => $sumOfCart
            ])
            ->layout('livewire.cashbook.layout');
    }
}
