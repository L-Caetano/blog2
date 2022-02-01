<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220201220251 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, creation_date DATETIME NOT NULL, update_date DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE imagem (id INT AUTO_INCREMENT NOT NULL, usuario_id INT DEFAULT NULL, titulo VARCHAR(255) NOT NULL, created DATETIME NOT NULL, url VARCHAR(255) NOT NULL, INDEX IDX_1A108309DB38439E (usuario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE imagem_postagem (imagem_id INT NOT NULL, postagem_id INT NOT NULL, INDEX IDX_4AA7486064892549 (imagem_id), INDEX IDX_4AA748606DD36FEA (postagem_id), PRIMARY KEY(imagem_id, postagem_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE postagem (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, titulo VARCHAR(30) NOT NULL, descricao VARCHAR(800) NOT NULL, imagem VARCHAR(300) DEFAULT NULL, autor VARCHAR(255) NOT NULL, INDEX IDX_D0E3845112469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE imagem ADD CONSTRAINT FK_1A108309DB38439E FOREIGN KEY (usuario_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE imagem_postagem ADD CONSTRAINT FK_4AA7486064892549 FOREIGN KEY (imagem_id) REFERENCES imagem (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE imagem_postagem ADD CONSTRAINT FK_4AA748606DD36FEA FOREIGN KEY (postagem_id) REFERENCES postagem (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE postagem ADD CONSTRAINT FK_D0E3845112469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE postagem DROP FOREIGN KEY FK_D0E3845112469DE2');
        $this->addSql('ALTER TABLE imagem_postagem DROP FOREIGN KEY FK_4AA7486064892549');
        $this->addSql('ALTER TABLE imagem_postagem DROP FOREIGN KEY FK_4AA748606DD36FEA');
        $this->addSql('ALTER TABLE imagem DROP FOREIGN KEY FK_1A108309DB38439E');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE imagem');
        $this->addSql('DROP TABLE imagem_postagem');
        $this->addSql('DROP TABLE postagem');
        $this->addSql('DROP TABLE user');
    }
}
