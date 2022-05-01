<?php

namespace App\Http\Controllers\API;

use App\Exceptions\HttpResponse\InternalServerError;
use App\Exceptions\HttpResponse\NotFound;
use App\Exceptions\ResourceNotFound;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Products;
// use Validator;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ProductResource;
use App\Models\Category;

class ProductController extends BaseController
{

    public function index()
    {
        try {
            /** @var Products|null */
            // $product = Products::all()->load('categories');
            // return ProductResource::collection($product);
            $product = Products::with(['categories'])->get();
            return ProductResource::collection($product);
        } catch (ResourceNotFound $e) {
            throw new NotFound($e->getMessage());
        } catch (\Throwable $e) {
            throw new InternalServerError();
        }
        // $product = Products::all();
        // return $this->sendResponse(ProductResource::collection($product), 'Product retrieved successfully.');
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'product_name' => 'required',
            'category_id' => 'required',
            'product_thumb' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:1000',
            'product_description' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        } else {

            $file = $request->file('product_thumb');
            $fileName = $file->hashName();
            $request->file('product_thumb')->storeAs('public/images/product_thumb', $fileName);

            $product = new Products;
            $product->product_name = $request->product_name;
            $product->category_id = $request->category_id;
            $product->product_description = $request->product_description;
            $product->product_thumb = $fileName;
            $product->save();
        }
        return $this->sendResponse(new ProductResource($product), 'Product created successfully.');
    }

    public function show($id)
    {
        try {
            /** @var Products|null */
            $product = Products::findByIdOrFail($id)->load('categories');
            return ProductResource::make($product);
        } catch (ResourceNotFound $e) {
            throw new NotFound($e->getMessage());
        } catch (\Throwable $e) {
            throw new InternalServerError();
        }
    }

    public function update(Request $request, Products $product)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'product_name' => 'required',
            'category_id' => 'required',
            'product_description' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $product->product_name = $request->product_name;
        $product->category_id = $request->category_id;
        $product->product_description = $request->product_description;
        $product->save();

        return $this->sendResponse(new ProductResource($product), 'Product updated successfully.');
    }


    public function destroy(Products $product)
    {
        $product->delete();

        return $this->sendResponse([], 'Product deleted successfully.');
    }
}
