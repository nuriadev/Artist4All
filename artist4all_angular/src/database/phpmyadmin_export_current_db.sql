-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Servidor: artist4all_db:3306
-- Tiempo de generación: 19-03-2021 a las 16:11:23
-- Versión del servidor: 10.2.36-MariaDB-1:10.2.36+maria~bionic
-- Versión de PHP: 7.4.14

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
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `surname1` varchar(50) NOT NULL,
  `surname2` varchar(50) NOT NULL,
  `email` varchar(90) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(120) NOT NULL,
  `isArtist` tinyint(1) NOT NULL,
  `imgAvatar` varchar(300) NOT NULL,
  `aboutMe` varchar(155) NOT NULL,
  `token` varchar(600) NOT NULL,
  `deleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `surname1`, `surname2`, `email`, `username`, `password`, `isArtist`, `imgAvatar`, `aboutMe`, `token`, `deleted`) VALUES
(1, 'Alec', 'Sung', 'Yang', 'alec@gmail.com', 'Ritter ', '$2y$10$XSD0WWjE4gUGmK7fPglziuQUpJSfofxGhQNhMCFFGBmjXbyoj0HiO', 0, 'http://localhost:81/artist4all_php/User/assets/img/imgUnknown.png', 'Bienvendio a mi perfil!!!', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.Mi4kMnkkMTAkWFNEMFdXakU0Z1VHbUs3ZlBnbHppdVFVcEpTZm9meEdoUU5oTUNGRkdCbWpYYnlvajBIaU8uZUpWMlJrWXpyLg==.F/z2h0j3Fs1Qf+FuBDpMYnXDiojsDgdeOEmN145/25U=', 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
