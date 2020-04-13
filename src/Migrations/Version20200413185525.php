<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200413185525 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE game (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game_response (game_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', response_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_953FF4DDE48FD905 (game_id), INDEX IDX_953FF4DDFBF32840 (response_id), PRIMARY KEY(game_id, response_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE blue_card (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', content_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', UNIQUE INDEX UNIQ_78A276C884A0A3ED (content_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE iknow_card (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', response_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', first_indication VARCHAR(255) NOT NULL, second_indication VARCHAR(255) NOT NULL, third_indication VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_33E5F49CFBF32840 (response_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE edition (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', title VARCHAR(50) NOT NULL, color VARCHAR(50) DEFAULT NULL, logo VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', email VARCHAR(180) NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, activation_token VARCHAR(100) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE times_up_card (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', edition_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', yellow_content_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', blue_content_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_1105E1E074281A5E (edition_id), INDEX IDX_1105E1E0DB2B9CE8 (yellow_content_id), INDEX IDX_1105E1E05DF67 (blue_content_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE yellow_card (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', content_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', UNIQUE INDEX UNIQ_2FFCB4A84A0A3ED (content_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', title VARCHAR(50) NOT NULL, color VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category_category (category_source CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', category_target CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_B1369DBA5062B508 (category_source), INDEX IDX_B1369DBA4987E587 (category_target), PRIMARY KEY(category_source, category_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE response (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE response_category (response_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', category_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_B15AFFBCFBF32840 (response_id), INDEX IDX_B15AFFBC12469DE2 (category_id), PRIMARY KEY(response_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE game_response ADD CONSTRAINT FK_953FF4DDE48FD905 FOREIGN KEY (game_id) REFERENCES game (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE game_response ADD CONSTRAINT FK_953FF4DDFBF32840 FOREIGN KEY (response_id) REFERENCES response (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE blue_card ADD CONSTRAINT FK_78A276C884A0A3ED FOREIGN KEY (content_id) REFERENCES response (id)');
        $this->addSql('ALTER TABLE iknow_card ADD CONSTRAINT FK_33E5F49CFBF32840 FOREIGN KEY (response_id) REFERENCES response (id)');
        $this->addSql('ALTER TABLE times_up_card ADD CONSTRAINT FK_1105E1E074281A5E FOREIGN KEY (edition_id) REFERENCES edition (id)');
        $this->addSql('ALTER TABLE times_up_card ADD CONSTRAINT FK_1105E1E0DB2B9CE8 FOREIGN KEY (yellow_content_id) REFERENCES yellow_card (id)');
        $this->addSql('ALTER TABLE times_up_card ADD CONSTRAINT FK_1105E1E05DF67 FOREIGN KEY (blue_content_id) REFERENCES blue_card (id)');
        $this->addSql('ALTER TABLE yellow_card ADD CONSTRAINT FK_2FFCB4A84A0A3ED FOREIGN KEY (content_id) REFERENCES response (id)');
        $this->addSql('ALTER TABLE category_category ADD CONSTRAINT FK_B1369DBA5062B508 FOREIGN KEY (category_source) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_category ADD CONSTRAINT FK_B1369DBA4987E587 FOREIGN KEY (category_target) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE response_category ADD CONSTRAINT FK_B15AFFBCFBF32840 FOREIGN KEY (response_id) REFERENCES response (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE response_category ADD CONSTRAINT FK_B15AFFBC12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE game_response DROP FOREIGN KEY FK_953FF4DDE48FD905');
        $this->addSql('ALTER TABLE times_up_card DROP FOREIGN KEY FK_1105E1E05DF67');
        $this->addSql('ALTER TABLE times_up_card DROP FOREIGN KEY FK_1105E1E074281A5E');
        $this->addSql('ALTER TABLE times_up_card DROP FOREIGN KEY FK_1105E1E0DB2B9CE8');
        $this->addSql('ALTER TABLE category_category DROP FOREIGN KEY FK_B1369DBA5062B508');
        $this->addSql('ALTER TABLE category_category DROP FOREIGN KEY FK_B1369DBA4987E587');
        $this->addSql('ALTER TABLE response_category DROP FOREIGN KEY FK_B15AFFBC12469DE2');
        $this->addSql('ALTER TABLE game_response DROP FOREIGN KEY FK_953FF4DDFBF32840');
        $this->addSql('ALTER TABLE blue_card DROP FOREIGN KEY FK_78A276C884A0A3ED');
        $this->addSql('ALTER TABLE iknow_card DROP FOREIGN KEY FK_33E5F49CFBF32840');
        $this->addSql('ALTER TABLE yellow_card DROP FOREIGN KEY FK_2FFCB4A84A0A3ED');
        $this->addSql('ALTER TABLE response_category DROP FOREIGN KEY FK_B15AFFBCFBF32840');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE game_response');
        $this->addSql('DROP TABLE blue_card');
        $this->addSql('DROP TABLE iknow_card');
        $this->addSql('DROP TABLE edition');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE times_up_card');
        $this->addSql('DROP TABLE yellow_card');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE category_category');
        $this->addSql('DROP TABLE response');
        $this->addSql('DROP TABLE response_category');
    }
}
