<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221210181217 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE content (id INT AUTO_INCREMENT NOT NULL, created_at DATE DEFAULT NULL, comments LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE content_user (content_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_40F13B8C84A0A3ED (content_id), INDEX IDX_40F13B8CA76ED395 (user_id), PRIMARY KEY(content_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE decision_content (decision_id INT NOT NULL, content_id INT NOT NULL, INDEX IDX_2A07FBB6BDEE7539 (decision_id), INDEX IDX_2A07FBB684A0A3ED (content_id), PRIMARY KEY(decision_id, content_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE content_user ADD CONSTRAINT FK_40F13B8C84A0A3ED FOREIGN KEY (content_id) REFERENCES content (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE content_user ADD CONSTRAINT FK_40F13B8CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE decision_content ADD CONSTRAINT FK_2A07FBB6BDEE7539 FOREIGN KEY (decision_id) REFERENCES decision (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE decision_content ADD CONSTRAINT FK_2A07FBB684A0A3ED FOREIGN KEY (content_id) REFERENCES content (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE decision_comments DROP FOREIGN KEY FK_D242591463379586');
        $this->addSql('ALTER TABLE decision_comments DROP FOREIGN KEY FK_D2425914BDEE7539');
        $this->addSql('ALTER TABLE comments_user DROP FOREIGN KEY FK_942FBEE63379586');
        $this->addSql('ALTER TABLE comments_user DROP FOREIGN KEY FK_942FBEEA76ED395');
        $this->addSql('DROP TABLE comments');
        $this->addSql('DROP TABLE decision_comments');
        $this->addSql('DROP TABLE comments_user');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comments (id INT AUTO_INCREMENT NOT NULL, created_at DATE DEFAULT NULL, comments LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE decision_comments (decision_id INT NOT NULL, comments_id INT NOT NULL, INDEX IDX_D2425914BDEE7539 (decision_id), INDEX IDX_D242591463379586 (comments_id), PRIMARY KEY(decision_id, comments_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE comments_user (comments_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_942FBEEA76ED395 (user_id), INDEX IDX_942FBEE63379586 (comments_id), PRIMARY KEY(comments_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE decision_comments ADD CONSTRAINT FK_D242591463379586 FOREIGN KEY (comments_id) REFERENCES comments (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE decision_comments ADD CONSTRAINT FK_D2425914BDEE7539 FOREIGN KEY (decision_id) REFERENCES decision (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comments_user ADD CONSTRAINT FK_942FBEE63379586 FOREIGN KEY (comments_id) REFERENCES comments (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comments_user ADD CONSTRAINT FK_942FBEEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE content_user DROP FOREIGN KEY FK_40F13B8C84A0A3ED');
        $this->addSql('ALTER TABLE content_user DROP FOREIGN KEY FK_40F13B8CA76ED395');
        $this->addSql('ALTER TABLE decision_content DROP FOREIGN KEY FK_2A07FBB6BDEE7539');
        $this->addSql('ALTER TABLE decision_content DROP FOREIGN KEY FK_2A07FBB684A0A3ED');
        $this->addSql('DROP TABLE content');
        $this->addSql('DROP TABLE content_user');
        $this->addSql('DROP TABLE decision_content');
    }
}
