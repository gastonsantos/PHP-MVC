CREATE DATABASE IF NOT EXISTS GauchoRocket;

USE GauchoRocket;


CREATE TABLE IF NOT EXISTS rol (
id INT AUTO_INCREMENT PRIMARY KEY,
nombre VARCHAR(250) NOT NULL
);

CREATE TABLE IF NOT EXISTS centro_medico (
id INT  AUTO_INCREMENT PRIMARY KEY,
nombre VARCHAR(250) NOT NULL,
turnos_diarios SMALLINT NOT NULL
);

CREATE TABLE IF NOT EXISTS usuario (
id INT  AUTO_INCREMENT PRIMARY KEY,
nombre VARCHAR(250) NOT NULL,
apellido VARCHAR(250) NOT NULL,
direccion VARCHAR(300) NOT NULL,
email VARCHAR(150) NOT NULL,
contrasenia VARCHAR(500) NOT NULL,
activo BOOLEAN,
id_rol INT  NOT NULL,
FOREIGN KEY (id_rol) REFERENCES rol(id)
);

alter table chequeo modify column codigo int not null;
CREATE TABLE IF NOT EXISTS chequeo (
id INT  AUTO_INCREMENT PRIMARY KEY,
codigo TINYINT NOT NULL,
fecha date,
id_centro_medico INT  NOT NULL,
id_usuario INT  NOT NULL,
FOREIGN KEY (id_usuario) references usuario (id),
FOREIGN KEY (id_centro_medico) REFERENCES centro_medico(id)
);




CREATE TABLE lugares (
id INT  AUTO_INCREMENT PRIMARY KEY,
nombre VARCHAR(250) NOT NULL
);

CREATE TABLE IF NOT EXISTS tipo_equipo (
id INT  AUTO_INCREMENT PRIMARY KEY,
nombre VARCHAR(200) NOT NULL
);

CREATE TABLE IF NOT EXISTS tipo_cabina (
id INT  AUTO_INCREMENT PRIMARY KEY,
nombre VARCHAR(200) NOT NULL,
recargo DOUBLE NOT NULL
);


CREATE TABLE IF NOT EXISTS tipo_servicio (
id INT  AUTO_INCREMENT PRIMARY KEY,
nombre VARCHAR(200) NOT NULL,
recargo DOUBLE NOT NULL
);

CREATE TABLE IF NOT EXISTS tipo_viaje (
id INT  AUTO_INCREMENT PRIMARY KEY,
nombre VARCHAR(200) NOT NULL
);


CREATE TABLE recorrido (
  id int NOT NULL primary key,
  parada varchar(120) NOT NULL,
  transito varchar(120)  NOT NULL,
  id_tipo_equipo int NOT NULL,
  id_tipo_viaje int NOT NULL, 
  FOREIGN KEY (id_tipo_equipo) REFERENCES tipo_equipo(id),
  FOREIGN KEY (id_tipo_viaje) REFERENCES tipo_viaje(id)
);



CREATE TABLE vuelo (
id INT  AUTO_INCREMENT PRIMARY KEY,
capacidad INT NOT NULL, 
fecha_partida DATE,
hora TIME NOT NULL,
lugar_partida VARCHAR (100), 
destino varchar(250) NOT NULL,
precio DOUBLE NOT NULL, 
activo boolean NOT NULL,
id_tipo_equipo INT  NOT NULL,
id_tipo_viaje INT  NOT NULL,
id_tipo_cabina INT  NOT NULL,
FOREIGN KEY (id_tipo_equipo) REFERENCES tipo_equipo(id),
FOREIGN KEY (id_tipo_viaje) REFERENCES tipo_equipo(id),
FOREIGN KEY (id_tipo_cabina) REFERENCES tipo_cabina(id)
);
use GauchoRocket;
select * from reserva;
SELECT * FROM reserva WHERE id_usuario = 3 and confirmada = 0 and pagado = 0;
select * from usuario;
SELECT pagado from reserva where id = 26;

alter table reserva add column pagado boolean not null;
CREATE TABLE IF NOT EXISTS reserva (
id INT  AUTO_INCREMENT PRIMARY KEY,
codigo VARCHAR(500) NOT NULL,
precio INT  NOT NULL,
fecha varchar(40) NOT NULL,
confirmada boolean not null,
pagado boolean not null,
id_vuelo INT  NOT NULL,
id_cabina INT  NOT NULL,
id_servicio INT  NOT NULL,
id_usuario INT  NOT NULL,
FOREIGN KEY (id_vuelo) REFERENCES vuelo(id),
FOREIGN KEY (id_cabina) REFERENCES tipo_cabina(id),
FOREIGN KEY (id_servicio) REFERENCES tipo_servicio(id),
FOREIGN KEY (id_usuario) REFERENCES usuario(id)
);





INSERT INTO rol(id, nombre)
VALUES 
(1, "ADMIN"), 
(2, "CLIENTE");

INSERT INTO tipo_equipo (id, nombre)
VALUES (1, "ORBITAL"), (2, "BAJA ACELERACION"), (3, "ALTA ACELERACION");

INSERT INTO tipo_cabina (id, nombre, recargo)
VALUES (1, "TURISTA", 1000.0), (2, "EJECUTIVA", 2500.0), (3, "PRIMERA", 5000.0);

INSERT INTO tipo_servicio (id, nombre, recargo)
VALUES (1, "STANDARD", 1000.0), (2, "GOURMET", 15000.0), (3, "SPA", 30000.0);



INSERT INTO tipo_viaje (id, nombre) VALUES
(1, 'Suborbital'),
(2, 'Circuito1'),
(3, 'Circuito2'),
(4, 'Tour');
insert into centro_medico (nombre, turnos_diarios) values 
("Buenos Aires", 2), 
("Shangai", 1), 
("Ankara", 0);

INSERT INTO lugares (id, nombre) VALUES
(1, 'Ankara'),
(2, 'Buenos Aires'),
(3, 'EEI'),
(4, 'Orbital Hotel'),
(5, 'Luna'),
(6, 'Marte'),
(7, 'Ganimedes'),
(8, 'Europa'),
(9, 'Io'),
(10, 'Encedalo'),
(11, 'Titan');



INSERT INTO recorrido (id, parada, transito, id_tipo_equipo, id_tipo_viaje) VALUES
(1, 'EEI,Orbital Hotel,Luna,Marte', '4,1,16,26', 2, 2),
(2, 'EEI,Orbital Hotel,Luna,Marte', '3,1,9,22', 3, 2),
(3, 'EEI,Luna,Marte,Ganimedes,Europa,Io,Encedalo,Titan', '4,14,26,48,50,51,70,77', 2, 3),
(4, 'EEI,Luna,Marte,Ganimedes,Europa,Io,Encedalo,Titan','3,10,22,32,33,35,50,52','3','3');





INSERT INTO vuelo( activo,  capacidad, fecha_partida, hora, lugar_partida, destino, precio, id_tipo_equipo, id_tipo_viaje, id_tipo_cabina)
VALUES
( true, 500, "2022-08-05", "5:00:00", "Ankara", "Luna", 50000.0, 3, 3, 3),
(true ,20, "2022-07-06", "12:00:00", "Buenos Aires", "Jupiter", 180000.0, 3, 3, 3);

SELECT  *,DATE_FORMAT(fecha_partida, '%d-%m-%Y') fecha_partida,DATE_FORMAT(hora, '%H:%i') hora FROM vuelo join tipo_viaje on vuelo.id_tipo_viaje = tipo_viaje.id where lugar_partida = 'Ankara' and destino = 'Luna' and fecha_partida = '2022-08-05';

SELECT v.id, v.lugar_partida,v.destino,v.precio,tipo_viaje.nombre,r.parada, DATE_FORMAT(fecha_partida, '%d-%m-%Y') fecha_partida,DATE_FORMAT(hora, '%H:%i') hora FROM vuelo as v JOIN tipo_viaje ON v.id_tipo_viaje = tipo_viaje.id LEFT JOIN recorrido r ON v.id_tipo_equipo = r.id_tipo_equipo AND v.id_tipo_viaje = r.id_tipo_viaje AND v.activo = true;


SELECT v.fecha_partida as fecha from vuelo v join reserva r on r.id_vuelo = v.id where r.id = 18 and CURDATE() = v.fecha_partida;
SELECT  *,DATE_FORMAT(fecha_partida, '%d-%m-%Y') fecha_partida,DATE_FORMAT(hora, '%H:%i') hora FROM vuelo join tipo_viaje on vuelo.id_tipo_viaje = tipo_viaje.id where lugar_partida = 'Ankara' and destino = 'Luna' and fecha_partida = '23-06-2023';







select r.codigo, r.precio, r.fecha as Fecha_reserva, v.fecha_partida as fecha_partida,v.hora as hora, c.nombre as cabina, s.nombre as servicio
from reserva r join vuelo v on r.id_vuelo = v.id 
				join tipo_cabina c on r.id_cabina = c.id
                join tipo_servicio s on r.id_servicio = s.id where r.id = 1;
                
                SELECT r.precio from reserva r where r.id = 1;
	select * from vuelo;
    
    SELECT v.id, v.lugar_partida,v.destino,v.precio,v.id_tipo_equipo,v.id_tipo_viaje,tipo_equipo.nombre,recorrido.parada, DATE_FORMAT(fecha_partida, '%a. %d %M. %Y') fecha_partida,DATE_FORMAT(hora, '%H:%i') hora FROM vuelo as v JOIN tipo_equipo ON v.id_tipo_equipo = tipo_equipo.id LEFT JOIN recorrido ON v.id_tipo_equipo = recorrido.id_tipo_equipo AND v.id_tipo_viaje = recorrido.id_tipo_viaje AND v.activo = true;

Select tc.nombre as Cabina, count(r.id_cabina) as Cantidad from reserva r
join  tipo_cabina tc on r.id_cabina = tc.id group by tc.nombre order by tc.nombre asc; 

Select tc.nombre as Cabina, count(r.id_cabina) as Cantidad from reserva r
join  tipo_cabina tc on r.id_cabina = tc.id; 


SELECT date_format(v.fecha_partida, '%d-%m-%Y')as fecha_partida from vuelo v join reserva r on r.id_vuelo = v.id where r.id = 11;


SELECT te.nombre as nombre FROM tipo_equipo te join vuelo v on te.id = v.id_tipo_equipo WHERE v.id = 1;
SELECT te.id FROM tipo_equipo te join vuelo v on te.id = v.id_tipo_equipo WHERE v.id = 5;
SELECT STR_TO_DATE('01-01-2018', '%d-%m-%Y');


SELECT monthname(STR_TO_DATE(fecha, '%Y-%m-%d')) as MES, sum(precio) as DINERO from reserva group by MONTH(STR_TO_DATE(fecha, '%Y-%m-%d')) order by MONTH(STR_TO_DATE(fecha, '%Y-%m-%d'));


SELECT v.id, v.lugar_partida,v.destino,v.precio,tipo_viaje.nombre,recorrido.parada, DATE_FORMAT(fecha_partida, '%d-%m-%Y') fecha_partida,DATE_FORMAT(hora, '%H:%i') hora FROM vuelo as v JOIN tipo_viaje ON v.id_tipo_viaje = tipo_viaje.id LEFT JOIN recorrido ON v.id_tipo_equipo = recorrido.id_tipo_equipo AND v.id_tipo_viaje = recorrido.id_tipo_viaje AND v.activo = true;

SELECT v.id, v.lugar_partida,v.destino, te.nombre  as tipo_viaje ,v.precio,tipo_viaje.nombre,recorrido.parada as parada, DATE_FORMAT(fecha_partida, '%d-%m-%Y') fecha_partida,DATE_FORMAT(hora, '%H:%i') hora FROM vuelo as v JOIN tipo_viaje ON v.id_tipo_viaje = tipo_viaje.id 
join tipo_equipo te on v.id_tipo_equipo = te.id LEFT JOIN recorrido ON v.id_tipo_equipo = recorrido.id_tipo_equipo AND v.id_tipo_viaje = recorrido.id_tipo_viaje AND v.activo = true;

 
 SELECT v.id, v.capacidad, v.fecha_partida, v.hora, v.lugar_partida, v.destino, v.precio, te.nombre as equipo, v.id_tipo_viaje, v.id_tipo_cabina 
        from vuelo v join tipo_equipo te on v.id_tipo_equipo = te.id;

SELECT v.id, v.capacidad, v.fecha_partida, v.hora, v.lugar_partida, v.destino, v.precio, te.nombre as equipo, v.id_tipo_viaje, v.id_tipo_cabina 
        from vuelo v join tipo_equipo te on v.id_tipo_equipo = te.id;
        
        SELECT  *,DATE_FORMAT(fecha_partida, '%d-%m-%Y') fecha_partida,DATE_FORMAT(hora, '%H:%i') hora FROM vuelo v
       join  tipo_viaje tv on  v.id_tipo_viaje = tv.id where v.lugar_partida = 'Ankara' and v.destino = 'Luna' and v.fecha_partida = '2021-01-01';
       
      
       
       SELECT  *,DATE_FORMAT(fecha_partida, '%d-%m-%Y') fecha_partida,DATE_FORMAT(hora, '%H:%i') hora 
       FROM vuelo join tipo_viaje on vuelo.id_tipo_viaje = tipo_viaje.id where lugar_partida = 'Ankara' and destino = 'Luna' and fecha_partida = '2021-01-01';
       
       SELECT v.id, v.lugar_partida,v.destino,v.precio,tipo_viaje.nombre,recorrido.parada, DATE_FORMAT(fecha_partida, '%d-%m-%Y') fecha_partida,DATE_FORMAT(hora, '%H:%i') hora FROM vuelo as v JOIN tipo_viaje ON v.id_tipo_viaje = tipo_viaje.id LEFT JOIN recorrido ON v.id_tipo_equipo = recorrido.id_tipo_equipo AND v.id_tipo_viaje = recorrido.id_tipo_viaje;