<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Support\QueryBuilder\AggregateInclude;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = QueryBuilder::for(Product::class)
            ->allowedIncludes(
                'category',
                'brand',
                'uom',
                AllowedInclude::custom('stocks', new AggregateInclude('quantity', 'sum'), 'transactions as stocks')
            )
            ->allowedFilters(
                AllowedFilter::partial('code'),
                AllowedFilter::partial('unique_name'),
                AllowedFilter::partial('category', 'category.name'),
                AllowedFilter::partial('brand', 'brand.name'),
                AllowedFilter::partial('uom', 'uom.long_name'),
            )
            ->allowedSorts(
                AllowedSort::field('category', 'category.name'),
                AllowedSort::field('brand', 'brand.name'),
                AllowedSort::field('uom', 'uom.long_name'),
            )
            ->paginate(request()->per_page);

        return ProductResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        $product = Product::create($request->validated());

        return new ProductResource($product);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\ProductRequest  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, Product $product)
    {
        $product->update($request->validated());

        return new ProductResource($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->noContent();
    }
}
