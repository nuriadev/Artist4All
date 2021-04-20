-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Servidor: artist4all_db:3306
-- Tiempo de generación: 20-04-2021 a las 16:22:53
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
(1, 'defaultAvatarImg.png', 64),
(2, 'lolLogo.png', 64),
(5, 'defaultAvatarImg.png', 73),
(6, 'lolLogo.png', 73),
(7, 'defaultAvatarImg.png', 74),
(41, 'defaultAvatarImg.png', 109),
(42, 'lolLogo.png', 109),
(49, 'ashokaMandalore.jpg', 110),
(50, 'defaultAvatarImg.png', 110),
(51, 'lolLogo.png', 110),
(56, 'lolLogo.png', 90),
(57, 'wallpaperPrequels.jpg', 112);

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
(1, 44, 24, 0, 2, '2021-04-10 21:05:38'),
(2, 24, 45, 0, 2, '2021-04-12 08:12:38'),
(4, 45, 1, 0, 1, '2021-04-12 09:56:15');

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
(1, 1, 'Mi primera publicacion', '2021-03-23 17:27:03', 0),
(2, 1, 'Mi primera publicacion', '2021-03-23 17:27:31', 0),
(4, 1, 'Mi 2da publicaciÃ³n', '2021-03-23 17:39:02', 0),
(9, 1, 'Mi primera publicacion', '2021-03-23 17:45:45', 0),
(64, 1, 'WE', '2021-03-31 20:01:39', 0),
(67, 1, 'qweqreqrewqrewqweqrewqreq', '2021-03-31 20:06:50', 0),
(72, 1, 'mi nueva publi sin img', '2021-03-31 20:20:26', 0),
(73, 1, 'mis img', '2021-03-31 20:20:40', 0),
(74, 1, 'mis img 2', '2021-03-31 20:21:51', 0),
(90, 1, 'stop (edited66) 4', '2021-04-19 16:28:43', 1),
(109, 45, 'prrrrrrr', '2021-04-12 15:04:38', 0),
(110, 24, 'mi nueva publi (edited)', '2021-04-19 14:48:03', 1),
(112, 1, 'mi nueva publi 2', '2021-04-20 13:31:52', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `publication_comments`
--

CREATE TABLE `publication_comments` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `bodyComment` varchar(255) NOT NULL,
  `isEdited` int(11) NOT NULL,
  `comment_date` datetime NOT NULL,
  `id_publication` int(11) NOT NULL,
  `id_comment_reference` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `publication_comments`
--

INSERT INTO `publication_comments` (`id`, `id_user`, `bodyComment`, `isEdited`, `comment_date`, `id_publication`, `id_comment_reference`) VALUES
(1, 1, '12', 0, '2021-04-15 21:19:23', 90, NULL),
(4, 1, 'mi comment 2', 0, '2021-04-20 16:14:14', 112, NULL);

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
(1, 'Alec', 'Sung', 'Yang', 'alec@gmail.com', 'Ritter', '$2y$10$GZoVNs66nfOCB57l/Okfke.FciSU58zjnkTuKJNQhOumHmf4KeM.W', 1, 'defaultAvatarImg.png', 'Bienvenido a mi perfil!!!', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.YWxlY0BnbWFpbC5jb20uJDJ5JDEwJEdab1ZOczY2bmZPQ0I1N2wvT2tma2UuRmNpU1U1OHpqbmtUdUtKTlFoT3VtSG1mNEtlTS5XLnN4bjRrWl9QUUo=.GjVrlzPHWhuYesbxnqyPlZ3AIrT4gG9b1MiNUtrb0NY=', 1, 0),
(24, 'usu1', 'usu1', 'usu1', 'usu1@gmail.com', 'usu1', '$2y$10$iOb/TzHoIshx2HBjKSY1hecuScSHzKnZ.zwyBLUcGFo/a0t4vCus.', 1, 'defaultAvatarImg.png', 'Bienvenido a mi perfil!!! ', '', 1, 0),
(27, 'usu3', 'usu3', 'usu3', 'usu3@gmail.com', 'usu3', '$2y$10$YRenRAON2qbVUBRrPvJW6eyc2DYM8QgtsXjL37v7JaMVLtNWK331G', 0, 'lolLogo.png', 'Bienvenido a mi perfil!!! ', '', 0, 0),
(34, 'Usu2', 'Usu2', 'Usu2', 'usu2@gmail.com', 'Usu2', '$2y$10$7A70dpWM17bjGzUp.GzSBu.cHpJV0l1JU1sZkKEy5DOokxCa54tDa', 1, 'defaultAvatarImg.png', 'Bienvenido a mi perfil!!!', '', 0, 0),
(36, 'Erik ', 'Sung ', 'Yang ', 'erik@gmail.com ', 'No salt added ', '$2y$10$luteHXzZULMCwJTV6Z/sgOnyzGaivSKFt11JL7DLpuaQgy84Fd9wa', 1, 'lolLogo.png', 'Bienvenido a mi perfil!!! ', '', 0, 0),
(44, 'q', 'q', 'q', 'q', 'q', '$2y$10$j9DMh1sMCNxA8RQzQtBX6eUKJ2cxpfSSOlLvIs8INoWW53Uei6SKi', 1, 'defaultAvatarImg.png', 'Bienvenido a mi perfil!!!', '', 0, 0),
(45, 'aa', 'aa', 'aa', 'a@a.com', 'aaaaa', '$2y$10$x0FSNiLIsKTDvXxTu8ruluiUeE6v2XTlFIrBTGjRcGXdn7RmItnde', 1, 'defaultAvatarImg.png', 'Bienvenido a mi perfil!!!', '', 1, 0),
(50, 'yy', 'yy', 'yy', 'y@y.com', 'yyyyy', '$2y$10$9CYw7LXPPHnCrRVx0wOxfeLVU2c4nx5OX4F6M8sFpiD7O2O3j6yRK', 1, 'defaultAvatarImg.png', 'Bienvenido a mi perfil!!!', '', 0, 0),
(51, 'nn', 'nn', 'nn', 'n@n.com', 'nnnnn', '$2y$10$gCUboTlF.MzxP1.lHmAcjewPRbWWzfEYRSzflFWu24tBU22/oMj.K', 1, 'http://localhost:81/assets/img/defaultAvatarImg.png', 'Bienvenido a mi perfil!!!', '', 0, 0);

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
(85, 44, 27, 1),
(86, 24, 45, 1),
(89, 45, 1, 1);

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
(25, 1, 67, 0),
(26, 1, 4, 1),
(30, 1, 72, 1),
(31, 1, 110, 1);

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
  ADD KEY `id_publication` (`id_publication`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT de la tabla `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `notifications_type`
--
ALTER TABLE `notifications_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `publications`
--
ALTER TABLE `publications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT de la tabla `publication_comments`
--
ALTER TABLE `publication_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT de la tabla `users_followed`
--
ALTER TABLE `users_followed`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT de la tabla `users_followed_status`
--
ALTER TABLE `users_followed_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `users_likes_publications`
--
ALTER TABLE `users_likes_publications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

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
-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Servidor: artist4all_db:3306
-- Tiempo de generación: 20-04-2021 a las 16:22:53
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
(1, 'defaultAvatarImg.png', 64),
(2, 'lolLogo.png', 64),
(5, 'defaultAvatarImg.png', 73),
(6, 'lolLogo.png', 73),
(7, 'defaultAvatarImg.png', 74),
(41, 'defaultAvatarImg.png', 109),
(42, 'lolLogo.png', 109),
(49, 'ashokaMandalore.jpg', 110),
(50, 'defaultAvatarImg.png', 110),
(51, 'lolLogo.png', 110),
(56, 'lolLogo.png', 90),
(57, 'wallpaperPrequels.jpg', 112);

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
(1, 44, 24, 0, 2, '2021-04-10 21:05:38'),
(2, 24, 45, 0, 2, '2021-04-12 08:12:38'),
(4, 45, 1, 0, 1, '2021-04-12 09:56:15');

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
(1, 1, 'Mi primera publicacion', '2021-03-23 17:27:03', 0),
(2, 1, 'Mi primera publicacion', '2021-03-23 17:27:31', 0),
(4, 1, 'Mi 2da publicaciÃ³n', '2021-03-23 17:39:02', 0),
(9, 1, 'Mi primera publicacion', '2021-03-23 17:45:45', 0),
(64, 1, 'WE', '2021-03-31 20:01:39', 0),
(67, 1, 'qweqreqrewqrewqweqrewqreq', '2021-03-31 20:06:50', 0),
(72, 1, 'mi nueva publi sin img', '2021-03-31 20:20:26', 0),
(73, 1, 'mis img', '2021-03-31 20:20:40', 0),
(74, 1, 'mis img 2', '2021-03-31 20:21:51', 0),
(90, 1, 'stop (edited66) 4', '2021-04-19 16:28:43', 1),
(109, 45, 'prrrrrrr', '2021-04-12 15:04:38', 0),
(110, 24, 'mi nueva publi (edited)', '2021-04-19 14:48:03', 1),
(112, 1, 'mi nueva publi 2', '2021-04-20 13:31:52', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `publication_comments`
--

CREATE TABLE `publication_comments` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `bodyComment` varchar(255) NOT NULL,
  `isEdited` int(11) NOT NULL,
  `comment_date` datetime NOT NULL,
  `id_publication` int(11) NOT NULL,
  `id_comment_reference` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `publication_comments`
--

INSERT INTO `publication_comments` (`id`, `id_user`, `bodyComment`, `isEdited`, `comment_date`, `id_publication`, `id_comment_reference`) VALUES
(1, 1, '12', 0, '2021-04-15 21:19:23', 90, NULL),
(4, 1, 'mi comment 2', 0, '2021-04-20 16:14:14', 112, NULL);

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
(1, 'Alec', 'Sung', 'Yang', 'alec@gmail.com', 'Ritter', '$2y$10$GZoVNs66nfOCB57l/Okfke.FciSU58zjnkTuKJNQhOumHmf4KeM.W', 1, 'defaultAvatarImg.png', 'Bienvenido a mi perfil!!!', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.YWxlY0BnbWFpbC5jb20uJDJ5JDEwJEdab1ZOczY2bmZPQ0I1N2wvT2tma2UuRmNpU1U1OHpqbmtUdUtKTlFoT3VtSG1mNEtlTS5XLnN4bjRrWl9QUUo=.GjVrlzPHWhuYesbxnqyPlZ3AIrT4gG9b1MiNUtrb0NY=', 1, 0),
(24, 'usu1', 'usu1', 'usu1', 'usu1@gmail.com', 'usu1', '$2y$10$iOb/TzHoIshx2HBjKSY1hecuScSHzKnZ.zwyBLUcGFo/a0t4vCus.', 1, 'defaultAvatarImg.png', 'Bienvenido a mi perfil!!! ', '', 1, 0),
(27, 'usu3', 'usu3', 'usu3', 'usu3@gmail.com', 'usu3', '$2y$10$YRenRAON2qbVUBRrPvJW6eyc2DYM8QgtsXjL37v7JaMVLtNWK331G', 0, 'lolLogo.png', 'Bienvenido a mi perfil!!! ', '', 0, 0),
(34, 'Usu2', 'Usu2', 'Usu2', 'usu2@gmail.com', 'Usu2', '$2y$10$7A70dpWM17bjGzUp.GzSBu.cHpJV0l1JU1sZkKEy5DOokxCa54tDa', 1, 'defaultAvatarImg.png', 'Bienvenido a mi perfil!!!', '', 0, 0),
(36, 'Erik ', 'Sung ', 'Yang ', 'erik@gmail.com ', 'No salt added ', '$2y$10$luteHXzZULMCwJTV6Z/sgOnyzGaivSKFt11JL7DLpuaQgy84Fd9wa', 1, 'lolLogo.png', 'Bienvenido a mi perfil!!! ', '', 0, 0),
(44, 'q', 'q', 'q', 'q', 'q', '$2y$10$j9DMh1sMCNxA8RQzQtBX6eUKJ2cxpfSSOlLvIs8INoWW53Uei6SKi', 1, 'defaultAvatarImg.png', 'Bienvenido a mi perfil!!!', '', 0, 0),
(45, 'aa', 'aa', 'aa', 'a@a.com', 'aaaaa', '$2y$10$x0FSNiLIsKTDvXxTu8ruluiUeE6v2XTlFIrBTGjRcGXdn7RmItnde', 1, 'defaultAvatarImg.png', 'Bienvenido a mi perfil!!!', '', 1, 0),
(50, 'yy', 'yy', 'yy', 'y@y.com', 'yyyyy', '$2y$10$9CYw7LXPPHnCrRVx0wOxfeLVU2c4nx5OX4F6M8sFpiD7O2O3j6yRK', 1, 'defaultAvatarImg.png', 'Bienvenido a mi perfil!!!', '', 0, 0),
(51, 'nn', 'nn', 'nn', 'n@n.com', 'nnnnn', '$2y$10$gCUboTlF.MzxP1.lHmAcjewPRbWWzfEYRSzflFWu24tBU22/oMj.K', 1, 'http://localhost:81/assets/img/defaultAvatarImg.png', 'Bienvenido a mi perfil!!!', '', 0, 0);

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
(85, 44, 27, 1),
(86, 24, 45, 1),
(89, 45, 1, 1);

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
(25, 1, 67, 0),
(26, 1, 4, 1),
(30, 1, 72, 1),
(31, 1, 110, 1);

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
  ADD KEY `id_publication` (`id_publication`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT de la tabla `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `notifications_type`
--
ALTER TABLE `notifications_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `publications`
--
ALTER TABLE `publications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT de la tabla `publication_comments`
--
ALTER TABLE `publication_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT de la tabla `users_followed`
--
ALTER TABLE `users_followed`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT de la tabla `users_followed_status`
--
ALTER TABLE `users_followed_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `users_likes_publications`
--
ALTER TABLE `users_likes_publications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

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
-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Servidor: artist4all_db:3306
-- Tiempo de generación: 20-04-2021 a las 16:22:53
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
(1, 'defaultAvatarImg.png', 64),
(2, 'lolLogo.png', 64),
(5, 'defaultAvatarImg.png', 73),
(6, 'lolLogo.png', 73),
(7, 'defaultAvatarImg.png', 74),
(41, 'defaultAvatarImg.png', 109),
(42, 'lolLogo.png', 109),
(49, 'ashokaMandalore.jpg', 110),
(50, 'defaultAvatarImg.png', 110),
(51, 'lolLogo.png', 110),
(56, 'lolLogo.png', 90),
(57, 'wallpaperPrequels.jpg', 112);

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
(1, 44, 24, 0, 2, '2021-04-10 21:05:38'),
(2, 24, 45, 0, 2, '2021-04-12 08:12:38'),
(4, 45, 1, 0, 1, '2021-04-12 09:56:15');

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
(1, 1, 'Mi primera publicacion', '2021-03-23 17:27:03', 0),
(2, 1, 'Mi primera publicacion', '2021-03-23 17:27:31', 0),
(4, 1, 'Mi 2da publicaciÃ³n', '2021-03-23 17:39:02', 0),
(9, 1, 'Mi primera publicacion', '2021-03-23 17:45:45', 0),
(64, 1, 'WE', '2021-03-31 20:01:39', 0),
(67, 1, 'qweqreqrewqrewqweqrewqreq', '2021-03-31 20:06:50', 0),
(72, 1, 'mi nueva publi sin img', '2021-03-31 20:20:26', 0),
(73, 1, 'mis img', '2021-03-31 20:20:40', 0),
(74, 1, 'mis img 2', '2021-03-31 20:21:51', 0),
(90, 1, 'stop (edited66) 4', '2021-04-19 16:28:43', 1),
(109, 45, 'prrrrrrr', '2021-04-12 15:04:38', 0),
(110, 24, 'mi nueva publi (edited)', '2021-04-19 14:48:03', 1),
(112, 1, 'mi nueva publi 2', '2021-04-20 13:31:52', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `publication_comments`
--

CREATE TABLE `publication_comments` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `bodyComment` varchar(255) NOT NULL,
  `isEdited` int(11) NOT NULL,
  `comment_date` datetime NOT NULL,
  `id_publication` int(11) NOT NULL,
  `id_comment_reference` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `publication_comments`
--

INSERT INTO `publication_comments` (`id`, `id_user`, `bodyComment`, `isEdited`, `comment_date`, `id_publication`, `id_comment_reference`) VALUES
(1, 1, '12', 0, '2021-04-15 21:19:23', 90, NULL),
(4, 1, 'mi comment 2', 0, '2021-04-20 16:14:14', 112, NULL);

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
(1, 'Alec', 'Sung', 'Yang', 'alec@gmail.com', 'Ritter', '$2y$10$GZoVNs66nfOCB57l/Okfke.FciSU58zjnkTuKJNQhOumHmf4KeM.W', 1, 'defaultAvatarImg.png', 'Bienvenido a mi perfil!!!', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.YWxlY0BnbWFpbC5jb20uJDJ5JDEwJEdab1ZOczY2bmZPQ0I1N2wvT2tma2UuRmNpU1U1OHpqbmtUdUtKTlFoT3VtSG1mNEtlTS5XLnN4bjRrWl9QUUo=.GjVrlzPHWhuYesbxnqyPlZ3AIrT4gG9b1MiNUtrb0NY=', 1, 0),
(24, 'usu1', 'usu1', 'usu1', 'usu1@gmail.com', 'usu1', '$2y$10$iOb/TzHoIshx2HBjKSY1hecuScSHzKnZ.zwyBLUcGFo/a0t4vCus.', 1, 'defaultAvatarImg.png', 'Bienvenido a mi perfil!!! ', '', 1, 0),
(27, 'usu3', 'usu3', 'usu3', 'usu3@gmail.com', 'usu3', '$2y$10$YRenRAON2qbVUBRrPvJW6eyc2DYM8QgtsXjL37v7JaMVLtNWK331G', 0, 'lolLogo.png', 'Bienvenido a mi perfil!!! ', '', 0, 0),
(34, 'Usu2', 'Usu2', 'Usu2', 'usu2@gmail.com', 'Usu2', '$2y$10$7A70dpWM17bjGzUp.GzSBu.cHpJV0l1JU1sZkKEy5DOokxCa54tDa', 1, 'defaultAvatarImg.png', 'Bienvenido a mi perfil!!!', '', 0, 0),
(36, 'Erik ', 'Sung ', 'Yang ', 'erik@gmail.com ', 'No salt added ', '$2y$10$luteHXzZULMCwJTV6Z/sgOnyzGaivSKFt11JL7DLpuaQgy84Fd9wa', 1, 'lolLogo.png', 'Bienvenido a mi perfil!!! ', '', 0, 0),
(44, 'q', 'q', 'q', 'q', 'q', '$2y$10$j9DMh1sMCNxA8RQzQtBX6eUKJ2cxpfSSOlLvIs8INoWW53Uei6SKi', 1, 'defaultAvatarImg.png', 'Bienvenido a mi perfil!!!', '', 0, 0),
(45, 'aa', 'aa', 'aa', 'a@a.com', 'aaaaa', '$2y$10$x0FSNiLIsKTDvXxTu8ruluiUeE6v2XTlFIrBTGjRcGXdn7RmItnde', 1, 'defaultAvatarImg.png', 'Bienvenido a mi perfil!!!', '', 1, 0),
(50, 'yy', 'yy', 'yy', 'y@y.com', 'yyyyy', '$2y$10$9CYw7LXPPHnCrRVx0wOxfeLVU2c4nx5OX4F6M8sFpiD7O2O3j6yRK', 1, 'defaultAvatarImg.png', 'Bienvenido a mi perfil!!!', '', 0, 0),
(51, 'nn', 'nn', 'nn', 'n@n.com', 'nnnnn', '$2y$10$gCUboTlF.MzxP1.lHmAcjewPRbWWzfEYRSzflFWu24tBU22/oMj.K', 1, 'http://localhost:81/assets/img/defaultAvatarImg.png', 'Bienvenido a mi perfil!!!', '', 0, 0);

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
(85, 44, 27, 1),
(86, 24, 45, 1),
(89, 45, 1, 1);

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
(25, 1, 67, 0),
(26, 1, 4, 1),
(30, 1, 72, 1),
(31, 1, 110, 1);

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
  ADD KEY `id_publication` (`id_publication`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT de la tabla `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `notifications_type`
--
ALTER TABLE `notifications_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `publications`
--
ALTER TABLE `publications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT de la tabla `publication_comments`
--
ALTER TABLE `publication_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT de la tabla `users_followed`
--
ALTER TABLE `users_followed`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT de la tabla `users_followed_status`
--
ALTER TABLE `users_followed_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `users_likes_publications`
--
ALTER TABLE `users_likes_publications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

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
-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Servidor: artist4all_db:3306
-- Tiempo de generación: 20-04-2021 a las 16:22:53
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
(1, 'defaultAvatarImg.png', 64),
(2, 'lolLogo.png', 64),
(5, 'defaultAvatarImg.png', 73),
(6, 'lolLogo.png', 73),
(7, 'defaultAvatarImg.png', 74),
(41, 'defaultAvatarImg.png', 109),
(42, 'lolLogo.png', 109),
(49, 'ashokaMandalore.jpg', 110),
(50, 'defaultAvatarImg.png', 110),
(51, 'lolLogo.png', 110),
(56, 'lolLogo.png', 90),
(57, 'wallpaperPrequels.jpg', 112);

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
(1, 44, 24, 0, 2, '2021-04-10 21:05:38'),
(2, 24, 45, 0, 2, '2021-04-12 08:12:38'),
(4, 45, 1, 0, 1, '2021-04-12 09:56:15');

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
(1, 1, 'Mi primera publicacion', '2021-03-23 17:27:03', 0),
(2, 1, 'Mi primera publicacion', '2021-03-23 17:27:31', 0),
(4, 1, 'Mi 2da publicaciÃ³n', '2021-03-23 17:39:02', 0),
(9, 1, 'Mi primera publicacion', '2021-03-23 17:45:45', 0),
(64, 1, 'WE', '2021-03-31 20:01:39', 0),
(67, 1, 'qweqreqrewqrewqweqrewqreq', '2021-03-31 20:06:50', 0),
(72, 1, 'mi nueva publi sin img', '2021-03-31 20:20:26', 0),
(73, 1, 'mis img', '2021-03-31 20:20:40', 0),
(74, 1, 'mis img 2', '2021-03-31 20:21:51', 0),
(90, 1, 'stop (edited66) 4', '2021-04-19 16:28:43', 1),
(109, 45, 'prrrrrrr', '2021-04-12 15:04:38', 0),
(110, 24, 'mi nueva publi (edited)', '2021-04-19 14:48:03', 1),
(112, 1, 'mi nueva publi 2', '2021-04-20 13:31:52', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `publication_comments`
--

CREATE TABLE `publication_comments` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `bodyComment` varchar(255) NOT NULL,
  `isEdited` int(11) NOT NULL,
  `comment_date` datetime NOT NULL,
  `id_publication` int(11) NOT NULL,
  `id_comment_reference` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `publication_comments`
--

INSERT INTO `publication_comments` (`id`, `id_user`, `bodyComment`, `isEdited`, `comment_date`, `id_publication`, `id_comment_reference`) VALUES
(1, 1, '12', 0, '2021-04-15 21:19:23', 90, NULL),
(4, 1, 'mi comment 2', 0, '2021-04-20 16:14:14', 112, NULL);

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
(1, 'Alec', 'Sung', 'Yang', 'alec@gmail.com', 'Ritter', '$2y$10$GZoVNs66nfOCB57l/Okfke.FciSU58zjnkTuKJNQhOumHmf4KeM.W', 1, 'defaultAvatarImg.png', 'Bienvenido a mi perfil!!!', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.YWxlY0BnbWFpbC5jb20uJDJ5JDEwJEdab1ZOczY2bmZPQ0I1N2wvT2tma2UuRmNpU1U1OHpqbmtUdUtKTlFoT3VtSG1mNEtlTS5XLnN4bjRrWl9QUUo=.GjVrlzPHWhuYesbxnqyPlZ3AIrT4gG9b1MiNUtrb0NY=', 1, 0),
(24, 'usu1', 'usu1', 'usu1', 'usu1@gmail.com', 'usu1', '$2y$10$iOb/TzHoIshx2HBjKSY1hecuScSHzKnZ.zwyBLUcGFo/a0t4vCus.', 1, 'defaultAvatarImg.png', 'Bienvenido a mi perfil!!! ', '', 1, 0),
(27, 'usu3', 'usu3', 'usu3', 'usu3@gmail.com', 'usu3', '$2y$10$YRenRAON2qbVUBRrPvJW6eyc2DYM8QgtsXjL37v7JaMVLtNWK331G', 0, 'lolLogo.png', 'Bienvenido a mi perfil!!! ', '', 0, 0),
(34, 'Usu2', 'Usu2', 'Usu2', 'usu2@gmail.com', 'Usu2', '$2y$10$7A70dpWM17bjGzUp.GzSBu.cHpJV0l1JU1sZkKEy5DOokxCa54tDa', 1, 'defaultAvatarImg.png', 'Bienvenido a mi perfil!!!', '', 0, 0),
(36, 'Erik ', 'Sung ', 'Yang ', 'erik@gmail.com ', 'No salt added ', '$2y$10$luteHXzZULMCwJTV6Z/sgOnyzGaivSKFt11JL7DLpuaQgy84Fd9wa', 1, 'lolLogo.png', 'Bienvenido a mi perfil!!! ', '', 0, 0),
(44, 'q', 'q', 'q', 'q', 'q', '$2y$10$j9DMh1sMCNxA8RQzQtBX6eUKJ2cxpfSSOlLvIs8INoWW53Uei6SKi', 1, 'defaultAvatarImg.png', 'Bienvenido a mi perfil!!!', '', 0, 0),
(45, 'aa', 'aa', 'aa', 'a@a.com', 'aaaaa', '$2y$10$x0FSNiLIsKTDvXxTu8ruluiUeE6v2XTlFIrBTGjRcGXdn7RmItnde', 1, 'defaultAvatarImg.png', 'Bienvenido a mi perfil!!!', '', 1, 0),
(50, 'yy', 'yy', 'yy', 'y@y.com', 'yyyyy', '$2y$10$9CYw7LXPPHnCrRVx0wOxfeLVU2c4nx5OX4F6M8sFpiD7O2O3j6yRK', 1, 'defaultAvatarImg.png', 'Bienvenido a mi perfil!!!', '', 0, 0),
(51, 'nn', 'nn', 'nn', 'n@n.com', 'nnnnn', '$2y$10$gCUboTlF.MzxP1.lHmAcjewPRbWWzfEYRSzflFWu24tBU22/oMj.K', 1, 'http://localhost:81/assets/img/defaultAvatarImg.png', 'Bienvenido a mi perfil!!!', '', 0, 0);

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
(85, 44, 27, 1),
(86, 24, 45, 1),
(89, 45, 1, 1);

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
(25, 1, 67, 0),
(26, 1, 4, 1),
(30, 1, 72, 1),
(31, 1, 110, 1);

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
  ADD KEY `id_publication` (`id_publication`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT de la tabla `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `notifications_type`
--
ALTER TABLE `notifications_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `publications`
--
ALTER TABLE `publications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT de la tabla `publication_comments`
--
ALTER TABLE `publication_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT de la tabla `users_followed`
--
ALTER TABLE `users_followed`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT de la tabla `users_followed_status`
--
ALTER TABLE `users_followed_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `users_likes_publications`
--
ALTER TABLE `users_likes_publications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

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
-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Servidor: artist4all_db:3306
-- Tiempo de generación: 20-04-2021 a las 16:22:53
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
(1, 'defaultAvatarImg.png', 64),
(2, 'lolLogo.png', 64),
(5, 'defaultAvatarImg.png', 73),
(6, 'lolLogo.png', 73),
(7, 'defaultAvatarImg.png', 74),
(41, 'defaultAvatarImg.png', 109),
(42, 'lolLogo.png', 109),
(49, 'ashokaMandalore.jpg', 110),
(50, 'defaultAvatarImg.png', 110),
(51, 'lolLogo.png', 110),
(56, 'lolLogo.png', 90),
(57, 'wallpaperPrequels.jpg', 112);

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
(1, 44, 24, 0, 2, '2021-04-10 21:05:38'),
(2, 24, 45, 0, 2, '2021-04-12 08:12:38'),
(4, 45, 1, 0, 1, '2021-04-12 09:56:15');

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
(1, 1, 'Mi primera publicacion', '2021-03-23 17:27:03', 0),
(2, 1, 'Mi primera publicacion', '2021-03-23 17:27:31', 0),
(4, 1, 'Mi 2da publicaciÃ³n', '2021-03-23 17:39:02', 0),
(9, 1, 'Mi primera publicacion', '2021-03-23 17:45:45', 0),
(64, 1, 'WE', '2021-03-31 20:01:39', 0),
(67, 1, 'qweqreqrewqrewqweqrewqreq', '2021-03-31 20:06:50', 0),
(72, 1, 'mi nueva publi sin img', '2021-03-31 20:20:26', 0),
(73, 1, 'mis img', '2021-03-31 20:20:40', 0),
(74, 1, 'mis img 2', '2021-03-31 20:21:51', 0),
(90, 1, 'stop (edited66) 4', '2021-04-19 16:28:43', 1),
(109, 45, 'prrrrrrr', '2021-04-12 15:04:38', 0),
(110, 24, 'mi nueva publi (edited)', '2021-04-19 14:48:03', 1),
(112, 1, 'mi nueva publi 2', '2021-04-20 13:31:52', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `publication_comments`
--

CREATE TABLE `publication_comments` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `bodyComment` varchar(255) NOT NULL,
  `isEdited` int(11) NOT NULL,
  `comment_date` datetime NOT NULL,
  `id_publication` int(11) NOT NULL,
  `id_comment_reference` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `publication_comments`
--

INSERT INTO `publication_comments` (`id`, `id_user`, `bodyComment`, `isEdited`, `comment_date`, `id_publication`, `id_comment_reference`) VALUES
(1, 1, '12', 0, '2021-04-15 21:19:23', 90, NULL),
(4, 1, 'mi comment 2', 0, '2021-04-20 16:14:14', 112, NULL);

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
(1, 'Alec', 'Sung', 'Yang', 'alec@gmail.com', 'Ritter', '$2y$10$GZoVNs66nfOCB57l/Okfke.FciSU58zjnkTuKJNQhOumHmf4KeM.W', 1, 'defaultAvatarImg.png', 'Bienvenido a mi perfil!!!', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.YWxlY0BnbWFpbC5jb20uJDJ5JDEwJEdab1ZOczY2bmZPQ0I1N2wvT2tma2UuRmNpU1U1OHpqbmtUdUtKTlFoT3VtSG1mNEtlTS5XLnN4bjRrWl9QUUo=.GjVrlzPHWhuYesbxnqyPlZ3AIrT4gG9b1MiNUtrb0NY=', 1, 0),
(24, 'usu1', 'usu1', 'usu1', 'usu1@gmail.com', 'usu1', '$2y$10$iOb/TzHoIshx2HBjKSY1hecuScSHzKnZ.zwyBLUcGFo/a0t4vCus.', 1, 'defaultAvatarImg.png', 'Bienvenido a mi perfil!!! ', '', 1, 0),
(27, 'usu3', 'usu3', 'usu3', 'usu3@gmail.com', 'usu3', '$2y$10$YRenRAON2qbVUBRrPvJW6eyc2DYM8QgtsXjL37v7JaMVLtNWK331G', 0, 'lolLogo.png', 'Bienvenido a mi perfil!!! ', '', 0, 0),
(34, 'Usu2', 'Usu2', 'Usu2', 'usu2@gmail.com', 'Usu2', '$2y$10$7A70dpWM17bjGzUp.GzSBu.cHpJV0l1JU1sZkKEy5DOokxCa54tDa', 1, 'defaultAvatarImg.png', 'Bienvenido a mi perfil!!!', '', 0, 0),
(36, 'Erik ', 'Sung ', 'Yang ', 'erik@gmail.com ', 'No salt added ', '$2y$10$luteHXzZULMCwJTV6Z/sgOnyzGaivSKFt11JL7DLpuaQgy84Fd9wa', 1, 'lolLogo.png', 'Bienvenido a mi perfil!!! ', '', 0, 0),
(44, 'q', 'q', 'q', 'q', 'q', '$2y$10$j9DMh1sMCNxA8RQzQtBX6eUKJ2cxpfSSOlLvIs8INoWW53Uei6SKi', 1, 'defaultAvatarImg.png', 'Bienvenido a mi perfil!!!', '', 0, 0),
(45, 'aa', 'aa', 'aa', 'a@a.com', 'aaaaa', '$2y$10$x0FSNiLIsKTDvXxTu8ruluiUeE6v2XTlFIrBTGjRcGXdn7RmItnde', 1, 'defaultAvatarImg.png', 'Bienvenido a mi perfil!!!', '', 1, 0),
(50, 'yy', 'yy', 'yy', 'y@y.com', 'yyyyy', '$2y$10$9CYw7LXPPHnCrRVx0wOxfeLVU2c4nx5OX4F6M8sFpiD7O2O3j6yRK', 1, 'defaultAvatarImg.png', 'Bienvenido a mi perfil!!!', '', 0, 0),
(51, 'nn', 'nn', 'nn', 'n@n.com', 'nnnnn', '$2y$10$gCUboTlF.MzxP1.lHmAcjewPRbWWzfEYRSzflFWu24tBU22/oMj.K', 1, 'http://localhost:81/assets/img/defaultAvatarImg.png', 'Bienvenido a mi perfil!!!', '', 0, 0);

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
(85, 44, 27, 1),
(86, 24, 45, 1),
(89, 45, 1, 1);

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
(25, 1, 67, 0),
(26, 1, 4, 1),
(30, 1, 72, 1),
(31, 1, 110, 1);

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
  ADD KEY `id_publication` (`id_publication`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT de la tabla `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `notifications_type`
--
ALTER TABLE `notifications_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `publications`
--
ALTER TABLE `publications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT de la tabla `publication_comments`
--
ALTER TABLE `publication_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT de la tabla `users_followed`
--
ALTER TABLE `users_followed`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT de la tabla `users_followed_status`
--
ALTER TABLE `users_followed_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `users_likes_publications`
--
ALTER TABLE `users_likes_publications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

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
-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Servidor: artist4all_db:3306
-- Tiempo de generación: 20-04-2021 a las 16:22:53
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
(1, 'defaultAvatarImg.png', 64),
(2, 'lolLogo.png', 64),
(5, 'defaultAvatarImg.png', 73),
(6, 'lolLogo.png', 73),
(7, 'defaultAvatarImg.png', 74),
(41, 'defaultAvatarImg.png', 109),
(42, 'lolLogo.png', 109),
(49, 'ashokaMandalore.jpg', 110),
(50, 'defaultAvatarImg.png', 110),
(51, 'lolLogo.png', 110),
(56, 'lolLogo.png', 90),
(57, 'wallpaperPrequels.jpg', 112);

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
(1, 44, 24, 0, 2, '2021-04-10 21:05:38'),
(2, 24, 45, 0, 2, '2021-04-12 08:12:38'),
(4, 45, 1, 0, 1, '2021-04-12 09:56:15');

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
(1, 1, 'Mi primera publicacion', '2021-03-23 17:27:03', 0),
(2, 1, 'Mi primera publicacion', '2021-03-23 17:27:31', 0),
(4, 1, 'Mi 2da publicaciÃ³n', '2021-03-23 17:39:02', 0),
(9, 1, 'Mi primera publicacion', '2021-03-23 17:45:45', 0),
(64, 1, 'WE', '2021-03-31 20:01:39', 0),
(67, 1, 'qweqreqrewqrewqweqrewqreq', '2021-03-31 20:06:50', 0),
(72, 1, 'mi nueva publi sin img', '2021-03-31 20:20:26', 0),
(73, 1, 'mis img', '2021-03-31 20:20:40', 0),
(74, 1, 'mis img 2', '2021-03-31 20:21:51', 0),
(90, 1, 'stop (edited66) 4', '2021-04-19 16:28:43', 1),
(109, 45, 'prrrrrrr', '2021-04-12 15:04:38', 0),
(110, 24, 'mi nueva publi (edited)', '2021-04-19 14:48:03', 1),
(112, 1, 'mi nueva publi 2', '2021-04-20 13:31:52', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `publication_comments`
--

CREATE TABLE `publication_comments` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `bodyComment` varchar(255) NOT NULL,
  `isEdited` int(11) NOT NULL,
  `comment_date` datetime NOT NULL,
  `id_publication` int(11) NOT NULL,
  `id_comment_reference` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `publication_comments`
--

INSERT INTO `publication_comments` (`id`, `id_user`, `bodyComment`, `isEdited`, `comment_date`, `id_publication`, `id_comment_reference`) VALUES
(1, 1, '12', 0, '2021-04-15 21:19:23', 90, NULL),
(4, 1, 'mi comment 2', 0, '2021-04-20 16:14:14', 112, NULL);

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
(1, 'Alec', 'Sung', 'Yang', 'alec@gmail.com', 'Ritter', '$2y$10$GZoVNs66nfOCB57l/Okfke.FciSU58zjnkTuKJNQhOumHmf4KeM.W', 1, 'defaultAvatarImg.png', 'Bienvenido a mi perfil!!!', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.YWxlY0BnbWFpbC5jb20uJDJ5JDEwJEdab1ZOczY2bmZPQ0I1N2wvT2tma2UuRmNpU1U1OHpqbmtUdUtKTlFoT3VtSG1mNEtlTS5XLnN4bjRrWl9QUUo=.GjVrlzPHWhuYesbxnqyPlZ3AIrT4gG9b1MiNUtrb0NY=', 1, 0),
(24, 'usu1', 'usu1', 'usu1', 'usu1@gmail.com', 'usu1', '$2y$10$iOb/TzHoIshx2HBjKSY1hecuScSHzKnZ.zwyBLUcGFo/a0t4vCus.', 1, 'defaultAvatarImg.png', 'Bienvenido a mi perfil!!! ', '', 1, 0),
(27, 'usu3', 'usu3', 'usu3', 'usu3@gmail.com', 'usu3', '$2y$10$YRenRAON2qbVUBRrPvJW6eyc2DYM8QgtsXjL37v7JaMVLtNWK331G', 0, 'lolLogo.png', 'Bienvenido a mi perfil!!! ', '', 0, 0),
(34, 'Usu2', 'Usu2', 'Usu2', 'usu2@gmail.com', 'Usu2', '$2y$10$7A70dpWM17bjGzUp.GzSBu.cHpJV0l1JU1sZkKEy5DOokxCa54tDa', 1, 'defaultAvatarImg.png', 'Bienvenido a mi perfil!!!', '', 0, 0),
(36, 'Erik ', 'Sung ', 'Yang ', 'erik@gmail.com ', 'No salt added ', '$2y$10$luteHXzZULMCwJTV6Z/sgOnyzGaivSKFt11JL7DLpuaQgy84Fd9wa', 1, 'lolLogo.png', 'Bienvenido a mi perfil!!! ', '', 0, 0),
(44, 'q', 'q', 'q', 'q', 'q', '$2y$10$j9DMh1sMCNxA8RQzQtBX6eUKJ2cxpfSSOlLvIs8INoWW53Uei6SKi', 1, 'defaultAvatarImg.png', 'Bienvenido a mi perfil!!!', '', 0, 0),
(45, 'aa', 'aa', 'aa', 'a@a.com', 'aaaaa', '$2y$10$x0FSNiLIsKTDvXxTu8ruluiUeE6v2XTlFIrBTGjRcGXdn7RmItnde', 1, 'defaultAvatarImg.png', 'Bienvenido a mi perfil!!!', '', 1, 0),
(50, 'yy', 'yy', 'yy', 'y@y.com', 'yyyyy', '$2y$10$9CYw7LXPPHnCrRVx0wOxfeLVU2c4nx5OX4F6M8sFpiD7O2O3j6yRK', 1, 'defaultAvatarImg.png', 'Bienvenido a mi perfil!!!', '', 0, 0),
(51, 'nn', 'nn', 'nn', 'n@n.com', 'nnnnn', '$2y$10$gCUboTlF.MzxP1.lHmAcjewPRbWWzfEYRSzflFWu24tBU22/oMj.K', 1, 'http://localhost:81/assets/img/defaultAvatarImg.png', 'Bienvenido a mi perfil!!!', '', 0, 0);

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
(85, 44, 27, 1),
(86, 24, 45, 1),
(89, 45, 1, 1);

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
(25, 1, 67, 0),
(26, 1, 4, 1),
(30, 1, 72, 1),
(31, 1, 110, 1);

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
  ADD KEY `id_publication` (`id_publication`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT de la tabla `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `notifications_type`
--
ALTER TABLE `notifications_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `publications`
--
ALTER TABLE `publications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT de la tabla `publication_comments`
--
ALTER TABLE `publication_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT de la tabla `users_followed`
--
ALTER TABLE `users_followed`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT de la tabla `users_followed_status`
--
ALTER TABLE `users_followed_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `users_likes_publications`
--
ALTER TABLE `users_likes_publications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

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
-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Servidor: artist4all_db:3306
-- Tiempo de generación: 20-04-2021 a las 16:22:53
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
(1, 'defaultAvatarImg.png', 64),
(2, 'lolLogo.png', 64),
(5, 'defaultAvatarImg.png', 73),
(6, 'lolLogo.png', 73),
(7, 'defaultAvatarImg.png', 74),
(41, 'defaultAvatarImg.png', 109),
(42, 'lolLogo.png', 109),
(49, 'ashokaMandalore.jpg', 110),
(50, 'defaultAvatarImg.png', 110),
(51, 'lolLogo.png', 110),
(56, 'lolLogo.png', 90),
(57, 'wallpaperPrequels.jpg', 112);

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
(1, 44, 24, 0, 2, '2021-04-10 21:05:38'),
(2, 24, 45, 0, 2, '2021-04-12 08:12:38'),
(4, 45, 1, 0, 1, '2021-04-12 09:56:15');

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
(1, 1, 'Mi primera publicacion', '2021-03-23 17:27:03', 0),
(2, 1, 'Mi primera publicacion', '2021-03-23 17:27:31', 0),
(4, 1, 'Mi 2da publicaciÃ³n', '2021-03-23 17:39:02', 0),
(9, 1, 'Mi primera publicacion', '2021-03-23 17:45:45', 0),
(64, 1, 'WE', '2021-03-31 20:01:39', 0),
(67, 1, 'qweqreqrewqrewqweqrewqreq', '2021-03-31 20:06:50', 0),
(72, 1, 'mi nueva publi sin img', '2021-03-31 20:20:26', 0),
(73, 1, 'mis img', '2021-03-31 20:20:40', 0),
(74, 1, 'mis img 2', '2021-03-31 20:21:51', 0),
(90, 1, 'stop (edited66) 4', '2021-04-19 16:28:43', 1),
(109, 45, 'prrrrrrr', '2021-04-12 15:04:38', 0),
(110, 24, 'mi nueva publi (edited)', '2021-04-19 14:48:03', 1),
(112, 1, 'mi nueva publi 2', '2021-04-20 13:31:52', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `publication_comments`
--

CREATE TABLE `publication_comments` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `bodyComment` varchar(255) NOT NULL,
  `isEdited` int(11) NOT NULL,
  `comment_date` datetime NOT NULL,
  `id_publication` int(11) NOT NULL,
  `id_comment_reference` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `publication_comments`
--

INSERT INTO `publication_comments` (`id`, `id_user`, `bodyComment`, `isEdited`, `comment_date`, `id_publication`, `id_comment_reference`) VALUES
(1, 1, '12', 0, '2021-04-15 21:19:23', 90, NULL),
(4, 1, 'mi comment 2', 0, '2021-04-20 16:14:14', 112, NULL);

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
(1, 'Alec', 'Sung', 'Yang', 'alec@gmail.com', 'Ritter', '$2y$10$GZoVNs66nfOCB57l/Okfke.FciSU58zjnkTuKJNQhOumHmf4KeM.W', 1, 'defaultAvatarImg.png', 'Bienvenido a mi perfil!!!', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.YWxlY0BnbWFpbC5jb20uJDJ5JDEwJEdab1ZOczY2bmZPQ0I1N2wvT2tma2UuRmNpU1U1OHpqbmtUdUtKTlFoT3VtSG1mNEtlTS5XLnN4bjRrWl9QUUo=.GjVrlzPHWhuYesbxnqyPlZ3AIrT4gG9b1MiNUtrb0NY=', 1, 0),
(24, 'usu1', 'usu1', 'usu1', 'usu1@gmail.com', 'usu1', '$2y$10$iOb/TzHoIshx2HBjKSY1hecuScSHzKnZ.zwyBLUcGFo/a0t4vCus.', 1, 'defaultAvatarImg.png', 'Bienvenido a mi perfil!!! ', '', 1, 0),
(27, 'usu3', 'usu3', 'usu3', 'usu3@gmail.com', 'usu3', '$2y$10$YRenRAON2qbVUBRrPvJW6eyc2DYM8QgtsXjL37v7JaMVLtNWK331G', 0, 'lolLogo.png', 'Bienvenido a mi perfil!!! ', '', 0, 0),
(34, 'Usu2', 'Usu2', 'Usu2', 'usu2@gmail.com', 'Usu2', '$2y$10$7A70dpWM17bjGzUp.GzSBu.cHpJV0l1JU1sZkKEy5DOokxCa54tDa', 1, 'defaultAvatarImg.png', 'Bienvenido a mi perfil!!!', '', 0, 0),
(36, 'Erik ', 'Sung ', 'Yang ', 'erik@gmail.com ', 'No salt added ', '$2y$10$luteHXzZULMCwJTV6Z/sgOnyzGaivSKFt11JL7DLpuaQgy84Fd9wa', 1, 'lolLogo.png', 'Bienvenido a mi perfil!!! ', '', 0, 0),
(44, 'q', 'q', 'q', 'q', 'q', '$2y$10$j9DMh1sMCNxA8RQzQtBX6eUKJ2cxpfSSOlLvIs8INoWW53Uei6SKi', 1, 'defaultAvatarImg.png', 'Bienvenido a mi perfil!!!', '', 0, 0),
(45, 'aa', 'aa', 'aa', 'a@a.com', 'aaaaa', '$2y$10$x0FSNiLIsKTDvXxTu8ruluiUeE6v2XTlFIrBTGjRcGXdn7RmItnde', 1, 'defaultAvatarImg.png', 'Bienvenido a mi perfil!!!', '', 1, 0),
(50, 'yy', 'yy', 'yy', 'y@y.com', 'yyyyy', '$2y$10$9CYw7LXPPHnCrRVx0wOxfeLVU2c4nx5OX4F6M8sFpiD7O2O3j6yRK', 1, 'defaultAvatarImg.png', 'Bienvenido a mi perfil!!!', '', 0, 0),
(51, 'nn', 'nn', 'nn', 'n@n.com', 'nnnnn', '$2y$10$gCUboTlF.MzxP1.lHmAcjewPRbWWzfEYRSzflFWu24tBU22/oMj.K', 1, 'http://localhost:81/assets/img/defaultAvatarImg.png', 'Bienvenido a mi perfil!!!', '', 0, 0);

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
(85, 44, 27, 1),
(86, 24, 45, 1),
(89, 45, 1, 1);

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
(25, 1, 67, 0),
(26, 1, 4, 1),
(30, 1, 72, 1),
(31, 1, 110, 1);

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
  ADD KEY `id_publication` (`id_publication`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT de la tabla `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `notifications_type`
--
ALTER TABLE `notifications_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `publications`
--
ALTER TABLE `publications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT de la tabla `publication_comments`
--
ALTER TABLE `publication_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT de la tabla `users_followed`
--
ALTER TABLE `users_followed`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT de la tabla `users_followed_status`
--
ALTER TABLE `users_followed_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `users_likes_publications`
--
ALTER TABLE `users_likes_publications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

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
