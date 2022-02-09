<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\User;

class UserController extends BaseController
{
	protected $user;

	public function __construct()
	{
		$this->user = new User();
	}

	/**
	 * Return an array of resource objects, themselves in array format
	 *
	 * @return mixed
	 */
	public function index()
	{
		$isDeleted = $this->request->getVar('deleted');
		$type = $this->request->getVar('type');
		$limit = $this->request->getVar('limit');

		// if ((int) $isDeleted) {
		// 	if ($type) {
		// 		$users = $this->user->where('type', $type)->onlyDeleted()->findAll();
		// 	} else {
		// 		$users = $this->user->onlyDeleted()->findAll();
		// 	}
		// } else {
		// 	if ($type) {
		// 		$users = $this->user->where('type', $type)->findAll();
		// 	} else {
		// 		$users = $this->user->findAll();
		// 	}
		// }

		$builder = $this->user->builder();

		if ($type) {
			$builder->where('type', $type);
		}
		if ((int) $isDeleted) {
			$builder->where('deleted_at !=', '');
		} else {
			$builder->where('deleted_at =', null);
		}

		if ($limit) {
			$builder->limit($limit);
		}

		$users = $builder->get()->getResult();

		$response = [
            'status' => 200,
            'error' => null,
            'messages' => "Users List",
            "data" => $users,
        ];

		return $this->respond($response);
	}

	/**
	 * Create a new resource object, from "posted" parameters
	 *
	 * @return mixed
	 */
	public function store()
	{
		$rules = array(
			'name' => 'required|min_length[3]',
			'email' => 'required|valid_email|is_unique[users.email]|min_length[6]',
			'password' => 'required|min_length[6]',
			'password_confirmation' => 'required|matches[password]',
		);

		if (!$this->validate($rules)) {
			$data = array(
				'errors' => $this->validator->getErrors()
			);
			return $this->failValidationErrors($data, 422, 'Validation failed');
		}

		$requestData = $this->request->getVar();
		// $requestData['password'] = password_hash($this->request->getVar('password'), PASSWORD_DEFAULT);

		$this->user->insert($requestData);

		$response = [
            'status' => 201,
            'error' => null,
            'messages' => "Registered successfully",
        ];

		return $this->respond($response);
	}

	/**
	 * Return the properties of a resource object
	 *
	 * @return mixed
	 */
	public function show($id = null)
	{
		$user = $this->user->where(['id' => $id])->first();

		if ($user) {
			$response = [
				'status' => 200,
				'error' => null,
				'messages' => "User Found",
				"data" => $user,
			];
			return $this->respond($response);
		} else {
			return $this->failNotFound('No User Found with id ' . $id);
		}
	}

	/**
	 * Return the editable properties of a resource object
	 *
	 * @return mixed
	 */
	public function edit($id = null)
	{
		
	}

	/**
	 * Add or update a model resource, from "posted" properties
	 *
	 * @return mixed
	 */
	public function update($id = null)
	{
		$rules = array(
			'name' => 'if_exist|min_length[3]',
			'email' => 'if_exist|valid_email|is_unique[users.email,id,'.$id.']|min_length[6]',
		);

		if (!$this->validate($rules)) {
			$data = array(
				'errors' => $this->validator->getErrors()
			);
			return $this->failValidationErrors($data, 422, 'Validation failed');
		}

		$data = $this->request->getVar();

		unset($data['_method']);

		if (count($data)) {
			$this->user->update($id, $data);

			$response = [
				'status' => 200,
				'error' => null,
				'messages' => "User has been Updated."
			];
			return $this->respondUpdated($response);
		} else {
			return $this->fail('There is not data to update');
		}

	}

	/**
	 * Delete the designated resource object from the model
	 *
	 * @return mixed
	 */
	public function delete($id = null)
	{
		$data = $this->user->find($id);

		if ($data) {
			$this->user->delete($id);
			$response = [
				'status' => 200,
				'error' => null,
				'messages' => "User has been soft Deleted",
			];
			return $this->respondDeleted($response);
		} else {
			return $this->failNotFound('No User Found with id ' . $id);
		}
	}

}