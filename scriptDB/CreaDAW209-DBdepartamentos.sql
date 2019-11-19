/*
*autor: ismael heras
*fecha: 4/11/2019
*/


--creamos la base de datos 
create database IF NOT EXISTS DAW209DBdepartamentos2;

--la usamos
use DAW209DBdepartamentos2;

--creamos una tabla Departamentos si no existe, con los campos CodDepartamento(como clave privada) y descDepartamento.
create table IF NOT EXISTS Departamento(
CodDepartamento varchar(4),
DescDepartamento varchar(255) default null,
FechaBaja date default null,
VolumenNegocio float default null,
primary key (CodDepartamento))engine=innodb;

--creamos un usuario y una contraseña
CREATE USER 'usuarioDBdepartamentos2'@'%' IDENTIFIED BY 'paso';

--le damos privilegios sobre la base de datos que ceamos y a la tabla.
GRANT ALL PRIVILEGES ON DAW209DBdepartamentos2 . Departamento TO 'usuarioDBdepartamentos2'@'%';

--recargamos los privilegios
FLUSH PRIVILEGES;

--mostramos las tablas de la bas e datos que  hemos creado.
show tables;
--mostramos los campos de las tablas
describe Departamento;