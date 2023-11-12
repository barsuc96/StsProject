<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231110221210 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE wallet DROP FOREIGN KEY FK_7C68921F49CB4726');
        $this->addSql('DROP INDEX IDX_7C68921F49CB4726 ON wallet');
        $this->addSql('ALTER TABLE wallet CHANGE account_id_id account_id INT NOT NULL');
        $this->addSql('ALTER TABLE wallet ADD CONSTRAINT FK_7C68921F9B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id)');
        $this->addSql('CREATE INDEX IDX_7C68921F9B6B5FBA ON wallet (account_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE wallet DROP FOREIGN KEY FK_7C68921F9B6B5FBA');
        $this->addSql('DROP INDEX IDX_7C68921F9B6B5FBA ON wallet');
        $this->addSql('ALTER TABLE wallet CHANGE account_id account_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE wallet ADD CONSTRAINT FK_7C68921F49CB4726 FOREIGN KEY (account_id_id) REFERENCES account (id)');
        $this->addSql('CREATE INDEX IDX_7C68921F49CB4726 ON wallet (account_id_id)');
    }
}
