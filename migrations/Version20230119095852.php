<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230119095852 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE decision DROP FOREIGN KEY FK_84ACBE48EF1A9D84');
        $this->addSql('DROP INDEX IDX_84ACBE48EF1A9D84 ON decision');
        $this->addSql('ALTER TABLE decision DROP notification_id');
        $this->addSql('ALTER TABLE notification ADD decision_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CABDEE7539 FOREIGN KEY (decision_id) REFERENCES decision (id)');
        $this->addSql('CREATE INDEX IDX_BF5476CABDEE7539 ON notification (decision_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CABDEE7539');
        $this->addSql('DROP INDEX IDX_BF5476CABDEE7539 ON notification');
        $this->addSql('ALTER TABLE notification DROP decision_id');
        $this->addSql('ALTER TABLE decision ADD notification_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE decision ADD CONSTRAINT FK_84ACBE48EF1A9D84 FOREIGN KEY (notification_id) REFERENCES notification (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_84ACBE48EF1A9D84 ON decision (notification_id)');
    }
}
