<?php

use think\migration\Migrator;
use Phinx\Db\Adapter\MysqlAdapter;

class AuthGroupInit extends Migrator
{
	protected $table = 'auth_group';

	public function up()
	{
		$table = $this->table($this->table, ['id' => false, 'primary_key' => 'id', 'engine' => 'MyISAM', 'comment' => '权限分组']);

		$table->addColumn('id', 'integer', ['signed' => false, 'limit' => MysqlAdapter::INT_TINY, 'identity' => true])
			->addColumn('pid', 'integer', ['signed' => false, 'limit' => MysqlAdapter::INT_MEDIUM, 'default' => 0, 'comment' => '父级ID'])
			->addColumn('title', 'string', ['limit' => 20, 'default' => '', 'comment' => '标题'])
			->addColumn('name', 'string', ['limit' => 20, 'default' => '', 'comment' => '名称'])
			->addColumn('status', 'boolean', ['signed' => false, 'limit' => 2, 'default' => 1, 'comment' => '状态{1:正常,-1:禁用}'])
			->addIndex('pid')
			->addIndex('name')
			->addIndex('status')
			->create();

		$data = [
			[
				'id'    => 1,
				'title' => '管理员',
				'name'  => 'Admin',
			],
			[
				'id'    => 2,
				'title' => '编辑管理员',
				'name'  => 'ManagingEditor',
			],
			[
				'id'    => 3,
				'title' => '编辑员',
				'pid'   => 2,
				'name'  => 'Editor',
			],
			[
				'id'    => 4,
				'title' => '广告管理员',
				'name'  => 'Adv',
			],
		];
		$this->insert($this->table, $data);
	}

	public function down()
	{
		$this->dropTable($this->table);
	}
}
