<?php

namespace App\Http\Livewire\Cashbook;

use Livewire\Component;

use App\Models\DocType;
use App\Models\CashDoc;
use App\Models\IncomingOrder;

use App\Traits\GenerateDocNo;

class IncomingCash extends Component
{
    use GenerateDocNo;

    public $amount;
    public $comment;
    public $company;
    public $cashbook;

    protected $listeners = [
        'incomingCashInput',
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

    public function incomingCashInput($value)
    {
        $property = $value[1];
        $this->$property = $value[0];
    }

    public function credit()
    {
        $this->validate();

        $workplaceId = session()->get('cashdeskWorkplace');

        // Incoming Order
        $docType = DocType::where('slug', 'forma-ko-1')->first();

        $docNo = $this->generateIncomingCashDocNo($this->cashbook->id);

        $incomingOrder = new IncomingOrder;
        $incomingOrder->cashbook_id = $this->cashbook->id;
        $incomingOrder->company_id = $this->company->id;
        $incomingOrder->user_id = auth()->user()->id;
        $incomingOrder->workplace_id = $workplaceId;
        $incomingOrder->doc_no = $docNo;
        $incomingOrder->doc_type_id = $docType->id;
        $incomingOrder->products_data = null;
        $incomingOrder->contractor_type = 'App\Models\User';
        $incomingOrder->contractor_id = auth()->user()->id;
        $incomingOrder->operation_code = 'incoming-cash';
        $incomingOrder->payment_type_id = null;
        $incomingOrder->payment_detail = null;
        $incomingOrder->sum = $this->amount;
        $incomingOrder->currency = $this->company->currency->code;
        $incomingOrder->count = 0;
        $incomingOrder->comment = $this->comment;
        $incomingOrder->save();

        $cashDoc = new CashDoc;
        $cashDoc->cashbook_id = $this->cashbook->id;
        $cashDoc->company_id = $this->company->id;
        $cashDoc->user_id = auth()->user()->id;
        $cashDoc->order_type = 'App\Models\IncomingOrder';
        $cashDoc->order_id = $incomingOrder->id;
        $cashDoc->doc_id = null;
        $cashDoc->contractor_type = 'App\Models\User';
        $cashDoc->contractor_id = auth()->user()->id;
        $cashDoc->incoming_amount = $this->amount;
        $cashDoc->outgoing_amount = 0;
        $cashDoc->sum = $this->amount;
        $cashDoc->currency = $this->company->currency->code;
        $cashDoc->comment = $this->comment;
        $cashDoc->save();

        $this->dispatchBrowserEvent('show-toast', [
            'message' => 'Операция выполнена', 'selector' => 'closeIncomingCash'
        ]);
    }

    public function render()
    {
        return view('livewire.cashbook.incoming-cash');
    }
}
