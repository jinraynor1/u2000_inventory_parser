CREATE DATABASE inventario;

CREATE USER 'smithj'@'%' IDENTIFIED BY 'pwd4smithj';

GRANT ALL PRIVILEGES ON inventario.* TO 'smithj'@'%';

USE inventario;

CREATE TABLE customers
( customer_id varchar(10) NOT NULL,
  customer_name varchar(50) NOT NULL,
  city varchar(50)
);
