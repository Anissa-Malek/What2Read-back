<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220125161115 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE review ADD book_isbn VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6D581BFEE FOREIGN KEY (book_isbn) REFERENCES book (isbn)');
        $this->addSql('CREATE INDEX IDX_794381C6D581BFEE ON review (book_isbn)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6D581BFEE');
        $this->addSql('DROP INDEX IDX_794381C6D581BFEE ON review');
        $this->addSql('ALTER TABLE review DROP book_isbn');
    }
}
