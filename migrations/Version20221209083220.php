<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221209083220 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE decision_comments (decision_id INT NOT NULL, comments_id INT NOT NULL, INDEX IDX_D2425914BDEE7539 (decision_id), INDEX IDX_D242591463379586 (comments_id), PRIMARY KEY(decision_id, comments_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE decision_comments ADD CONSTRAINT FK_D2425914BDEE7539 FOREIGN KEY (decision_id) REFERENCES decision (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE decision_comments ADD CONSTRAINT FK_D242591463379586 FOREIGN KEY (comments_id) REFERENCES comments (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE decision_comments DROP FOREIGN KEY FK_D2425914BDEE7539');
        $this->addSql('ALTER TABLE decision_comments DROP FOREIGN KEY FK_D242591463379586');
        $this->addSql('DROP TABLE decision_comments');
    }
}
