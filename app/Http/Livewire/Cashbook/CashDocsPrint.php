<?php

namespace App\Http\Livewire\Cashbook;

use Livewire\Component;

use App\Models\IncomingOrder;
use App\Models\OutgoingOrder;
use App\Models\PaymentType;
use App\Models\Product;
use App\Models\User;

class CashDocsPrint extends Component
{
    public $search = '';
    public $lang;
    public $company;
    public $data = [];
    public $view;
    // public $incomingOrders;

    public function mount($type, $id)
    {
        $this->lang = app()->getLocale();
        $this->company = auth()->user()->profile->company;

        switch ($type) {
            case 'check':
                $this->incomingCheck($id);
                break;
            case 'incoming':
                $this->incomingOrder($id);
                break;
            case 'outging':
                $this->outgoingOrder($id);
                break;
        }
    }

    public function incomingCheck($id)
    {
        $incomingOrder = IncomingOrder::find($id);
        $paymentType = PaymentType::find($incomingOrder->payment_type_id);
        $paymentDetail = json_decode($incomingOrder->payment_detail, true);
        $clientName = 'No name';

        if (isset($paymentDetail['userId'])) {
            $user = User::find($paymentDetail['userId']);
            $clientName = $user->name;
        }

        $productsData = json_decode($incomingOrder->products_data, true) ?? [];
        $products = Product::whereIn('id', array_keys($productsData))->get();
        $productsList = [];

        foreach($products as $key => $product) {
            $productsList[$key]['title'] = $product->title;
            $productsList[$key]['count'] = $productsData[$product->id]['outgoingCount'];
            $productsList[$key]['price'] = $productsData[$product->id]['price'];
            $productsList[$key]['discount'] = $productsData[$product->id]['discount'] ?? 0;
        }

        $this->data = [
            'companyName' => $this->company->title,
            'docNo' => $incomingOrder->doc_no,
            'clientName' => $clientName,
            'productsList' => $productsList,
            'paymentType' => $paymentType->title,
            'currency' => $this->company->currency->symbol,
            'date' => $incomingOrder->created_at,
            'cashierName' => $incomingOrder->cashier_name,
            'prevPage' => '/'.$this->lang.'/cashdesk',
        ];

        $this->view = 'check';
    }

    public function incomingOrder($id)
    {
        $incomingOrder = IncomingOrder::find($id);
        $paymentType = PaymentType::find($incomingOrder->payment_type_id);
        $paymentDetail = json_decode($incomingOrder->payment_detail, true);
        $clientName = 'No name';

        if (isset($paymentDetail['userId'])) {
            $user = User::find($paymentDetail['userId']);
            $clientName = $user->name;
        }

        $productsData = json_decode($incomingOrder->products_data, true) ?? [];
        $products = Product::whereIn('id', array_keys($productsData))->get();
        $productsList = [];

        foreach($products as $key => $product) {
            $productsList[$key]['title'] = $product->title;
            $productsList[$key]['count'] = $productsData[$product->id]['outgoingCount'];
            $productsList[$key]['price'] = $productsData[$product->id]['price'];
            $productsList[$key]['discount'] = $productsData[$product->id]['discount'] ?? 0;
        }

        $this->data = [
            'companyName' => $this->company->title,
            'docNo' => $incomingOrder->doc_no,
            'clientName' => $clientName,
            'productsList' => $productsList,
            'paymentType' => $paymentType->title,
            'currency' => $this->company->currency->symbol,
            'date' => $incomingOrder->created_at,
            'cashierName' => $incomingOrder->cashier_name,
            'prevPage' => '/'.$this->lang.'/cashdesk',
        ];

        $this->view = 'incoming-order';
    }

    public function outgoingOrder($id)
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
        return view('livewire.docs.'.$this->view, $this->data)
            ->layout('livewire.docs.layout');
    }
}
