<?php

use think\migration\Migrator;
use Phinx\Db\Adapter\MysqlAdapter;

class MenuActionInit extends Migrator
{
	protected $table = 'menu_action';

	public function up()
	{
		$table = $this->table($this->table, ['id' => false, 'primary_key' => 'id', 'engine' => 'MyISAM', 'comment' => '菜单对应页面的操作']);

		$table->addColumn('id', 'integer', ['signed' => false, 'limit' => 11, 'identity' => true])
			->addColumn('menu_id', 'integer', ['signed' => false, 'limit' => MysqlAdapter::INT_MEDIUM, 'default' => 0, 'comment' => 'menu->id'])
			->addColumn('title', 'string', ['limit' => 50, 'default' => '', 'comment' => '菜单名称'])
			->addColumn('sign', 'string', ['limit' => 50, 'default' => '', 'comment' => '英文标记'])
			->addColumn('sort', 'boolean', ['signed' => false, 'limit' => 2, 'default' => 0, 'comment' => '排序'])
			->addIndex('menu_id')
			->create();
	}

	public function down()
	{
		$this->dropTable($this->table);
	}
}
