<?php

namespace Tests;

use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function createCategories(int $count = 1): array
    {
        $categoryRepository = $this->app->make(CategoryRepositoryInterface::class);

        $newCategory = [
            'name' => 'new category',
            'slug' => 'new-category',
        ];

        $categories = [];

        foreach (range(0, $count) as $item) {
            $categories[] = $categoryRepository->create($newCategory);
        }

        return $categories;
    }
}
