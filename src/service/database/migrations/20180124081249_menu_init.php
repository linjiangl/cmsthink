<?php

use think\migration\Migrator;
use Phinx\Db\Adapter\MysqlAdapter;

class MenuInit extends Migrator
{
	protected $table = 'menu';

	public function up()
	{
		$table = $this->table($this->table, ['id' => false, 'primary_key' => 'id', 'engine' => 'MyISAM', 'comment' => '菜单']);

		$table->addColumn('id', 'integer', ['signed' => false, 'limit' => MysqlAdapter::INT_MEDIUM, 'identity' => true])
			->addColumn('pid', 'integer', ['signed' => false, 'limit' => MysqlAdapter::INT_MEDIUM, 'default' => 0, 'comment' => '父级ID'])
			->addColumn('title', 'string', ['limit' => 50, 'comment' => '标题'])
			->addColumn('router', 'string', ['limit' => 50, 'comment' => 'router->name'])
			->addColumn('auth_group_ids', 'string', ['limit' => 100, 'default' => '', 'comment' => '分组ID集合:1,2,3'])
			->addColumn('sort', 'boolean', ['signed' => false, 'limit' => 2, 'default' => 0, 'comment' => '排序'])
			->addColumn('status', 'boolean', ['signed' => false, 'limit' => 2, 'default' => 1, 'comment' => '状态{0:禁用,1正常}'])
			->addColumn('create_time', 'integer', ['signed' => false, 'limit' => 11, 'default' => 0, 'comment' => '创建时间'])
			->addColumn('update_time', 'integer', ['signed' => false, 'limit' => 11, 'default' => 0, 'comment' => '修改时间'])
			->addIndex('router', ['unique' => true])
			->addIndex('pid')
			->addIndex('status')
			->create();
	}

	public function down()
	{
		$this->dropTable($this->table);
	}
}
