<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221209083008 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comments_user (comments_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_942FBEE63379586 (comments_id), INDEX IDX_942FBEEA76ED395 (user_id), PRIMARY KEY(comments_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comments_user ADD CONSTRAINT FK_942FBEE63379586 FOREIGN KEY (comments_id) REFERENCES comments (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comments_user ADD CONSTRAINT FK_942FBEEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comments_user DROP FOREIGN KEY FK_942FBEE63379586');
        $this->addSql('ALTER TABLE comments_user DROP FOREIGN KEY FK_942FBEEA76ED395');
        $this->addSql('DROP TABLE comments_user');
    }
}
