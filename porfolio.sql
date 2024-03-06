-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Drop existing schema and tables
-- -----------------------------------------------------

DROP SCHEMA IF EXISTS `porfolio`;

-- -----------------------------------------------------
-- Create new schema
-- -----------------------------------------------------

CREATE SCHEMA IF NOT EXISTS `porfolio` DEFAULT CHARACTER SET utf8mb4 ;
USE `porfolio` ;

-- -----------------------------------------------------
-- Table `porfolio`.`usuarios`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `porfolio`.`usuarios` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(128) NOT NULL,
  `apellidos` VARCHAR(128) NOT NULL,
  `foto` VARCHAR(128) NULL,
  `categoria_profesional` VARCHAR(64) NULL,
  `email` VARCHAR(64) NOT NULL,
  `resumen_perfil` TINYTEXT NULL,
  `password` VARCHAR(64) NOT NULL,
  `visible` TINYINT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `token` VARCHAR(128) NULL,
  `fecha_creacion_token` TIMESTAMP NULL,
  `cuenta_activa` TINYINT NULL,
  PRIMARY KEY (`id`)
)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `porfolio`.`trabajos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `trabajos`;
CREATE TABLE IF NOT EXISTS `porfolio`.`trabajos` (
  `usuarios_id` INT NOT NULL,
  `id` INT NOT NULL AUTO_INCREMENT,
  `titulo` VARCHAR(128) NOT NULL,
  `descripcion` VARCHAR(256) NULL,
  `fecha_inicio` DATE NULL,
  `fecha_final` DATE NULL,
  `logros` VARCHAR(512) NULL,
  `visible` TINYINT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `fk_table1_usuarios_idx` (`usuarios_id` ASC),
  CONSTRAINT `fk_table1_usuarios`
    FOREIGN KEY (`usuarios_id`)
    REFERENCES `usuarios` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `porfolio`.`proyectos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `proyectos`;
CREATE TABLE IF NOT EXISTS `porfolio`.`proyectos` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `titulo` VARCHAR(128) NOT NULL,
  `descripcion` VARCHAR(256) NULL,
  `logo` VARCHAR(128) NULL,
  `tecnologias` VARCHAR(256) NULL,
  `visible` TINYINT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `usuarios_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_proyectos_usuarios1_idx` (`usuarios_id` ASC),
  CONSTRAINT `fk_proyectos_usuarios1`
    FOREIGN KEY (`usuarios_id`)
    REFERENCES `usuarios` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `porfolio`.`redes_sociales`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `redes_sociales`;
CREATE TABLE IF NOT EXISTS `porfolio`.`redes_sociales` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `url` VARCHAR(256) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `usuarios_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_redes_sociales_usuarios1_idx` (`usuarios_id` ASC),
  CONSTRAINT `fk_redes_sociales_usuarios1`
    FOREIGN KEY (`usuarios_id`)
    REFERENCES `usuarios` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `porfolio`.`categorias_skills`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `categorias_skills`;
CREATE TABLE IF NOT EXISTS `porfolio`.`categorias_skills` (
  `categoria` VARCHAR(32) NOT NULL,
  PRIMARY KEY (`categoria`)
)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `porfolio`.`skills`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `skills`;
CREATE TABLE IF NOT EXISTS `porfolio`.`skills` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `habilidades` VARCHAR(256) NULL,
  `visible` TINYINT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `usuarios_id` INT NOT NULL,
  `categorias_skills_categoria` VARCHAR(32) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_skills_usuarios1_idx` (`usuarios_id` ASC),
  INDEX `fk_skills_categorias_skills1_idx` (`categorias_skills_categoria` ASC),
  CONSTRAINT `fk_skills_usuarios1`
    FOREIGN KEY (`usuarios_id`)
    REFERENCES `usuarios` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_skills_categorias_skills1`
    FOREIGN KEY (`categorias_skills_categoria`)
    REFERENCES `categorias_skills` (`categoria`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
