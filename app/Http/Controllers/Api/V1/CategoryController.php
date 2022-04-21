<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\Category\IndexRequest;
use App\Http\Requests\Api\V1\Category\StoreRequest;
use App\Http\Requests\Api\V1\Category\DeleteRequest;
use App\Http\Requests\Api\V1\Category\UpdateRequest;
use App\Http\Controllers\Api\Contracts\ApiController;
use App\Repositories\Contracts\CategoryRepositoryInterface;

class CategoryController extends ApiController
{
    public function __construct(private CategoryRepositoryInterface $categoryRepository)
    {
    }

    public function index(IndexRequest $request)
    {
        $categories = $this->categoryRepository->paginate($request->search, $request->page, $request->pagesize ?? 20, ['name', 'slug']);

        return $this->respondSuccess('دسته بندی ها', $categories);
    }

    public function store(StoreRequest $request)
    {
        $createdCategory = $this->categoryRepository->create([
            'name' => $request->name,
            'slug' => $request->slug,
        ]);
        return $this->respondCreated('دسته بندی ایجاد شد', [
            'name' => $createdCategory->getName(),
            'slug' => $createdCategory->getSlug(),
        ]);
    }

    public function update(UpdateRequest $request)
    {
        $updatedUser = $this->categoryRepository->update($request->id, [
            'name' => $request->name,
            'slug' => $request->slug,
        ]);

        return $this->respondSuccess('دسته بندی بروزرسانی شد', [
            'name' => $updatedUser->getName(),
            'slug' => $updatedUser->getSlug(),
        ]);
    }

    public function delete(DeleteRequest $request)
    {
        if (!$this->categoryRepository->find($request->id)) {
            return $this->respondNotFound('دسته بندی وجود ندارد');
        }

        if (!$this->categoryRepository->delete($request->id)) {
            return $this->respondInternalError('دسته بندی حذف نشد');
        }

        return $this->respondSuccess('دسته بندی حذف شد', []);
    }
}
