<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220314113215 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE images DROP INDEX UNIQ_E01FBE6A4C2885D7, ADD INDEX IDX_E01FBE6A4C2885D7 (annonces_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE images DROP INDEX IDX_E01FBE6A4C2885D7, ADD UNIQUE INDEX UNIQ_E01FBE6A4C2885D7 (annonces_id)');
    }
}
