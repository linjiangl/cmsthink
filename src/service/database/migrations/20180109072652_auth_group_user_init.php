<?php

use think\migration\Migrator;
use Phinx\Db\Adapter\MysqlAdapter;

class AuthGroupUserInit extends Migrator
{
	protected $table = 'auth_group_user';

	public function up()
	{
		$table = $this->table($this->table, ['id' => false, 'engine' => 'MyISAM', 'comment' => '权限组用户']);

		$table->addColumn('group_id', 'integer', ['signed' => false, 'limit' => MysqlAdapter::INT_TINY, 'default' => 0, 'comment' => 'authGroup->id'])
			->addColumn('user_id', 'integer', ['signed' => false, 'limit' => 11, 'default' => 0, 'comment' => 'authRule->id'])
			->addIndex(['group_id', 'user_id'], ['unique' => true, 'name' => 'group_user'])
			->addIndex('group_id')
			->addIndex('user_id')
			->create();

		$data = [
			[
				'group_id' => 1,
				'user_id'  => 1,
			],
			[
				'group_id' => 2,
				'user_id'  => 2,
			],
			[
				'group_id' => 3,
				'user_id'  => 3,
			],
			[
				'group_id' => 4,
				'user_id'  => 4,
			]
		];
		$this->insert($this->table, $data);
	}

	public function down()
	{
		$this->dropTable($this->table);
	}
}
