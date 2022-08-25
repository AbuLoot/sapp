<?php

namespace App\Http\Livewire\Cashbook;

use Livewire\Component;

use App\Models\IncomingOrder;
use App\Models\OutgoingOrder;
use App\Models\PaymentType;
use App\Models\Product;

class CashDocsPrint extends Component
{
    public $search = '';
    public $company;
    public $docType;
    public $docId;
    // public $incomingOrders;

    public function mount($type, $id)
    {
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

        $this->company = auth()->user()->profile->company;
    }

    public function incomingCheck($id)
    {
        $incomingOrder = IncomingOrder::find($id);
        $paymentType = PaymentType::find($incomingOrder->payment_type_id);
        $paymentDetail = json_decode($incomingOrder->payment_detail, true);
        $clientName = 'No name';

        if (isset($paymentDetail['user_id'])) {
            $user = User::find($paymentDetail['user_id']);
            $clientName = $user->name;
        }

        $productsData = json_decode($incomingOrder->products_data, true);
        $products = Product::whereIn('id', array_keys($productsData))->get();
        $productsList = [];

        foreach($products as $key => $product) {
            $productsList[$key]['title'] = $product->title;
            $productsList[$key]['count'] = $productsData[$product->id]['outgoing_count'];
            $productsList[$key]['price'] = $productsData[$product->id]['price'];
            $productsList[$key]['discount'] = $productsData[$product->id]['discount'] ?? 0;
        }

            // response()
        return view('cashbook.check', [
                'docNo' => $incomingOrder->doc_no,
                'clientName' => $clientName,
                'productsList' => $productsList,
                'paymentType' => $paymentType->title,
                'date' => $incomingOrder->created_at,
                'cashierName' => $incomingOrder->cashier_name
            ])
            ->layout('cashbook.layout');
            // ->header('Content-Type', 'text/plain');
    }

    public function incomingOrder($id)
    {
        $incomingOrder = IncomingOrder::find($id);
        $paymentType = PaymentType::find($incomingOrder->payment_type_id);
        $paymentDetail = json_decode($incomingOrder->payment_detail, true);
        $clientName = 'No name';

        if (isset($paymentDetail['user_id'])) {
            $user = User::find($paymentDetail['user_id']);
            $clientName = $user->name;
        }

        $productsData = json_decode($incomingOrder->products_data, true);
        $products = Product::whereIn('id', array_keys($productsData))->get();
        $productsList = [];

        foreach($products as $key => $product) {
            $productsList[$key]['title'] = $product->title;
            $productsList[$key]['count'] = $productsData[$product->id]['outgoing_count'];
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

    public function outgoingOrder($id)
    {
        $incomingOrder = IncomingOrder::find($id);
        $paymentType = PaymentType::find($incomingOrder->payment_type_id);
        $paymentDetail = json_decode($incomingOrder->payment_detail, true);
        $clientName = 'No name';

        if (isset($paymentDetail['user_id'])) {
            $user = User::find($paymentDetail['user_id']);
            $clientName = $user->name;
        }

        $productsData = json_decode($incomingOrder->products_data, true);
        $products = Product::whereIn('id', array_keys($productsData))->get();
        $productsList = [];

        foreach($products as $key => $product) {
            $productsList[$key]['title'] = $product->title;
            $productsList[$key]['count'] = $productsData[$product->id]['outgoing_count'];
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
        return view('livewire.cashbook.cash-docs-print');
    }
}
