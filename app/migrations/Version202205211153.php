<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version202205211153 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Initial Migration with base products';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your need

        $this->addSql("INSERT INTO product (id, uuid, date_created, name, price) VALUES (1, '301f9e4a-dab8-11ec-b1db-0242c0a80004', '2022-05-23 16:48:35', 'The Godfather', 59.99);");
        
        $this->addSql("INSERT INTO product (id, uuid, date_created, name, price) VALUES (2, '30226698-dab8-11ec-b422-0242c0a80004', '2022-05-23 16:48:35', 'Steve Jobs', 49.95);");

        $this->addSql("INSERT INTO product (id, uuid, date_created, name, price) VALUES (3, '30229c58-dab8-11ec-964b-0242c0a80004', '2022-05-23 16:48:35', 'The Return of Sherlock Holmes ', 39.99);");

        $this->addSql("INSERT INTO product (id, uuid, date_created, name, price) VALUES (4, '3022cc50-dab8-11ec-9536-0242c0a80004', '2022-05-23 16:48:35', 'The Little Prince', 29.99);");

        $this->addSql("INSERT INTO product (id, uuid, date_created, name, price) VALUES (5, '3022fe78-dab8-11ec-9e61-0242c0a80004', '2022-05-23 16:48:35', 'I Hate Myselfie', 19.99);");

        $this->addSql("INSERT INTO product (id, uuid, date_created, name, price) VALUES (6, '302351ac-dab8-11ec-be30-0242c0a80004', '2022-05-23 16:48:35', 'The Trial ', 9.99);");

    }

    public function down(Schema $schema): void
    {
        $this->addSql("DELETE FROM product WHERE id IN(1,2,3,4,5,6);");
    }
}
