<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221215130823 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE expertise DROP FOREIGN KEY FK_229ADF8B5585C142');
        $this->addSql('CREATE TABLE content (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, decision_id INT DEFAULT NULL, created_at DATE DEFAULT NULL, comment LONGTEXT NOT NULL, INDEX IDX_FEC530A9A76ED395 (user_id), INDEX IDX_FEC530A9BDEE7539 (decision_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE department (id INT AUTO_INCREMENT NOT NULL, decision_id INT DEFAULT NULL, name VARCHAR(50) NOT NULL, INDEX IDX_CD1DE18ABDEE7539 (decision_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE history (id INT AUTO_INCREMENT NOT NULL, decision_id INT NOT NULL, started_at DATE NOT NULL, ended_at DATE NOT NULL, status VARCHAR(50) NOT NULL, INDEX IDX_27BA704BBDEE7539 (decision_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE content ADD CONSTRAINT FK_FEC530A9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE content ADD CONSTRAINT FK_FEC530A9BDEE7539 FOREIGN KEY (decision_id) REFERENCES decision (id)');
        $this->addSql('ALTER TABLE department ADD CONSTRAINT FK_CD1DE18ABDEE7539 FOREIGN KEY (decision_id) REFERENCES decision (id)');
        $this->addSql('ALTER TABLE history ADD CONSTRAINT FK_27BA704BBDEE7539 FOREIGN KEY (decision_id) REFERENCES decision (id)');
        $this->addSql('ALTER TABLE comments_user DROP FOREIGN KEY FK_942FBEE63379586');
        $this->addSql('ALTER TABLE comments_user DROP FOREIGN KEY FK_942FBEEA76ED395');
        $this->addSql('ALTER TABLE decision_comments DROP FOREIGN KEY FK_D242591463379586');
        $this->addSql('ALTER TABLE decision_comments DROP FOREIGN KEY FK_D2425914BDEE7539');
        $this->addSql('ALTER TABLE decision_history DROP FOREIGN KEY FK_F378BB54BDEE7539');
        $this->addSql('DROP TABLE comments');
        $this->addSql('DROP TABLE comments_user');
        $this->addSql('DROP TABLE decision_comments');
        $this->addSql('DROP TABLE skill');
        $this->addSql('DROP TABLE decision_history');
        $this->addSql('ALTER TABLE decision ADD created_by_id INT DEFAULT NULL, ADD created_at DATE NOT NULL, ADD updated_at DATE NOT NULL, DROP start_at, DROP end_at, CHANGE impact impacts LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE decision ADD CONSTRAINT FK_84ACBE48B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_84ACBE48B03A8386 ON decision (created_by_id)');
        $this->addSql('DROP INDEX UNIQ_229ADF8B5585C142 ON expertise');
        $this->addSql('ALTER TABLE expertise ADD department_id INT DEFAULT NULL, ADD user_id INT DEFAULT NULL, ADD is_expert TINYINT(1) NOT NULL, DROP skill_id, DROP type');
        $this->addSql('ALTER TABLE expertise ADD CONSTRAINT FK_229ADF8BAE80F5DF FOREIGN KEY (department_id) REFERENCES department (id)');
        $this->addSql('ALTER TABLE expertise ADD CONSTRAINT FK_229ADF8BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_229ADF8BAE80F5DF ON expertise (department_id)');
        $this->addSql('CREATE INDEX IDX_229ADF8BA76ED395 ON expertise (user_id)');
        $this->addSql('ALTER TABLE notification ADD decision_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CABDEE7539 FOREIGN KEY (decision_id) REFERENCES decision (id)');
        $this->addSql('CREATE INDEX IDX_BF5476CABDEE7539 ON notification (decision_id)');
        $this->addSql('ALTER TABLE opinion ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE opinion ADD CONSTRAINT FK_AB02B027A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_AB02B027A76ED395 ON opinion (user_id)');
        $this->addSql('ALTER TABLE user ADD created_at DATE NOT NULL, ADD updated_at DATE NOT NULL, DROP is_admin');
        $this->addSql('ALTER TABLE validation CHANGE comments comment LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE expertise DROP FOREIGN KEY FK_229ADF8BAE80F5DF');
        $this->addSql('CREATE TABLE comments (id INT AUTO_INCREMENT NOT NULL, created_at DATE DEFAULT NULL, comments LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE comments_user (comments_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_942FBEE63379586 (comments_id), INDEX IDX_942FBEEA76ED395 (user_id), PRIMARY KEY(comments_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE decision_comments (decision_id INT NOT NULL, comments_id INT NOT NULL, INDEX IDX_D242591463379586 (comments_id), INDEX IDX_D2425914BDEE7539 (decision_id), PRIMARY KEY(decision_id, comments_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE skill (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE decision_history (id INT AUTO_INCREMENT NOT NULL, decision_id INT NOT NULL, start_at DATE NOT NULL, end_at DATE NOT NULL, status VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_F378BB54BDEE7539 (decision_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE comments_user ADD CONSTRAINT FK_942FBEE63379586 FOREIGN KEY (comments_id) REFERENCES comments (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comments_user ADD CONSTRAINT FK_942FBEEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE decision_comments ADD CONSTRAINT FK_D242591463379586 FOREIGN KEY (comments_id) REFERENCES comments (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE decision_comments ADD CONSTRAINT FK_D2425914BDEE7539 FOREIGN KEY (decision_id) REFERENCES decision (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE decision_history ADD CONSTRAINT FK_F378BB54BDEE7539 FOREIGN KEY (decision_id) REFERENCES decision (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE content DROP FOREIGN KEY FK_FEC530A9A76ED395');
        $this->addSql('ALTER TABLE content DROP FOREIGN KEY FK_FEC530A9BDEE7539');
        $this->addSql('ALTER TABLE department DROP FOREIGN KEY FK_CD1DE18ABDEE7539');
        $this->addSql('ALTER TABLE history DROP FOREIGN KEY FK_27BA704BBDEE7539');
        $this->addSql('DROP TABLE content');
        $this->addSql('DROP TABLE department');
        $this->addSql('DROP TABLE history');
        $this->addSql('ALTER TABLE expertise DROP FOREIGN KEY FK_229ADF8BA76ED395');
        $this->addSql('DROP INDEX IDX_229ADF8BAE80F5DF ON expertise');
        $this->addSql('DROP INDEX IDX_229ADF8BA76ED395 ON expertise');
        $this->addSql('ALTER TABLE expertise ADD skill_id INT NOT NULL, ADD type VARCHAR(255) NOT NULL, DROP department_id, DROP user_id, DROP is_expert');
        $this->addSql('ALTER TABLE expertise ADD CONSTRAINT FK_229ADF8B5585C142 FOREIGN KEY (skill_id) REFERENCES skill (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_229ADF8B5585C142 ON expertise (skill_id)');
        $this->addSql('ALTER TABLE decision DROP FOREIGN KEY FK_84ACBE48B03A8386');
        $this->addSql('DROP INDEX IDX_84ACBE48B03A8386 ON decision');
        $this->addSql('ALTER TABLE decision ADD start_at DATE NOT NULL, ADD end_at DATE NOT NULL, DROP created_by_id, DROP created_at, DROP updated_at, CHANGE impacts impact LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD is_admin TINYINT(1) DEFAULT NULL, DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE opinion DROP FOREIGN KEY FK_AB02B027A76ED395');
        $this->addSql('DROP INDEX IDX_AB02B027A76ED395 ON opinion');
        $this->addSql('ALTER TABLE opinion DROP user_id');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CABDEE7539');
        $this->addSql('DROP INDEX IDX_BF5476CABDEE7539 ON notification');
        $this->addSql('ALTER TABLE notification DROP decision_id');
        $this->addSql('ALTER TABLE validation CHANGE comment comments LONGTEXT DEFAULT NULL');
    }
}
