<?php

use Phinx\Migration\AbstractMigration;

class CreateInvoicesCurrentView extends AbstractMigration
{

    public function up()
    {
        $this->execute(
            <<<'EOT'
CREATE VIEW invoices_current
AS 
SELECT MAX(created) as created,
  order_id,
  invoice_status,
  invoice_id,
  invoice_created,
  invoice_expires,
  invoice_amount,
  invoice_currency,
  invoice_url,
  checkout_address,
  checkout_amount,
  checkout_currency,
  date_time 
FROM invoices 
GROUP BY order_id;
EOT
        );
    }

    public function down(): void
    {
        $this->execute('DROP VIEW invoices_current;');
    }
}
