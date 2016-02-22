<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150130213517 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        
        $this->addSql('CREATE TABLE subscription_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT DEFAULT NULL, description LONGTEXT NOT NULL, title VARCHAR(255) NOT NULL, price NUMERIC(10, 0) NOT NULL, locale VARCHAR(255) NOT NULL, INDEX IDX_897335F82C2AC5D3 (translatable_id), UNIQUE INDEX UNIQ_897335F82C2AC5D34180C698 (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE subscription_translation ADD CONSTRAINT FK_897335F82C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES subscription (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE subscription DROP description, DROP price, CHANGE title identifier VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        
        $this->addSql('DROP TABLE subscription_translation');
        $this->addSql('ALTER TABLE subscription ADD description LONGTEXT NOT NULL, ADD price NUMERIC(10, 0) NOT NULL, CHANGE identifier title VARCHAR(255) NOT NULL');
    }
}
