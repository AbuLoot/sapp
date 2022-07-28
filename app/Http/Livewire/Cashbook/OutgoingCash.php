<?php

namespace App\Http\Livewire\Cashbook;

use Livewire\Component;

use App\Models\DocType;
use App\Models\CashDoc;
use App\Models\OutgoingOrder;

class OutgoingCash extends Component
{
    public $amount;
    public $comment;
    public $cashbook;
    public $alert = false;
    public $outgoingCash = false;

    protected $rules = [
        'amount' => 'required|numeric|min:2',
        'comment' => 'required|max:2000',
    ];

    public function mount()
    {
        $this->company = auth()->user()->profile->company;
        $this->cashbook = $this->company->cashbooks->first();
    }

    public function generateDocNo($cashbook_id, $docNo = null)
    {
        if (is_null($docNo)) {

            $lastDoc = OutgoingOrder::where('doc_no', 'like', $cashbook_id.'/_')->orderByDesc('id')->first();

            if ($lastDoc) {
                list($first, $second) = explode('/', $lastDoc->doc_no);
                $docNo = $first.'/'.++$second;
            } elseif (is_null($docNo)) {
                $docNo = $cashbook_id.'/1';
            }
        }

        $existDoc = OutgoingOrder::where('doc_no', $docNo)->first();

        if ($existDoc) {
            list($first, $second) = explode('/', $docNo);
            $docNo = $first.'/'.++$second;
            $this->generateDocNo($cashbook_id, $docNo);
        }

        return $docNo;
    }

    public function debit()
    {
        $this->validate();

        // Outgoing Order
        $docType = DocType::where('slug', 'forma-ko-2')->first();

        $docNo = $this->generateDocNo($this->cashbook->id);

        $outgoingOrder = new OutgoingOrder;
        $outgoingOrder->cashbook_id = $this->cashbook->id;
        $outgoingOrder->company_id = $this->company->id;
        $outgoingOrder->user_id = auth()->user()->id;
        $outgoingOrder->cashier_name = auth()->user()->name;
        $outgoingOrder->doc_no = $docNo;
        $outgoingOrder->doc_type_id = $docType->id;
        $outgoingOrder->to_contractors = json_encode([$this->company->title]);
        $outgoingOrder->sum = $this->amount;
        $outgoingOrder->currency = $this->company->currency->code;
        $outgoingOrder->count = 0;
        $outgoingOrder->comment = $this->comment;
        $outgoingOrder->save();

        $cashDoc = new CashDoc;
        $cashDoc->cashbook_id = $this->cashbook->id;
        $cashDoc->company_id = $this->company->id;
        $cashDoc->user_id = auth()->user()->id;
        $cashDoc->doc_id = $outgoingOrder->id;
        $cashDoc->doc_type_id = $docType->id;
        $cashDoc->from_contractor = auth()->user()->name;
        $cashDoc->to_contractor = $this->company->title;
        $cashDoc->incoming_amount = 0;
        $cashDoc->outgoing_amount = $this->amount;
        $cashDoc->sum = $this->amount;
        $cashDoc->currency = $this->company->currency->code;
        $cashDoc->comment = $this->comment;
        $cashDoc->save();

        $this->emitUp('newData');
        $this->dispatchBrowserEvent('close-modal');
    }

    public function render()
    {
        return view('livewire.cashbook.outgoing-cash');
    }
}
