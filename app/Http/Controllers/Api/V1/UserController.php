<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Api\V1\User\IndexRequest;
use App\Http\Requests\Api\V1\User\StoreRequest;
use App\Http\Requests\Api\V1\User\DeleteRequest;
use App\Http\Requests\Api\V1\User\UpdateInfoRequest;
use App\Http\Controllers\API\contracts\ApiController;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Http\Requests\Api\V1\User\UpdatePasswordRequest;

class UserController extends ApiController
{
    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }

    public function index(IndexRequest $request)
    {
        $users = $this->userRepository->paginate($request->search, $request->page, $request->pagesize ?? 20, ['full_name', 'mobile', 'email']);
        
        return $this->respondSuccess('کابران', $users);
    }

    public function store(StoreRequest $request)
    {
        $newUser = $this->userRepository->create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'password' => Hash::make($request->password),
        ]);

        return $this->respondCreated('کاربر با موفقیت ایجاد شد', [
            'full_name' => $newUser->getFullName(),
            'email' => $newUser->getEmail(),
            'mobile' => $newUser->getMobile(),
            'password' => $newUser->getPassword(),
        ]);
    }

    public function UpdateInfo(updateInfoRequest $request)
    {
        $user = $this->userRepository->update($request->id, [
            'full_name' => $request->full_name,
            'email' => $request->email,
            'mobile' => $request->mobile,
        ]);

        return $this->respondSuccess('کاربر با موفقیت بروزرسانی شد', [
            'full_name' => $user->getFullName(),
            'email' => $user->getEmail(),
            'mobile' => $user->getMobile(),
        ]);
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        try {
            $user = $this->userRepository->update($request->id, [
                'password' => Hash::make($request->password),
            ]);
        } catch (\Exception $e) {
            return $this->respondInternalError('کاربر بروزرسانی نشد');
        }

        return $this->respondSuccess('رمز عبور شما با موفقیت بروزرسانی شد', [
            'full_name' => $user->getFullName(),
            'email' => $user->getEmail(),
            'mobile' => $user->getMobile(),
        ]);
    }

    public function delete(DeleteRequest $request)
    {
        $this->validate($request, [
            'id' => 'required',
        ]);

        if (!$this->userRepository->find($request->id)) {
            return $this->respondNotFound('کاربری با این آیدی وجود ندارد');
        }

        if (!$this->userRepository->delete($request->id)) {
            return $this->respondInternalError('خطایی وجود دارد لطفا مجددا تلاش نمایید');
        }

        return $this->respondSuccess('کاربر باموفقیت حذف شد', []);
    }
}
