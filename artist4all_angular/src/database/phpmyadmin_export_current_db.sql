-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Servidor: artist4all_db:3306
-- Tiempo de generación: 10-04-2021 a las 22:23:48
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
-- Estructura de tabla para la tabla `imgs_publications`
--

CREATE TABLE `imgs_publications` (
  `id` int(11) NOT NULL,
  `imgPublication` varchar(200) NOT NULL,
  `id_publication` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `imgs_publications`
--

INSERT INTO `imgs_publications` (`id`, `imgPublication`, `id_publication`) VALUES
(1, 'http://localhost:81/assets/img/defaultAvatarImg.png', 64),
(2, 'http://localhost:81/assets/img/lolLogo.png', 64),
(5, 'http://localhost:81/assets/img/defaultAvatarImg.png', 73),
(6, 'http://localhost:81/assets/img/lolLogo.png', 73),
(7, 'http://localhost:81/assets/img/defaultAvatarImg.png', 74),
(29, 'http://localhost:81/assets/img/ashokaMandalore.jpg', 90),
(30, 'http://localhost:81/assets/img/defaultAvatarImg.png', 90),
(31, 'http://localhost:81/assets/img/lolLogo.png', 90);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `id_responsible` int(11) NOT NULL,
  `id_receiver` int(11) NOT NULL,
  `bodyNotification` varchar(255) NOT NULL,
  `isRead` tinyint(4) NOT NULL,
  `typeNotification` int(11) NOT NULL,
  `notification_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `notifications`
--

INSERT INTO `notifications` (`id`, `id_responsible`, `id_receiver`, `bodyNotification`, `isRead`, `typeNotification`, `notification_date`) VALUES
(1, 44, 24, 'te ha enviado una solicitud de amistad', 0, 2, '2021-04-10 21:05:38');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notifications_type`
--

CREATE TABLE `notifications_type` (
  `id` int(11) NOT NULL,
  `type` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `notifications_type`
--

INSERT INTO `notifications_type` (`id`, `type`) VALUES
(1, 'Nuevo seguidor'),
(2, 'Solicitud de amistad pendiente'),
(3, 'Solicitud de amistad aceptada');

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
  `n_comments` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `publications`
--

INSERT INTO `publications` (`id`, `id_user`, `bodyPublication`, `upload_date`, `n_likes`, `n_comments`) VALUES
(1, 1, 'Mi primera publicacion', '2021-03-23 17:27:03', 0, 0),
(2, 1, 'Mi primera publicacion', '2021-03-23 17:27:31', 0, 0),
(4, 1, 'Mi 2da publicaciÃ³n', '2021-03-23 17:39:02', 0, 0),
(9, 1, 'Mi primera publicacion', '2021-03-23 17:45:45', 0, 0),
(64, 1, 'WE', '2021-03-31 20:01:39', 0, 0),
(67, 1, 'qweqreqrewqrewqweqrewqreq', '2021-03-31 20:06:50', 0, 0),
(72, 1, 'mi nueva publi sin img', '2021-03-31 20:20:26', 0, 0),
(73, 1, 'mis img', '2021-03-31 20:20:40', 0, 0),
(74, 1, 'mis img 2', '2021-03-31 20:21:51', 0, 0),
(90, 1, 'stop (edited66)', '2021-04-09 08:21:06', 0, 0);

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
  `isPrivate` tinyint(1) NOT NULL,
  `deactivated` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `surname1`, `surname2`, `email`, `username`, `password`, `isArtist`, `imgAvatar`, `aboutMe`, `token`, `isPrivate`, `deactivated`) VALUES
(1, 'Alec', 'Sung', 'Yang', 'alec@gmail.com', 'Ritter', '$2y$10$96dvqk1CdJ2rMlqmKAfX.OlUZvQOaMwGeGjB4IlSsdz02eF93LwfO', 1, 'http://localhost:81/assets/img/defaultAvatarImg.png', 'Bienvenido a mi perfil!!!', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.YWxlY0BnbWFpbC5jb20uJDJ5JDEwJDk2ZHZxazFDZEoyck1scW1LQWZYLk9sVVp2UU9hTXdHZUdqQjRJbFNzZHowMmVGOTNMd2ZPLkJQTFMudnRzZjY=.DHXWQsBkfUKL7klYEzLVZb/50IL5ATh3y/hNLvClUgM=', 0, 0),
(24, 'usu1', 'usu1', 'usu1', 'usu1@gmail.com', 'usu1', '$2y$10$iOb/TzHoIshx2HBjKSY1hecuScSHzKnZ.zwyBLUcGFo/a0t4vCus.', 1, 'http://localhost:81/assets/img/defaultAvatarImg.png', 'Bienvenido a mi perfil!!! ', '', 1, 0),
(27, 'usu3', 'usu3', 'usu3', 'usu3@gmail.com', 'usu3', '$2y$10$YRenRAON2qbVUBRrPvJW6eyc2DYM8QgtsXjL37v7JaMVLtNWK331G', 0, 'http://localhost:81/assets/img/lolLogo.png', 'Bienvenido a mi perfil!!! ', '', 0, 0),
(34, 'Usu2', 'Usu2', 'Usu2', 'usu2@gmail.com', 'Usu2', '$2y$10$7A70dpWM17bjGzUp.GzSBu.cHpJV0l1JU1sZkKEy5DOokxCa54tDa', 1, 'http://localhost:81/assets/img/defaultAvatarImg.png', 'Bienvenido a mi perfil!!!', '', 0, 0),
(36, 'Erik ', 'Sung ', 'Yang ', 'erik@gmail.com ', 'No salt added ', '$2y$10$luteHXzZULMCwJTV6Z/sgOnyzGaivSKFt11JL7DLpuaQgy84Fd9wa', 1, 'http://localhost:81/assets/img/lolLogo.png', 'Bienvenido a mi perfil!!! ', '', 0, 0),
(44, 'q', 'q', 'q', 'q', 'q', '$2y$10$j9DMh1sMCNxA8RQzQtBX6eUKJ2cxpfSSOlLvIs8INoWW53Uei6SKi', 1, 'http://localhost:81/assets/img/defaultAvatarImg.png', 'Bienvenido a mi perfil!!!', '', 0, 0);

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
(16, 24, 1, 3),
(19, 36, 1, 3),
(37, 1, 24, 3),
(56, 1, 36, 3),
(80, 44, 24, 2),
(85, 44, 27, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users_followed_status`
--

CREATE TABLE `users_followed_status` (
  `id` int(11) NOT NULL,
  `status` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `users_followed_status`
--

INSERT INTO `users_followed_status` (`id`, `status`) VALUES
(1, 'No seguido'),
(2, 'Pendiente de aceptación'),
(3, 'Seguido');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `imgs_publications`
--
ALTER TABLE `imgs_publications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_publication` (`id_publication`);

--
-- Indices de la tabla `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_responsible` (`id_responsible`),
  ADD KEY `id_receiver` (`id_receiver`),
  ADD KEY `type` (`typeNotification`);

--
-- Indices de la tabla `notifications_type`
--
ALTER TABLE `notifications_type`
  ADD PRIMARY KEY (`id`);

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
  ADD KEY `id_followed` (`id_followed`),
  ADD KEY `status_follow` (`status_follow`);

--
-- Indices de la tabla `users_followed_status`
--
ALTER TABLE `users_followed_status`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `imgs_publications`
--
ALTER TABLE `imgs_publications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de la tabla `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `notifications_type`
--
ALTER TABLE `notifications_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `publications`
--
ALTER TABLE `publications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT de la tabla `users_followed`
--
ALTER TABLE `users_followed`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT de la tabla `users_followed_status`
--
ALTER TABLE `users_followed_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `imgs_publications`
--
ALTER TABLE `imgs_publications`
  ADD CONSTRAINT `imgspublications_ibfk_1` FOREIGN KEY (`id_publication`) REFERENCES `publications` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`id_responsible`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`id_receiver`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `notifications_ibfk_3` FOREIGN KEY (`typeNotification`) REFERENCES `notifications_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
  ADD CONSTRAINT `users_followed_ibfk_2` FOREIGN KEY (`id_followed`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `users_followed_ibfk_3` FOREIGN KEY (`status_follow`) REFERENCES `users_followed_status` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
