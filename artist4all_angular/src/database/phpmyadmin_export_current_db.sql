-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Servidor: artist4all_db:3306
-- Tiempo de generación: 16-04-2021 a las 12:36:01
-- Versión del servidor: 10.2.36-MariaDB-1:10.2.36+maria~bionic
-- Versión de PHP: 7.4.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `artist4alldb`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users_likes_publications`
--

CREATE TABLE `users_likes_publications` (
  `id` int(11) NOT NULL,
  `my_id` int(11) NOT NULL,
  `id_publication` int(11) NOT NULL,
  `status_like` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `users_likes_publications`
--

INSERT INTO `users_likes_publications` (`id`, `my_id`, `id_publication`, `status_like`) VALUES
(22, 1, 90, 1),
(23, 1, 74, 1),
(24, 1, 73, 0),
(25, 1, 67, 1),
(26, 1, 4, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `users_likes_publications`
--
ALTER TABLE `users_likes_publications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `my_id` (`my_id`),
  ADD KEY `id_publication` (`id_publication`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `users_likes_publications`
--
ALTER TABLE `users_likes_publications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `users_likes_publications`
--
ALTER TABLE `users_likes_publications`
  ADD CONSTRAINT `users_likes_publications_ibfk_1` FOREIGN KEY (`my_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `users_likes_publications_ibfk_2` FOREIGN KEY (`id_publication`) REFERENCES `publications` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
