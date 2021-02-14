<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200902115825 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contribution ADD participant_id INT NOT NULL, ADD event_id INT NOT NULL');
        $this->addSql('ALTER TABLE contribution ADD CONSTRAINT FK_EA351E159D1C3019 FOREIGN KEY (participant_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE contribution ADD CONSTRAINT FK_EA351E1571F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('CREATE INDEX IDX_EA351E159D1C3019 ON contribution (participant_id)');
        $this->addSql('CREATE INDEX IDX_EA351E1571F7E88B ON contribution (event_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contribution DROP FOREIGN KEY FK_EA351E159D1C3019');
        $this->addSql('ALTER TABLE contribution DROP FOREIGN KEY FK_EA351E1571F7E88B');
        $this->addSql('DROP INDEX IDX_EA351E159D1C3019 ON contribution');
        $this->addSql('DROP INDEX IDX_EA351E1571F7E88B ON contribution');
        $this->addSql('ALTER TABLE contribution DROP participant_id, DROP event_id');
    }
}
