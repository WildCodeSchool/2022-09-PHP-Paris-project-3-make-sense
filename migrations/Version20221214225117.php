<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221214225117 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE content_user DROP FOREIGN KEY FK_40F13B8C84A0A3ED');
        $this->addSql('ALTER TABLE content_user DROP FOREIGN KEY FK_40F13B8CA76ED395');
        $this->addSql('DROP TABLE content_user');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE content_user (content_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_40F13B8CA76ED395 (user_id), INDEX IDX_40F13B8C84A0A3ED (content_id), PRIMARY KEY(content_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE content_user ADD CONSTRAINT FK_40F13B8C84A0A3ED FOREIGN KEY (content_id) REFERENCES content (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE content_user ADD CONSTRAINT FK_40F13B8CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE CASCADE');
    }
}
