<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221214225332 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE content ADD decision_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE content ADD CONSTRAINT FK_FEC530A9BDEE7539 FOREIGN KEY (decision_id) REFERENCES decision (id)');
        $this->addSql('CREATE INDEX IDX_FEC530A9BDEE7539 ON content (decision_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE content DROP FOREIGN KEY FK_FEC530A9BDEE7539');
        $this->addSql('DROP INDEX IDX_FEC530A9BDEE7539 ON content');
        $this->addSql('ALTER TABLE content DROP decision_id');
    }
}
