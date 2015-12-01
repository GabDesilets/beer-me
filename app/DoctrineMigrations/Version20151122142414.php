<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20151122142414 extends AbstractMigration
{
    /**
     **
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('
          CREATE TABLE business_requests (
            id INT UNSIGNED AUTO_INCREMENT NOT NULL,
            first_name VARCHAR(255) NOT NULL,
            last_name VARCHAR(255) NOT NULL,
            business_name VARCHAR(255) NOT NULL,
            phone VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            address VARCHAR(255) NOT NULL,
            zip_code CHAR(6) NOT NULL,
            country VARCHAR(255) NOT NULL,
            province VARCHAR(255) NOT NULL,
            city VARCHAR(255) NOT NULL,
            PRIMARY KEY(id)
          ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('DROP TABLE business_requests');
    }
}
