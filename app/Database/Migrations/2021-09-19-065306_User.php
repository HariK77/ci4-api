<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class User extends Migration
{
	public function up()
	{
		$this->forge->addField([
			'id'          => [
				'type'           => 'INT',
				'constraint'     => 11,
				'unsigned'       => true,
				'auto_increment' => true,
			],
			'name'       => [
				'type'       => 'VARCHAR',
				'constraint' => '100',
			],
			'email' => [
				'type' => 'VARCHAR',
				// 'null' => true,
				'unique' => true,
				'constraint' => '250',
			],
			'password' => [
				'type' => 'VARCHAR',
				// 'null' => true,
				'constraint' => 250
			],
			'created_at' => [
				'type' => 'TIMESTAMP',
				'null' => true,
			],
			'updated_at' => [
				'type' => 'TIMESTAMP',
				'null' => true,
			],
			'deleted_at' => [
				'type' => 'TIMESTAMP',
				'null' => true,
			],
		]);
		$this->forge->addKey('id', true);
		$this->forge->createTable('users');
	}

	public function down()
	{
		$this->forge->dropTable('users');
	}
}
