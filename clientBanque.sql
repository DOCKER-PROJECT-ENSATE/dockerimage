create database IF NOT exists clientBanque;
USE clientBanque;

create table IF NOT exists client(
idClient int primary key not null AUTO_INCREMENT,
nom varchar(50) not null,
prenom varchar(50) not null,
solde float not null
);

insert into client(nom,prenom,solde) values
("Heriniavo", "Faly", 12000),
("Haingotiana", "Francia", 10000);