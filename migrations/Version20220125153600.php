<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220125153600 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE suggestion_history ADD book_isbn VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE suggestion_history ADD CONSTRAINT FK_4860333FD581BFEE FOREIGN KEY (book_isbn) REFERENCES book (isbn)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4860333FD581BFEE ON suggestion_history (book_isbn)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE suggestion_history DROP FOREIGN KEY FK_4860333FD581BFEE');
        $this->addSql('DROP INDEX UNIQ_4860333FD581BFEE ON suggestion_history');
        $this->addSql('ALTER TABLE suggestion_history DROP book_isbn');
    }
}
