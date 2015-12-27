<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151204223905 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('
            CREATE TABLE business_beer_categories (
                id INT UNSIGNED AUTO_INCREMENT NOT NULL,
                business_id INT UNSIGNED NOT NULL,
                name VARCHAR(255) NOT NULL,

                FOREIGN KEY business_beer_categories__business (business_id) REFERENCES businesses (id)
                  ON UPDATE CASCADE ON DELETE CASCADE,

                UNIQUE INDEX business_beer_categories__name (business_id, name),
                UNIQUE INDEX business_beer_categories__id (business_id, id),

                PRIMARY KEY (id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ');

        $this->addSql('
            CREATE TABLE business_beers (
              id INT UNSIGNED AUTO_INCREMENT NOT NULL,
              business_id INT UNSIGNED NOT NULL,
              category_id INT UNSIGNED NOT NULL,
              name VARCHAR(255) NOT NULL,
              notes TEXT NOT NULL,

              FOREIGN KEY business_beers__category (business_id, category_id)
                REFERENCES business_beer_categories (business_id, id)
                ON UPDATE CASCADE ON DELETE RESTRICT,

              UNIQUE INDEX business_beers__name (business_id, name),

              PRIMARY KEY (id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('DROP TABLE business_beers');
        $this->addSql('DROP TABLE business_beer_categories');
    }
}
