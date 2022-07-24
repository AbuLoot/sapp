<?php

namespace App\Http\Livewire\Cashbook;

use Livewire\Component;

use App\Models\DocType;
use App\Models\CashDoc;
use App\Models\IncomingOrder;

class IncomingCash extends Component
{
    public $amount;
    public $comment;
    public $cashbook;
    public $alert = false;
    public $incomingCash = false;

    protected $rules = [
        'amount' => 'required|numeric|min:2',
        'comment' => 'required|max:2000',
    ];

    public function mount()
    {
        $company = auth()->user()->profile->company;
        $this->cashbook = $company->first();
    }

    public function generateDocNo($cashbook_id, $docNo = null)
    {
        if (is_null($docNo)) {

            $lastDoc = IncomingOrder::where('doc_no', 'like', $cashbook_id.'/_')->orderByDesc('id')->first();

            if ($lastDoc) {
                list($first, $second) = explode('/', $lastDoc->doc_no);
                $docNo = $first.'/'.++$second;
            } elseif (is_null($docNo)) {
                $docNo = $cashbook_id.'/1';
            }
        }

        $existDoc = IncomingOrder::where('doc_no', $docNo)->first();

        if ($existDoc) {
            list($first, $second) = explode('/', $docNo);
            $docNo = $first.'/'.++$second;
            $this->generateDocNo($cashbook_id, $docNo);
        }

        return $docNo;
    }

    public function test()
    {
        $this->dispatchBrowserEvent('close-modal');
    }

    public function credit()
    {
        $this->validate();

        $company = auth()->user()->profile->company;
        $cashbook = $company->cashbooks->first();

        // Incoming Order
        $docType = DocType::where('slug', 'forma-ko-1')->first();

        $docNo = $this->generateDocNo($cashbook->id);

        $incomingOrder = new IncomingOrder;
        $incomingOrder->cashbook_id = $cashbook->id;
        $incomingOrder->company_id = $company->id;
        $incomingOrder->user_id = auth()->user()->id;
        $incomingOrder->cashier_name = auth()->user()->name;
        $incomingOrder->doc_no = $docNo;
        $incomingOrder->doc_type_id = $docType->id;
        $incomingOrder->products_data = null;
        $incomingOrder->from_contractor = null;
        $incomingOrder->payment_type_id = null;
        $incomingOrder->payment_detail = null;
        $incomingOrder->sum = $this->amount;
        $incomingOrder->currency = $company->currency->code;
        $incomingOrder->count = 0;
        $incomingOrder->comment = $this->comment;
        $incomingOrder->save();

        $cashDoc = new CashDoc;
        $cashDoc->cashbook_id = $cashbook->id;
        $cashDoc->company_id = $company->id;
        $cashDoc->user_id = auth()->user()->id;
        $cashDoc->doc_id = $incomingOrder->id;
        $cashDoc->doc_type_id = $docType->id;
        $cashDoc->from_contractor = auth()->user()->name;
        $cashDoc->to_contractor = $company->title;
        $cashDoc->incoming_amount = $this->amount;
        $cashDoc->outgoing_amount = 0;
        $cashDoc->sum = $this->amount;
        $cashDoc->currency = $company->currency->code;
        $cashDoc->comment = $this->comment;
        $cashDoc->save();

        $this->emitUp('newData');
        $this->dispatchBrowserEvent('close-modal');
    }

    public function render()
    {
        return view('livewire.cashbook.incoming-cash');
    }
}
