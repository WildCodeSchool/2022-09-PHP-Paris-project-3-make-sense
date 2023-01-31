<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230130124043 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CA1E058452');
        $this->addSql('DROP INDEX IDX_BF5476CA1E058452 ON notification');
        $this->addSql('ALTER TABLE notification DROP history_id');
        $this->addSql('ALTER TABLE user ADD poster VARCHAR(255) DEFAULT NULL, DROP image_name');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notification ADD history_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA1E058452 FOREIGN KEY (history_id) REFERENCES history (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_BF5476CA1E058452 ON notification (history_id)');
        $this->addSql('ALTER TABLE user ADD image_name VARCHAR(255) NOT NULL, DROP poster');
    }
}
