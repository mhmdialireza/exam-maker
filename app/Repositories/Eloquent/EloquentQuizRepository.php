<?php

namespace App\Repositories\Eloquent;

use App\Models\Quiz;
use App\Entities\Quiz\QuizEntity;
use App\Entities\Quiz\QuizEloquentEntity;
use App\Repositories\Contracts\QuizRepositoryInterface;

class EloquentQuizRepository extends EloquentBaseRepository implements QuizRepositoryInterface
{
    protected $model = Quiz::class;

    public function create(array $data)
    {
        $newQuiz =  parent::create($data);

        return new QuizEloquentEntity($newQuiz);
    }

    public function update(int $id, array $data): QuizEntity
    {
        parent::update($id, $data);
//        dd(QuizEloquentEntity(parent::find($id)));
        return new QuizEloquentEntity(parent::find($id));
    }
}
