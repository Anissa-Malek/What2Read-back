<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220124124646 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE book DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE book ADD isbn VARCHAR(255) NOT NULL, DROP id');
        $this->addSql('ALTER TABLE book ADD PRIMARY KEY (isbn)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book ADD id INT AUTO_INCREMENT NOT NULL, DROP isbn, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
    }
}
