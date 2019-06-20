<?php

use Phinx\Migration\AbstractMigration;

class CreateInvoicesTable extends AbstractMigration
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
        $table = $this->table('invoices', ['id' => 'id']);

        $table->addColumn('created', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
              ->addColumn('order_id', 'text', ['default' => ''])
              ->addColumn('invoice_id', 'text', ['default' => ''])
              ->addColumn('invoice_created', 'text', ['default' => ''])
              ->addColumn('invoice_expires', 'text', ['default' => ''])
              ->addColumn('invoice_amount', 'text', ['default' => ''])
              ->addColumn('invoice_currency', 'text', ['default' => ''])
              ->addColumn('invoice_status', 'text', ['default' => ''])
              ->addColumn('invoice_url', 'text', ['default' => ''])
              ->addColumn('checkout_address', 'text', ['default' => ''])
              ->addColumn('checkout_amount', 'text', ['default' => ''])
              ->addColumn('checkout_currency', 'text', ['default' => ''])
              ->addColumn('date_time', 'text', ['default' => ''])
              ->addIndex(['order_id'])
              ->addIndex(['created'])
              ->addForeignKey('order_id', 'orders', 'id', [
                  'delete' => 'CASCADE',
                  'update' => 'NO_ACTION',
              ])
              ->create();
    }
}
