<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Category;
use Validator;
use App\Http\Resources\CategoryResource;

class CategoryController extends BaseController
{

    public function index()
    {
        $category = Category::all();
        return $this->sendResponse(CategoryResource::collection($category), 'Category retrieved successfully.');
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'category_name' => 'required',
            'category_description' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $category = Category::create($input);

        return $this->sendResponse(new CategoryResource($category), 'Category created successfully.');
    }

    public function show($id)
    {
        $category = Category::find($id);
        if (is_null($category)) {
            return $this->sendError('Category not found.');
        }
        return $this->sendResponse(new CategoryResource($category), 'Category retrieved successfully.');
    }


    public function update(Request $request, Category $category)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'category_name' => 'required',
            'category_description' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $category->category_name = $input['category_name'];
        $category->category_description = $input['category_description'];
        $category->save();

        return $this->sendResponse(new CategoryResource($category), 'Category updated successfully.');
    }


    public function destroy(Category $category)
    {
        $category->delete();

        return $this->sendResponse([], 'Category deleted successfully.');
    }
}
