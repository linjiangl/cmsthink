<?php

use think\migration\Migrator;
use Phinx\Db\Adapter\MysqlAdapter;

class MenuInit extends Migrator
{
	protected $table = 'menu';

	public function up()
	{
		$table = $this->table($this->table, ['id' => false, 'primary_key' => 'id', 'engine' => 'MyISAM', 'comment' => '后台菜单']);

		$table->addColumn('id', 'integer', ['signed' => false, 'limit' => MysqlAdapter::INT_MEDIUM, 'identity' => true])
			->addColumn('pid', 'integer', ['signed' => false, 'limit' => MysqlAdapter::INT_MEDIUM, 'default' => 0, 'comment' => '父级菜单'])
			->addColumn('title', 'string', ['limit' => 50, 'default' => '', 'comment' => '菜单名称'])
			->addColumn('tip', 'string', ['limit' => 50, 'default' => '', 'comment' => '提示'])
			->addColumn('url', 'string', ['limit' => 255, 'default' => '', 'comment' => '链接地址'])
			->addColumn('sort', 'boolean', ['signed' => false, 'limit' => 2, 'default' => 0, 'comment' => '排序'])
			->addColumn('status', 'boolean', ['signed' => false, 'limit' => 2, 'default' => 1, 'comment' => '状态{1:正常,-1:禁用}'])
			->addIndex('pid')
			->addIndex('status')
			->create();
	}

	public function down()
	{
		$this->dropTable($this->table);
	}
}
