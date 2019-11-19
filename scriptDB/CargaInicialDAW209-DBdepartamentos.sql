/*
*autor: ismael heras
*fecha: 4/11/2019
*/
--base de datos a insertar
use DAW209DBdepartamentos2;
--hacemos un insert into a la tabla Departamentos que habiamos.
insert into Departamento  values
(

'DWES','Desarrollo WEB entorno servidor','2019-11-17','1100'),

('DWEC','Desarrollo WEB entorno cliente','2019-11-17','1200'),

('DAW','Despliege de aplicaciones WEB','2019-11-17','1200'),

('DIW','Desarrollo interfaces WEB','2019-11-17','1000'),

('EIE','Empresa e inciativa Emprendedora','2019-11-17','1500');

--mostramos todos los valores que hemos introducido.
SELECT * FROM Departamento;