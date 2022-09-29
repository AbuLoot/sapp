<?php

namespace App\Http\Livewire\Store;

use Livewire\Component;

use App\Models\CashDoc;
use App\Models\IncomingOrder;
use App\Models\OutgoingOrder;
use App\Models\StoreDoc;
use App\Models\IncomingDoc;
use App\Models\OutgoingDoc;
use App\Models\PaymentType;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use App\Models\Unit;

class StoreDocsPrint extends Component
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
            case 'incoming-doc':
                $this->incomingDoc($id);
                break;
            case 'outgoing-doc':
                $this->outgoingDoc($id);
                break;
            case 'inventory-doc':
                $this->inventoryDoc($id);
                break;
            case 'writeoff-doc':
                $this->writeoffDoc($id);
                break;
        }
    }

    public function incomingDoc($id)
    {
        $incomingDoc = IncomingDoc::find($id);
        $contractorName = 'No name';

        $productsData = json_decode($incomingDoc->products_data, true) ?? [];
        $products = Product::whereIn('id', array_keys($productsData))->get();

        foreach($products as $key => $product) {
            $productsData[$product->id]['title'] = $product->title;
            $productsData[$product->id]['unit'] = $product->unit;
            $productsData[$product->id]['barcodes'] = json_decode($product->barcodes);
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
            'docNo' => $incomingDoc->doc_no,
            'createdAt' => $incomingDoc->created_at,
            'productsData' => $productsData,
            'units' => $this->units,
            'currency' => $this->company->currency->symbol,
            'contractorName' => $contractorName,
            'cashierName' => $incomingDoc->cashier_name,
            // 'paymentType' => $paymentType->title,
            // 'paymentDocNo' => $paymentType->title,
            'prevPage' => '/'.$this->lang.'/storage/docs',
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

            if (isset($paymentDetail['userId'])) {
                $user = User::find($paymentDetail['userId']);
                $customerName = $user->name.' '.$user->lastname;
            }
        }

        $productsData = json_decode($outgoingDoc->products_data, true) ?? [];
        $products = Product::whereIn('id', array_keys($productsData))->get();

        foreach($products as $key => $product) {
            $productsData[$product->id]['title'] = $product->title;
            $productsData[$product->id]['unit'] = $product->unit;
            $productsData[$product->id]['barcodes'] = json_decode($product->barcodes);
        }

        $this->data = [
            'companyName' => $this->company->title,
            'companyBin' => $this->company->bin,
            'docNo' => $outgoingDoc->doc_no,
            'createdAt' => $outgoingDoc->created_at,
            'productsData' => $productsData,
            'units' => $this->units,
            'currency' => $this->company->currency->symbol,
            'customerName' => $customerName,
            'cashierName' => $outgoingDoc->cashier_name,
            // 'paymentType' => $paymentType->title,
            'prevPage' => '/'.$this->lang.'/storage/docs/outgoing',
        ];

        $this->view = 'outgoing-doc';
    }

    public function inventoryDoc($id)
    {
        $this->data = [
            'companyName' => $this->company->title,
            'companyBin' => $this->company->bin,
            'docNo' => $outgoingDoc->doc_no,
            'createdAt' => $outgoingDoc->created_at,
            'productsData' => $productsData,
            'units' => $this->units,
            'currency' => $this->company->currency->symbol,
            'customerName' => $customerName,
            'cashierName' => $outgoingDoc->cashier_name,
            // 'paymentType' => $paymentType->title,
            'prevPage' => '/'.$this->lang.'/storage/storedocs',
        ];

        $this->view = 'inventory-doc';
    }

    public function writeoffDoc($id)
    {
        $this->data = [
            'companyName' => $this->company->title,
            'companyBin' => $this->company->bin,
            'docNo' => $outgoingDoc->doc_no,
            'createdAt' => $outgoingDoc->created_at,
            'productsData' => $productsData,
            'units' => $this->units,
            'currency' => $this->company->currency->symbol,
            'customerName' => $customerName,
            'cashierName' => $outgoingDoc->cashier_name,
            // 'paymentType' => $paymentType->title,
            'prevPage' => '/'.$this->lang.'/storage/storedocs',
        ];

        $this->view = 'writeoff-doc';
    }

    public function render()
    {
        return view('livewire.docs.'.$this->view, $this->data)
            ->layout('livewire.docs.layout');
    }
}