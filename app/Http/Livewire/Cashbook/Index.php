<?php

namespace App\Http\Livewire\Cashbook;

use Illuminate\Support\Facades\Cache;

use Livewire\Component;

use App\Models\Store;
use App\Models\Cashbook;
use App\Models\Unit;
use App\Models\User;
use App\Models\Product;

class Index extends Component
{
    protected $queryString = ['searchProduct', 'searchClient'];

    public $lang;
    public $company;
    public $store;
    public $store_id;
    public $searchProduct = '';
    public $searchClient = '';
    public $cartProducts = [];
    public $discounts = [];
    public $totalDiscount = 0;
    public $totalDiscountView;
    public $priceMode = 'retail';

    protected $listeners = ['newData', 'addToCart', 'returnDeferredCheck'];

    protected $rules = [
        'store' => 'required|numeric',
        'cartProducts.*.countInCart' => 'required|numeric',
        'discounts.*' => 'required|numeric',
    ];

    public function newData()
    {
        session()->flash('message', 'Операция выполнена');
    }

    public function mount()
    {
        $this->lang = app()->getLocale();
        $this->company = auth()->user()->profile->company;

        if (is_null(session()->get('store'))) {
            session()->put('store', Store::first());
        }

        $this->store = session()->get('store');
        $this->store_id = $this->store->id;

        session()->put('totalDiscount', $this->totalDiscount);
    }

    public function updated($key, $value)
    {
        // Stores Switching
        if ($key == 'store_id') {
            $this->store = Store::where('id', $value)->first();
            session()->put('store', $this->store);
            session()->forget('cartProducts');
            $this->discounts = [];
        }

        // Setting Total Discount
        if ($key == 'totalDiscount') {

            if ($value < 0 || !is_numeric($value)) {
                $totalDiscount = 0;
            } else {
                $totalDiscount = (10 < $value)
                    ? 10
                    : $value;
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

    public function sumOfCart()
    {
        // Count The Order
        $percent = 0;
        $totalCount = 0;
        $sumDiscounted = 0;
        $sumUndiscounted = 0;

        $cartProducts = session()->get('cartProducts');

        foreach($cartProducts as $index => $cartProduct) {

            if ($cartProduct->countInCart == 0) {
                continue;
            }

            $totalCount++;

            $price = (session()->get('priceMode') == 'retail') ? $cartProduct->price : $cartProduct->wholesale_price ?? 0;

            if ($cartProduct->discount != 0) {
                $percent = $cartProduct->discount;
            } elseif(session()->get('totalDiscount') != 0) {
                $percent = session()->get('totalDiscount');
            }

            $percentage = $price / 100;
            $amount = $price - ($percentage * $percent);

            $sumDiscounted += $cartProduct->countInCart * $amount;
            $sumUndiscounted += $cartProduct->countInCart * $price;
        }

        $data['totalCount'] = $totalCount;
        $data['sumDiscounted'] = number_format(round($sumDiscounted, -1), 0, '.', ' ');
        $data['sumUndiscounted'] = number_format(round($sumUndiscounted, -1), 0, '.', ' ');

        return $data;
    }

    public function setValidCount($product_id, $value)
    {
        $cartProducts = session()->get('cartProducts');
        $countInStores = json_decode($cartProducts[$product_id]->count_in_stores, true) ?? [];
        $countInStore = $countInStores[$this->store->id] ?? 0;

        if ($value <= 0 || !is_numeric($value)) {
            $validCount = ($countInStore == 0) ? 0 : 1;
        } else {
            $validCount = ($countInStore < $value)
                ? $countInStore
                : $value;
        }

        $cartProducts[$product_id]['countInCart'] = $validCount;
        session()->put('cartProducts', $cartProducts);
    }

    public function setValidDiscount($product_id, $value)
    {
        $cartProducts = session()->get('cartProducts');

        if ($value < 0 || !is_numeric($value)) {
            $validDiscount = 0;
        } else {
            $validDiscount = (10 < $value)
                ? 10
                : $value;
        }

        $cartProducts[$product_id]['discount'] = $validDiscount;
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
        if (session()->get('priceMode') == 'retail') {
            session()->put('priceMode', 'wholesale');
        } else {
            session()->put('priceMode', 'retail');
        }
    }

    public function addToCart($id)
    {
        $product = Product::findOrFail($id);

        $countInStores = json_decode($product->count_in_stores, true) ?? [];
        $countInStore = $countInStores[$this->store->id] ?? 0;

        if (session()->has('cartProducts')) {
            $cartProducts = session()->get('cartProducts');
        }

        $cartProducts[$id] = $product;
        $cartProducts[$id]['countInCart'] = ($countInStore == 0) ? 0 : 1;
        $cartProducts[$id]['discount'] = 0;
        $cartProducts[$id]['input'] = false;

        session()->put('cartProducts', $cartProducts);
        $this->searchProduct = '';
    }

    public function removeFromCart($id)
    {
        $cartProducts = session()->get('cartProducts');

        if (count($cartProducts) == 0) {
            session()->forget('cartProducts');
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
        if (is_null(session()->get('cartProducts'))) {
            session()->flash('message', 'No data');
            return false;
        }

        // Getting Sum Of Cart
        $sumOfCart = $this->sumOfCart();
        $orderName = $this->store_id.'/'.$sumOfCart['totalCount'].'/'.date("Y-m-d/H:i");

        if (Cache::has('deferredChecks')) {
            $deferredChecks = Cache::get('deferredChecks');
        }

        $deferredChecks[$orderName]['totalDiscount'] = $this->totalDiscount;
        $deferredChecks[$orderName]['sumDiscounted'] = number_format(round($sumOfCart['sumDiscounted'], -1), 0, '.', ' ').$this->company->currency->symbol;
        $deferredChecks[$orderName]['sumUndiscounted'] = number_format(round($sumOfCart['sumUndiscounted'], -1), 0, '.', ' ').$this->company->currency->symbol;
        $deferredChecks[$orderName]['cart'] = $this->cartProducts;

        Cache::put('deferredChecks', $deferredChecks);

        $this->totalDiscount = 0;
        session()->forget('cartProducts');
        session()->flash('message', 'Операция выполнена');

        // $this->emitTo('deferredchecks', 'newData');
        $this->emit('$refresh');
    }

    public function returnDeferredCheck($orderName)
    {
        if (session()->has('cartProducts')) {
            session()->forget('cartProducts');
        }

        if (Cache::has('deferredChecks')) {
            $deferredChecks = Cache::get('deferredChecks');
        }

        $deferredCheck = $deferredChecks[$orderName];

        foreach($deferredCheck['cart'] as $id => $check) {

            $cartProducts[$id] = Product::findOrFail($id);
            $cartProducts[$id]['countInCart'] = $check['countInCart'];
            $cartProducts[$id]['discount'] = $check['discount'];
            $cartProducts[$id]['input'] = false;
        }

        $this->totalDiscount = $deferredCheck['totalDiscount'];
        session()->put('cartProducts', $cartProducts);
    }

    public function render()
    {
        $products = [];
        $clients = [];

        if (strlen($this->searchProduct) >= 2) {
            $products = Product::search($this->searchProduct)->get()->take(7);
        }

        if (strlen($this->searchClient) >= 2) {
            $clients = User::where('name', 'like', $this->searchClient.'%')
                ->orWhere('lastname', 'like', $this->searchClient.'%')
                ->orWhere('tel', 'like', $this->searchClient.'%')
                ->get()->take(7);
        }

        $this->priceMode = session()->get('priceMode');
        $this->cartProducts = session()->get('cartProducts') ?? [];
        $this->totalDiscount = session()->get('totalDiscount');

        return view('livewire.cashbook.index', ['products' => $products, 'clients' => $clients])
            ->layout('livewire.cashbook.layout');
    }
}
