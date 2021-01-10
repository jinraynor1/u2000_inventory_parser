CREATE DATABASE inventario;

CREATE USER 'smithj'@'%' IDENTIFIED BY 'pwd4smithj';

GRANT ALL PRIVILEGES ON inventario.* TO 'smithj'@'%';

USE inventario;

CREATE TABLE `ControlFiles` (
    `xml` varchar(250) NOT NULL,
    `registerDate` datetime NOT NULL,
    `recordsInserted` int DEFAULT NULL,
    `recordsFailed` int DEFAULT NULL,
    `recordsSkipped` int DEFAULT NULL,
    PRIMARY KEY (`xml`)
);

CREATE TABLE `ControlFilesDetails` (
   `xml` varchar(250) NOT NULL,
   `table` varchar(64) NOT NULL,
   `recordsInserted` int DEFAULT NULL,
   `recordsFailed` int DEFAULT NULL,
   `recordsSkipped` int DEFAULT NULL
);
