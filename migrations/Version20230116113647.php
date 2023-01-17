<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230116113647 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE decision_department (decision_id INT NOT NULL, department_id INT NOT NULL, INDEX IDX_8703A80ABDEE7539 (decision_id), INDEX IDX_8703A80AAE80F5DF (department_id), PRIMARY KEY(decision_id, department_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE department (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE expertise (id INT AUTO_INCREMENT NOT NULL, department_id INT DEFAULT NULL, user_id INT DEFAULT NULL, is_expert TINYINT(1) NOT NULL, INDEX IDX_229ADF8BAE80F5DF (department_id), INDEX IDX_229ADF8BA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE history (id INT AUTO_INCREMENT NOT NULL, decision_id INT NOT NULL, started_at DATE NOT NULL, ended_at DATE NOT NULL, status VARCHAR(50) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_27BA704BBDEE7539 (decision_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(80) NOT NULL, lastname VARCHAR(80) NOT NULL, imagename VARCHAR(255) NOT NULL, phone INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE decision_department ADD CONSTRAINT FK_8703A80ABDEE7539 FOREIGN KEY (decision_id) REFERENCES decision (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE decision_department ADD CONSTRAINT FK_8703A80AAE80F5DF FOREIGN KEY (department_id) REFERENCES department (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE expertise ADD CONSTRAINT FK_229ADF8BAE80F5DF FOREIGN KEY (department_id) REFERENCES department (id)');
        $this->addSql('ALTER TABLE expertise ADD CONSTRAINT FK_229ADF8BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE history ADD CONSTRAINT FK_27BA704BBDEE7539 FOREIGN KEY (decision_id) REFERENCES decision (id)');
        $this->addSql('ALTER TABLE comments_user DROP FOREIGN KEY FK_942FBEE63379586');
        $this->addSql('DROP TABLE comments');
        $this->addSql('DROP TABLE comments_user');
        $this->addSql('DROP TABLE decision_comments');
        $this->addSql('DROP TABLE decision_history');
        $this->addSql('ALTER TABLE decision ADD owner_id INT DEFAULT NULL, ADD updated_at DATE NOT NULL, ADD status VARCHAR(50) NOT NULL, CHANGE end_at end_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE impact impacts LONGTEXT DEFAULT NULL, CHANGE start_at created_at DATE NOT NULL');
        $this->addSql('ALTER TABLE decision ADD CONSTRAINT FK_84ACBE487E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_84ACBE487E3C61F9 ON decision (owner_id)');
        $this->addSql('ALTER TABLE notification ADD history_id INT DEFAULT NULL, DROP created_at, DROP message');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA1E058452 FOREIGN KEY (history_id) REFERENCES history (id)');
        $this->addSql('CREATE INDEX IDX_BF5476CA1E058452 ON notification (history_id)');
        $this->addSql('ALTER TABLE opinion ADD user_id INT DEFAULT NULL, DROP created_at');
        $this->addSql('ALTER TABLE opinion ADD CONSTRAINT FK_AB02B027BDEE7539 FOREIGN KEY (decision_id) REFERENCES decision (id)');
        $this->addSql('ALTER TABLE opinion ADD CONSTRAINT FK_AB02B027A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_AB02B027A76ED395 ON opinion (user_id)');
        $this->addSql('ALTER TABLE validation CHANGE is_approuved is_approved TINYINT(1) NOT NULL, CHANGE comments comment LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE validation ADD CONSTRAINT FK_16AC5B6EBDEE7539 FOREIGN KEY (decision_id) REFERENCES decision (id)');
        $this->addSql('ALTER TABLE validation ADD CONSTRAINT FK_16AC5B6EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CA1E058452');
        $this->addSql('ALTER TABLE decision DROP FOREIGN KEY FK_84ACBE487E3C61F9');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CAA76ED395');
        $this->addSql('ALTER TABLE opinion DROP FOREIGN KEY FK_AB02B027A76ED395');
        $this->addSql('ALTER TABLE validation DROP FOREIGN KEY FK_16AC5B6EA76ED395');
        $this->addSql('CREATE TABLE comments (id INT AUTO_INCREMENT NOT NULL, created_at DATE DEFAULT NULL, comments LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE comments_user (comments_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_942FBEE63379586 (comments_id), INDEX IDX_942FBEEA76ED395 (user_id), PRIMARY KEY(comments_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE decision_comments (decision_id INT NOT NULL, comments_id INT NOT NULL, INDEX IDX_D242591463379586 (comments_id), INDEX IDX_D2425914BDEE7539 (decision_id), PRIMARY KEY(decision_id, comments_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE decision_history (id INT AUTO_INCREMENT NOT NULL, decision_id INT NOT NULL, start_at DATE NOT NULL, end_at DATE NOT NULL, status VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_F378BB54BDEE7539 (decision_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE comments_user ADD CONSTRAINT FK_942FBEE63379586 FOREIGN KEY (comments_id) REFERENCES comments (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE decision_department DROP FOREIGN KEY FK_8703A80ABDEE7539');
        $this->addSql('ALTER TABLE decision_department DROP FOREIGN KEY FK_8703A80AAE80F5DF');
        $this->addSql('ALTER TABLE expertise DROP FOREIGN KEY FK_229ADF8BAE80F5DF');
        $this->addSql('ALTER TABLE expertise DROP FOREIGN KEY FK_229ADF8BA76ED395');
        $this->addSql('ALTER TABLE history DROP FOREIGN KEY FK_27BA704BBDEE7539');
        $this->addSql('DROP TABLE decision_department');
        $this->addSql('DROP TABLE department');
        $this->addSql('DROP TABLE expertise');
        $this->addSql('DROP TABLE history');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('DROP INDEX IDX_84ACBE487E3C61F9 ON decision');
        $this->addSql('ALTER TABLE decision ADD start_at DATE NOT NULL, DROP owner_id, DROP created_at, DROP updated_at, DROP status, CHANGE end_at end_at DATE NOT NULL, CHANGE impacts impact LONGTEXT DEFAULT NULL');
        $this->addSql('DROP INDEX IDX_BF5476CA1E058452 ON notification');
        $this->addSql('ALTER TABLE notification ADD created_at DATE NOT NULL, ADD message LONGTEXT NOT NULL, DROP history_id');
        $this->addSql('ALTER TABLE opinion DROP FOREIGN KEY FK_AB02B027BDEE7539');
        $this->addSql('DROP INDEX IDX_AB02B027A76ED395 ON opinion');
        $this->addSql('ALTER TABLE opinion ADD created_at DATE NOT NULL, DROP user_id');
        $this->addSql('ALTER TABLE validation DROP FOREIGN KEY FK_16AC5B6EBDEE7539');
        $this->addSql('ALTER TABLE validation CHANGE is_approved is_approuved TINYINT(1) NOT NULL, CHANGE comment comments LONGTEXT DEFAULT NULL');
    }
}
