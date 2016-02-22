<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160127163252 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        
        $this->addSql('ALTER TABLE category CHANGE slug slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE definition ADD extra_link_text VARCHAR(255) DEFAULT NULL, ADD extra_link_url VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE subcategory CHANGE slug slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE ensie_user ADD header_image VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        
        $this->addSql('ALTER TABLE category CHANGE slug slug VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE definition DROP extra_link_text, DROP extra_link_url');
        $this->addSql('ALTER TABLE ensie_user DROP header_image');
        $this->addSql('ALTER TABLE subcategory CHANGE slug slug VARCHAR(255) DEFAULT NULL');
    }
}
