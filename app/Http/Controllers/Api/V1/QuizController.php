<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\Quiz\UpdateRequest;
use Carbon\Carbon;
use App\Http\Requests\Api\V1\Quiz\IndexRequest;
use App\Http\Requests\Api\V1\Quiz\StoreRequest;
use App\Http\Requests\Api\V1\Quiz\DeleteRequest;
use App\Http\Controllers\Api\Contracts\ApiController;
use App\Repositories\Contracts\QuizRepositoryInterface;

class QuizController extends ApiController
{
    public function __construct(private QuizRepositoryInterface $quizRepository)
    {
    }


    public function index(IndexRequest $request)
    {
        $quizzes = $this->quizRepository->paginate(
            $request->search,
            $request->page,
            $request->page_size ?? 0,
            ['title', 'description', 'start_date', 'end_date','is_active']
        );

        return $this->respondSuccess('آزمون ها', $quizzes);
    }

    public function store(StoreRequest $request)
    {
        $inputs = $request->all();
        $inputs['end_date'] = Carbon::parse($request->start_date)->addMinutes($request->duration)->toDateTimeString();
        $inputs['start_date'] = Carbon::parse($request->start_date)->toDateTimeString();

        $createdQuiz = $this->quizRepository->create($inputs);

        return $this->respondCreated('آزمون ساخته شد', [
            'category_id' => $createdQuiz->getCategoryId(),
            'title' => $createdQuiz->getTitle(),
            'description' => $createdQuiz->getDescription(),
            'start_date' => $createdQuiz->getStartDate(),
            'is_active' => $createdQuiz->getIsActive(),
            'end_date' => $createdQuiz->getEndDate(),
        ]);
    }

    public function delete(DeleteRequest $request)
    {
        if (!$this->quizRepository->find($request->id)) {
            return $this->respondNotFound('آزمون وجود ندارد');
        }

        if (!$this->quizRepository->delete($request->id)) {
            return $this->respondInternalError('آزمون حذف نشد');
        }

        return $this->respondSuccess('آزمون حذف شد', []);
    }

    public function update(UpdateRequest $request)
    {
        $inputs = $request->all();
        $inputs['end_date'] = Carbon::parse($request->start_date)->addMinutes($request->duration)->toDateTimeString();
        $inputs['start_date'] = Carbon::parse($request->start_date)->toDateTimeString();


            $updatedQuiz = $this->quizRepository->update($request->id, [
                'category_id' => $request->category_id,
                'title' => $request->title,
                'description' => $request->description,
                'start_date' => $inputs['start_date'],
                'end_date' => $inputs['end_date'],
                'is_active' => $request->is_active,
            ]);

        return $this->respondSuccess('آزمون بروزرسانی شد', [
            'category_id' => $updatedQuiz->getCategoryId(),
            'title' => $updatedQuiz->getTitle(),
            'description' => $updatedQuiz->getDescription(),
            'start_date' => $updatedQuiz->getStartDate(),
            'end_date' => $updatedQuiz->getEndDate(),
            'is_active' => $updatedQuiz->getIsActive(),
        ]);
    }
}
