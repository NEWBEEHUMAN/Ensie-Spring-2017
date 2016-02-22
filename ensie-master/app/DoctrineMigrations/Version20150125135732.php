<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150125135732 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        
        $this->addSql('ALTER TABLE ensie_user ADD subscription_id INT DEFAULT NULL, CHANGE telnumber telnumber VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE ensie_user ADD CONSTRAINT FK_32B9AD979A1887DC FOREIGN KEY (subscription_id) REFERENCES subscription (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_32B9AD979A1887DC ON ensie_user (subscription_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        
        $this->addSql('ALTER TABLE ensie_user DROP FOREIGN KEY FK_32B9AD979A1887DC');
        $this->addSql('DROP INDEX IDX_32B9AD979A1887DC ON ensie_user');
        $this->addSql('ALTER TABLE ensie_user DROP subscription_id, CHANGE telnumber telnumber VARCHAR(255) NOT NULL');
    }
}
