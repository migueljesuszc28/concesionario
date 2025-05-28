-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-05-2025 a las 04:42:24
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `concesionario`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `cliente_id` int(11) NOT NULL,
  `tipo_cliente` enum('Persona','Empresa') NOT NULL DEFAULT 'Persona',
  `nombre` varchar(50) NOT NULL,
  `apellidos` varchar(50) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `direccion` varchar(200) DEFAULT NULL,
  `fecha_registro` date DEFAULT curdate(),
  `estado` tinyint(1) DEFAULT 1,
  `licencia_conducir` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`cliente_id`, `tipo_cliente`, `nombre`, `apellidos`, `email`, `telefono`, `direccion`, `fecha_registro`, `estado`, `licencia_conducir`) VALUES
(3, 'Persona', 'Jorge', 'Blanquicett', 'Jorge@gmail.com', '3562498516', 'N/A', '2025-05-19', 1, 'si'),
(4, 'Persona', 'Jhasazaray ', 'Lopez', 'Jhasa@gmail.com', '3260154926', 'Barrio el Country', '2025-05-27', 1, 'si'),
(5, 'Persona', 'Miguel Jesús', 'Zuñiga Coneo', 'miguel@gmail.com', '3016542987', 'Brisas de Galicia', '2025-05-27', 1, 'si');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleado`
--

CREATE TABLE `empleado` (
  `empleado_id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellidos` varchar(50) NOT NULL,
  `puesto` varchar(50) NOT NULL DEFAULT 'Vendedor',
  `email` varchar(100) NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `fecha_contratacion` date NOT NULL,
  `salario` decimal(10,2) NOT NULL DEFAULT 0.00,
  `departamento` varchar(50) NOT NULL DEFAULT 'Ventas',
  `estado` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empleado`
--

INSERT INTO `empleado` (`empleado_id`, `nombre`, `apellidos`, `puesto`, `email`, `telefono`, `fecha_contratacion`, `salario`, `departamento`, `estado`) VALUES
(1, 'Andres', 'Molina', 'Vendedor', 'Andres@gmail.com', '3165429870', '2025-05-27', 2000000.00, 'Ventas', 1),
(2, 'Carolina', 'Consuegra', 'Vendedor', 'Carolina@gmail.com', '3235498526', '2025-05-14', 2000000.00, 'Ventas', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `financiamiento`
--

CREATE TABLE `financiamiento` (
  `financiamiento_id` int(11) NOT NULL,
  `venta_id` int(11) NOT NULL,
  `monto_financiado` decimal(12,2) NOT NULL,
  `plazo_meses` int(11) NOT NULL,
  `tasa_interes` decimal(5,2) NOT NULL,
  `fecha_aprobacion` date NOT NULL,
  `institucion_financiera` varchar(100) NOT NULL,
  `estado` enum('Aprobado','Rechazado','En proceso') NOT NULL DEFAULT 'En proceso'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `financiamiento`
--

INSERT INTO `financiamiento` (`financiamiento_id`, `venta_id`, `monto_financiado`, `plazo_meses`, `tasa_interes`, `fecha_aprobacion`, `institucion_financiera`, `estado`) VALUES
(6, 11, 500000000.00, 12, 8.50, '2025-05-27', 'Banco De Bogota', 'En proceso'),
(7, 14, 800000000.00, 12, 8.50, '2025-05-27', 'BanColombia', 'Aprobado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marca`
--

CREATE TABLE `marca` (
  `marca_id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `pais_origen` varchar(50) DEFAULT NULL,
  `anio_fundacion` int(11) DEFAULT NULL,
  `sitio_web` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `marca`
--

INSERT INTO `marca` (`marca_id`, `nombre`, `pais_origen`, `anio_fundacion`, `sitio_web`) VALUES
(1, 'Toyota', 'Japón', 1937, 'https://www.toyota.com'),
(2, 'Volkswagen', 'Alemania', 1937, 'https://www.vw.com'),
(3, 'Ford', 'Estados Unidos', 1903, 'https://www.ford.com'),
(4, 'Honda', 'Japón', 1948, 'https://www.honda.com'),
(5, 'BMW', 'Alemania', 1916, 'https://www.bmw.com'),
(6, 'Mercedes-Benz', 'Alemania', 1926, 'https://www.mercedes-benz.com'),
(7, 'Chevrolet', 'Estados Unidos', 1911, 'https://www.chevrolet.com'),
(8, 'Nissan', 'Japón', 1933, 'https://www.nissan.com'),
(9, 'Hyundai', 'Corea del Sur', 1967, 'https://www.hyundai.com'),
(10, 'Kia', 'Corea del Sur', 1944, 'https://www.kia.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculo`
--

CREATE TABLE `vehiculo` (
  `vehiculo_id` int(11) NOT NULL,
  `vin` varchar(17) NOT NULL,
  `marca_id` int(11) NOT NULL,
  `modelo` varchar(50) NOT NULL,
  `anio` int(11) NOT NULL,
  `color` varchar(30) NOT NULL,
  `transmision` enum('Automática','Manual') NOT NULL DEFAULT 'Automática',
  `tipo_combustible` enum('Gasolina','Diésel','Híbrido','Eléctrico') NOT NULL DEFAULT 'Gasolina',
  `kilometraje` decimal(10,2) DEFAULT 0.00,
  `precio` decimal(12,2) NOT NULL,
  `fecha_adquisicion` date NOT NULL,
  `estado` enum('Nuevo','Usado','Certificado') NOT NULL DEFAULT 'Nuevo',
  `stock` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `vehiculo`
--

INSERT INTO `vehiculo` (`vehiculo_id`, `vin`, `marca_id`, `modelo`, `anio`, `color`, `transmision`, `tipo_combustible`, `kilometraje`, `precio`, `fecha_adquisicion`, `estado`, `stock`) VALUES
(1, '1HGCM82633A004352', 1, 'Corolla', 2025, 'Azul', 'Automática', 'Gasolina', 0.00, 500000000.00, '2025-05-20', 'Nuevo', 0),
(2, 'WVWZZZ1JZXW000123', 3, 'Mustang', 2025, 'Amarillo', 'Automática', 'Gasolina', 0.00, 800000000.00, '2025-05-20', 'Nuevo', 2),
(5, '3', 4, 'Civic', 2025, 'Plata', 'Automática', 'Eléctrico', 0.00, 50000000.00, '0000-00-00', 'Nuevo', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta`
--

CREATE TABLE `venta` (
  `venta_id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `empleado_id` int(11) NOT NULL,
  `vehiculo_id` int(11) NOT NULL,
  `fecha_venta` datetime DEFAULT current_timestamp(),
  `precio_final` decimal(12,2) NOT NULL,
  `metodo_pago` enum('Efectivo','Tarjeta','Financiamiento') NOT NULL DEFAULT 'Efectivo',
  `estado` enum('Completado','Cancelado','Pendiente') DEFAULT 'Completado',
  `descuento` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `venta`
--

INSERT INTO `venta` (`venta_id`, `cliente_id`, `empleado_id`, `vehiculo_id`, `fecha_venta`, `precio_final`, `metodo_pago`, `estado`, `descuento`) VALUES
(11, 4, 2, 1, '2025-05-27 21:36:37', 500000000.00, 'Financiamiento', 'Completado', 0.00),
(12, 3, 1, 2, '2025-05-27 21:37:43', 700000000.00, 'Tarjeta', 'Completado', 99999999.99),
(13, 5, 2, 5, '2025-05-27 21:38:16', 40000000.00, 'Efectivo', 'Completado', 10000000.00),
(14, 5, 1, 2, '2025-05-27 21:41:16', 800000000.00, 'Financiamiento', 'Completado', 0.00);

--
-- Disparadores `venta`
--
DELIMITER $$
CREATE TRIGGER `disminuir_stock_vehiculo` AFTER INSERT ON `venta` FOR EACH ROW BEGIN
UPDATE vehiculo
SET stock = stock - 1
WHERE vehiculo_id = NEW.vehiculo_id;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `validar_stock_antes_venta` BEFORE INSERT ON `venta` FOR EACH ROW BEGIN
DECLARE cantidad_stock INT;
SELECT stock INTO cantidad_stock FROM vehiculo WHERE vehiculo_id = NEW.vehiculo_id;  IF cantidad_stock <= 0 THEN
SIGNAL SQLSTATE '45000'
SET MESSAGE_TEXT = 'No hay stock disponible para este vehículo';
END IF;
END
$$
DELIMITER ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`cliente_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD PRIMARY KEY (`empleado_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `financiamiento`
--
ALTER TABLE `financiamiento`
  ADD PRIMARY KEY (`financiamiento_id`),
  ADD KEY `venta_id` (`venta_id`);

--
-- Indices de la tabla `marca`
--
ALTER TABLE `marca`
  ADD PRIMARY KEY (`marca_id`);

--
-- Indices de la tabla `vehiculo`
--
ALTER TABLE `vehiculo`
  ADD PRIMARY KEY (`vehiculo_id`),
  ADD UNIQUE KEY `vin` (`vin`),
  ADD KEY `marca_id` (`marca_id`);

--
-- Indices de la tabla `venta`
--
ALTER TABLE `venta`
  ADD PRIMARY KEY (`venta_id`),
  ADD KEY `cliente_id` (`cliente_id`),
  ADD KEY `empleado_id` (`empleado_id`),
  ADD KEY `vehiculo_id` (`vehiculo_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `cliente_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `empleado`
--
ALTER TABLE `empleado`
  MODIFY `empleado_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `financiamiento`
--
ALTER TABLE `financiamiento`
  MODIFY `financiamiento_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `marca`
--
ALTER TABLE `marca`
  MODIFY `marca_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `vehiculo`
--
ALTER TABLE `vehiculo`
  MODIFY `vehiculo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `venta`
--
ALTER TABLE `venta`
  MODIFY `venta_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `financiamiento`
--
ALTER TABLE `financiamiento`
  ADD CONSTRAINT `financiamiento_ibfk_1` FOREIGN KEY (`venta_id`) REFERENCES `venta` (`venta_id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `vehiculo`
--
ALTER TABLE `vehiculo`
  ADD CONSTRAINT `vehiculo_ibfk_1` FOREIGN KEY (`marca_id`) REFERENCES `marca` (`marca_id`);

--
-- Filtros para la tabla `venta`
--
ALTER TABLE `venta`
  ADD CONSTRAINT `venta_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`cliente_id`),
  ADD CONSTRAINT `venta_ibfk_2` FOREIGN KEY (`empleado_id`) REFERENCES `empleado` (`empleado_id`),
  ADD CONSTRAINT `venta_ibfk_3` FOREIGN KEY (`vehiculo_id`) REFERENCES `vehiculo` (`vehiculo_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
