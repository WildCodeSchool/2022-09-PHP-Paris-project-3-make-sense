<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230116112431 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CA76ED395');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CBDEE7539');
        $this->addSql('DROP TABLE comment');
        $this->addSql('ALTER TABLE decision ADD status VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE history DROP updated_at');
        $this->addSql('ALTER TABLE opinion DROP created_at');
        $this->addSql('ALTER TABLE user CHANGE image_name imagename VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, decision_id INT DEFAULT NULL, created_at DATE DEFAULT NULL, content LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_9474526CA76ED395 (user_id), INDEX IDX_9474526CBDEE7539 (decision_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CBDEE7539 FOREIGN KEY (decision_id) REFERENCES decision (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE history ADD updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE decision DROP status');
        $this->addSql('ALTER TABLE opinion ADD created_at DATE NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE imagename image_name VARCHAR(255) NOT NULL');
    }
}
