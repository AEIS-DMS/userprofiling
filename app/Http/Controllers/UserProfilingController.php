<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;

class UserProfilingController extends Controller
{

    /**
     * The service to consume the documents micro-service
     * @var UserService
     */
    public $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function profileUpdate(Request $request)
    {
        try {
            $params = $request->all();
            return $this->userService->profileUpdate($params);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
