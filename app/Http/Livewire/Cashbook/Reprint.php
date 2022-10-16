<?php

namespace App\Http\Livewire\Cashbook;

use Livewire\Component;

use App\Models\IncomingOrder;
use App\Models\PaymentType;
use App\Models\Product;

class Reprint extends Component
{
    public $search = '';
    public $lang;
    public $company;
    // public $incomingOrders;

    protected $listeners = [
        'reprintInput',
    ];

    public function mount()
    {
        $this->lang = app()->getLocale();
        $this->company = auth()->user()->profile->company;
    }

    public function reprintInput($value)
    {
        $property = $value[1];
        $this->$property = $value[0];
    }

    public function getCheck($id)
    {
        $incomingOrder = IncomingOrder::find($id);
        $paymentType = PaymentType::find($incomingOrder->payment_type_id);
        $paymentDetail = json_decode($incomingOrder->payment_detail, true);
        $clientName = 'No name';

        if (isset($paymentDetail['userId'])) {
            $user = User::find($paymentDetail['userId']);
            $clientName = $user->name;
        }

        $productsData = json_decode($incomingOrder->products_data, true);
        $products = Product::whereIn('id', array_keys($productsData))->get();
        $productsList = [];

        foreach($products as $key => $product) {
            $productsList[$key]['title'] = $product->title;
            $productsList[$key]['count'] = $productsData[$product->id]['outgoingCount'];
            $productsList[$key]['price'] = $productsData[$product->id]['price'];
            $productsList[$key]['discount'] = $productsData[$product->id]['discount'] ?? 0;
        }

        $view = response()->view('cashbook.check', [
            'docNo' => $incomingOrder->doc_no,
            'clientName' => $clientName,
            'productsList' => $productsList,
            'paymentType' => $paymentType->title,
            'date' => $incomingOrder->created_at,
            'cashierName' => $incomingOrder->cashier_name
        ]);
    }

    public function render()
    {
        $incomingOrders = [];

        if ($this->search) {
            $incomingOrders = IncomingOrder::where('doc_no', 'like', '%'.$this->search.'%')->orderByDesc('id')->paginate(12);
        }

        return view('livewire.cashbook.reprint', ['incomingOrders' => $incomingOrders]);
    }
}
