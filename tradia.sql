-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-05-2026 a las 16:16:27
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
-- Base de datos: `tradia`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `descuentos`
--

CREATE TABLE `descuentos` (
  `id_descuento` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `porcentaje_descuento` decimal(5,2) NOT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `imagenesproducto`
--

CREATE TABLE `imagenesproducto` (
  `id_imagen` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `ruta_archivo` varchar(255) NOT NULL,
  `es_principal` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL,
  `ruta_archivo` varchar(255) NOT NULL,
  `nombre_producto` varchar(255) NOT NULL,
  `marca` varchar(100) NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `utilidad` varchar(100) NOT NULL,
  `presentacion` varchar(100) DEFAULT NULL,
  `tamano` varchar(50) DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `forma` varchar(50) DEFAULT NULL,
  `cantidad_stock` int(11) NOT NULL DEFAULT 0,
  `precio_unitario` decimal(10,2) NOT NULL,
  `fecha_ingreso` date NOT NULL,
  `fecha_elaboracion` date DEFAULT NULL,
  `fecha_caducidad` date DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `usuario_registro` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `ruta_archivo`, `nombre_producto`, `marca`, `tipo`, `utilidad`, `presentacion`, `tamano`, `color`, `forma`, `cantidad_stock`, `precio_unitario`, `fecha_ingreso`, `fecha_elaboracion`, `fecha_caducidad`, `descripcion`, `usuario_registro`) VALUES
(8, '../uploads/Usados/6a01d990163ca_Tablas.jpg', 'Tablones de madera', 'Tupper', 'ferreteria', '10', '', '', '', '', 4980, 150.00, '2026-05-11', NULL, NULL, 'Tablas de madera', NULL),
(9, '../uploads/Usados/6a01e2fbc795d_TelasedaR.jpg', 'Tela de Seda Rosa', 'Parisina', 'textiles', '20', '1 piezas', '30 metros', 'Rosa', 'Cilindrica', 9954, 10000.00, '2026-05-11', '2026-05-01', NULL, 'Tela de seda Rosa si', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `ID` int(20) NOT NULL,
  `Nombre` varchar(20) NOT NULL,
  `Apellido` varchar(20) NOT NULL,
  `Correo` varchar(50) NOT NULL,
  `Telefono` int(20) NOT NULL,
  `Password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`ID`, `Nombre`, `Apellido`, `Correo`, `Telefono`, `Password`) VALUES
(6, 'Luis Fernando', 'Colin', 'hu471777@uaeh.edu.mx', 2147483647, '$2y$10$hHmudqJqswxNKPejVPLpneiYpQxPA/w65uR./ckEKYlepHlfhpefG'),
(7, 'ysjdb', 'qwertyuiop', 'gu424903@uaeh.edu.mx', 2147483647, '$2y$10$ao7IErhokliN8rMLS8HnTObTS3GSv8JVeMpUcoM/pivFtDNdBSmwK'),
(8, 'Jose', 'Ramirez', 'ra8955874@uaeh.edu.mx', 2147483647, '$2y$10$9F8j8lFViAi8CICFfZpnDuNyRyIN7IAv6o1X6aZX51awKmZ6uDKLW'),
(9, 'William ', 'Levy', 'WilliamLevy1@outlook.com', 2147483647, '$2y$10$fE4MqjohED0jqywtTUCo3.6KmAV8qaDydmdG6ZgmE/QbR2tgbkplS');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `descuentos`
--
ALTER TABLE `descuentos`
  ADD PRIMARY KEY (`id_descuento`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `imagenesproducto`
--
ALTER TABLE `imagenesproducto`
  ADD PRIMARY KEY (`id_imagen`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `descuentos`
--
ALTER TABLE `descuentos`
  MODIFY `id_descuento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `imagenesproducto`
--
ALTER TABLE `imagenesproducto`
  MODIFY `id_imagen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `ID` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `descuentos`
--
ALTER TABLE `descuentos`
  ADD CONSTRAINT `descuentos_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `imagenesproducto`
--
ALTER TABLE `imagenesproducto`
  ADD CONSTRAINT `imagenesproducto_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
