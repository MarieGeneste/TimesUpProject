<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200417154645 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE blue_card CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE iknow_card CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE edition CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE game_mode CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE times_up_card CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE yellow_card CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE category CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE response CHANGE updated_at updated_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE blue_card CHANGE updated_at updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE category CHANGE updated_at updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE edition CHANGE updated_at updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE game_mode CHANGE updated_at updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE iknow_card CHANGE updated_at updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE response CHANGE updated_at updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE times_up_card CHANGE updated_at updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE updated_at updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE yellow_card CHANGE updated_at updated_at DATETIME NOT NULL');
    }
}
