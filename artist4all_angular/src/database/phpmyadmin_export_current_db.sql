-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 07-03-2021 a las 17:49:24
-- Versión del servidor: 5.7.24
-- Versión de PHP: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
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
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `name_user` varchar(50) NOT NULL,
  `surname1` varchar(50) NOT NULL,
  `surname2` varchar(50) NOT NULL,
  `email` varchar(90) NOT NULL,
  `username` varchar(50) NOT NULL,
  `passwd` varchar(120) NOT NULL,
  `type_user` int(11) NOT NULL,
  `img` varchar(300) NOT NULL,
  `token` varchar(600) NOT NULL,
  `deleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id_user`, `name_user`, `surname1`, `surname2`, `email`, `username`, `passwd`, `type_user`, `img`, `token`, `deleted`) VALUES
(19, 'usu1', 'usu1', 'usu1', 'usu1@gmail.com', 'usu1', '$2y$10$W9roMlNGzQRqPBTYw27LG.gW5cwNImpHTigLzS.u1Y85ML0DB/TQi', 1, 'http:\\\\localhost\\daw2\\Artist4all\\artist4all_php\\User\\assets\\img\\imgUnknown.png', '', 0),
(20, 'usu2', 'usu2', 'usu2', 'usu2@gmail.com', 'usu2', '$2y$10$iSwd2abDuBdK061hx6lu6.r9uJpf6m2mnDAoOh35kFgXtG7gEN6Ba', 0, 'http:\\\\localhost\\daw2\\Artist4all\\artist4all_php\\User\\assets\\img\\imgUnknown.png', '', 0),
(21, 'usu3', 'usu3', 'usu3', 'usu3@gmail.com', 'usu3', '$2y$10$vMzRk6ZDlyOGN9yCoT7d0evpbUDgeLOCSv4134TNexdkd1RnHcee.', 1, 'http:\\\\localhost\\daw2\\Artist4all\\artist4all_php\\User\\assets\\img\\imgUnknown.png', '', 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
