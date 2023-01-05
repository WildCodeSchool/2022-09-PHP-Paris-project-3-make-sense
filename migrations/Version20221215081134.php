<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221215081134 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE history (id INT AUTO_INCREMENT NOT NULL, decision_id INT NOT NULL, started_at DATE NOT NULL, ended_at DATE NOT NULL, status VARCHAR(50) NOT NULL, INDEX IDX_27BA704BBDEE7539 (decision_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE history ADD CONSTRAINT FK_27BA704BBDEE7539 FOREIGN KEY (decision_id) REFERENCES decision (id)');
        $this->addSql('ALTER TABLE decision_history DROP FOREIGN KEY FK_F378BB54BDEE7539');
        $this->addSql('DROP TABLE decision_history');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE decision_history (id INT AUTO_INCREMENT NOT NULL, decision_id INT NOT NULL, status VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, started_at DATE NOT NULL, ended_at DATE NOT NULL, INDEX IDX_F378BB54BDEE7539 (decision_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE decision_history ADD CONSTRAINT FK_F378BB54BDEE7539 FOREIGN KEY (decision_id) REFERENCES decision (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE history DROP FOREIGN KEY FK_27BA704BBDEE7539');
        $this->addSql('DROP TABLE history');
    }
}
