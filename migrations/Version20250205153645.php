<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250205153645 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE recette_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE ingredient_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE stock_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE recetteingredient_id_seq CASCADE');
        $this->addSql('ALTER TABLE stock DROP CONSTRAINT stock_idingredient_fkey');
        $this->addSql('ALTER TABLE recetteingredient DROP CONSTRAINT recetteingredient_idrecette_fkey');
        $this->addSql('ALTER TABLE recetteingredient DROP CONSTRAINT recetteingredient_idingredient_fkey');
        $this->addSql('DROP TABLE ingredient');
        $this->addSql('DROP TABLE stock');
        $this->addSql('DROP TABLE recette');
        $this->addSql('DROP TABLE recetteingredient');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE recette_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE ingredient_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE stock_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE recetteingredient_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE ingredient (id INT DEFAULT ingredient_id_seq NOT NULL, nom VARCHAR(255) NOT NULL, photo VARCHAR(255) NOT NULL, assets VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE stock (id INT DEFAULT stock_id_seq NOT NULL, idingredient INT DEFAULT NULL, nombre INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4B365660A606654A ON stock (idingredient)');
        $this->addSql('CREATE TABLE recette (id INT DEFAULT recette_id_seq NOT NULL, nom VARCHAR(255) NOT NULL, photo VARCHAR(255) NOT NULL, assets VARCHAR(255) NOT NULL, prix INT NOT NULL, tempscuisson INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE recetteingredient (id INT DEFAULT recetteingredient_id_seq NOT NULL, idrecette INT NOT NULL, idingredient INT NOT NULL, nombre INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_95E5B7A396BF31FE ON recetteingredient (idrecette)');
        $this->addSql('CREATE INDEX IDX_95E5B7A3A606654A ON recetteingredient (idingredient)');
        $this->addSql('ALTER TABLE stock ADD CONSTRAINT stock_idingredient_fkey FOREIGN KEY (idingredient) REFERENCES ingredient (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE recetteingredient ADD CONSTRAINT recetteingredient_idrecette_fkey FOREIGN KEY (idrecette) REFERENCES recette (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE recetteingredient ADD CONSTRAINT recetteingredient_idingredient_fkey FOREIGN KEY (idingredient) REFERENCES ingredient (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
