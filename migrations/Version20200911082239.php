<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200911082239 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE media (id INT AUTO_INCREMENT NOT NULL, event_media_id INT NOT NULL, media_author_id INT NOT NULL, caption VARCHAR(255) NOT NULL, INDEX IDX_6A2CA10CAC7334A5 (event_media_id), INDEX IDX_6A2CA10CD3154095 (media_author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10CAC7334A5 FOREIGN KEY (event_media_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10CD3154095 FOREIGN KEY (media_author_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE media');
    }
}
