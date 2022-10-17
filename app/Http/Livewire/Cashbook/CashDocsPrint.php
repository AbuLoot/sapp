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
        $cashier = User::find($incomingOrder->user_id);
        $customerName = 'No name';
        $paymentTypeTitle = null;

        if (!is_null($incomingOrder->payment_type_id)) {

            $paymentType = PaymentType::find($incomingOrder->payment_type_id);
            $paymentDetail = json_decode($incomingOrder->payment_detail, true);
        }


        if (!is_null($incomingOrder->contractor_type)) {
            $user = User::find($incomingOrder->contractor_id);
            $customerName = $user->name.' '.$user->lastname;
        }

        $productsData = json_decode($incomingOrder->products_data, true) ?? [];
        $products = Product::whereIn('id', array_keys($productsData))->get();

        foreach($products as $key => $product) {
            $productsData[$product->id]['title'] = $product->title;
            $productsData[$product->id]['barcodes'] = json_decode($product->barcodes, true) ?? [];
        }

        $this->data = [
            'companyName' => $this->company->title,
            'incomingOrder' => $incomingOrder,
            'customer' => $user ?? null,
            'customerName' => $customerName,
            'productsData' => $productsData,
            'units' => $this->units,
            'paymentTypeTitle' => $paymentTypeTitle,
            'currency' => $this->company->currency->symbol,
            'cashierName' => $cashier->name.' '.$cashier->lastname,
            'prevPage' => url()->previous(),
        ];

        $this->view = 'check';
    }

    public function incomingOrder($id)
    {
        $incomingOrder = IncomingOrder::find($id);
        $cashier = User::find($incomingOrder->user_id);
        $customerName = 'No name';
        $paymentTypeTitle = null;

        if (!is_null($incomingOrder->payment_type_id)) {

            $paymentType = PaymentType::find($incomingOrder->payment_type_id);
            $paymentDetail = json_decode($incomingOrder->payment_detail, true);
        }


        if (!is_null($incomingOrder->contractor_type)) {
            $user = User::find($incomingOrder->contractor_id);
            $customerName = $user->name.' '.$user->lastname;
        }

        $productsData = json_decode($incomingOrder->products_data, true) ?? [];
        $products = Product::whereIn('id', array_keys($productsData))->get();

        foreach($products as $key => $product) {
            $productsData[$product->id]['title'] = $product->title;
            $productsData[$product->id]['barcodes'] = json_decode($product->barcodes, true) ?? [];
        }

        $uri = session()->get('incomingOrderId') ? '/payment-type/success' : '';

        $this->data = [
            'companyName' => $this->company->title,
            'companyBin' => $this->company->bin,
            'incomingOrder' => $incomingOrder,
            'productsData' => $productsData,
            'units' => $this->units,
            'currency' => $this->company->currency->symbol,
            'customer' => $user ?? null,
            'customerName' => $customerName,
            'cashierName' => $cashier->name.' '.$cashier->lastname,
            'paymentTypeTitle' => $paymentTypeTitle,
            'prevPage' => url()->previous(),
        ];

        $this->view = 'incoming-order';
    }

    public function outgoingOrder($id)
    {
        $outgoingOrder = OutgoingOrder::find($id);
        $cashier = User::find($outgoingOrder->user_id);
        $customerName = 'No name';

        if (!is_null($outgoingOrder->contractor_type)) {
            $user = User::find($outgoingOrder->contractor_id);
            $customerName = $user->name.' '.$user->lastname;
        }

        $this->data = [
            'companyName' => $this->company->title,
            'companyBin' => $this->company->bin,
            'outgoingOrder' => $outgoingOrder,
            'units' => $this->units,
            'currency' => $this->company->currency->symbol,
            'customerName' => $customerName,
            'cashierName' => $cashier->name.' '.$cashier->lastname,
            'prevPage' => url()->previous(),
        ];

        $this->view = 'outgoing-order';
    }

    public function incomingDoc($id)
    {
        $incomingDoc = IncomingDoc::find($id);
        $contractorName = 'No name';

        $productsData = json_decode($incomingDoc->products_data, true) ?? [];
        $products = Product::whereIn('id', array_keys($productsData))->get();

        foreach($products as $key => $product) {
            $productsData[$product->id]['title'] = $product->title;
            $productsData[$product->id]['barcodes'] = json_decode($product->barcodes, true) ?? [];
        }

        if ($incomingDoc->contractorType == 'App\Models\Company') {
            $contractorName = $incomingDoc->contractor->titles;
        } elseif ($incomingDoc->contractorType == 'App\Models\User') {
            $contractorName = $incomingDoc->contractor->name.' '.$incomingDoc->contractor->lastname;
        }

        $this->data = [
            'companyName' => $this->company->title,
            'storeTitle' => $incomingDoc->storeDoc->store->title,
            'companyBin' => $this->company->bin,
            'incomingDoc' => $incomingDoc,
            'productsData' => $productsData,
            'units' => $this->units,
            'currency' => $this->company->currency->symbol,
            'contractorName' => $contractorName,
            'prevPage' => url()->previous(),
        ];

        $this->view = 'incoming-doc';
    }

    public function outgoingDoc($id)
    {
        $outgoingDoc = OutgoingDoc::find($id);

        $customerName = 'No name';

        if (!is_null($outgoingDoc->storeDoc->order_id)) {
            $incomingOrder = IncomingOrder::find($outgoingDoc->storeDoc->order_id);
            $paymentType = PaymentType::find($incomingOrder->payment_type_id);
            $paymentDetail = json_decode($incomingOrder->payment_detail, true);
        }

        if (!is_null($incomingOrder->contractor_type)) {
            $user = User::find($incomingOrder->contractor_id);
            $customerName = $user->name.' '.$user->lastname;
        }

        $productsData = json_decode($outgoingDoc->products_data, true) ?? [];
        $products = Product::whereIn('id', array_keys($productsData))->get();

        foreach($products as $key => $product) {
            $productsData[$product->id]['title'] = $product->title;
            $productsData[$product->id]['unit'] = $product->unit;
            $productsData[$product->id]['barcodes'] = json_decode($product->barcodes, true) ?? [];
        }

        $this->data = [
            'companyName' => $this->company->title,
            'companyBin' => $this->company->bin,
            'outgoingDoc' => $outgoingDoc,
            'productsData' => $productsData,
            'units' => $this->units,
            'currency' => $this->company->currency->symbol,
            'customerName' => $customerName,
            'prevPage' => url()->previous(),
        ];

        $this->view = 'outgoing-doc';
    }

    public function render()
    {
        return view('livewire.docs.'.$this->view, $this->data)
            ->layout('livewire.docs.layout');
    }
}
