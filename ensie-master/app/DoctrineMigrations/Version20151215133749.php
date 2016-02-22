<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151215133749 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, subtitle VARCHAR(255) NOT NULL, header_image VARCHAR(255) NOT NULL, tile_image VARCHAR(255) NOT NULL, logo_image VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subcategory (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, subtitle VARCHAR(255) NOT NULL, header_image VARCHAR(255) NOT NULL, tile_image VARCHAR(255) NOT NULL, logo_image VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_DDCA44812469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE subcategory ADD CONSTRAINT FK_DDCA44812469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE definition ADD subcategory_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE definition ADD CONSTRAINT FK_68302FD85DC6FE57 FOREIGN KEY (subcategory_id) REFERENCES subcategory (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_68302FD85DC6FE57 ON definition (subcategory_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE subcategory DROP FOREIGN KEY FK_DDCA44812469DE2');
        $this->addSql('ALTER TABLE definition DROP FOREIGN KEY FK_68302FD85DC6FE57');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE subcategory');
        $this->addSql('DROP INDEX IDX_68302FD85DC6FE57 ON definition');
        $this->addSql('ALTER TABLE definition DROP subcategory_id');
    }
}
