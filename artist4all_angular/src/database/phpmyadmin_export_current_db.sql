-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:8889
-- Tiempo de generación: 05-03-2021 a las 17:30:05
-- Versión del servidor: 5.7.32
-- Versión de PHP: 7.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

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
  `token` varchar(300) NOT NULL,
  `deleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id_user`, `name_user`, `surname1`, `surname2`, `email`, `username`, `passwd`, `type_user`, `img`, `token`, `deleted`) VALUES
(1, 'usu1', 'usu1', 'usu1', 'usu1@gmail.com', 'usu1', '$2y$10$rWG3V45SUGB/u9pgc5dLj.ZjS2z1mstSv4L9DBE3vC7hFIW837JTS', 1, '', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.dXN1MUBnbWFpbC5jb20uJDJ5JDEwJHJXRzNWNDVTVUdCL3U5cGdjNWRMai5aalMyejFtc3RTdjRMOURCRTN2QzdoRklXODM3SlRTLmlKOUl3VmUmQ3g=.MHUEF09AxhtvyWsNazlou2enJ6O/Ecjqko78ngWLg1k=', 0),
(2, 'usu2', 'usu2', 'usu2', 'usu2@gmail.com', 'usu2', '$2y$10$ZgW45KwUvI1mY0WoeaaBROgRlAW6VgJcN0REZEwKnBbM2LuWPT/Qm', 0, '', '', 0),
(3, 'usu3', 'usu3', 'usu3', 'usu3@gmail.com', 'usu3', '$2y$10$dnvjTOigP.AhShO9FNC1tORcMzIVuspXLbn6OGslDCZHK97J3JC7y', 1, '', '', 0),
(6, 'usu5', 'usu5', 'usu5', 'usu5@gmail.com', 'usu5', '$2y$10$nUitnzj89LqSl92nLg2jd.0wl2Q4tnJstsigpztvy5YUUPZkA/.52', 1, '', '', 0),
(7, 'usu4', 'usu4', 'usu4', 'usu4@gmail.com', 'usu4', '$2y$10$FKvwQNohcciAbToypyg15OrCCEcDeMLVDXcDD1kN/sW95NwofcFwW', 1, '', '', 0),
(16, 'usu10', 'usu10', 'usu10', 'usu10@gmail.com', 'usu10', '$2y$10$J/qK9c7gwX6tyYMY4bhUG./EwYLQh5wf05QKy7qBNms7Hl8N5GwCq', 1, 'daw2/artist4all/artist4all_php/User/assets/img/imgUnknown.png', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.dXN1MTBAZ21haWwuY29tLiQyeSQxMCRKL3FLOWM3Z3dYNnR5WU1ZNGJoVUcuL0V3WUxRaDV3ZjA1UUt5N3FCTm1zN0hsOE41R3dDcS52Uk0zSyVyY1hi.bRjw5DAQ0tNPX5hKtzuGq1tP0Rawlm/KBoFSVgDTXRQ=', 0);

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
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
