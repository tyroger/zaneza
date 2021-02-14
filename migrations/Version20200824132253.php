<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200824132253 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE invitation (id INT AUTO_INCREMENT NOT NULL, event_id INT NOT NULL, sent TINYINT(1) NOT NULL, accepted TINYINT(1) NOT NULL, refused TINYINT(1) NOT NULL, INDEX IDX_F11D61A271F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE invitation_user (invitation_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_40921A1DA35D7AF0 (invitation_id), INDEX IDX_40921A1DA76ED395 (user_id), PRIMARY KEY(invitation_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE invitation ADD CONSTRAINT FK_F11D61A271F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE invitation_user ADD CONSTRAINT FK_40921A1DA35D7AF0 FOREIGN KEY (invitation_id) REFERENCES invitation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE invitation_user ADD CONSTRAINT FK_40921A1DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE invitation_user DROP FOREIGN KEY FK_40921A1DA35D7AF0');
        $this->addSql('DROP TABLE invitation');
        $this->addSql('DROP TABLE invitation_user');
    }
}
