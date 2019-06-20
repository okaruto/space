<?php


use Phinx\Migration\AbstractMigration;

class CreateTokensAvailableView extends AbstractMigration
{
    public function up()
    {
        $this->execute(
            <<<'EOT'
CREATE VIEW tokens_available
AS
SELECT tokens.id, tokens.value, tokens.type FROM tokens WHERE NOT EXISTS
  (SELECT id, token_id FROM orders WHERE orders.token_id = tokens.id);
EOT
        );
    }

    public function down(): void
    {
        $this->execute('DROP VIEW tokens_available;');
    }
}
