<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221214212747 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE decision DROP started_at, DROP ended_at');
        $this->addSql('ALTER TABLE decision_history ADD started_at DATE NOT NULL, ADD ended_at DATE NOT NULL, DROP start_at, DROP end_at');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE decision_history ADD start_at DATE NOT NULL, ADD end_at DATE NOT NULL, DROP started_at, DROP ended_at');
        $this->addSql('ALTER TABLE decision ADD started_at DATE NOT NULL, ADD ended_at DATE NOT NULL');
    }
}
