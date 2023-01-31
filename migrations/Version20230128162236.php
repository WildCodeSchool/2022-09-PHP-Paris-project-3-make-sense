<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230128162236 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE decision CHANGE report report LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE notification ADD user_read TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE poster poster VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notification DROP user_read');
        $this->addSql('ALTER TABLE user CHANGE poster poster VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE decision CHANGE report report LONGTEXT DEFAULT NULL');
    }
}
