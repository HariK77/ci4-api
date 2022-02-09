<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\JWTAuth;
use App\Models\User;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use ReflectionException;

class AuthController extends BaseController
{

    function __construct()
    {
        $this->userModel = new User();
        $this->JWTAuth = new JWTAuth();
    }

    /**
     * Register a new user
     * @return Response
     * @throws ReflectionException
     */
    public function register()
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|min_length[6]|max_length[50]|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]|max_length[255]',
            'password_confirmation' => 'required|matches[password]',
        ];

        if (!$this->validate($rules)) {
            $data = array(
                'errors' => $this->validator->getErrors()
            );
            return $this->failValidationErrors($data, 422, 'Validation failed');
        }

        $requestData = $this->request->getPost();
        $userId = $this->userModel->insert($requestData);

        $user = $this->userModel->find($userId);
        return $this->respondCreated($user, 'Registered successfully');
    }

    /**
     * Authenticate Existing User
     * @return Response
     */
    public function login()
    {
        $rules = [
            'email' => 'required|min_length[6]|max_length[50]|valid_email|checkEmailRegistered[email]',
            'password' => 'required|min_length[8]|max_length[255]|validateUser[email, password]'
        ];

        $errorMessages = [
            'password' => [
                'validateUser' => 'Invalid username or password'
            ],
            'email' => [
                'checkEmailRegistered' => 'This email is not registered in our system'
            ]
        ];

        if (!$this->validate($rules, $errorMessages)) {
            $data = array(
                'errors' => $this->validator->getErrors()
            );
            return $this->failValidationErrors($data, 422, 'Validation failed');
        }

        $user = $this->userModel->where('email', $this->request->getPost('email'))->first();
        unset($user->password);

        $accessToken = $this->JWTAuth->getSignedJWTForUser($user->email);
        $user->access_token = $accessToken;

        return $this->respond($user, null, 'User Authenticated Successfully');
    }
}
