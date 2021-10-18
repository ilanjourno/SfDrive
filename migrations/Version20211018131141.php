<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211018131141 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE file (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, sub_folder_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, size INTEGER NOT NULL, type VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, brochure_filename VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE INDEX IDX_8C9F3610421FFFC ON file (sub_folder_id)');
        $this->addSql('CREATE TABLE folder (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, sub_folder_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_ECA209CD421FFFC ON folder (sub_folder_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE file');
        $this->addSql('DROP TABLE folder');
    }
}
