-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Servidor: artist4all_db:3306
-- Tiempo de generación: 20-04-2021 a las 12:00:56
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
  `imgPublication` varchar(45) NOT NULL,
  `id_publication` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `imgs_publications`
--

INSERT INTO `imgs_publications` (`id`, `imgPublication`, `id_publication`) VALUES
(1, 'defaultAvatarImg.png', 64),
(2, 'lolLogo.png', 64),
(5, 'defaultAvatarImg.png', 73),
(6, 'lolLogo.png', 73),
(7, 'defaultAvatarImg.png', 74),
(29, 'ashokaMandalore.jpg', 90),
(30, 'defaultAvatarImg.png', 90),
(31, 'lolLogo.png', 90),
(61, 'ashokaMandalore.jpg', 142),
(66, 'wallpaperPrequels.jpg', 143),
(67, 'ashokaMandalore.jpg', 139);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `id_responsible` int(11) NOT NULL,
  `id_receiver` int(11) NOT NULL,
  `isRead` tinyint(4) NOT NULL,
  `typeNotification` int(11) NOT NULL,
  `notification_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `notifications`
--

INSERT INTO `notifications` (`id`, `id_responsible`, `id_receiver`, `isRead`, `typeNotification`, `notification_date`) VALUES
(37, 24, 1, 1, 1, '2021-04-16 22:01:03'),
(38, 1, 24, 1, 3, '2021-04-16 22:01:03'),
(39, 24, 1, 1, 2, '2021-04-16 22:03:23'),
(40, 24, 1, 1, 2, '2021-04-16 22:03:36'),
(43, 24, 1, 1, 2, '2021-04-16 22:18:16'),
(44, 24, 1, 1, 1, '2021-04-16 22:19:53'),
(45, 1, 24, 1, 3, '2021-04-16 22:19:53'),
(59, 54, 1, 1, 2, '2021-04-16 22:32:17'),
(62, 1, 54, 1, 1, '2021-04-16 22:35:11'),
(63, 54, 1, 1, 1, '2021-04-16 22:35:41'),
(64, 1, 54, 1, 3, '2021-04-16 22:35:41');

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
  `isEdited` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `publications`
--

INSERT INTO `publications` (`id`, `id_user`, `bodyPublication`, `upload_date`, `isEdited`) VALUES
(9, 1, 'Mi primera publicaciÃ³n editada ', '2021-04-16 19:48:22', 1),
(64, 1, 'WE', '2021-03-31 20:01:39', 0),
(72, 1, 'mi nueva publi sin img editado', '2021-04-16 19:48:39', 1),
(73, 1, 'mis img', '2021-03-31 20:20:40', 0),
(74, 1, 'mis img 2', '2021-03-31 20:21:51', 0),
(90, 1, 'stop (edited66) weee', '2021-04-17 22:54:36', 1),
(139, 1, 'asdfads dghddgs21', '2021-04-19 19:23:17', 1),
(142, 24, 'sdfeqewasdfa2', '2021-04-18 00:53:29', 1),
(143, 1, 'mi publi', '2021-04-19 19:20:49', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `publication_comments`
--

CREATE TABLE `publication_comments` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `bodyNotification` varchar(255) NOT NULL,
  `isEdited` int(11) NOT NULL,
  `comment_date` datetime NOT NULL,
  `id_publication` int(11) NOT NULL,
  `id_comment_reference` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `publication_comments`
--

INSERT INTO `publication_comments` (`id`, `id_user`, `bodyNotification`, `isEdited`, `comment_date`, `id_publication`, `id_comment_reference`) VALUES
(1, 1, '12', 0, '2021-04-15 21:19:23', 90, NULL);

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
  `imgAvatar` varchar(45) NOT NULL,
  `aboutMe` varchar(155) NOT NULL,
  `token` varchar(600) NOT NULL,
  `isPrivate` tinyint(1) NOT NULL,
  `deactivated` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `surname1`, `surname2`, `email`, `username`, `password`, `isArtist`, `imgAvatar`, `aboutMe`, `token`, `isPrivate`, `deactivated`) VALUES
(1, 'Alec', 'Sung', 'Yang', 'alec@gmail.com', 'Ritter', '$2y$10$GZoVNs66nfOCB57l/Okfke.FciSU58zjnkTuKJNQhOumHmf4KeM.W', 1, 'defaultAvatarImg.png', 'Bienvenido a mi perfil!!!', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.YWxlY0BnbWFpbC5jb20uJDJ5JDEwJEdab1ZOczY2bmZPQ0I1N2wvT2tma2UuRmNpU1U1OHpqbmtUdUtKTlFoT3VtSG1mNEtlTS5XLnllWnpNVXQuNlA=.eHXjxOEnITo595hhHjYlO9MHkcl/5mXHLZk+3ITOemA=', 1, 0),
(24, 'usu1', 'usu1', 'usu1', 'usu1@gmail.com', 'usu1', '$2y$10$iOb/TzHoIshx2HBjKSY1hecuScSHzKnZ.zwyBLUcGFo/a0t4vCus.', 1, 'defaultAvatarImg.png', 'Bienvenido a mi perfil!!! ', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.dXN1MUBnbWFpbC5jb20uJDJ5JDEwJGlPYi9UekhvSXNoeDJIQmpLU1kxaGVjdVNjU0h6S25aLnp3eUJMVWNHRm8vYTB0NHZDdXMuLk5fa1h3cVJWYjk=.4Ltflu6hMJrWvCgOs9ZT+c/YWbzF9XIaUujF0H76JSE=', 1, 0),
(27, 'usu3', 'usu3', 'usu3', 'usu3@gmail.com', 'usu3', '$2y$10$YRenRAON2qbVUBRrPvJW6eyc2DYM8QgtsXjL37v7JaMVLtNWK331G', 0, 'lolLogo.png', 'Bienvenido a mi perfil!!! ', '', 0, 0),
(34, 'Usu2', 'Usu2', 'Usu2', 'usu2@gmail.com', 'Usu2', '$2y$10$7A70dpWM17bjGzUp.GzSBu.cHpJV0l1JU1sZkKEy5DOokxCa54tDa', 1, 'defaultAvatarImg.png', 'Bienvenido a mi perfil!!!', '', 0, 0),
(36, 'Erik ', 'Sung ', 'Yang ', 'erik@gmail.com ', 'No salt added ', '$2y$10$luteHXzZULMCwJTV6Z/sgOnyzGaivSKFt11JL7DLpuaQgy84Fd9wa', 1, 'lolLogo.png', 'Bienvenido a mi perfil!!! ', '', 0, 0),
(54, 'aa', 'aa', 'aa', 'a@a.com', 'aaaaa', '$2y$10$rfbTuHwdSODNiapHkxK0.u/EIu2Yq8tAgeSkYSze9.XC8FCL/cE0m', 0, 'defaultAvatarImg.png', 'Bienvenido a mi perfil!!!', '', 0, 0),
(56, 'mm', 'mm', 'mm', 'm@m.com', 'mmmmm', '$2y$10$Qb1ZMxvplWRIwHQUdyxEt.YrzlQigaeg8lDlCU69qecGQYkVlsVqu', 1, 'defaultAvatarImg.png', 'Bienvenido a mi perfil!!!', '', 0, 0),
(57, 'oo', 'oo', 'oo', 'o@o.com', 'ooooo', '$2y$10$9woV2fJqHyPlU6s4F/u2fOItgHNWKSsBb7GVf4LuDG8XYIaOUV3oa', 1, 'defaultAvatarImg.png', 'Bienvenido a mi perfil!!!', '', 0, 0);

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
(103, 1, 54, 3),
(105, 54, 1, 3),
(106, 34, 54, 3);

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
(31, 24, 139, 1),
(32, 24, 90, 1),
(33, 24, 74, 1);

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
-- Indices de la tabla `publication_comments`
--
ALTER TABLE `publication_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_publication` (`id_publication`),
  ADD KEY `id_comment_reference` (`id_comment_reference`);

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
-- AUTO_INCREMENT de la tabla `imgs_publications`
--
ALTER TABLE `imgs_publications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT de la tabla `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT de la tabla `notifications_type`
--
ALTER TABLE `notifications_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `publications`
--
ALTER TABLE `publications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=144;

--
-- AUTO_INCREMENT de la tabla `publication_comments`
--
ALTER TABLE `publication_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT de la tabla `users_followed`
--
ALTER TABLE `users_followed`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT de la tabla `users_followed_status`
--
ALTER TABLE `users_followed_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `users_likes_publications`
--
ALTER TABLE `users_likes_publications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `imgs_publications`
--
ALTER TABLE `imgs_publications`
  ADD CONSTRAINT `imgs_publications_ibfk_1` FOREIGN KEY (`id_publication`) REFERENCES `publications` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
-- Filtros para la tabla `publication_comments`
--
ALTER TABLE `publication_comments`
  ADD CONSTRAINT `publication_comments_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `publication_comments_ibfk_2` FOREIGN KEY (`id_publication`) REFERENCES `publications` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `publication_comments_ibfk_3` FOREIGN KEY (`id_comment_reference`) REFERENCES `publication_comments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `users_followed`
--
ALTER TABLE `users_followed`
  ADD CONSTRAINT `users_followed_ibfk_1` FOREIGN KEY (`id_follower`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `users_followed_ibfk_2` FOREIGN KEY (`id_followed`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `users_followed_ibfk_3` FOREIGN KEY (`status_follow`) REFERENCES `users_followed_status` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
