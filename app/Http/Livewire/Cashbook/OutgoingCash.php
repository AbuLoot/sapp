<?php

namespace App\Http\Livewire\Cashbook;

use Livewire\Component;

use App\Models\DocType;
use App\Models\CashDoc;
use App\Models\OutgoingOrder;

use App\Traits\GenerateDocNo;

class OutgoingCash extends Component
{
    use GenerateDocNo;

    public $amount;
    public $comment;
    public $company;
    public $cashbook;

    protected $listeners = [
        'outgoingCashInput',
    ];

    protected $rules = [
        'amount' => 'required|numeric|min:2',
        'comment' => 'required|max:2000',
    ];

    public function mount()
    {
        $this->company = auth()->user()->profile->company;
        $this->cashbook = session()->get('cashbook');
    }

    public function outgoingCashInput($value)
    {
        $property = $value[1];
        $this->$property = $value[0];
    }

    public function debit()
    {
        $this->validate();

        $workplaceId = session()->get('cashdeskWorkplace');

        // Outgoing Order
        $docType = DocType::where('slug', 'forma-ko-2')->first();

        $docNo = $this->generateOutgoingCashDocNo($this->cashbook->id);

        $outgoingOrder = new OutgoingOrder;
        $outgoingOrder->cashbook_id = $this->cashbook->id;
        $outgoingOrder->company_id = $this->company->id;
        $outgoingOrder->user_id = auth()->user()->id;
        $outgoingOrder->workplace_id = $workplaceId;
        $outgoingOrder->doc_no = $docNo;
        $outgoingOrder->doc_type_id = $docType->id;
        $outgoingOrder->contractor_type = 'App\Models\User';
        $outgoingOrder->contractor_id = auth()->user()->id;
        $outgoingOrder->operation_code = 'outgoing-cash';
        $outgoingOrder->sum = $this->amount;
        $outgoingOrder->currency = $this->company->currency->code;
        $outgoingOrder->count = 0;
        $outgoingOrder->comment = $this->comment;
        $outgoingOrder->save();

        $cashDoc = new CashDoc;
        $cashDoc->cashbook_id = $this->cashbook->id;
        $cashDoc->company_id = $this->company->id;
        $cashDoc->user_id = auth()->user()->id;
        $cashDoc->order_type = 'App\Models\OutgoingOrder';
        $cashDoc->order_id = $outgoingOrder->id;
        $cashDoc->doc_id = null;
        $cashDoc->contractor_type = 'App\Models\User';
        $cashDoc->contractor_id = auth()->user()->id;
        $cashDoc->incoming_amount = 0;
        $cashDoc->outgoing_amount = $this->amount;
        $cashDoc->sum = $this->amount;
        $cashDoc->currency = $this->company->currency->code;
        $cashDoc->comment = $this->comment;
        $cashDoc->save();

        $this->dispatchBrowserEvent('show-toast', [
            'message' => 'Операция выполнена', 'selector' => 'closeOutgoingCash'
        ]);
    }

    public function render()
    {
        return view('livewire.cashbook.outgoing-cash');
    }
}
