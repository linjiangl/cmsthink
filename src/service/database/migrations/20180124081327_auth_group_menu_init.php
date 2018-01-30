<?php

use think\migration\Migrator;
use Phinx\Db\Adapter\MysqlAdapter;

class AuthGroupMenuInit extends Migrator
{
	protected $table = 'auth_group_menu';

	public function up()
	{
		$table = $this->table($this->table, ['id' => false, 'engine' => 'MyISAM', 'comment' => '权限组菜单']);

		$table->addColumn('group_id', 'integer', ['signed' => false, 'limit' => MysqlAdapter::INT_TINY, 'default' => 0, 'comment' => 'authGroup->id'])
			->addColumn('menu_id', 'integer', ['signed' => false, 'limit' => MysqlAdapter::INT_MEDIUM, 'default' => 0, 'comment' => 'menu->id'])
			->addIndex(['group_id', 'menu_id'], ['unique' => true, 'name' => 'group_menu'])
			->addIndex('group_id')
			->create();
	}

	public function down()
	{
		$this->dropTable($this->table);
	}
}
