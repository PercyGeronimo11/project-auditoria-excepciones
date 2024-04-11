
DROP DATABASE IF EXISTS bd_propio_ventas;
CREATE DATABASE if NOT EXISTS bd_propio_ventas;
USE bd_propio_ventas;

-- Crear la tabla categorias
CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255)
);
-- Crear la tabla clientes
CREATE TABLE clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255),
    email VARCHAR(255)
);
-- Crear la tabla productos
CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255),
    precio DECIMAL(10, 2),
    categoria_id INT,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id)
);

-- Crear la tabla ventas
CREATE TABLE ventas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    producto_id INT,
    cantidad INT,
    total DECIMAL(10, 2),
    fecha_venta DATE,
    FOREIGN KEY (producto_id) REFERENCES productos(id)
);

CREATE TABLE detalle_venta (
    id INT AUTO_INCREMENT PRIMARY KEY,
    venta_id INT,
    producto_id INT,
    cantidad INT,
    precio_unitario DECIMAL(10, 2),
    total DECIMAL(10, 2),
    FOREIGN KEY (venta_id) REFERENCES ventas(id),
    FOREIGN KEY (producto_id) REFERENCES productos(id)
);

-- Crear la tabla boleta
CREATE TABLE boleta (
    id INT AUTO_INCREMENT PRIMARY KEY,
    venta_id INT,
    cliente_id INT,
    fecha_boleta DATE,
    FOREIGN KEY (venta_id) REFERENCES ventas(id),
    FOREIGN KEY (cliente_id) REFERENCES clientes(id)
);


-- Insertar datos en la tabla categorias
INSERT INTO categorias (nombre) VALUES
('Electrónica'),
('Ropa'),
('Hogar'),
('Alimentos'),
('Libros'),
('Deportes'),
('Juguetes'),
('Salud y belleza'),
('Mascotas'),
('Herramientas');

-- Insertar datos en la tabla clientes
INSERT INTO clientes (nombre, email) VALUES
('Juan Pérez', 'juan@example.com'),
('María García', 'maria@example.com'),
('Carlos López', 'carlos@example.com'),
('Laura Martínez', 'laura@example.com'),
('Pedro Rodríguez', 'pedro@example.com'),
('Ana Sánchez', 'ana@example.com'),
('Luis González', 'luis@example.com'),
('Elena Díaz', 'elena@example.com'),
('José Ramírez', 'jose@example.com'),
('Sofía Fernández', 'sofia@example.com');

-- Insertar datos en la tabla productos
INSERT INTO productos (nombre, precio, categoria_id)
SELECT 
    CONCAT('Producto ', FLOOR(RAND() * 1000)),  -- Genera nombres de productos aleatorios
    ROUND(10 + (RAND() * 90), 2),               -- Genera precios aleatorios entre 10 y 100
    FLOOR(1 + RAND() * 10)                      -- Asigna una categoría aleatoria del 1 al 10
FROM
    information_schema.tables
LIMIT 100;


-- Insertar datos en la tabla ventas
INSERT INTO ventas (producto_id, cantidad, total, fecha_venta)
SELECT 
    (SELECT id FROM productos ORDER BY RAND() LIMIT 1),  -- Selecciona un producto aleatorio
    FLOOR(1 + RAND() * 10),                             -- Genera cantidades aleatorias entre 1 y 10
    ROUND((SELECT precio FROM productos ORDER BY RAND() LIMIT 1) * (1 + RAND() * 5), 2), -- Calcula un total aleatorio
    DATE_SUB(NOW(), INTERVAL FLOOR(RAND() * 30) DAY)     -- Fecha de venta aleatoria en los últimos 30 días
FROM
    information_schema.tables
LIMIT 70;



INSERT INTO detalle_venta (venta_id, producto_id, cantidad, precio_unitario, total)
SELECT 
    v.id,                               -- ID de la venta
    p.id,                               -- ID del producto
    FLOOR(1 + RAND() * 10),             -- Genera cantidades aleatorias entre 1 y 10
    p.precio,                           -- Precio unitario del producto
    ROUND(p.precio * (1 + RAND()), 2)  -- Calcula un total aleatorio
FROM
    ventas v
JOIN
    productos p ON v.producto_id = p.id
LIMIT 30;


-- Insertar datos en la tabla boleta
INSERT INTO boleta (venta_id, cliente_id, fecha_boleta)
SELECT 
    v.id,                           -- ID de la venta
    (SELECT id FROM clientes ORDER BY RAND() LIMIT 1),  -- Selecciona un cliente aleatorio
    fecha_venta                     -- Utiliza la misma fecha de la venta
FROM
    ventas v
LIMIT 100;
