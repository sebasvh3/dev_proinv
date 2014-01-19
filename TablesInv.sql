CREATE TABLE producto(
    id INT NOT NULL AUTO_INCREMENT,
    descripcion VARCHAR(256) NOT NULL,
    codigo DECIMAL(15,0) NOT NULL,
    cantidad_gr INT NOT NULL,
    estado VARCHAR(4) NOT NULL,
    id_categoria INT NULL,
    id_tercero INT NULL,
    fecha_crea DATETIME,
    fecha_mod  DATETIME,
    propietario VARCHAR(22),
    usuario   VARCHAR(22),
    
    PRIMARY KEY ( id )
);

CREATE TABLE categoria(
    id INT NOT NULL AUTO_INCREMENT,
    descripcion VARCHAR(128) NOT NULL,
    estado VARCHAR(4) NOT NULL,
    fecha_crea DATETIME,
    fecha_mod  DATETIME,
    propietario VARCHAR(22),
    usuario   VARCHAR(22),
    
    PRIMARY KEY ( id )
);

CREATE TABLE tercero(
    id INT NOT NULL AUTO_INCREMENT,
    descripcion VARCHAR(128) NOT NULL,
    estado VARCHAR(4) NOT NULL,
    fecha_crea DATETIME,
    fecha_mod  DATETIME,
    propietario VARCHAR(22),
    usuario   VARCHAR(22),
    
    PRIMARY KEY ( id )
);



CREATE TABLE bodega(
    id INT NOT NULL AUTO_INCREMENT,
    id_producto INT NOT NULL,
    existencia DECIMAL( 12, 2 ) NOT NULL DEFAULT  '0',
    estado VARCHAR(4) NOT NULL,
    fecha_crea DATETIME,
    fecha_mod  DATETIME,
    propietario VARCHAR(22),
    usuario   VARCHAR(22),
    
    PRIMARY KEY ( id )
);

ALTER TABLE `bodega` ADD INDEX ( `id_producto` )
ALTER TABLE  `bodega` CHANGE  `existencia`  `existencia` DECIMAL( 12 ) NOT NULL DEFAULT  '0'

ALTER TABLE  `bodega` 
ADD FOREIGN KEY (  `id_producto` ) 
REFERENCES  `dbinventario`.`producto` (`id`) 
ON DELETE RESTRICT ON UPDATE CASCADE ;


ALTER TABLE `producto` ADD INDEX ( `id_categoria` ) 

ALTER TABLE  `bodega` CHANGE  `existencia`  `existencia` DECIMAL( 12 ) NOT NULL DEFAULT  '0'
ALTER TABLE  `bodega` CHANGE  `existencia`  `existencia` DECIMAL( 12, 3 ) NOT NULL DEFAULT  '0'


Insertar datos;

INSERT INTO  `dbinventario`.`producto` (
`id` ,
`descripcion` ,
`codigo` ,
`cantidad_gr` ,
`estado` ,
`id_categoria` ,
`id_tercero` ,
`fecha_crea` ,
`fecha_mod` ,
`propietario` ,
`usuario`
)
VALUES (
NULL ,  'producto por 125 gr',  '1256587454125',  '125',  'ACT', NULL , NULL ,  '2013-12-11 00:00:00',  '2013-12-10 00:00:00',  'jvahos',  'jvahos'
);

INSERT INTO `dbinventario`.`producto` (`id`, `descripcion`, `codigo`, `cantidad_gr`, `estado`, `id_categoria`, `id_tercero`, `fecha_crea`, `fecha_mod`, `propietario`, `usuario`) VALUES (NULL, 'producto por 125 gr', '1256587454125', '125', 'ACT', NULL, NULL, '2013-12-11 00:00:00', '2013-12-10 00:00:00', 'jvahos', 'jvahos');

INSERT INTO `dbinventario`.`producto` (``descripcion`, `codigo`, `cantidad_gr`, `estado`, `id_categoria`, `id_tercero`, `fecha_crea`, `fecha_mod`, `propietario`, `usuario`) VALUES ( 'producto por 200 gr', '1254785698745', '200', 'ACT', NULL, NULL, '2013-13-11 00:00:00', '2013-13-10 00:00:00', 'mgutierrez', 'jvahos');

INSERT INTO `dbinventario`.`producto` (``descripcion`, `codigo`, `cantidad_gr`, `estado`, `id_categoria`, `id_tercero`, `fecha_crea`, `fecha_mod`, `propietario`, `usuario`) VALUES ( 'producto por 500 gr', '1254785678965', '500', 'ACT', NULL, NULL, '2013-13-11 00:00:00', '2013-13-10 00:00:00', 'mgutierrez', 'jvahos');


