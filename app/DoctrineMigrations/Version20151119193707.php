<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151119193707 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('
          CREATE TABLE users (
            id INT UNSIGNED AUTO_INCREMENT NOT NULL,
            username VARCHAR(255) NOT NULL,
            username_canonical VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            email_canonical VARCHAR(255) NOT NULL,
            enabled TINYINT(1) NOT NULL,
            salt VARCHAR(255) NOT NULL,
            password VARCHAR(255) NOT NULL,
            last_login DATETIME DEFAULT NULL,
            locked TINYINT(1) NOT NULL,
            expired TINYINT(1) NOT NULL,
            expires_at DATETIME DEFAULT NULL,
            confirmation_token VARCHAR(255) DEFAULT NULL,
            password_requested_at DATETIME DEFAULT NULL,
            roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\',
            credentials_expired TINYINT(1) NOT NULL,
            credentials_expire_at DATETIME DEFAULT NULL,

            UNIQUE INDEX users__username_canonical (username_canonical),
            UNIQUE INDEX users__email_canonical (email_canonical),
            PRIMARY KEY(id)

          ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ');

        $this->addSql('
          CREATE TABLE businesses (
            id INT UNSIGNED AUTO_INCREMENT NOT NULL,
            name VARCHAR(255) NOT NULL,
            address VARCHAR(255) NOT NULL,
            phone CHAR(15) NOT NULL,

            administrator_user_id INT UNSIGNED NOT NULL,

            PRIMARY KEY(id),
            UNIQUE INDEX businesses__administrator_user (administrator_user_id),
            FOREIGN KEY businesses__administrator (administrator_user_id) REFERENCES users (id)
              ON UPDATE CASCADE ON DELETE RESTRICT

          ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('DROP TABLE business');
        $this->addSql('DROP TABLE users');
    }
}
