drop database manometro;

create database manometro;

use manometro;


create table if not EXISTS datos_pozo(
id_pozo int(10) unsigned not null AUTO_INCREMENT,
nombre_pozo varchar(60) not null,
presion float(10,2) not null,
fecha datetime not null,
primary key (id_pozo)
);