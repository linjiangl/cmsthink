<?php

use think\migration\Migrator;
use Phinx\Db\Adapter\MysqlAdapter;

class CategoryInit extends Migrator
{
	protected $table = 'category';

	public function up()
	{
		$table = $this->table($this->table, ['id' => false, 'primary_key' => 'id', 'comment' => '内容分类表']);

		$table->addColumn('id', 'integer', ['signed' => false, 'limit' => MysqlAdapter::INT_MEDIUM, 'identity' => true])
			->addColumn('pid', 'integer', ['signed' => false, 'limit' => MysqlAdapter::INT_MEDIUM, 'default' => 0, 'comment' => '父级ID'])
			->addColumn('name', 'string', ['limit' => 50, 'default' => '', 'comment' => '名称'])
			->addColumn('sort', 'boolean', ['signed' => false, 'limit' => 2, 'default' => 0, 'comment' => '排序'])
			->addColumn('status', 'boolean', ['signed' => false, 'limit' => 2, 'default' => 1, 'comment' => '状态{0:禁用,1:正常}'])
			->addColumn('create_time', 'integer', ['signed' => false, 'limit' => 11, 'default' => 0, 'comment' => '创建时间'])
			->addColumn('update_time', 'integer', ['signed' => false, 'limit' => 11, 'default' => 0, 'comment' => '修改时间'])
			->addIndex('name', ['unique' => true])
			->addIndex('pid')
			->addIndex('sort')
			->addIndex('status')
			->create();
	}

	public function down()
	{
		$this->dropTable($this->table);
	}
}
