<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221209095454 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comments (id INT AUTO_INCREMENT NOT NULL, created_at DATE DEFAULT NULL, comments LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comments_user (comments_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_942FBEE63379586 (comments_id), INDEX IDX_942FBEEA76ED395 (user_id), PRIMARY KEY(comments_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE decision (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, impact LONGTEXT DEFAULT NULL, benefits LONGTEXT NOT NULL, risks LONGTEXT DEFAULT NULL, start_at DATE NOT NULL, end_at DATE NOT NULL, like_threshold INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE decision_comments (decision_id INT NOT NULL, comments_id INT NOT NULL, INDEX IDX_D2425914BDEE7539 (decision_id), INDEX IDX_D242591463379586 (comments_id), PRIMARY KEY(decision_id, comments_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE decision_history (id INT AUTO_INCREMENT NOT NULL, decision_id INT NOT NULL, start_at DATE NOT NULL, end_at DATE NOT NULL, status VARCHAR(50) NOT NULL, INDEX IDX_F378BB54BDEE7539 (decision_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, created_at DATE NOT NULL, message LONGTEXT NOT NULL, INDEX IDX_BF5476CAA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE opinion (id INT AUTO_INCREMENT NOT NULL, decision_id INT NOT NULL, is_like TINYINT(1) NOT NULL, created_at DATE NOT NULL, INDEX IDX_AB02B027BDEE7539 (decision_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE validation (id INT AUTO_INCREMENT NOT NULL, decision_id INT DEFAULT NULL, user_id INT DEFAULT NULL, is_approuved TINYINT(1) NOT NULL, created_at DATE NOT NULL, comments LONGTEXT DEFAULT NULL, INDEX IDX_16AC5B6EBDEE7539 (decision_id), INDEX IDX_16AC5B6EA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comments_user ADD CONSTRAINT FK_942FBEE63379586 FOREIGN KEY (comments_id) REFERENCES comments (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comments_user ADD CONSTRAINT FK_942FBEEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE decision_comments ADD CONSTRAINT FK_D2425914BDEE7539 FOREIGN KEY (decision_id) REFERENCES decision (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE decision_comments ADD CONSTRAINT FK_D242591463379586 FOREIGN KEY (comments_id) REFERENCES comments (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE decision_history ADD CONSTRAINT FK_F378BB54BDEE7539 FOREIGN KEY (decision_id) REFERENCES decision (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE opinion ADD CONSTRAINT FK_AB02B027BDEE7539 FOREIGN KEY (decision_id) REFERENCES decision (id)');
        $this->addSql('ALTER TABLE validation ADD CONSTRAINT FK_16AC5B6EBDEE7539 FOREIGN KEY (decision_id) REFERENCES decision (id)');
        $this->addSql('ALTER TABLE validation ADD CONSTRAINT FK_16AC5B6EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE expertise ADD CONSTRAINT FK_229ADF8B5585C142 FOREIGN KEY (skill_id) REFERENCES skill (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comments_user DROP FOREIGN KEY FK_942FBEE63379586');
        $this->addSql('ALTER TABLE comments_user DROP FOREIGN KEY FK_942FBEEA76ED395');
        $this->addSql('ALTER TABLE decision_comments DROP FOREIGN KEY FK_D2425914BDEE7539');
        $this->addSql('ALTER TABLE decision_comments DROP FOREIGN KEY FK_D242591463379586');
        $this->addSql('ALTER TABLE decision_history DROP FOREIGN KEY FK_F378BB54BDEE7539');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CAA76ED395');
        $this->addSql('ALTER TABLE opinion DROP FOREIGN KEY FK_AB02B027BDEE7539');
        $this->addSql('ALTER TABLE validation DROP FOREIGN KEY FK_16AC5B6EBDEE7539');
        $this->addSql('ALTER TABLE validation DROP FOREIGN KEY FK_16AC5B6EA76ED395');
        $this->addSql('DROP TABLE comments');
        $this->addSql('DROP TABLE comments_user');
        $this->addSql('DROP TABLE decision');
        $this->addSql('DROP TABLE decision_comments');
        $this->addSql('DROP TABLE decision_history');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE opinion');
        $this->addSql('DROP TABLE validation');
        $this->addSql('ALTER TABLE expertise DROP FOREIGN KEY FK_229ADF8B5585C142');
    }
}
