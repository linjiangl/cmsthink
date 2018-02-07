<?php

use think\migration\Migrator;
use Phinx\Db\Adapter\MysqlAdapter;

class PostInit extends Migrator
{
	protected $table = 'post';

	public function up()
	{
		$table = $this->table($this->table, ['id' => false, 'primary_key' => 'id', 'collation' => 'utf8mb4_general_ci', 'comment' => '内容表']);

		$table->addColumn('id', 'integer', ['signed' => false, 'limit' => 11, 'identity' => true])
			->addColumn('user_id', 'integer', ['signed' => false, 'limit' => 11, 'default' => 0, 'comment' => 'user->id'])
			->addColumn('category_id', 'integer', ['signed' => false, 'limit' => MysqlAdapter::INT_MEDIUM, 'default' => 0, 'comment' => 'category->id'])
			->addColumn('type', 'boolean', ['signed' => false,  'limit' => 1, 'default' => 2, 'comment' => '类型{1:视频,2:图文,3:文章}'])
			->addColumn('small_type', 'boolean', ['signed' => false,  'limit' => 1, 'default' => 1, 'comment' => '类型{10:视频,20:段子,21:趣图,22:动图,30:文章}'])
			->addColumn('title', 'string', ['limit' => 50, 'default' => '', 'comment' => '标题'])
			->addColumn('intro', 'string', ['limit' => 255, 'default' => '', 'comment' => '简介'])
			->addColumn('cover', 'string', ['limit' => 255, 'default' => '', 'comment' => '封面图'])
			->addColumn('content', 'text', ['comment' => '内容'])
			->addColumn('status', 'boolean', ['signed' => false, 'limit' => 2, 'default' => 1, 'comment' => '状态{0:已删除,1:已通过,2:未审核,3:未通过}'])
			->addColumn('audit_time', 'integer', ['signed' => false, 'limit' => 11, 'default' => 0, 'comment' => '审核时间'])
			->addColumn('create_time', 'integer', ['signed' => false, 'limit' => 11, 'default' => 0, 'comment' => '创建时间'])
			->addColumn('update_time', 'integer', ['signed' => false, 'limit' => 11, 'default' => 0, 'comment' => '修改时间'])
			->addIndex('user_id')
			->addIndex('category_id')
			->addIndex('type')
			->addIndex('status')
			->addIndex(['create_time', 'status'], ['name' => 'create_time_status'])
			->create();
	}

	public function down()
	{
		$this->dropTable($this->table);
	}
}
