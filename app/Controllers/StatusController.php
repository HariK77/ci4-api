<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;

class StatusController extends BaseController
{
	public function index()
	{
		$response = [
			'status' => 'Rest Api is running'
		];

		if ($this->request->getMethod() === 'post') {
			$rules = array(
				'name' => 'required',
			);
	
			$messages = array(
				'name' => array(
					'required' => 'Please send a parameter with name and value as your name.'
				)
			);
	
			if (!$this->validate($rules, $messages)) {
				$data = array(
					'errors' => $this->validator->getErrors()
				);
				return $this->failValidationErrors($data, 422, 'Validation failed');
			}
	
			$response = [
				'status' => "Your post request is successful, you post param is {$this->request->getVar('name')}"
			];
		}

		return $this->respond($response, null, 'Status Ok');
	}

}
