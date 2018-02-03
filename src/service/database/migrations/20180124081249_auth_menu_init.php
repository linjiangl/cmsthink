<?php

use think\migration\Migrator;
use Phinx\Db\Adapter\MysqlAdapter;

class AuthMenuInit extends Migrator
{
	protected $table = 'auth_menu';

	public function up()
	{
		$table = $this->table($this->table, ['id' => false, 'primary_key' => 'id', 'engine' => 'MyISAM', 'comment' => '菜单权限']);

		$table->addColumn('id', 'integer', ['signed' => false, 'limit' => MysqlAdapter::INT_MEDIUM, 'identity' => true])
			->addColumn('pid', 'integer', ['signed' => false, 'limit' => MysqlAdapter::INT_MEDIUM, 'default' => 0, 'comment' => '父级菜单'])
			->addColumn('title', 'string', ['limit' => 50, 'default' => '', 'comment' => '标题'])
			->addColumn('name', 'string', ['limit' => 50, 'default' => '', 'comment' => '名称'])
			->addColumn('group_ids', 'string', ['limit' => 100, 'default' => '', 'comment' => '分组ID集合'])
			->addColumn('sort', 'boolean', ['signed' => false, 'limit' => 2, 'default' => 0, 'comment' => '排序'])
			->addColumn('status', 'boolean', ['limit' => 2, 'default' => 1, 'comment' => '状态{1:正常,-1:禁用}'])
			->addColumn('create_time', 'integer', ['signed' => false, 'limit' => 11, 'default' => 0, 'comment' => '创建时间'])
			->addColumn('update_time', 'integer', ['signed' => false, 'limit' => 11, 'default' => 0, 'comment' => '修改时间'])
			->addIndex('name', ['unique' => true])
			->addIndex('pid')
			->addIndex('status')
			->create();
	}

	public function down()
	{
		$this->dropTable($this->table);
	}
}
