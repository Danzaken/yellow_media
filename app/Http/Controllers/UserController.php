<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\CompanyService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class UserController extends Controller
{
    private Request $request;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function register(UserService $userService)
    {
        $valid = $this->validate($this->request, $userService->getUserValidateConfig());
        if (!$valid) {
            return new HttpException(400, implode(',', $valid));
        }
        return response()->json($userService->register($this->request->all()));
    }

    /**
     * @param UserService $userService
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function signIn(UserService $userService)
    {
        try {
            if (!$this->validate($this->request, $this->getSignInValidateConfig())) {
                return response()->json(['status' => 'fail'], 401);
            }
            $token = $userService->login($this->request['email'], $this->request['password']);
            if (!empty($token)) {
                return response()->json(['status' => 'success', 'api_token' => $token]);
            }
            return response()->json(['status' => 'fail'], 401);
        } catch (HttpException $exception) {
            return $this->getResponseByException($exception);
        } catch (Throwable $exception) {
            var_dump($exception);
            return response()->json(['status' => 'fail'], 400);
        }
    }

    public function recoverPassword(UserService $userService)
    {
        try {
            if (!$this->validate($this->request, $this->getResetPasswordValidateConfig())) {
                return response()->json(['status' => 'fail'], 401);
            }
            $token = $userService->resetPassword($this->request->input('email'));
            return response()->json(['status' => 'success', 'reset_token' => $token]);
        } catch (HttpException $exception) {
            return $this->getResponseByException($exception);
        } catch (Throwable $exception) {
            return response()->json(['status' => 'fail'], 400);
        }
    }

    public function getCompanies(UserService $userService, CompanyService $companyService)
    {
        if (!$this->validate($this->request, $this->getGetCompaniesValidateConfig())) {
            return response()->json(['status' => 'fail'], 401);
        }
        /** @var User $user */
        $user = $userService->getUser($this->request->input('email'));
        return response()->json($companyService->getCompanies($user));
    }

    public function addCompanies(UserService $userService, CompanyService $companyService)
    {
        try {
            if (!$this->validate($this->request, $this->getAddCompaniesValidateConfig())) {
                return response()->json(['status' => 'fail'], 401);
            }
            /** @var User $user */
            $user = $userService->getUser($this->request->input('email'));
            $companyService->addCompanies($user, $this->request['companies']);
            return response()->json(['status' => 'success']);
        } catch (Throwable $exception) {
            return response()->json(['status' => 'fail'], 401);
        }
    }

    private function getAddCompaniesValidateConfig()
    {
        return [
            'email' => 'required|min:3',
            'companies' => 'required',
        ];
    }

    private function getGetCompaniesValidateConfig()
    {
        return [
            'email' => 'required|min:3',
        ];
    }

    private function getSignInValidateConfig()
    {
        return [
            'email' => 'required|min:3',
            'password' => 'required|min:3',
        ];
    }
    private function getResetPasswordValidateConfig()
    {
        return [
            'email' => 'required|min:3',
        ];
    }

    private function getResponseByException(HttpException $exception)
    {
        return response()->json(['status' => 'fail', 'message' => $exception->getMessage()], $exception->getCode());
    }
}
