CREATE DATABASE userDeatils;
USE userDeatils;

CREATE TABLE `userdeatils`.`users` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `first_name` VARCHAR(255) NOT NULL,
    `last_name` VARCHAR(200) NULL,
    `email` VARCHAR(200) NOT NULL,
    `password_hash` VARCHAR(255) NOT NULL,
    `position` VARCHAR(200) NOT NULL,
    `status_id` INT NULL,
    PRIMARY KEY (`id`)
) ENGINE = MyISAM;


CREATE TABLE `userdeatils`.`status` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = MyISAM;


CREATE TABLE `userdeatils`.`master_status` (`id` INT NOT NULL AUTO_INCREMENT , `name` VARCHAR(50) NOT NULL , `color` VARCHAR(50) NOT NULL , `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = MyISAM;
