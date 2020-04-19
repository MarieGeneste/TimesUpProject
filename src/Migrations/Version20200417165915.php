<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200417165915 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user_profile (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', user_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', updated_at DATETIME DEFAULT NULL, avatar_name VARCHAR(255) DEFAULT NULL, friend_request LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, UNIQUE INDEX UNIQ_D95AB405A76ED395 (user_id), INDEX created_at_index (created_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_profile_user (user_profile_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', user_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_BB9725DF6B9DD454 (user_profile_id), INDEX IDX_BB9725DFA76ED395 (user_id), PRIMARY KEY(user_profile_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_profile ADD CONSTRAINT FK_D95AB405A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_profile_user ADD CONSTRAINT FK_BB9725DF6B9DD454 FOREIGN KEY (user_profile_id) REFERENCES user_profile (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_profile_user ADD CONSTRAINT FK_BB9725DFA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE user_user');
        $this->addSql('ALTER TABLE user DROP avatar_name, DROP friend_request');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_profile_user DROP FOREIGN KEY FK_BB9725DF6B9DD454');
        $this->addSql('CREATE TABLE user_user (user_source CHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:uuid)\', user_target CHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:uuid)\', INDEX IDX_F7129A80233D34C1 (user_target), INDEX IDX_F7129A803AD8644E (user_source), PRIMARY KEY(user_source, user_target)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE user_user ADD CONSTRAINT FK_F7129A80233D34C1 FOREIGN KEY (user_target) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_user ADD CONSTRAINT FK_F7129A803AD8644E FOREIGN KEY (user_source) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE user_profile');
        $this->addSql('DROP TABLE user_profile_user');
        $this->addSql('ALTER TABLE user ADD avatar_name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD friend_request LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:array)\'');
    }
}
