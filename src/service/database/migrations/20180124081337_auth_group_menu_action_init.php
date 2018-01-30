<?php

use think\migration\Migrator;
use Phinx\Db\Adapter\MysqlAdapter;

class AuthGroupMenuActionInit extends Migrator
{
	protected $table = 'auth_group_menu_action';

	public function up()
	{
		$table = $this->table($this->table, ['id' => false, 'engine' => 'MyISAM', 'comment' => '权限组菜单']);

		$table->addColumn('group_id', 'integer', ['signed' => false, 'limit' => MysqlAdapter::INT_TINY, 'default' => 0, 'comment' => 'authGroup->id'])
			->addColumn('menu_action_id', 'integer', ['signed' => false, 'limit' => 11, 'default' => 0, 'comment' => 'menuAction->id'])
			->addIndex(['group_id', 'menu_action_id'], ['unique' => true, 'name' => 'group_menu_action'])
			->addIndex('group_id')
			->create();
	}

	public function down()
	{
		$this->dropTable($this->table);
	}
}
