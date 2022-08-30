<?php

namespace App\Http\Livewire\Cashbook;

use Livewire\Component;
use Livewire\WithPagination;

use App\Http\Livewire\Cashbook\Index as CashbookIndex;

use App\Models\PaymentType;
use App\Models\User;
use App\Models\Profile;
use App\Models\Product;
use App\Models\DocType;
use App\Models\CashDoc;
use App\Models\IncomingOrder;

use App\Traits\GenerateDocNo;

class ListOfDebtors extends Component
{
    use GenerateDocNo, WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $lang;
    public $company;
    public $profile;
    public $repaymentAmount;
    public $docNo;
    public $paymentTypes = [];
    public $paymentTypeId;

    protected $rules = [
        'profile' => 'required|numeric|min:2',
        'repaymentAmount' => 'required|numeric',
    ];

    protected $listeners = ['refresh' => '$refresh'];

    public function mount()
    {
        $this->lang = app()->getLocale();
        $this->company = auth()->user()->profile->company;
        $this->paymentTypes = PaymentType::where('slug', '!=', 'sale-on-credit')->get();
        $this->paymentTypeId = $this->paymentTypes->where('slug', 'bank-card')->pluck('id')->first();
    }

    public function repayFor($debtor_id)
    {
        $this->profile = Profile::find($debtor_id);
        $this->dispatchBrowserEvent('show-modal');
    }

    public function repay()
    {
        $debtSum = $this->profile->debt_sum;

        if ($this->repaymentAmount > $debtSum || $this->repaymentAmount <= 0) {
            $this->addError('message', 'Неверные данные');
            return;
        }

        $profile = Profile::find($this->profile->id);
        $debtOrders = json_decode($profile->debt_orders, true) ?? [];
        $debtOrdersNew = [];
        $repaymentAmount = $this->repaymentAmount;

        foreach($debtOrders as $key => $debtOrder) {

            $balance = $debtOrder['sum'] - $repaymentAmount;

            if ($balance < 0) {
                $repaymentAmount = abs($balance);
                $profile->debt_sum -= $debtOrder['sum'];
            } elseif ($balance == 0) {
                $repaymentAmount = 0;
                $profile->debt_sum = 0;
            } else {
                $profile->debt_sum -= $repaymentAmount;
                $debtOrdersNew[$key]['sum'] = $balance;
                $debtOrdersNew[$key]['docNo'] = $debtOrder['docNo'];
            }
        }

        $profile->debt_orders = json_encode($debtOrdersNew);

        if ($profile->debt_sum == 0) {
            $profile->is_debtor = null;
        }

        $profile->save();

        $paymentDetail['typeId'] = $this->paymentTypeId;
        $paymentDetail['type'] = $this->paymentTypes->where('id', $this->paymentTypeId)->pluck('slug')->first();
        $paymentDetail['user_id'] = $this->profile->user_id;
        $paymentDetail['repaymentAmount'] = $this->repaymentAmount;

        $this->makeRepaymentDocs($paymentDetail);
        $this->dispatchBrowserEvent('show-toast', [
            'message' => 'Операция выполнена', 'selector' => 'closeRepaymentOfDebt'
        ]);
    }

    public function makeRepaymentDocs($paymentDetail)
    {
        $store = session()->get('store');
        $cashbook = $this->company->cashbooks->first();

        // Incoming Order
        $docType = DocType::where('slug', 'forma-ko-1')->first();

        $cashDocNo = $this->generateIncomingCashDocNo($cashbook->id);

        $incomingOrder = new IncomingOrder;
        $incomingOrder->cashbook_id = $cashbook->id;
        $incomingOrder->company_id = $this->company->id;
        $incomingOrder->user_id = auth()->user()->id;
        $incomingOrder->cashier_name = auth()->user()->name;
        $incomingOrder->doc_no = $cashDocNo;
        $incomingOrder->doc_type_id = $docType->id;
        $incomingOrder->products_data = null;
        $incomingOrder->from_contractor = $this->profile->user_id;
        $incomingOrder->payment_type_id = $paymentDetail['typeId'];
        $incomingOrder->payment_detail = json_encode($paymentDetail);
        $incomingOrder->sum = $this->repaymentAmount;
        $incomingOrder->currency = $this->company->currency->code;
        $incomingOrder->count = 0;
        // $incomingOrder->comment = $this->comment;
        $incomingOrder->save();

        // Cashbook
        $cashDoc = new CashDoc;
        $cashDoc->cashbook_id = $cashbook->id;
        $cashDoc->company_id = $this->company->id;
        $cashDoc->user_id = auth()->user()->id;
        $cashDoc->doc_id = $incomingOrder->id;
        $cashDoc->doc_type_id = $docType->id;
        $cashDoc->from_contractor = $store->title;
        $cashDoc->to_contractor = $cashbook->title; // $this->company->title;
        $cashDoc->incoming_amount = $this->repaymentAmount;
        $cashDoc->outgoing_amount = 0;
        $cashDoc->sum = $this->repaymentAmount;
        $cashDoc->currency = $this->company->currency->code;
        // $cashDoc->comment = $this->comment;
        $cashDoc->save();
    }

    public function render()
    {
        $debtors = Profile::where('is_debtor', 1)->paginate(30);

        return view('livewire.cashbook.list-of-debtors', ['debtors' => $debtors]);
    }
}
