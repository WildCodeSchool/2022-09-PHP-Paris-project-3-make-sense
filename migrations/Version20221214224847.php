<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221214224847 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE decision_content DROP FOREIGN KEY FK_2A07FBB684A0A3ED');
        $this->addSql('ALTER TABLE decision_content DROP FOREIGN KEY FK_2A07FBB6BDEE7539');
        $this->addSql('DROP TABLE decision_content');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE decision_content (decision_id INT NOT NULL, content_id INT NOT NULL, INDEX IDX_2A07FBB6BDEE7539 (decision_id), INDEX IDX_2A07FBB684A0A3ED (content_id), PRIMARY KEY(decision_id, content_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE decision_content ADD CONSTRAINT FK_2A07FBB684A0A3ED FOREIGN KEY (content_id) REFERENCES content (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE decision_content ADD CONSTRAINT FK_2A07FBB6BDEE7539 FOREIGN KEY (decision_id) REFERENCES decision (id) ON UPDATE NO ACTION ON DELETE CASCADE');
    }
}
