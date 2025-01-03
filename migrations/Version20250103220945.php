<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250103220945 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category_shop (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cursus (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, INDEX IDX_255A0C312469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lessons (id INT AUTO_INCREMENT NOT NULL, cursus_id INT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, price DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, attachment VARCHAR(255) NOT NULL, is_validated TINYINT(1) NOT NULL, INDEX IDX_3F4218D940AEF4B9 (cursus_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, delivery_address VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, token_registration VARCHAR(255) DEFAULT NULL, token_registration_life_time DATETIME NOT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_cursus (user_id INT NOT NULL, cursus_id INT NOT NULL, INDEX IDX_6707BBFEA76ED395 (user_id), INDEX IDX_6707BBFE40AEF4B9 (cursus_id), PRIMARY KEY(user_id, cursus_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_lessons (user_id INT NOT NULL, lessons_id INT NOT NULL, INDEX IDX_674F06D3A76ED395 (user_id), INDEX IDX_674F06D3FED07355 (lessons_id), PRIMARY KEY(user_id, lessons_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_cursus_validation (user_id INT NOT NULL, cursus_id INT NOT NULL, INDEX IDX_660B53D9A76ED395 (user_id), INDEX IDX_660B53D940AEF4B9 (cursus_id), PRIMARY KEY(user_id, cursus_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_lessons_validation (user_id INT NOT NULL, lessons_id INT NOT NULL, INDEX IDX_3541309FA76ED395 (user_id), INDEX IDX_3541309FFED07355 (lessons_id), PRIMARY KEY(user_id, lessons_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cursus ADD CONSTRAINT FK_255A0C312469DE2 FOREIGN KEY (category_id) REFERENCES category_shop (id)');
        $this->addSql('ALTER TABLE lessons ADD CONSTRAINT FK_3F4218D940AEF4B9 FOREIGN KEY (cursus_id) REFERENCES cursus (id)');
        $this->addSql('ALTER TABLE user_cursus ADD CONSTRAINT FK_6707BBFEA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_cursus ADD CONSTRAINT FK_6707BBFE40AEF4B9 FOREIGN KEY (cursus_id) REFERENCES cursus (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_lessons ADD CONSTRAINT FK_674F06D3A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_lessons ADD CONSTRAINT FK_674F06D3FED07355 FOREIGN KEY (lessons_id) REFERENCES lessons (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_cursus_validation ADD CONSTRAINT FK_660B53D9A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_cursus_validation ADD CONSTRAINT FK_660B53D940AEF4B9 FOREIGN KEY (cursus_id) REFERENCES cursus (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_lessons_validation ADD CONSTRAINT FK_3541309FA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_lessons_validation ADD CONSTRAINT FK_3541309FFED07355 FOREIGN KEY (lessons_id) REFERENCES lessons (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cursus DROP FOREIGN KEY FK_255A0C312469DE2');
        $this->addSql('ALTER TABLE lessons DROP FOREIGN KEY FK_3F4218D940AEF4B9');
        $this->addSql('ALTER TABLE user_cursus DROP FOREIGN KEY FK_6707BBFEA76ED395');
        $this->addSql('ALTER TABLE user_cursus DROP FOREIGN KEY FK_6707BBFE40AEF4B9');
        $this->addSql('ALTER TABLE user_lessons DROP FOREIGN KEY FK_674F06D3A76ED395');
        $this->addSql('ALTER TABLE user_lessons DROP FOREIGN KEY FK_674F06D3FED07355');
        $this->addSql('ALTER TABLE user_cursus_validation DROP FOREIGN KEY FK_660B53D9A76ED395');
        $this->addSql('ALTER TABLE user_cursus_validation DROP FOREIGN KEY FK_660B53D940AEF4B9');
        $this->addSql('ALTER TABLE user_lessons_validation DROP FOREIGN KEY FK_3541309FA76ED395');
        $this->addSql('ALTER TABLE user_lessons_validation DROP FOREIGN KEY FK_3541309FFED07355');
        $this->addSql('DROP TABLE category_shop');
        $this->addSql('DROP TABLE cursus');
        $this->addSql('DROP TABLE lessons');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE user_cursus');
        $this->addSql('DROP TABLE user_lessons');
        $this->addSql('DROP TABLE user_cursus_validation');
        $this->addSql('DROP TABLE user_lessons_validation');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
