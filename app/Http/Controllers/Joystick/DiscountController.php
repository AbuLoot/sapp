<?php

namespace App\Http\Controllers\Joystick;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Http\Controllers\Joystick\Controller;
use App\Models\Discount;
use App\Models\Category;

class DiscountController extends Controller
{
    public function index()
    {
        // $this->authorize('viewAny', Discount::class);

        $discounts = Discount::paginate(50);

        return view('joystick.discounts.index', compact('discounts'));
    }

    public function create($lang)
    {
        // $this->authorize('create', Discount::class);
        $categories = Category::orderBy('sort_id')->get()->toTree();

        return view('joystick.discounts.create', ['categories' => $categories]);
    }

    public function store(Request $request)
    {
        // $this->authorize('create', Discount::class);

        $this->validate($request, [
            'percent' => 'required|max:2',
        ]);

        $discount = new Discount;
        $discount->category_id = $request->category_id;
        $discount->percent = $request->percent;
        $discount->start_date = $request->start_date;
        $discount->end_date = $request->end_date;
        $discount->sum = $request->sum;
        $discount->save();

        return redirect($request->lang.'/admin/discounts')->with('status', 'Запись добавлена.');
    }

    public function edit($lang, $id)
    {
        $discount = Discount::findOrFail($id);
        $categories = Category::orderBy('sort_id')->get()->toTree();

        // $this->authorize('update', $discount);

        return view('joystick.discounts.edit', compact('categories', 'discount'));
    }

    public function update(Request $request, $lang, $id)
    {
        $this->validate($request, [
            'percent' => 'required|max:2',
        ]);

        $discount = Discount::findOrFail($id);

        // $this->authorize('update', $discount);

        $discount->category_id = $request->category_id;
        $discount->percent = $request->percent;
        $discount->start_date = $request->start_date;
        $discount->end_date = $request->end_date;
        $discount->sum = $request->sum;
        // $discount->status = ($request->status == 'on') ? 1 : 0;
        $discount->save();

        return redirect($lang.'/admin/discounts')->with('status', 'Запись обновлена.');
    }

    public function destroy($lang, $id)
    {
        $discount = Discount::find($id);

        // $this->authorize('delete', $discount);

        $discount->delete();

        return redirect($lang.'/admin/discounts')->with('status', 'Запись удалена.');
    }
}