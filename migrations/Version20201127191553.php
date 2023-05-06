<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201127191553 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE token DROP FOREIGN KEY token_user_id_fk');
        $this->addSql('CREATE TABLE link_day_report (id INT AUTO_INCREMENT NOT NULL, date DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE link_day_report_row (id INT AUTO_INCREMENT NOT NULL, report_id_id INT NOT NULL, link_id_id INT NOT NULL, position INT NOT NULL, INDEX IDX_463FCAC05558992E (report_id_id), INDEX IDX_463FCAC0D0FFC289 (link_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE link_day_report_row ADD CONSTRAINT FK_463FCAC05558992E FOREIGN KEY (report_id_id) REFERENCES link_day_report (id)');
        $this->addSql('ALTER TABLE link_day_report_row ADD CONSTRAINT FK_463FCAC0D0FFC289 FOREIGN KEY (link_id_id) REFERENCES link (id)');
        $this->addSql('ALTER TABLE link DROP FOREIGN KEY FK_link_link');
        $this->addSql('ALTER TABLE link CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE block_id block_id INT NOT NULL, CHANGE href href VARCHAR(255) NOT NULL, CHANGE icon icon VARCHAR(255) NOT NULL, CHANGE private private TINYINT(1) NOT NULL, CHANGE deleted deleted TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE link ADD CONSTRAINT FK_36AC99F1E9ED820C FOREIGN KEY (block_id) REFERENCES link_block (id)');
        $this->addSql('ALTER TABLE link RENAME INDEX fk_link_block_id TO IDX_36AC99F1E9ED820C');
        $this->addSql('ALTER TABLE link_block CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE col_num col_num INT NOT NULL, CHANGE sort sort INT NOT NULL, CHANGE private private TINYINT(1) NOT NULL, CHANGE deleted deleted TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE link_view DROP FOREIGN KEY FK2');
        $this->addSql('ALTER TABLE link_view CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE link_id link_id INT NOT NULL, CHANGE ip4 ip4 INT NOT NULL, CHANGE is_guest is_guest TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE link_view ADD CONSTRAINT FK_4FBA3927ADA40271 FOREIGN KEY (link_id) REFERENCES link (id)');
        $this->addSql('ALTER TABLE link_view RENAME INDEX fk2 TO IDX_4FBA3927ADA40271');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE link_day_report_row DROP FOREIGN KEY FK_463FCAC05558992E');
        $this->addSql('CREATE TABLE bank_card (id TINYINT(1) NOT NULL COMMENT \'№\', bank_name VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci` COMMENT \'Банк\', card_no VARCHAR(16) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci` COMMENT \'Номер карты\', sort TINYINT(1) NOT NULL COMMENT \'Сортировка\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE token (id INT UNSIGNED AUTO_INCREMENT NOT NULL COMMENT \'№\', user_id INT NOT NULL COMMENT \'Пользователь\', token VARCHAR(128) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci` COMMENT \'Токен\', die DATETIME NOT NULL COMMENT \'Время смерти\', INDEX token__index (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, auth_key VARCHAR(32) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, password_hash VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, password_reset_token VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`, email VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, status SMALLINT DEFAULT 10 NOT NULL, created_at INT NOT NULL, updated_at INT NOT NULL, verification_token VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`, UNIQUE INDEX email (email), UNIQUE INDEX password_reset_token (password_reset_token), UNIQUE INDEX username (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE token ADD CONSTRAINT token_user_id_fk FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('DROP TABLE link_day_report');
        $this->addSql('DROP TABLE link_day_report_row');
        $this->addSql('ALTER TABLE link DROP FOREIGN KEY FK_36AC99F1E9ED820C');
        $this->addSql('ALTER TABLE link CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL, CHANGE block_id block_id INT UNSIGNED DEFAULT NULL, CHANGE href href VARCHAR(150) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, CHANGE icon icon TEXT CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, CHANGE deleted deleted TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE private private TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE link ADD CONSTRAINT FK_link_link FOREIGN KEY (block_id) REFERENCES link_block (id) ON UPDATE CASCADE ON DELETE SET NULL');
        $this->addSql('ALTER TABLE link RENAME INDEX idx_36ac99f1e9ed820c TO FK_link_block_id');
        $this->addSql('ALTER TABLE link_block CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL, CHANGE col_num col_num TINYINT(1) DEFAULT \'1\' NOT NULL, CHANGE sort sort INT UNSIGNED DEFAULT 1 NOT NULL, CHANGE private private TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE deleted deleted TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE link_view DROP FOREIGN KEY FK_4FBA3927ADA40271');
        $this->addSql('ALTER TABLE link_view CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL, CHANGE link_id link_id INT UNSIGNED NOT NULL, CHANGE ip4 ip4 INT UNSIGNED NOT NULL, CHANGE is_guest is_guest TINYINT(1) DEFAULT \'0\' NOT NULL COMMENT \'Гость?\'');
        $this->addSql('ALTER TABLE link_view ADD CONSTRAINT FK2 FOREIGN KEY (link_id) REFERENCES link (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE link_view RENAME INDEX idx_4fba3927ada40271 TO FK2');
    }
}
