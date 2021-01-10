CREATE DATABASE inventario;

CREATE USER 'smithj'@'%' IDENTIFIED BY 'pwd4smithj';

GRANT ALL PRIVILEGES ON inventario.* TO 'smithj'@'%';

USE inventario;

CREATE TABLE control_files
( xml varchar(250) NOT NULL,
  registerDate datetime NOT NULL,
  PRIMARY KEY (xml)
);
