-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 16-11-2022 a las 02:55:06
-- Versión del servidor: 10.4.24-MariaDB
-- Versión de PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `db_mueble`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `id_categoria` int(11) NOT NULL,
  `categoria` varchar(30) NOT NULL,
  `detalles` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`id_categoria`, `categoria`, `detalles`) VALUES
(3, 'Cocina', 'Muebles relativos a una cocina.'),
(4, 'Habitación', 'Muebles relativos a una habitación.'),
(5, 'Baño', 'Muebles relativos a un baño.'),
(6, 'Comedor', 'Muebles relativos a un comedor.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `comment` varchar(300) NOT NULL,
  `id_mueble` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `comments`
--

INSERT INTO `comments` (`id`, `comment`, `id_mueble`) VALUES
(1, 'Pellentesque quis purus lectus. Sed laoreet in urna non consequat.', 1),
(2, 'Quisque vestibulum interdum vulputate. Pellentesque dignissim, sapien eget aliquam facilisis.', 1),
(7, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec semper.', 2),
(8, 'Cras libero ante, finibus sed risus a, tristique hendrerit sapien.', 3),
(9, 'Neque porro quisquam est qui dolorem ipsum quia dolor sit amet.', 4),
(10, 'Proin id nulla a felis varius tempus sit amet ac.', 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mueble`
--

CREATE TABLE `mueble` (
  `id_mueble` int(11) NOT NULL,
  `mueble` varchar(50) NOT NULL,
  `descripcion` varchar(200) NOT NULL,
  `precio` double NOT NULL,
  `id_categoria` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `mueble`
--

INSERT INTO `mueble` (`id_mueble`, `mueble`, `descripcion`, `precio`, `id_categoria`) VALUES
(1, 'Mesa', 'Mesa para 6 sillas, con opción a expandirse para 8 sillas', 24999, 3),
(2, 'Silla algarrobo-recto', 'Silla de algarrobo con respaldo recto.', 6999, 6),
(3, 'Silla algarrobo-curvo', 'Silla de algarrobo con una curva en el respaldo.', 9999, 6),
(4, 'Bajomesadas de 3 cuerpos', 'Bajomesadas con 3 espacios, con libertad para asignar el uso del espacio', 37999, 3),
(5, 'Alacena', 'Alacena de 1,80m con 3 secciones.', 33999, 3),
(6, 'Cama de 1 plaza', 'Cama de 1,80m x 80cm, para colchón de 1 plaza.', 12999, 4),
(7, 'Cama queen-size', 'Cama de 1,90m x 1,50m, para colchón de 2 plazas formato queen-size, con 4 cajones.', 40999, 4),
(8, 'Cama king-size', 'Cama de 2m x 2m, para colchón de 2 plazas formato king-size, con 6 cajones.', 62499, 4),
(9, 'Placard [consultar precio]', 'Interiores de placard en diversas presentaciones y materiales, consultar precios por nuestras vías de contacto', 69999, 4),
(10, 'Mueble p/bacha c/estantes', 'Mueble para situar debajo de la bacha del baño (a proveer por el cliente), con flexibilidad para asignar el uso del espacio', 17999, 5),
(11, 'Estantería varias secciones', 'Estantería con variedad de secciones, ideal para almacenar elementos de consumo frecuente en un baño.', 26999, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `mail` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `user`
--

INSERT INTO `user` (`id_user`, `mail`, `password`, `role`) VALUES
(1, 'admin@admin.com', '$2a$12$4zxxBHPAWyIT4w4oiagPpON1kZXjSTzAbTKSSPp3EyIilvscxVsom', 'admin');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comment` (`comment`),
  ADD KEY `id_mueble` (`id_mueble`),
  ADD KEY `comment_2` (`comment`);

--
-- Indices de la tabla `mueble`
--
ALTER TABLE `mueble`
  ADD PRIMARY KEY (`id_mueble`),
  ADD KEY `id_categoria` (`id_categoria`);

--
-- Indices de la tabla `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `mueble`
--
ALTER TABLE `mueble`
  MODIFY `id_mueble` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`id_mueble`) REFERENCES `mueble` (`id_mueble`) ON DELETE CASCADE;

--
-- Filtros para la tabla `mueble`
--
ALTER TABLE `mueble`
  ADD CONSTRAINT `mueble_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id_categoria`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
