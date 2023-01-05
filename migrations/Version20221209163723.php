<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221209163723 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_expertise (user_id INT NOT NULL, expertise_id INT NOT NULL, INDEX IDX_227A526FA76ED395 (user_id), INDEX IDX_227A526F9D5B92F9 (expertise_id), PRIMARY KEY(user_id, expertise_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_expertise ADD CONSTRAINT FK_227A526FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_expertise ADD CONSTRAINT FK_227A526F9D5B92F9 FOREIGN KEY (expertise_id) REFERENCES expertise (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_expertise DROP FOREIGN KEY FK_227A526FA76ED395');
        $this->addSql('ALTER TABLE user_expertise DROP FOREIGN KEY FK_227A526F9D5B92F9');
        $this->addSql('DROP TABLE user_expertise');
    }
}
