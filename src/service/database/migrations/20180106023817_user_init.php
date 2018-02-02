<?php

use think\migration\Migrator;

class UserInit extends Migrator
{
	protected $table = 'user';

	public function up()
	{
		$table = $this->table($this->table, ['id' => false, 'primary_key' => 'id', 'comment' => '用户表', 'collation' => 'utf8mb4_general_ci']);

		$table->addColumn('id', 'integer', ['signed' => false, 'identity' => true, 'limit' => 11])
			->addColumn('username', 'string', ['limit' => 30, 'default' => '', 'comment' => '账号'])
			->addColumn('nickname', 'string', ['limit' => 20, 'default' => '', 'comment' => '昵称'])
			->addColumn('avatar', 'string', ['limit' => 255, 'default' => '', 'comment' => '头像'])
			->addColumn('mobile', 'string', ['limit' => 20, 'default' => '', 'comment' => '手机'])
			->addColumn('email', 'string', ['limit' => 50, 'default' => '', 'comment' => '邮箱'])
			->addColumn('password', 'string', ['limit' => 60, 'default' => '', 'comment' => '密码'])
			->addColumn('auth_key', 'string', ['limit' => 40, 'default' => '', 'comment' => '授权码'])
			->addColumn('status', 'boolean', ['signed' => false, 'limit' => 2, 'default' => 10, 'comment' => '状态{10:正常,11:禁用}'])
			->addColumn('type', 'boolean', ['signed' => false, 'limit' => 2, 'default' => 10, 'comment' => '类型{10:普通,11:管理}'])
			->addColumn('reg_ip', 'integer', ['signed' => false, 'limit' => 11, 'default' => 0, 'comment' => '注册IP'])
			->addColumn('last_login_ip', 'integer', ['signed' => false, 'limit' => 11, 'default' => 0, 'comment' => '最后登录IP'])
			->addColumn('last_login_time', 'integer', ['signed' => false, 'limit' => 11, 'default' => 0, 'comment' => '最后登录时间'])
			->addColumn('create_time', 'integer', ['signed' => false, 'limit' => 11, 'default' => 0, 'comment' => '创建时间'])
			->addColumn('update_time', 'integer', ['signed' => false, 'limit' => 11, 'default' => 0, 'comment' => '修改时间'])
			->addIndex(['username'], ['unique' => true])
			->addIndex(['auth_key'], ['unique' => true])
			->addIndex(['nickname'])
			->addIndex(['mobile'])
			->addIndex(['create_time', 'status'], ['name' => 'create_time_status'])
			->create();

		$data = [
			[
				'id'       => 1,
				'username' => 'admin',
				'nickname' => '管理员',
				'password' => generate_pwd('123456'),
				'auth_key' => generate_auth_key(),
				'type'     => 11
			],
			[
				'id'       => 2,
				'username' => 'su_editor',
				'nickname' => '编辑管理员',
				'password' => generate_pwd('123456'),
				'auth_key' => generate_auth_key(),
				'type'     => 11
			],
			[
				'id'       => 3,
				'username' => 'editor',
				'nickname' => '编辑员',
				'password' => generate_pwd('123456'),
				'auth_key' => generate_auth_key(),
				'type'     => 11
			],
			[
				'id'       => 4,
				'username' => 'su_adv',
				'nickname' => '广告管理员',
				'password' => generate_pwd('123456'),
				'auth_key' => generate_auth_key(),
				'type'     => 11
			],
			[
				'id'       => 5,
				'username' => 'tourist',
				'nickname' => '游客',
				'password' => generate_pwd('123456'),
				'auth_key' => generate_auth_key(),
				'type'     => 11
			],
		];
		$this->insert($this->table, $data);
	}

	public function down()
	{
		$this->dropTable($this->table);
	}
}
