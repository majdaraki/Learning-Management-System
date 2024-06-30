<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

use App\Http\Requests\Api\V1\Admin\{
    CategoryRequest,
    UpdateCategoryRequest
};
use Illuminate\Support\Facades\{
    Auth,
    DB
};
use App\Traits\Media;
class CategoriesController extends Controller
{
    use Media;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::parents()->with('childrens')->get();
        return response()->json($categories);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        return DB::transaction(function () use ($request) {
        $category=Category::create($request->all());
        if ($request->hasFile('image')) {
            $request_image = $request->image;
            $image = $this->setMediaName([$request_image], 'Category')[0];
            $category->image()->create(['name' => $image]);
            $this->saveMedia([$request_image], [$image], 'public');
        }
        return $this->sudResponse('category created successfully');
    });
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return $this->indexOrShowResponse('body',$category->load('childrens'));
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        return DB::transaction(function () use ($request, $category) {
            $teacher = Auth::user();
            $category->update($request->validated());

            if ($request->hasFile('image')) {
                $request_image = $request->image;
                $current_image = $category->image()->pluck('name')->first();
                $image = $this->setMediaName([$request_image], 'Category')[0];
                $category->image()->update(['name' => $image]);
                $this->saveMedia([$request_image], [$image], 'public');
                $this->deleteMedia('storage', [$current_image]);
            }

            return $this->sudResponse('category updated successfully.');
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        return DB::transaction(function () use ($category) {
            $current_image = $category->image()->pluck('name')->first();
            $category->delete();
            $this->deleteMedia(
                'storage',
                array_merge([$current_image])
            );
            return $this->sudResponse('category Deleted Successfully');
        });
    }
}
