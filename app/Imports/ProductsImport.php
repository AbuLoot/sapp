<?php

namespace App\Imports;

use Illuminate\Support\Str;

use App\Models\Product;
use App\Models\Category;
use App\Models\Company;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductsImport implements ToModel, WithHeadingRow, WithChunkReading, SkipsEmptyRows
{
    use SkipsFailures;

    private $user_id;
    private $categories;
    private $products;
    private $products_count;
    private $companies;

    public function __construct()
    {
        $this->user_id = auth()->user()->id;
        $this->categories = Category::select('id', 'slug', 'title')->get();
        $this->companies = Category::select('id', 'slug', 'title')->get();
        $this->products_count = Product::count();
    }

    public function rules()
    {
        return [
            'kategorii' => function($attribute, $value, $onFailure) {
                if (empty($value)) {
                    $onFailure('Неверно указана категория.'.$value);
                }
            }
        ];
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return false;
        $category = $this->categories->where('title', trim($row['kategorii']))->first();
        $company = $this->companies->where('title', trim($row['kompanii']))->first();

        if (is_null($row['naimenovanie']) || is_null($category) || is_null($company)) {
            return null;
        }

        return new Product([
            'sort_id' => ++$this->products_count,
            'user_id' => $this->user_id,
            'category_id' => $category->id,
            'company_id' => $company->id ?? 0,
            'project_id' => $_REQUEST['project_id'] ?? 0,
            'title' => $row['naimenovanie'],
            'slug' => Str::slug($row['naimenovanie']),
            'meta_title' => $row['naimenovanie'].' '.$row['artikul'] ?? $row['naimenovanie'].' '.$row['part_nomer'],
            'meta_description' => $row['naimenovanie'].' - '.$category->title.' '.$row['artikul'] ?? $row['naimenovanie'].' - '.$category->title.' '.$row['part_nomer'],
            'barcode' => $row['shtrihkod'] ?? NULL,
            'id_code' => $row['artikul'] ?? NULL,
            'wholesale_price' => (int) str_replace(" ", "", $row['cena_optovaya']) ?? 0,
            'price' => (int) str_replace(" ", "", $row['cena']) ?? 0,
            'count' => $row['kolicestvo'] ?? 0,
            'type' => ($row['tip'] == 'Товар') ? 1 : 2,
            'image' => 'no-image-middle.png',
            'lang' => 'ru',
            'status' => 1
        ]);
    }

    public function chunkSize(): int
    {
        return 500;
    }
}
