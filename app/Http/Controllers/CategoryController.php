<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    //
    public function index()
    {
        $categories = Category::orderBy('name')->get();
        return view('tenant.admin.categories.index', compact('categories'));
    }
    public function store(StoreCategory $request) {
        $validated = $request->validated();
        $category = Category::create([
            'name' => $validated['name'],
            'organization_id' => tenant()->organization_id
        ]);
        return back()->withSuccess('Added ' . $category->name);
    }
}
