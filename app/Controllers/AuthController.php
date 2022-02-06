<?php

namespace App\Controllers;

use App\Controllers\BaseController;
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
        unset($requestData['password_confirmation']);

        $this->userModel->save($requestData);

        return $this->getJWTForUser($requestData['email'], ResponseInterface::HTTP_CREATED);
    }

    /**
     * Authenticate Existing User
     * @return Response
     */
    public function login()
    {
        $rules = [
            'email' => 'required|min_length[6]|max_length[50]|valid_email',
            'password' => 'required|min_length[8]|max_length[255]|validateUser[email, password]'
        ];

        $errors = [
            'password' => [
                'validateUser' => 'Invalid login credentials provided'
            ]
        ];

        $input = $this->getRequestInput($this->request);

        if (!$this->validateRequest($input, $rules, $errors)) {
            return $this->getResponse($this->validator->getErrors(), ResponseInterface::HTTP_BAD_REQUEST);
        }

        return $this->getJWTForUser($input['email']);
    }

    private function getJWTForUser(string $emailAddress, int $responseCode = ResponseInterface::HTTP_OK) {
        try {
            $user = $this->userModel->findByEmail($emailAddress);
            unset($user['password']);

            return $this
                ->getResponse(
                    [
                        'message' => 'User authenticated successfully',
                        'user' => $user,
                        'access_token' => getSignedJWTForUser($emailAddress)
                    ]
                );
        } catch (Exception $exception) {
            return $this
                ->getResponse(
                    [
                        'error' => $exception->getMessage(),
                    ],
                    $responseCode
                );
        }
    }
}
