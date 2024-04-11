-- ################  CLAVES PRIMARIAS   ####################
-- Eliminar la restriccion de clave primaria
ALTER TABLE ventas 
	DROP PRIMARY KEY;
	
-- crear la restriccion de clave primaria
ALTER TABLE ventas 
	ADD PRIMARY KEY (id);
	
-- Modificar la restriccion de clave primariaventas
ALTER TABLE ventas 
	MODIFY COLUMN id INT PRIMARY KEY;
	
-- Desactivamos el AUTO_INCREMENT
ALTER TABLE ventas 
	MODIFY COLUMN id AUTO_INCREMENT;
	

-- #################### CLAVE FORANEA  ###################3
-- elimina la restrinccion de fk
ALTER table boleta
	drop foreign key boleta_ibfk_1
	
-- Desactivar clave externa de modo general -> esto es temporal
	SET FOREIGN_KEY_CHECKS=0;
	
-- Desactivar clave externa de una tabla especifika -> esto es temporal
ALTER TABLE detalle_venta DISABLE KEYS 


-- ################ INSERCIONES  ##################
INSERT INTO detalle_venta VALUE(32,150,20,40,53,189)
	
-- Ver detalles :
SHOW CREATE TABLE detalle_venta;
SHOW CREATE TABLE boleta;