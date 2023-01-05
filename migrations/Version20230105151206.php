<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230105151206 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE decision DROP end_at');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CA1E058452');
        $this->addSql('DROP INDEX IDX_BF5476CA1E058452 ON notification');
        $this->addSql('ALTER TABLE notification ADD message LONGTEXT NOT NULL, CHANGE history_id decision_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CABDEE7539 FOREIGN KEY (decision_id) REFERENCES decision (id)');
        $this->addSql('CREATE INDEX IDX_BF5476CABDEE7539 ON notification (decision_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CABDEE7539');
        $this->addSql('DROP INDEX IDX_BF5476CABDEE7539 ON notification');
        $this->addSql('ALTER TABLE notification DROP message, CHANGE decision_id history_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA1E058452 FOREIGN KEY (history_id) REFERENCES history (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_BF5476CA1E058452 ON notification (history_id)');
        $this->addSql('ALTER TABLE decision ADD end_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }
}
