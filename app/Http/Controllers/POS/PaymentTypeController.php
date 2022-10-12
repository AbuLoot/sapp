<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\POS\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\PaymentType;

class PaymentTypeController extends Controller
{
    public function index()
    {
        // $this->authorize('viewAny', PaymentType::class);

        $payment_types = PaymentType::paginate(50);

        return view('pos.payment_types.index', compact('payment_types'));
    }

    public function create($lang)
    {
        // $this->authorize('create', PaymentType::class);

        return view('pos.payment_types.create');
    }

    public function store(Request $request)
    {
        // $this->authorize('create', PaymentType::class);

        $this->validate($request, [
            'title' => 'required|min:2|max:80|unique:payment_types',
        ]);

        $payment_type = new PaymentType;

        $payment_type->title = $request->title;
        $payment_type->slug = (empty($request->slug)) ? Str::slug($request->title) : $request->slug;
        $payment_type->image = $request->image;
        $payment_type->description = $request->description;
        $payment_type->lang = $request->lang;
        $payment_type->save();

        return redirect($request->lang.'/pos/payment_types')->with('status', 'Запись добавлена.');
    }

    public function edit($lang, $id)
    {
        $payment_type = PaymentType::findOrFail($id);

        // $this->authorize('update', $payment_type);

        return view('pos.payment_types.edit', compact('payment_type'));
    }

    public function update(Request $request, $lang, $id)
    {
        $this->validate($request, [
            'title' => 'required|min:2|max:80',
        ]);

        $payment_type = PaymentType::findOrFail($id);

        // $this->authorize('update', $payment_type);

        $payment_type->title = $request->title;
        $payment_type->slug = (empty($request->slug)) ? Str::slug($request->title) : $request->slug;
        $payment_type->image = $request->image;
        $payment_type->description = $request->description;
        $payment_type->lang = $request->lang;
        $payment_type->save();

        return redirect($lang.'/pos/payment_types')->with('status', 'Запись обновлена.');
    }

    public function destroy($lang, $id)
    {
        $payment_type = PaymentType::find($id);

        // $this->authorize('delete', $payment_type);

        $payment_type->delete();

        return redirect($lang.'/pos/payment_types')->with('status', 'Запись удалена.');
    }
}
