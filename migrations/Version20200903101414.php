<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200903101414 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE invitation CHANGE sent sent TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE accepted accepted TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE refused refused TINYINT(1) DEFAULT \'0\' NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE invitation CHANGE sent sent TINYINT(1) DEFAULT \'0\', CHANGE accepted accepted TINYINT(1) DEFAULT \'0\', CHANGE refused refused TINYINT(1) DEFAULT \'0\'');
    }
}
