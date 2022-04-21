<?php

namespace App\Repositories\Eloquent;

use App\Models\Category;
use App\Entities\Category\CategoryEloquentEntity;
use App\Repositories\Contracts\CategoryRepositoryInterface;

class EloquentCategoryRepository extends EloquentBaseRepository implements CategoryRepositoryInterface
{
    protected $model = Category::class;

    public function create(array $data)
    {
        $createdCategory = parent::create($data);

        return new CategoryEloquentEntity($createdCategory);
    }

    public function update(int $id, array $data)
    {
        if(!parent::update($id, $data))
        {
            throw new \RuntimeException('دسته بندی بروزرسانی نشد');
        }

        return new CategoryEloquentEntity(parent::find($id));
    }
}
