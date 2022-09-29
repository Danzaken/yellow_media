<?php
namespace App\Services;

use App\Models\User;
use App\Providers\AuthServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserService
{
    protected $authServiceProvider;

    public function __construct()
    {
    }

    public function register($data)
    {
        $data['password'] = Hash::make($data['password']);
        $user = new User($data);
        $user->save();
        return $user->getAttributes();
    }

    public function login($email, $password)
    {
        $user = $this->getUser($email);
        if (!Hash::check($password, $user->password)) {
            new HttpException(400, 'Wrong email or password');
        }
        $user->api_token = base64_encode(random_bytes(32));
        return $user->save() ? $user->api_token : false;
    }
    public function resetPassword(string $email)
    {
        $user = $this->getUser($email);
        $user->reset_token = base64_encode(random_bytes(32));
        $user->save();
        return $user->reset_token;
    }
    public function getUserValidateConfig()
    {
        return [
            'first_name' => 'required|min:3',
            'last_name' => 'required|min:2',
            'email' => 'required|min:10',
            'phone' => 'required|min:8',
        ];
    }

    public function getUser($email)
    {
        $user =  User::query()->where(['email' => $email])->first();
        if ($user === null) {
            throw new HttpException(400, 'Not found user with email: ' . $email);
        }
        return $user;
    }

}
