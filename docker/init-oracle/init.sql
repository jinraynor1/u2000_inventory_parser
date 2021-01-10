CREATE TABLESPACE tbs_perm_01
  DATAFILE 'tbs_perm_01.dat'
    SIZE 20M
  ONLINE;

CREATE TEMPORARY TABLESPACE tbs_temp_01
TEMPFILE 'tbs_temp_01.dbf'
SIZE 5M
AUTOEXTEND ON;


CREATE USER smithj
  IDENTIFIED BY pwd4smithj
  DEFAULT TABLESPACE tbs_perm_01
  TEMPORARY TABLESPACE tbs_temp_01
  QUOTA 20M on tbs_perm_01;

GRANT create session TO smithj;
GRANT create table TO smithj;
GRANT create view TO smithj;
GRANT create any trigger TO smithj;
GRANT create any procedure TO smithj;
GRANT create sequence TO smithj;
GRANT create synonym TO smithj;

ALTER SESSION SET CURRENT_SCHEMA = smithj;


CREATE TABLE "ControlFiles"
( "xml" varchar2(250) NOT NULL,
  "registerDate" DATE NOT NULL,
  "recordsInserted" INTEGER,
  "recordsFailed" INTEGER,
  "recordsSkipped" INTEGER,
  CONSTRAINT ControlFilesPk PRIMARY KEY ("xml")
);


CREATE TABLE "ControlFilesDetails"
( "xml" varchar2(250) NOT NULL,
  "table" varchar2(64) ,
  "recordsInserted" INTEGER,
  "recordsFailed" INTEGER,
  "recordsSkipped" INTEGER
);
