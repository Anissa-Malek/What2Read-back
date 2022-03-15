<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220126143816 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reading (id INT AUTO_INCREMENT NOT NULL, book_isbn VARCHAR(255) NOT NULL, user_id INT NOT NULL, added_at DATETIME NOT NULL, INDEX IDX_C11AFC41D581BFEE (book_isbn), INDEX IDX_C11AFC41A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reading ADD CONSTRAINT FK_C11AFC41D581BFEE FOREIGN KEY (book_isbn) REFERENCES book (isbn)');
        $this->addSql('ALTER TABLE reading ADD CONSTRAINT FK_C11AFC41A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE reading');
    }
}