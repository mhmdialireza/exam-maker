<?php

namespace Tests\Unit\Api\V1;

use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use App\Repositories\Contracts\QuizRepositoryInterface;

class QuizTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh');
    }

    public function test_ensure_that_we_can_create_a_new_quiz()
    {
        $category = $this->createCategories()[0];

        $startDate = Carbon::now()->format('Y-m-d H:i:s');
        $duration = 90;
        $quizData = [
            'category_id' => $category->getId(),
            'title' => 'x',
            'description' => 'this is a new quiz for test',
            'start_date' => $startDate,
            'duration' => $duration,
            'is_active' => true,
        ];

        $response = $this->call('POST', 'api/v1/quizzes', $quizData);

        $quizData['end_date'] = Carbon::parse($quizData['start_date'])->addMinutes($quizData['duration'])->toDateTimeString();
        $quizData['start_date'] = Carbon::parse($quizData['start_date'])->toDateTimeString();
        unset($quizData['duration']);

        $responseData = json_decode($response->getContent(), true)['data'];

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertDatabaseHas('quizzes', $quizData);
        $this->assertEquals($quizData['category_id'], $responseData['category_id']);
        $this->assertEquals($quizData['title'], $responseData['title']);
        $this->assertEquals($quizData['start_date'], $responseData['start_date']);
        $this->assertEquals($quizData['end_date'], $responseData['end_date']);
        $this->assertEquals($quizData['is_active'], $responseData['is_active']);

        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'category_id',
                'title',
                'description',
                'start_date',
                'end_date',
                'is_active',
            ],
        ]);
    }

     public function test_ensure_that_we_can_delete_a_quiz()
     {
         $quiz = $this->createQuiz()[0];

         $response = $this->call('DELETE', 'api/v1/quizzes', [
             'id' => $quiz->getId(),
         ]);

         $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
         $response->assertJsonStructure([
             'success',
             'message',
             'data',
         ]);
     }

     public function test_ensure_that_we_can_get_quizzes()
     {
         $this->createQuiz(30);

         $pageSize = 3;

         $response = $this->call('GET', 'api/v1/quizzes', [
             'page' => 1,
             'page_size' => $pageSize,
         ]);

         $data = json_decode($response->getContent(), true);

         $this->assertEquals($pageSize, count($data['data']));
         $this->assertEquals(200, $response->status());
         $response->assertJsonStructure([
             'success',
             'message',
             'data',
         ]);
     }

     public function test_ensure_we_can_get_filtered_quiz()
     {
         $this->createQuiz(30);
         $category = $this->createCategories()[0];
         $startDate = Carbon::now()->addDays(4)->toDateTimeString();

         $searchKey = 'specific-quiz';

         $this->createQuiz(1, [
             'category_id' => $category->getId(),
             'title' => $searchKey,
             'description' => 'this is the specific quiz',
             'duration' => 30,
             'start_date' => $startDate,
         ]);

         $pageSize = 3;

         $response = $this->call('GET', 'api/v1/quizzes', [
             'page' => 1,
             'search' => $searchKey,
             'page_size' => $pageSize,
         ]);

         $data = json_decode($response->getContent(), true);

         foreach ($data['data'] as $quiz) {
             $this->assertEquals($quiz['title'], $searchKey);
         }

         $this->assertEquals(200, $response->status());
         $response->assertJsonStructure([
             'success',
             'message',
             'data',
         ]);
     }

     public function test_ensure_we_can_update_a_quiz()
     {
         $quiz = $this->createQuiz()[0];
         $category = $this->createCategories()[0];
         $startDate = Carbon::now()->addDays(5)->toDateTimeString();

         $quizData = [
             'id' => $quiz->getId(),
             'category_id' => $category->getId(),
             'title' => 'quiz updated',
             'description' => 'this is a updated quiz for test',
             'start_date' => $startDate,
             'duration' => 20,
             'is_active' => false,
         ];

         $response = $this->call('PUT', 'api/v1/quizzes', $quizData);

         $data = json_decode($response->getContent(), true)['data'];

         $this->assertEquals(200, $response->getStatusCode());

         $this->assertEquals($data['title'], $quizData['title']);
         $this->assertEquals($data['start_date'], $quizData['start_date']);
         $this->assertEquals($data['is_active'], $quizData['is_active']);
         $response->assertJsonStructure([
             'success',
             'message',
             'data' => [
                 'category_id',
                 'title',
                 'description',
                 'end_date',
                 'start_date',
                 'is_active'
             ],
         ]);
     }

     private function createQuiz(int $count = 1, array $data = []): array
     {
         $quizRepository = $this->app->make(QuizRepositoryInterface::class);

         $category = $this->createCategories()[0];

         $startDate = Carbon::now()->addDay()->toDateTimeString();

         $quizData = empty($data) ? [
             'category_id' => $category->getId(),
             'title' => 'Quiz 1',
             'description' => 'this is a test quiz',
             'duration' => 90,
             'start_date' => $startDate,
         ] : $data;

         $quizzes = [];

         foreach (range(0, $count) as $item) {
             $quizzes[] = $quizRepository->create($quizData);
         }

         return $quizzes;
     }
}
