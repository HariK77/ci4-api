<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\User;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as ReaderXlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExcelController extends BaseController
{

	protected $user;

	public function __construct()
	{
		$this->user = new User();
	}

	public function export()
	{
		$users = $this->user->findAll();

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		$sheet->setCellValue('A1', 'Id');
		$sheet->setCellValue('B1', 'Name');
		$sheet->setCellValue('C1', 'Email');
		$sheet->setCellValue('D1', 'Password');

		$sheet->getColumnDimension('A')->setAutoSize(true);
		$sheet->getColumnDimension('B')->setAutoSize(true);
		$sheet->getColumnDimension('C')->setAutoSize(true);
		$sheet->getColumnDimension('D')->setAutoSize(true);

		$rows = 2;
		foreach ($users as $user) {
			$sheet->setCellValue('A' . $rows, $user->id);
			$sheet->setCellValue('B' . $rows, $user->name);
			$sheet->setCellValue('C' . $rows, $user->email);
			$sheet->setCellValue('D' . $rows, $user->password);
			$rows++;
		}

		$writer = new Xlsx($spreadsheet);
		$filePath = 'uploads/users.xlsx';
		$writer->save($filePath);

		header("Content-Type: application/vnd.ms-excel");
		header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize($filePath));
		flush(); // Flush system output buffer
		readfile($filePath);
		exit;
	}

	public function import()
	{
		$rules = array(
			'file' => 'uploaded[file]|max_size[file,2048]|ext_in[file,xlsx]'
		);

		if (!$this->validate($rules)) {
			$data = array(
				'errors' => $this->validator->getErrors()
			);
			return $this->failValidationErrors($data, 422, 'Validation failed');
		}

		$reader = new ReaderXlsx();
		$reader->setReadDataOnly(true);
		$spreadsheet = $reader->load($this->request->getFile('file'));
		$data = $spreadsheet->getActiveSheet()->toArray();

		$response = [
            'status' => 200,
            'error' => null,
            'messages' => "Users List",
            "data" => $data,
        ];

		return $this->respond($response);
	}

	public function validation()
	{
		
	}
}
