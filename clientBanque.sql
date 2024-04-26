create database IF NOT exists clientBanque;
USE clientBanque;

create table IF NOT exists client(
idClient int primary key not null,
nom varchar(50) not null,
prenom varchar(50) not null,
solde float not null
);

-- insert into client values
-- (1, "Heriniavo", "Faly", 12000),
-- (2, "Chioua", "Hiba", 10000),
-- (3, "El bejaoui", "Nada", 10000),
-- (4, "Aya", "Aya", 10000);