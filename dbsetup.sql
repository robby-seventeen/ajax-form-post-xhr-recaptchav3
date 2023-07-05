CREATE DATABASE test;

CREATE TABLE `test`.`test` (`id` INT NOT NULL AUTO_INCREMENT , `email` VARCHAR(200) NOT NULL , PRIMARY KEY (`id`)) ENGINE = MyISAM;

CREATE USER 'test'@'localhost' IDENTIFIED BY 'test1234';

GRANT SELECT, INSERT, UPDATE, DELETE ON `test`.* TO 'test'@'localhost'; ALTER USER 'test'@'localhost';