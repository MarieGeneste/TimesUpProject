<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200330144452 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE blue_card (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', content_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', UNIQUE INDEX UNIQ_78A276C884A0A3ED (content_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE word (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', yellow_card_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', blue_card_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_C3F175111E402C2C (yellow_card_id), UNIQUE INDEX UNIQ_C3F17511B8401E04 (blue_card_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE word_category (word_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', category_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_22F2C810E357438D (word_id), INDEX IDX_22F2C81012469DE2 (category_id), PRIMARY KEY(word_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE edition (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', title VARCHAR(50) NOT NULL, color VARCHAR(50) DEFAULT NULL, logo VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE card (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', edition_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', yellow_content_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', blue_content_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_161498D374281A5E (edition_id), INDEX IDX_161498D3DB2B9CE8 (yellow_content_id), INDEX IDX_161498D35DF67 (blue_content_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE yellow_card (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', content_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', UNIQUE INDEX UNIQ_2FFCB4A84A0A3ED (content_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', title VARCHAR(50) NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE blue_card ADD CONSTRAINT FK_78A276C884A0A3ED FOREIGN KEY (content_id) REFERENCES word (id)');
        $this->addSql('ALTER TABLE word ADD CONSTRAINT FK_C3F175111E402C2C FOREIGN KEY (yellow_card_id) REFERENCES yellow_card (id)');
        $this->addSql('ALTER TABLE word ADD CONSTRAINT FK_C3F17511B8401E04 FOREIGN KEY (blue_card_id) REFERENCES blue_card (id)');
        $this->addSql('ALTER TABLE word_category ADD CONSTRAINT FK_22F2C810E357438D FOREIGN KEY (word_id) REFERENCES word (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE word_category ADD CONSTRAINT FK_22F2C81012469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE card ADD CONSTRAINT FK_161498D374281A5E FOREIGN KEY (edition_id) REFERENCES edition (id)');
        $this->addSql('ALTER TABLE card ADD CONSTRAINT FK_161498D3DB2B9CE8 FOREIGN KEY (yellow_content_id) REFERENCES yellow_card (id)');
        $this->addSql('ALTER TABLE card ADD CONSTRAINT FK_161498D35DF67 FOREIGN KEY (blue_content_id) REFERENCES blue_card (id)');
        $this->addSql('ALTER TABLE yellow_card ADD CONSTRAINT FK_2FFCB4A84A0A3ED FOREIGN KEY (content_id) REFERENCES word (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE word DROP FOREIGN KEY FK_C3F17511B8401E04');
        $this->addSql('ALTER TABLE card DROP FOREIGN KEY FK_161498D35DF67');
        $this->addSql('ALTER TABLE blue_card DROP FOREIGN KEY FK_78A276C884A0A3ED');
        $this->addSql('ALTER TABLE word_category DROP FOREIGN KEY FK_22F2C810E357438D');
        $this->addSql('ALTER TABLE yellow_card DROP FOREIGN KEY FK_2FFCB4A84A0A3ED');
        $this->addSql('ALTER TABLE card DROP FOREIGN KEY FK_161498D374281A5E');
        $this->addSql('ALTER TABLE word DROP FOREIGN KEY FK_C3F175111E402C2C');
        $this->addSql('ALTER TABLE card DROP FOREIGN KEY FK_161498D3DB2B9CE8');
        $this->addSql('ALTER TABLE word_category DROP FOREIGN KEY FK_22F2C81012469DE2');
        $this->addSql('DROP TABLE blue_card');
        $this->addSql('DROP TABLE word');
        $this->addSql('DROP TABLE word_category');
        $this->addSql('DROP TABLE edition');
        $this->addSql('DROP TABLE card');
        $this->addSql('DROP TABLE yellow_card');
        $this->addSql('DROP TABLE category');
    }
}
