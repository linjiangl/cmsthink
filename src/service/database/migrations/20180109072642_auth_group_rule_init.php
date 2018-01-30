<?php

use think\migration\Migrator;
use Phinx\Db\Adapter\MysqlAdapter;

class AuthGroupRuleInit extends Migrator
{
	protected $table = 'auth_group_rule';

	public function up()
	{
		$table = $this->table($this->table, ['id' => false, 'engine' => 'MyISAM', 'comment' => '权限组规则']);

		$table->addColumn('group_id', 'integer', ['signed' => false, 'limit' => MysqlAdapter::INT_TINY, 'default' => 0, 'comment' => 'authGroup->id'])
			->addColumn('rule_id', 'integer', ['signed' => false, 'limit' => MysqlAdapter::INT_MEDIUM, 'default' => 0, 'comment' => 'authRule->id'])
			->addIndex(['group_id', 'rule_id'], ['unique' => true, 'name' => 'group_rule'])
			->addIndex('group_id')
			->create();
	}

	public function down()
	{
		$this->dropTable($this->table);
	}
}
