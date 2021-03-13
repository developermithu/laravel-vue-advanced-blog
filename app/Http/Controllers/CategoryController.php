<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function index()
    {
        // return Category::orderBy('id', 'desc')->get();
        return Category::latest()->get();
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'categoryName' => 'required | unique:categories',
            'categoryImage' => 'required'
        ]);

        return Category::create([
            'categoryName' => $request->categoryName,
            'categoryImage' => $request->categoryImage
        ]);
    }

    public function update(Request $request)
    {
        // $category = Category::where('id', $request->id)->get();
        // if ($request->categoryName == $category->categoryName) {
        //     $this->validate($request, [
        //         'categoryName' => 'required',
        //         'categoryImage' => 'required'
        //     ]);
        // } else {
        //     $this->validate($request, [
        //         'categoryName' => 'required | unique:categories',
        //         'categoryImage' => 'required'
        //     ]);
        // }

        $this->validate($request, [
            'categoryName' => 'required',
            'categoryImage' => 'required'
        ]);

        return Category::where('id', $request->id)->update([
            'categoryName' => $request->categoryName,
            'categoryImage' => $request->categoryImage
        ]);

        // $category->categoryName = $request->categoryName;
        // $category->categoryImage = $request->categoryImage;
        // $category->save();
    }

    public function destroy()
    {
        //
    }
}
