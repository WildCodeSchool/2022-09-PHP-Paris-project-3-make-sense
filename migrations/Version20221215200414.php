<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221215200414 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE decision_department (decision_id INT NOT NULL, department_id INT NOT NULL, INDEX IDX_8703A80ABDEE7539 (decision_id), INDEX IDX_8703A80AAE80F5DF (department_id), PRIMARY KEY(decision_id, department_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE decision_department ADD CONSTRAINT FK_8703A80ABDEE7539 FOREIGN KEY (decision_id) REFERENCES decision (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE decision_department ADD CONSTRAINT FK_8703A80AAE80F5DF FOREIGN KEY (department_id) REFERENCES department (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE decision_department DROP FOREIGN KEY FK_8703A80ABDEE7539');
        $this->addSql('ALTER TABLE decision_department DROP FOREIGN KEY FK_8703A80AAE80F5DF');
        $this->addSql('DROP TABLE decision_department');
    }
}
