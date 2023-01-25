<?php

namespace App\Repositories\Eloquent;

use App\City;
use App\Repositories\Interfaces\ProductNotFoundInterface;
use App\Http\Requests\ProductNotFoundStoreRequest;
use App\Http\Requests\ProductNotFoundUpdateRequest;
use App\Mail\ConfirmationSendMailProductNotFound;
use App\Mail\ProductNotFoundMail;
use App\Product;
use App\ProductNotFound;
use Exception;
use Illuminate\Support\Facades\Mail;

class ProductNotFoundEloquent extends AbstractEloquent implements ProductNotFoundInterface {

    public function __construct() {
        parent::__construct('ProductNotFound');
    }

    public function create(ProductNotFoundStoreRequest $request) {
        $validated = $request->validated();
        $city = City::find($validated['city_id']);
        $managers = $city->managers()->get();
        $products = Product::whereIn('id', $validated['products'])->get();

        try {
            Mail::send(new ProductNotFoundMail([
                'users' => $managers->pluck('email')->toArray(),
                'subject' => 'Produto não encontrado',
                'message' => 'Os produtos ' .
                implode(', ', $products->pluck('name')->toArray()) .
                ' não foram encontrados no estabelecimento '
                . $validated['establishment_name'] . ' em '
                . $validated['establishment_address'] . ' na cidade de '
                . $city->name . ' - ' . $city->state->acronym
            ]));

            $productNotFound = ProductNotFound::create($validated);
            $products->each(function ($product) use ($productNotFound) {
                $productNotFound->products()->attach([
                    'product_id' => $product->id,
                ]);
            });

            Mail::send(new ConfirmationSendMailProductNotFound);

            return  $productNotFound;
        } catch (Exception $exception) {
            throw new Exception("It was not possible to send the email");
        }
    }

    public function update(ProductNotFoundUpdateRequest $request, $model) {
        $model->update($request->all());
        return $model;
    }

    public function delete($model) {
        $model->delete();
    }
}

