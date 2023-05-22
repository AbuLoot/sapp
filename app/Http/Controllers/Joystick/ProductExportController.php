<?php

namespace App\Http\Controllers\Joystick;

use App\Http\Controllers\Joystick\Controller;
use App\Models\Product;

use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;

class ProductExportController extends Controller
{
    public $companyId;

    public function export()
    {
        if (! Gate::allows('export', \Auth::user())) {
            abort(403);
        }

        $products = Product::where('in_company_id', $this->companyId)->get();
        $types = [1 => 'Товар', 2 => 'Услуга'];

        return (new FastExcel($products))->download('products.xlsx', function ($product) use ($types) {

            $barcodes = json_decode($product->barcodes, true);

            return [
                'Title' => $product->title,
                'Category' => $product->category->title,
                'Company' => $product->company->title,
                'Code' => $barcodes,
                'Purchase price' => $product->purchase_price,
                'Wholesale price' => $product->wholesale_price,
                'Price' => $product->price,
                'Count' => $product->count,
                'Type' => $types[$product->type],
            ];
        });
    }
}
