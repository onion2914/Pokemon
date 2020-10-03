# comment
-- comment
/*
comment
comment
comment
*/
--drop database if exists myapp;
create database pokemon_db;
grant all on pokemon_db.* to dbuser@localhost identified by 'poruno&&0200';