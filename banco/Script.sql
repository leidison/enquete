-- MySQL Script generated by MySQL Workbench
-- Ter 05 Abr 2016 13:33:29 BRT
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema enquete
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `enquete` ;

-- -----------------------------------------------------
-- Schema enquete
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `enquete` DEFAULT CHARACTER SET utf8 ;
USE `enquete` ;

-- -----------------------------------------------------
-- Table `user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `user` ;

CREATE TABLE IF NOT EXISTS `user` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '	',
  `username` VARCHAR(255) NOT NULL,
  `username_canonical` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `email_canonical` VARCHAR(255) NOT NULL,
  `enabled` TINYINT(1) NOT NULL,
  `salt` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `last_login` DATETIME NULL,
  `locked` TINYINT(1) NOT NULL,
  `expired` TINYINT(1) NOT NULL,
  `expires_at` DATETIME NULL,
  `confirmation_token` VARCHAR(255) NULL,
  `password_requested_at` DATETIME NULL,
  `roles` LONGTEXT NOT NULL,
  `credentials_expired` TINYINT(1) NOT NULL,
  `credentials_expire_at` DATETIME NULL,
  `person_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `username_canonical_UNIQUE` (`username_canonical` ASC),
  UNIQUE INDEX `email_canonical_UNIQUE` (`email_canonical` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `oauth2_client`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `oauth2_client` ;

CREATE TABLE IF NOT EXISTS `oauth2_client` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `random_id` VARCHAR(255) NOT NULL,
  `redirect_uris` LONGTEXT NOT NULL,
  `secret` VARCHAR(255) NOT NULL,
  `allowed_grant_types` LONGTEXT NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `oauth2_access_token`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `oauth2_access_token` ;

CREATE TABLE IF NOT EXISTS `oauth2_access_token` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `token` VARCHAR(45) NOT NULL,
  `expires_at` INT(11) NULL,
  `scope` VARCHAR(255) NULL,
  `user_id` INT NULL,
  `client_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `token_UNIQUE` (`token` ASC),
  INDEX `fk_oauth2_access_token_user1_idx` (`user_id` ASC),
  INDEX `fk_oauth2_access_token_oauth2_client1_idx` (`client_id` ASC),
  CONSTRAINT `fk_oauth2_access_token_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_oauth2_access_token_oauth2_client1`
    FOREIGN KEY (`client_id`)
    REFERENCES `oauth2_client` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `oauth2_auth_code`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `oauth2_auth_code` ;

CREATE TABLE IF NOT EXISTS `oauth2_auth_code` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `token` VARCHAR(255) NOT NULL,
  `redirect_uri` LONGTEXT NOT NULL,
  `expires_at` INT(11) NULL,
  `scope` VARCHAR(255) NULL,
  `client_id` INT NOT NULL,
  `user_id` INT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `token_UNIQUE` (`token` ASC),
  INDEX `fk_oauth2_auth_code_oauth2_client1_idx` (`client_id` ASC),
  INDEX `fk_oauth2_auth_code_user1_idx` (`user_id` ASC),
  CONSTRAINT `fk_oauth2_auth_code_oauth2_client1`
    FOREIGN KEY (`client_id`)
    REFERENCES `oauth2_client` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_oauth2_auth_code_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `oauth2_refresh_token`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `oauth2_refresh_token` ;

CREATE TABLE IF NOT EXISTS `oauth2_refresh_token` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `token` VARCHAR(255) NOT NULL,
  `expires_at` INT(11) NULL,
  `scope` VARCHAR(255) NULL,
  `client_id` INT NOT NULL,
  `user_id` INT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_oauth2_refresh_token_oauth2_client1_idx` (`client_id` ASC),
  INDEX `fk_oauth2_refresh_token_user1_idx` (`user_id` ASC),
  UNIQUE INDEX `token_UNIQUE` (`token` ASC),
  CONSTRAINT `fk_oauth2_refresh_token_oauth2_client1`
    FOREIGN KEY (`client_id`)
    REFERENCES `oauth2_client` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_oauth2_refresh_token_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `enquete`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `enquete` ;

CREATE TABLE IF NOT EXISTS `enquete` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `titulo` VARCHAR(80) NOT NULL,
  `slug` VARCHAR(100) NOT NULL,
  `user_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_enquete_user1_idx` (`user_id` ASC),
  CONSTRAINT `fk_enquete_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pergunta`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pergunta` ;

CREATE TABLE IF NOT EXISTS `pergunta` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `descricao` VARCHAR(45) NOT NULL,
  `enquete_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_pergunta_enquete1_idx` (`enquete_id` ASC),
  CONSTRAINT `fk_pergunta_enquete1`
    FOREIGN KEY (`enquete_id`)
    REFERENCES `enquete` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `resposta`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `resposta` ;

CREATE TABLE IF NOT EXISTS `resposta` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `descricao` VARCHAR(45) NOT NULL,
  `pergunta_id` INT NOT NULL,
  `quantidade` INT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  INDEX `fk_resposta_pergunta1_idx` (`pergunta_id` ASC),
  CONSTRAINT `fk_resposta_pergunta1`
    FOREIGN KEY (`pergunta_id`)
    REFERENCES `pergunta` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
