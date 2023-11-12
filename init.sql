CREATE DATABASE cmc_db;
USE `cmc_db`;

CREATE TABLE `job` (
    `id` int NOT NULL auto_increment,
    `reference` varchar(255),
    `title` varchar(255),
    `description` TEXT,
    `url` varchar(255),
    `company_name` varchar(255),
    `publication` date,
    PRIMARY KEY(`id`)
);
