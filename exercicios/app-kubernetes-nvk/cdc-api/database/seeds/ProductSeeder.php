<?php

use App\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('products_not_found')->delete();
        // DB::table('products_not_found')->truncate();
        // DB::table('products')->delete();
        // DB::table('products')->truncate();
        //

        // factory(Product::class, 10)->create();
        $path = database_path('data');
        $file = File::get($path . '/products.json');

        $products = collect(json_decode($file, true));

        $products->each(function ($product) {
            Product::create([
                'name' => $product['name'],
                'description' => $product['description'],
                'ml' => $product['ml'],
                'type' => $product['type'],
                'barcode_ean' => $product['barcode_ean'],
                'barcode_dun' => $product['barcode_dun'],
            ]);
        });
    }
}
