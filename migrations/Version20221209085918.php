<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221209085918 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE decision_history ADD decision_id INT NOT NULL');
        $this->addSql('ALTER TABLE decision_history ADD CONSTRAINT FK_F378BB54BDEE7539 FOREIGN KEY (decision_id) REFERENCES decision (id)');
        $this->addSql('CREATE INDEX IDX_F378BB54BDEE7539 ON decision_history (decision_id)');
        $this->addSql('ALTER TABLE validation ADD decision_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE validation ADD CONSTRAINT FK_16AC5B6EBDEE7539 FOREIGN KEY (decision_id) REFERENCES decision (id)');
        $this->addSql('CREATE INDEX IDX_16AC5B6EBDEE7539 ON validation (decision_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE decision_history DROP FOREIGN KEY FK_F378BB54BDEE7539');
        $this->addSql('DROP INDEX IDX_F378BB54BDEE7539 ON decision_history');
        $this->addSql('ALTER TABLE decision_history DROP decision_id');
        $this->addSql('ALTER TABLE validation DROP FOREIGN KEY FK_16AC5B6EBDEE7539');
        $this->addSql('DROP INDEX IDX_16AC5B6EBDEE7539 ON validation');
        $this->addSql('ALTER TABLE validation DROP decision_id');
    }
}
