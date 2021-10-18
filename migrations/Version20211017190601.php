<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211017190601 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_ECA209CD421FFFC');
        $this->addSql('CREATE TEMPORARY TABLE __temp__folder AS SELECT id, sub_folder_id, name, created_at, updated_at FROM folder');
        $this->addSql('DROP TABLE folder');
        $this->addSql('CREATE TABLE folder (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, sub_folder_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        , CONSTRAINT FK_ECA209CD421FFFC FOREIGN KEY (sub_folder_id) REFERENCES folder (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO folder (id, sub_folder_id, name, created_at, updated_at) SELECT id, sub_folder_id, name, created_at, updated_at FROM __temp__folder');
        $this->addSql('DROP TABLE __temp__folder');
        $this->addSql('CREATE INDEX IDX_ECA209CD421FFFC ON folder (sub_folder_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
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
