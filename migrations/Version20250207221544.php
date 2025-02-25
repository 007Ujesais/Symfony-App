<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250207221544 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ingredient (id INT GENERATED BY DEFAULT AS IDENTITY NOT NULL, nom VARCHAR(255) NOT NULL, photo BYTEA DEFAULT NULL, assets BYTEA DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE recette (id INT GENERATED BY DEFAULT AS IDENTITY NOT NULL, nom VARCHAR(255) DEFAULT NULL, photo BYTEA DEFAULT NULL, assets BYTEA DEFAULT NULL, prix INT DEFAULT NULL, temps_cuisson INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE recette_ingredient (id INT GENERATED BY DEFAULT AS IDENTITY NOT NULL, nombre INT NOT NULL, recette_id INT DEFAULT NULL, ingredient_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_17C041A989312FE9 ON recette_ingredient (recette_id)');
        $this->addSql('CREATE INDEX IDX_17C041A9933FE08C ON recette_ingredient (ingredient_id)');
        $this->addSql('CREATE TABLE stock (id INT GENERATED BY DEFAULT AS IDENTITY NOT NULL, nombre INT NOT NULL, ingredient_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4B365660933FE08C ON stock (ingredient_id)');
        $this->addSql('ALTER TABLE recette_ingredient ADD CONSTRAINT FK_17C041A989312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE recette_ingredient ADD CONSTRAINT FK_17C041A9933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE stock ADD CONSTRAINT FK_4B365660933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recette_ingredient DROP CONSTRAINT FK_17C041A989312FE9');
        $this->addSql('ALTER TABLE recette_ingredient DROP CONSTRAINT FK_17C041A9933FE08C');
        $this->addSql('ALTER TABLE stock DROP CONSTRAINT FK_4B365660933FE08C');
        $this->addSql('DROP TABLE ingredient');
        $this->addSql('DROP TABLE recette');
        $this->addSql('DROP TABLE recette_ingredient');
        $this->addSql('DROP TABLE stock');
    }
}
