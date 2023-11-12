<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231110224844 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE history DROP FOREIGN KEY FK_27BA704B49CB4726');
        $this->addSql('DROP INDEX IDX_27BA704B49CB4726 ON history');
        $this->addSql('ALTER TABLE history DROP account_id_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE history ADD account_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE history ADD CONSTRAINT FK_27BA704B49CB4726 FOREIGN KEY (account_id_id) REFERENCES account (id)');
        $this->addSql('CREATE INDEX IDX_27BA704B49CB4726 ON history (account_id_id)');
    }
}
