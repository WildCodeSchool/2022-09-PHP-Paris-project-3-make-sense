<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230117160021 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE decision (id INT AUTO_INCREMENT NOT NULL, owner_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, impacts LONGTEXT DEFAULT NULL, benefits LONGTEXT NOT NULL, risks LONGTEXT DEFAULT NULL, like_threshold INT NOT NULL, created_at DATE NOT NULL, updated_at DATE NOT NULL, end_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', status VARCHAR(50) NOT NULL, INDEX IDX_84ACBE487E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE validation (id INT AUTO_INCREMENT NOT NULL, decision_id INT DEFAULT NULL, user_id INT DEFAULT NULL, is_approved TINYINT(1) NOT NULL, created_at DATE NOT NULL, comment LONGTEXT DEFAULT NULL, INDEX IDX_16AC5B6EBDEE7539 (decision_id), INDEX IDX_16AC5B6EA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE decision ADD CONSTRAINT FK_84ACBE487E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE validation ADD CONSTRAINT FK_16AC5B6EBDEE7539 FOREIGN KEY (decision_id) REFERENCES decision (id)');
        $this->addSql('ALTER TABLE validation ADD CONSTRAINT FK_16AC5B6EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE decision_department ADD CONSTRAINT FK_8703A80ABDEE7539 FOREIGN KEY (decision_id) REFERENCES decision (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE decision_department ADD CONSTRAINT FK_8703A80AAE80F5DF FOREIGN KEY (department_id) REFERENCES department (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE expertise ADD CONSTRAINT FK_229ADF8BAE80F5DF FOREIGN KEY (department_id) REFERENCES department (id)');
        $this->addSql('ALTER TABLE expertise ADD CONSTRAINT FK_229ADF8BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE history ADD CONSTRAINT FK_27BA704BBDEE7539 FOREIGN KEY (decision_id) REFERENCES decision (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA1E058452 FOREIGN KEY (history_id) REFERENCES history (id)');
        $this->addSql('ALTER TABLE opinion ADD CONSTRAINT FK_AB02B027BDEE7539 FOREIGN KEY (decision_id) REFERENCES decision (id)');
        $this->addSql('ALTER TABLE opinion ADD CONSTRAINT FK_AB02B027A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user CHANGE imagename image_name VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE decision_department DROP FOREIGN KEY FK_8703A80ABDEE7539');
        $this->addSql('ALTER TABLE history DROP FOREIGN KEY FK_27BA704BBDEE7539');
        $this->addSql('ALTER TABLE opinion DROP FOREIGN KEY FK_AB02B027BDEE7539');
        $this->addSql('ALTER TABLE decision DROP FOREIGN KEY FK_84ACBE487E3C61F9');
        $this->addSql('ALTER TABLE validation DROP FOREIGN KEY FK_16AC5B6EBDEE7539');
        $this->addSql('ALTER TABLE validation DROP FOREIGN KEY FK_16AC5B6EA76ED395');
        $this->addSql('DROP TABLE decision');
        $this->addSql('DROP TABLE validation');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE decision_department DROP FOREIGN KEY FK_8703A80AAE80F5DF');
        $this->addSql('ALTER TABLE opinion DROP FOREIGN KEY FK_AB02B027A76ED395');
        $this->addSql('ALTER TABLE user CHANGE image_name imagename VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CAA76ED395');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CA1E058452');
        $this->addSql('ALTER TABLE expertise DROP FOREIGN KEY FK_229ADF8BAE80F5DF');
        $this->addSql('ALTER TABLE expertise DROP FOREIGN KEY FK_229ADF8BA76ED395');
    }
}