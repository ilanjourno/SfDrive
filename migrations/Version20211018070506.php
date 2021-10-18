<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211018070506 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__file AS SELECT id, name, size, type, created_at, updated_at FROM file');
        $this->addSql('DROP TABLE file');
        $this->addSql('CREATE TABLE file (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, sub_folder_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY, size INTEGER NOT NULL, type VARCHAR(255) NOT NULL COLLATE BINARY, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, brochure_filename VARCHAR(255) NOT NULL, CONSTRAINT FK_8C9F3610421FFFC FOREIGN KEY (sub_folder_id) REFERENCES folder (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO file (id, name, size, type, created_at, updated_at) SELECT id, name, size, type, created_at, updated_at FROM __temp__file');
        $this->addSql('DROP TABLE __temp__file');
        $this->addSql('CREATE INDEX IDX_8C9F3610421FFFC ON file (sub_folder_id)');
        $this->addSql('DROP INDEX IDX_ECA209CD421FFFC');
        $this->addSql('CREATE TEMPORARY TABLE __temp__folder AS SELECT id, sub_folder_id, name, created_at, updated_at FROM folder');
        $this->addSql('DROP TABLE folder');
        $this->addSql('CREATE TABLE folder (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, sub_folder_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, CONSTRAINT FK_ECA209CD421FFFC FOREIGN KEY (sub_folder_id) REFERENCES folder (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO folder (id, sub_folder_id, name, created_at, updated_at) SELECT id, sub_folder_id, name, created_at, updated_at FROM __temp__folder');
        $this->addSql('DROP TABLE __temp__folder');
        $this->addSql('CREATE INDEX IDX_ECA209CD421FFFC ON folder (sub_folder_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_8C9F3610421FFFC');
        $this->addSql('CREATE TEMPORARY TABLE __temp__file AS SELECT id, name, size, type, created_at, updated_at FROM file');
        $this->addSql('DROP TABLE file');
        $this->addSql('CREATE TABLE file (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, size INTEGER NOT NULL, type VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('INSERT INTO file (id, name, size, type, created_at, updated_at) SELECT id, name, size, type, created_at, updated_at FROM __temp__file');
        $this->addSql('DROP TABLE __temp__file');
        $this->addSql('DROP INDEX IDX_ECA209CD421FFFC');
        $this->addSql('CREATE TEMPORARY TABLE __temp__folder AS SELECT id, sub_folder_id, name, created_at, updated_at FROM folder');
        $this->addSql('DROP TABLE folder');
        $this->addSql('CREATE TABLE folder (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, sub_folder_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('INSERT INTO folder (id, sub_folder_id, name, created_at, updated_at) SELECT id, sub_folder_id, name, created_at, updated_at FROM __temp__folder');
        $this->addSql('DROP TABLE __temp__folder');
        $this->addSql('CREATE INDEX IDX_ECA209CD421FFFC ON folder (sub_folder_id)');
    }
}
