<?php

use think\migration\Migrator;
use Phinx\Db\Adapter\MysqlAdapter;

class AuthRuleInit extends Migrator
{
    protected $table = 'auth_rule';

	public function up()
	{
		$table = $this->table($this->table, ['id' => false, 'primary_key' => 'id', 'engine' => 'MyISAM', 'comment' => '权限规则']);

		$table->addColumn('id', 'integer', ['signed' => false, 'limit' => MysqlAdapter::INT_MEDIUM, 'identity' => true])
			->addColumn('pid', 'integer', ['signed' => false, 'limit' => MysqlAdapter::INT_MEDIUM, 'default' => 0, 'comment' => '父级规则'])
			->addColumn('title', 'string', ['limit' => 50, 'default' => '', 'comment' => '规则名称'])
			->addColumn('sign', 'string', ['limit' => 100, 'default' => '', 'comment' => '标记:对应的控制方法'])
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
