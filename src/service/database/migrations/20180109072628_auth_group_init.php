<?php

use think\migration\Migrator;
use Phinx\Db\Adapter\MysqlAdapter;

class AuthGroupInit extends Migrator
{
	protected $table = 'auth_group';

	public function up()
	{
		$table = $this->table($this->table, ['id' => false, 'primary_key' => 'id', 'engine' => 'MyISAM', 'comment' => '权限分组']);

		$table->addColumn('id', 'integer', ['signed' => false, 'limit' => MysqlAdapter::INT_TINY, 'identity' => true])
			->addColumn('title', 'string', ['limit' => 50, 'default' => '', 'comment' => '组名称'])
			->addColumn('status', 'boolean', ['signed' => false, 'limit' => 2, 'default' => 1, 'comment' => '状态{1:正常,-1:禁用}'])
			->addIndex('status')
			->create();
	}

	public function down()
	{
		$this->dropTable($this->table);
	}
}
