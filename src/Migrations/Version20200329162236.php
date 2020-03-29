<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200329162236 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE word DROP FOREIGN KEY FK_C3F175114ACC9A20');
        $this->addSql('DROP INDEX IDX_C3F175114ACC9A20 ON word');
        $this->addSql('ALTER TABLE word DROP card_id');
        $this->addSql('ALTER TABLE card ADD yellow_word_id INT NOT NULL, ADD blue_word_id INT NOT NULL');
        $this->addSql('ALTER TABLE card ADD CONSTRAINT FK_161498D3B7DBF581 FOREIGN KEY (yellow_word_id) REFERENCES word (id)');
        $this->addSql('ALTER TABLE card ADD CONSTRAINT FK_161498D311DBC7A9 FOREIGN KEY (blue_word_id) REFERENCES word (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_161498D3B7DBF581 ON card (yellow_word_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_161498D311DBC7A9 ON card (blue_word_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE card DROP FOREIGN KEY FK_161498D3B7DBF581');
        $this->addSql('ALTER TABLE card DROP FOREIGN KEY FK_161498D311DBC7A9');
        $this->addSql('DROP INDEX UNIQ_161498D3B7DBF581 ON card');
        $this->addSql('DROP INDEX UNIQ_161498D311DBC7A9 ON card');
        $this->addSql('ALTER TABLE card DROP yellow_word_id, DROP blue_word_id');
        $this->addSql('ALTER TABLE word ADD card_id INT NOT NULL');
        $this->addSql('ALTER TABLE word ADD CONSTRAINT FK_C3F175114ACC9A20 FOREIGN KEY (card_id) REFERENCES card (id)');
        $this->addSql('CREATE INDEX IDX_C3F175114ACC9A20 ON word (card_id)');
    }
}
