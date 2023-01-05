<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221216081551 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE decision_department DROP FOREIGN KEY FK_8703A80AAE80F5DF');
        $this->addSql('ALTER TABLE decision_department DROP FOREIGN KEY FK_8703A80ABDEE7539');
        $this->addSql('DROP TABLE decision_department');
        $this->addSql('ALTER TABLE decision DROP FOREIGN KEY FK_84ACBE48B03A8386');
        $this->addSql('DROP INDEX IDX_84ACBE48B03A8386 ON decision');
        $this->addSql('ALTER TABLE decision CHANGE created_by_id owner_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE decision ADD CONSTRAINT FK_84ACBE487E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_84ACBE487E3C61F9 ON decision (owner_id)');
        $this->addSql('ALTER TABLE department ADD decision_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE department ADD CONSTRAINT FK_CD1DE18ABDEE7539 FOREIGN KEY (decision_id) REFERENCES decision (id)');
        $this->addSql('CREATE INDEX IDX_CD1DE18ABDEE7539 ON department (decision_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE decision_department (decision_id INT NOT NULL, department_id INT NOT NULL, INDEX IDX_8703A80ABDEE7539 (decision_id), INDEX IDX_8703A80AAE80F5DF (department_id), PRIMARY KEY(decision_id, department_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE decision_department ADD CONSTRAINT FK_8703A80AAE80F5DF FOREIGN KEY (department_id) REFERENCES department (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE decision_department ADD CONSTRAINT FK_8703A80ABDEE7539 FOREIGN KEY (decision_id) REFERENCES decision (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE decision DROP FOREIGN KEY FK_84ACBE487E3C61F9');
        $this->addSql('DROP INDEX IDX_84ACBE487E3C61F9 ON decision');
        $this->addSql('ALTER TABLE decision CHANGE owner_id created_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE decision ADD CONSTRAINT FK_84ACBE48B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_84ACBE48B03A8386 ON decision (created_by_id)');
        $this->addSql('ALTER TABLE department DROP FOREIGN KEY FK_CD1DE18ABDEE7539');
        $this->addSql('DROP INDEX IDX_CD1DE18ABDEE7539 ON department');
        $this->addSql('ALTER TABLE department DROP decision_id');
    }
}
