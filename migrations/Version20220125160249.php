<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220125160249 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE favorite ADD book_isbn VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE favorite ADD CONSTRAINT FK_68C58ED9D581BFEE FOREIGN KEY (book_isbn) REFERENCES book (isbn)');
        $this->addSql('CREATE INDEX IDX_68C58ED9D581BFEE ON favorite (book_isbn)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE favorite DROP FOREIGN KEY FK_68C58ED9D581BFEE');
        $this->addSql('DROP INDEX IDX_68C58ED9D581BFEE ON favorite');
        $this->addSql('ALTER TABLE favorite DROP book_isbn');
    }
}
