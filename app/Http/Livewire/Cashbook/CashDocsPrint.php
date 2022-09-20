<?php

namespace App\Http\Livewire\Cashbook;

use Livewire\Component;

use App\Models\CashDoc;
use App\Models\IncomingOrder;
use App\Models\OutgoingOrder;
use App\Models\StoreDoc;
use App\Models\IncomingDoc;
use App\Models\OutgoingDoc;
use App\Models\PaymentType;
use App\Models\Product;
use App\Models\User;
use App\Models\Unit;

class CashDocsPrint extends Component
{
    public $lang;
    public $company;
    public $data = [];
    public $units;
    public $view;

    public function mount($type, $id)
    {
        $this->lang = app()->getLocale();
        $this->company = auth()->user()->profile->company;
        $this->units = Unit::get();

        switch ($type) {
            case 'incoming-check':
                $this->incomingCheck($id);
                break;
            case 'incoming-order':
                $this->incomingOrder($id);
                break;
            case 'outgoing-order':
                $this->outgoingOrder($id);
                break;
            case 'incoming-doc':
                $this->incomingDoc($id);
                break;
            case 'outgoing-doc':
                $this->outgoingDoc($id);
                break;
        }
    }

    public function incomingCheck($id)
    {
        $incomingOrder = IncomingOrder::find($id);
        $paymentType = PaymentType::find($incomingOrder->payment_type_id);
        $paymentDetail = json_decode($incomingOrder->payment_detail, true);
        $customerName = 'No name';

        if (isset($paymentDetail['userId'])) {
            $user = User::find($paymentDetail['userId']);
            $customerName = $user->name.' '.$user->lastname;
        }

        $productsData = json_decode($incomingOrder->products_data, true) ?? [];
        $products = Product::whereIn('id', array_keys($productsData))->get();
        $productsList = [];

        foreach($products as $key => $product) {
            $productsList[$key]['title'] = $product->title;
            $productsList[$key]['count'] = $productsData[$product->id]['outgoingCount'];
            $productsList[$key]['price'] = $productsData[$product->id]['price'];
            $productsList[$key]['discount'] = $productsData[$product->id]['discount'];
        }

        $uri = session()->get('incomingOrder') ? '/payment-type/success' : '';

        $this->data = [
            'companyName' => $this->company->title,
            'docNo' => $incomingOrder->doc_no,
            'customerName' => $customerName,
            'productsList' => $productsList,
            'units' => $this->units,
            'paymentType' => $paymentType->title,
            'currency' => $this->company->currency->symbol,
            'createdAt' => $incomingOrder->created_at,
            'cashierName' => $incomingOrder->cashier_name,
            'prevPage' => '/'.$this->lang.'/cashdesk'.$uri,
        ];

        $this->view = 'check';
    }

    public function incomingOrder($id)
    {
        $incomingOrder = IncomingOrder::find($id);
        $paymentType = PaymentType::find($incomingOrder->payment_type_id);
        $paymentDetail = json_decode($incomingOrder->payment_detail, true);
        $customerName = 'No name';

        if (isset($paymentDetail['userId'])) {
            $user = User::find($paymentDetail['userId']);
            $customerName = $user->name.' '.$user->lastname;
        }

        $productsData = json_decode($incomingOrder->products_data, true) ?? [];
        $products = Product::whereIn('id', array_keys($productsData))->get();
        $productsList = [];

        foreach($products as $key => $product) {
            $productsList[$key]['title'] = $product->title;
            $productsList[$key]['count'] = $productsData[$product->id]['outgoingCount'];
            $productsList[$key]['price'] = $productsData[$product->id]['price'];
            $productsList[$key]['discount'] = $productsData[$product->id]['discount'];
            $productsList[$key]['barcodes'] = $productsData[$product->id]['barcodes'];
        }

        $uri = session()->get('incomingOrder') ? '/payment-type/success' : '';

        $this->data = [
            'companyName' => $this->company->title,
            'companyBin' => $this->company->bin,
            'docNo' => $incomingOrder->doc_no,
            'createdAt' => $incomingOrder->created_at,
            'productsList' => $productsList,
            'units' => $this->units,
            'currency' => $this->company->currency->symbol,
            'customerName' => $customerName,
            'cashierName' => $incomingOrder->cashier_name,
            'paymentType' => $paymentType->title,
            'prevPage' => '/'.$this->lang.'/cashdesk'.$uri,
        ];

        $this->view = 'incoming-order';
    }

    public function outgoingOrder($id)
    {
        $outgoingOrder = OutgoingOrder::find($id);
        $customerName = 'No name';

        if (!is_null($outgoingOrder->clientId)) {
            $user = User::find($paymentDetail['userId']);
            $customerName = $user->name.' '.$user->lastname;
        }

        $productsData = json_decode($outgoingOrder->products_data, true) ?? [];
        $products = Product::whereIn('id', array_keys($productsData))->get();
        $productsList = [];

        foreach($products as $key => $product) {
            $productsList[$key]['title'] = $product->title;
            $productsList[$key]['count'] = $productsData[$product->id]['outgoingCount'];
            $productsList[$key]['price'] = $productsData[$product->id]['price'];
            $productsList[$key]['discount'] = $productsData[$product->id]['discount'];
            $productsList[$key]['barcodes'] = $productsData[$product->id]['barcodes'];
        }

        $this->data = [
            'companyName' => $this->company->title,
            'companyBin' => $this->company->bin,
            'docNo' => $outgoingOrder->doc_no,
            'createdAt' => $outgoingOrder->created_at,
            'outgoingAmount' => $outgoingOrder->sum,
            'productsList' => $productsList,
            'units' => $this->units,
            'currency' => $this->company->currency->symbol,
            'customerName' => $customerName,
            'cashierName' => $outgoingOrder->cashier_name,
            'prevPage' => '/'.$this->lang.'/cashdesk',
        ];

        $this->view = 'outgoing-order';
    }

    public function incomingDoc($id)
    {
        $incomingDoc = IncomingDoc::find($id);
        $paymentType = PaymentType::find($incomingDoc->payment_type_id);
        $paymentDetail = json_decode($incomingDoc->payment_detail, true);
        $customerName = 'No name';

        if (isset($paymentDetail['userId'])) {
            $user = User::find($paymentDetail['userId']);
            $customerName = $user->name.' '.$user->lastname;
        }

        $productsData = json_decode($incomingDoc->products_data, true) ?? [];
        $products = Product::whereIn('id', array_keys($productsData))->get();
        $productsList = [];

        foreach($products as $key => $product) {
            $productsList[$key]['title'] = $product->title;
            $productsList[$key]['count'] = $productsData[$product->id]['outgoingCount'];
            $productsList[$key]['price'] = $productsData[$product->id]['price'];
            $productsList[$key]['discount'] = $productsData[$product->id]['discount'];
            $productsList[$key]['barcodes'] = $productsData[$product->id]['barcodes'];
        }

        $uri = session()->get('incomingDoc') ? '/payment-type/success' : '';

        $this->data = [
            'companyName' => $this->company->title,
            'companyBin' => $this->company->bin,
            'docNo' => $incomingDoc->doc_no,
            'createdAt' => $incomingDoc->created_at,
            'productsList' => $productsList,
            'units' => $this->units,
            'currency' => $this->company->currency->symbol,
            'customerName' => $customerName,
            'cashierName' => $incomingDoc->cashier_name,
            'paymentType' => $paymentType->title,
            'prevPage' => '/'.$this->lang.'/cashdesk'.$uri,
        ];

        $this->view = 'incoming-doc';
    }

    public function outgoingDoc($id)
    {
        $outgoingDoc = OutgoingDoc::find($id);
        $cashDoc = CashDoc::where('doc_id', $outgoingDoc->id)->first();
        $incomingOrder = IncomingOrder::find($cashDoc->order_id);
        $paymentType = PaymentType::find($incomingOrder->payment_type_id);
        $paymentDetail = json_decode($incomingOrder->payment_detail, true);
        $customerName = 'No name';

        if (isset($paymentDetail['userId'])) {
            $user = User::find($paymentDetail['userId']);
            $customerName = $user->name.' '.$user->lastname;
        }

        $productsData = json_decode($outgoingDoc->products_data, true) ?? [];
        $products = Product::whereIn('id', array_keys($productsData))->get();
        $productsList = [];

        foreach($products as $key => $product) {
            $productsList[$key]['title'] = $product->title;
            $productsList[$key]['count'] = $productsData[$product->id]['outgoingCount'];
            $productsList[$key]['unit'] = $product->unit;
            $productsList[$key]['price'] = $productsData[$product->id]['price'];
            $productsList[$key]['discount'] = $productsData[$product->id]['discount'];
            $productsList[$key]['barcodes'] = $productsData[$product->id]['barcodes'];
        }

        $uri = session()->get('incomingDoc') ? '/payment-type/success' : '';

        $this->data = [
            'companyName' => $this->company->title,
            'companyBin' => $this->company->bin,
            'docNo' => $outgoingDoc->doc_no,
            'createdAt' => $outgoingDoc->created_at,
            'productsList' => $productsList,
            'units' => $this->units,
            'currency' => $this->company->currency->symbol,
            'customerName' => $customerName,
            'cashierName' => $outgoingDoc->cashier_name,
            // 'paymentType' => $paymentType->title,
            'prevPage' => '/'.$this->lang.'/cashdesk'.$uri,
        ];

        $this->view = 'outgoing-doc';
    }

    public function render()
    {
        return view('livewire.docs.'.$this->view, $this->data)
            ->layout('livewire.docs.layout');
    }
}
