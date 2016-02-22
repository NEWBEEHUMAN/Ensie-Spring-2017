<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150204204321 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        
        $this->addSql('ALTER TABLE subscription_log ADD subscription_id INT DEFAULT NULL, ADD user_id INT DEFAULT NULL, DROP subscription, DROP user');
        $this->addSql('ALTER TABLE subscription_log ADD CONSTRAINT FK_6B14862E9A1887DC FOREIGN KEY (subscription_id) REFERENCES subscription (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE subscription_log ADD CONSTRAINT FK_6B14862EA76ED395 FOREIGN KEY (user_id) REFERENCES ensie_user (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_6B14862E9A1887DC ON subscription_log (subscription_id)');
        $this->addSql('CREATE INDEX IDX_6B14862EA76ED395 ON subscription_log (user_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        
        $this->addSql('ALTER TABLE subscription_log DROP FOREIGN KEY FK_6B14862E9A1887DC');
        $this->addSql('ALTER TABLE subscription_log DROP FOREIGN KEY FK_6B14862EA76ED395');
        $this->addSql('DROP INDEX IDX_6B14862E9A1887DC ON subscription_log');
        $this->addSql('DROP INDEX IDX_6B14862EA76ED395 ON subscription_log');
        $this->addSql('ALTER TABLE subscription_log ADD subscription INT NOT NULL, ADD user INT NOT NULL, DROP subscription_id, DROP user_id');
    }
}
