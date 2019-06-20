<?php

use Phinx\Migration\AbstractMigration;

class CreateOrdersTable extends AbstractMigration
{

    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    addCustomColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Any other destructive changes will result in an error when trying to
     * rollback the migration.
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $table = $this->table('orders', ['id' => false, 'primary_key' => ['id']]);

        $table->addColumn('id', 'text')
              ->addColumn('created', 'timestamp', [
                  'default' => 'CURRENT_TIMESTAMP',
              ])
              ->addColumn('updated', 'timestamp', [
                  'default' => 'CURRENT_TIMESTAMP',
              ])
              ->addColumn('status', 'enum', [
                  'default' => 'new',
                  'values' => [
                      'new',
                      'clear',
                  ],
              ])
              ->addColumn('price', 'float', ['default' => ''])
              ->addColumn('currency', 'text', ['default' => ''])
              ->addColumn('token_id', 'integer')
              ->addIndex(['id'], ['unique' => true])
              ->addIndex(['status'])
              ->addIndex(['token_id'], ['unique' => true])
              ->addForeignKey('token_id', 'tokens', 'id', [
                  'delete' => 'CASCADE',
                  'update' => 'NO_ACTION',
              ])
              ->create();

        $this->execute(<<<'EOT'
CREATE TRIGGER orderUpdated UPDATE OF status ON orders
BEGIN
  UPDATE orders SET updated=CURRENT_TIMESTAMP WHERE id=old.id;
END;
EOT
        );
    }
}
