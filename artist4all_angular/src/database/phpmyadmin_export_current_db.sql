-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Servidor: artist4all_db:3306
-- Tiempo de generación: 31-03-2021 a las 20:10:14
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
-- Estructura de tabla para la tabla `imgsPublications`
--

CREATE TABLE `imgsPublications` (
  `id` int(11) NOT NULL,
  `imgPublication` varchar(200) NOT NULL,
  `id_publication` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `imgsPublications`
--

INSERT INTO `imgsPublications` (`id`, `imgPublication`, `id_publication`) VALUES
(1, 'http://localhost:81/assets/img/defaultAvatarImg.png', 64),
(2, 'http://localhost:81/assets/img/lolLogo.png', 64),
(3, 'http://localhost:81/assets/img/defaultAvatarImg.png', 65),
(4, 'http://localhost:81/assets/img/lolLogo.png', 65);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `publications`
--

CREATE TABLE `publications` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `bodyPublication` varchar(255) NOT NULL,
  `upload_date` datetime NOT NULL,
  `n_likes` int(11) NOT NULL,
  `n_comments` int(11) NOT NULL,
  `n_views` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `publications`
--

INSERT INTO `publications` (`id`, `id_user`, `bodyPublication`, `upload_date`, `n_likes`, `n_comments`, `n_views`) VALUES
(1, 1, 'Mi primera publicacion', '2021-03-23 17:27:03', 0, 0, 0),
(2, 1, 'Mi primera publicacion', '2021-03-23 17:27:31', 0, 0, 0),
(4, 1, 'Mi 2da publicaciÃ³n', '2021-03-23 17:39:02', 0, 0, 0),
(9, 1, 'Mi primera publicacion', '2021-03-23 17:45:45', 0, 0, 0),
(64, 1, 'WE', '2021-03-31 20:01:39', 0, 0, 0),
(65, 1, 'ERTYEW', '2021-03-31 20:03:00', 0, 0, 0),
(67, 1, 'qweqreqrewqrewqweqrewqreq', '2021-03-31 20:06:50', 0, 0, 0);

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
  `deactivated` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `surname1`, `surname2`, `email`, `username`, `password`, `isArtist`, `imgAvatar`, `aboutMe`, `token`, `deactivated`) VALUES
(1, 'Alec', 'Sung', 'Yang', 'alec@gmail.com', 'Ritter', '$2y$10$pe9VMnZ6.7XYH0edKx8l/.HSkmmOsixz/l9QtF87rnrx6EJB/yCwi', 1, 'http://localhost:81/assets/img/defaultAvatarImg.png', 'Bienvenido a mi perfil!!!', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.YWxlY0BnbWFpbC5jb20uJDJ5JDEwJHBlOVZNblo2LjdYWUgwZWRLeDhsLy5IU2ttbU9zaXh6L2w5UXRGODdybnJ4NkVKQi95Q3dpLlJxTjNYLnh5QWQ=.zbm/Kd3hzT9k/FJoodIR4ZRJcVE1k6XROhfAYBuJ7J8=', 0),
(24, 'usu1', 'usu1', 'usu1', 'usu1@gmail.com', 'usu1', '$2y$10$iOb/TzHoIshx2HBjKSY1hecuScSHzKnZ.zwyBLUcGFo/a0t4vCus.', 1, 'http://localhost:81/assets/img/defaultAvatarImg.png', 'Bienvenido a mi perfil!!! ', '', 0),
(27, 'usu3', 'usu3', 'usu3', 'usu3@gmail.com', 'usu3', '$2y$10$YRenRAON2qbVUBRrPvJW6eyc2DYM8QgtsXjL37v7JaMVLtNWK331G', 0, 'http://localhost:81/assets/img/lolLogo.png', 'Bienvenido a mi perfil!!! ', '', 0),
(34, 'Usu2', 'Usu2', 'Usu2', 'usu2@gmail.com', 'Usu2', '$2y$10$7A70dpWM17bjGzUp.GzSBu.cHpJV0l1JU1sZkKEy5DOokxCa54tDa', 1, 'http://localhost:81/assets/img/defaultAvatarImg.png', 'Bienvenido a mi perfil!!!', '', 0),
(36, 'Erik ', 'Sung ', 'Yang ', 'erik@gmail.com ', 'No salt added ', '$2y$10$luteHXzZULMCwJTV6Z/sgOnyzGaivSKFt11JL7DLpuaQgy84Fd9wa', 1, 'http://localhost:81/assets/img/lolLogo.png', 'Bienvenido a mi perfil!!! ', '', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users_followed`
--

CREATE TABLE `users_followed` (
  `id` int(11) NOT NULL,
  `id_follower` int(11) NOT NULL,
  `id_followed` int(11) NOT NULL,
  `status_follow` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `users_followed`
--

INSERT INTO `users_followed` (`id`, `id_follower`, `id_followed`, `status_follow`) VALUES
(11, 1, 24, 0),
(16, 24, 1, 0),
(19, 36, 1, 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `imgsPublications`
--
ALTER TABLE `imgsPublications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_publication` (`id_publication`);

--
-- Indices de la tabla `publications`
--
ALTER TABLE `publications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users_followed`
--
ALTER TABLE `users_followed`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_follower` (`id_follower`),
  ADD KEY `id_followed` (`id_followed`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `imgsPublications`
--
ALTER TABLE `imgsPublications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `publications`
--
ALTER TABLE `publications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de la tabla `users_followed`
--
ALTER TABLE `users_followed`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `imgsPublications`
--
ALTER TABLE `imgsPublications`
  ADD CONSTRAINT `imgspublications_ibfk_1` FOREIGN KEY (`id_publication`) REFERENCES `publications` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `publications`
--
ALTER TABLE `publications`
  ADD CONSTRAINT `publications_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `users_followed`
--
ALTER TABLE `users_followed`
  ADD CONSTRAINT `users_followed_ibfk_1` FOREIGN KEY (`id_follower`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `users_followed_ibfk_2` FOREIGN KEY (`id_followed`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
