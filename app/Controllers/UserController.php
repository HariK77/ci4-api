<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\User;

class UserController extends BaseController
{
	protected $user;

	public function __construct()
	{
		$this->userModel = new User();
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
		// 		$users = $this->userModel->where('type', $type)->onlyDeleted()->findAll();
		// 	} else {
		// 		$users = $this->userModel->onlyDeleted()->findAll();
		// 	}
		// } else {
		// 	if ($type) {
		// 		$users = $this->userModel->where('type', $type)->findAll();
		// 	} else {
		// 		$users = $this->userModel->findAll();
		// 	}
		// }

		$builder = $this->userModel->builder();

		if ($type) {
			$builder->where('type', $type);
		}
		if ((int) $isDeleted) {
			$builder->where('deleted_at IS NOT NULL');
		} else {
			$builder->where('deleted_at =', null);
		}

		if ($limit) {
			$builder->limit($limit);
		}

		$users = $builder->get()->getResult();

		return $this->respond($users, 200, 'User List');
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

		$userId = $this->userModel->insert($requestData);
        $user = $this->userModel->find($userId);

		return $this->respondCreated($user, 'User has been added successfully');
	}

	/**
	 * Return the properties of a resource object
	 *
	 * @return mixed
	 */
	public function show($id = null)
	{
		$user = $this->userModel->where(['id' => $id])->first();

		if ($user) {
			return $this->respond($user, 200, 'User data');
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
			$this->userModel->update($id, $data);
			$user = $this->userModel->find($id);
			return $this->respondUpdated($user, "User has been Updated.");
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
		$data = $this->userModel->find($id);

		if ($data) {
			$this->userModel->delete($id);
			return $this->respondDeleted($data, 'User has been soft Deleted');
		} else {
			return $this->failNotFound('No User Found with id ' . $id);
		}
	}

}