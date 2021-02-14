<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200911082823 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media DROP FOREIGN KEY FK_6A2CA10CAC7334A5');
        $this->addSql('DROP INDEX IDX_6A2CA10CAC7334A5 ON media');
        $this->addSql('ALTER TABLE media CHANGE event_media_id media_event_id INT NOT NULL');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10C7E96C639 FOREIGN KEY (media_event_id) REFERENCES event (id)');
        $this->addSql('CREATE INDEX IDX_6A2CA10C7E96C639 ON media (media_event_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media DROP FOREIGN KEY FK_6A2CA10C7E96C639');
        $this->addSql('DROP INDEX IDX_6A2CA10C7E96C639 ON media');
        $this->addSql('ALTER TABLE media CHANGE media_event_id event_media_id INT NOT NULL');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10CAC7334A5 FOREIGN KEY (event_media_id) REFERENCES event (id)');
        $this->addSql('CREATE INDEX IDX_6A2CA10CAC7334A5 ON media (event_media_id)');
    }
}
