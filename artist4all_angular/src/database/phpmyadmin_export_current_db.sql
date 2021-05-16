-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Servidor: artist4all_db:3306
-- Tiempo de generación: 16-05-2021 a las 23:08:57
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
-- Estructura de tabla para la tabla `contact`
--

CREATE TABLE `contact` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `surname1` varchar(50) NOT NULL,
  `email` varchar(60) NOT NULL,
  `phone` char(9) DEFAULT NULL,
  `bodyMessage` varchar(510) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `contact`
--

INSERT INTO `contact` (`id`, `name`, `surname1`, `email`, `phone`, `bodyMessage`) VALUES
(1, 'qq', 'qq', 'alec@gmail.com', '', 'Don Quijote de la Mancha, cuyo tÃ­tulo original es El ingenioso hidalgo don Quijote de La Mancha, es una novela del subgÃ©nero literario burlesco. Fue escrita por el espaÃ±ol Miguel de Cervantes Saavedra (1547-1616) y publicada en dos entregas: El primer tomo en el aÃ±o 1605 y el segundo en 1615.\n\nSe considera Don Quijote de la Mancha la primera novela moderna y obra cumbre de la literatura espaÃ±ola. En ella, mediante la parodia de las novelas de caballerÃ­a,'),
(2, 'qwe', 'qweq', 'usu3@gmail.com', '999999999', 'Don Quijote de la Mancha, cuyo tÃ­tulo original es El ingenioso hidalgo don Quijote de La Mancha, es una novela del subgÃ©nero literario burlesco. Fue escrita por el espaÃ±ol Miguel de Cervantes Saavedra (1547-1616) y publicada en dos entregas: El primer tomo en el aÃ±o 1605 y el segundo en 1615.\n\nSe considera Don Quijote de la Mancha la primera novela moderna y obra cumbre de la literatura espaÃ±ola. En ella, mediante la parodia de las novelas de caballerÃ­a,'),
(3, 'us', 'us', 'alec@gmail.com', '', 'Don Quijote de la Mancha, cuyo tÃ­tulo original es El ingenioso hidalgo don Quijote de La Mancha, es una novela del subgÃ©nero literario burlesco. Fue escrita por el espaÃ±ol Miguel de Cervantes Saavedra (1547-1616) y publicada en dos entregas: El primer tomo en el aÃ±o 1605 y el segundo en 1615.\n\nSe considera Don Quijote de la Mancha la primera novela moderna y obra cumbre de la literatura espaÃ±ola. En ella, mediante la parodia de las novelas de caballerÃ­a,');

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
(56, 'lolLogo.png', 90),
(63, 'ashokaMandalore.jpg', 113),
(64, 'defaultAvatarImg.png', 113),
(82, 'defaultAvatarImg.png', 119),
(83, 'descarga.jpeg', 119),
(84, 'ebcdfd67ddb0c76511f224f7ff340345.jpg', 119),
(85, 'foto.jpeg', 119),
(86, 'image.png', 119),
(87, 'lolLogo.png', 119),
(88, 'perfil.jpeg', 119),
(89, 'wallpaperPrequels.jpg', 119);

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
(11, 95, 1, 1, 2, '2021-05-01 15:54:14'),
(12, 95, 1, 1, 1, '2021-05-01 15:54:49'),
(13, 1, 95, 1, 3, '2021-05-01 15:54:49'),
(15, 1, 95, 0, 3, '2021-05-16 14:49:30'),
(17, 50, 1, 1, 1, '2021-05-01 15:54:14'),
(22, 1, 44, 0, 1, '2021-05-16 17:52:30'),
(23, 1, 92, 0, 1, '2021-05-16 17:55:56'),
(24, 1, 45, 0, 2, '2021-05-16 18:02:26'),
(25, 1, 27, 0, 1, '2021-05-16 18:50:26'),
(26, 98, 36, 0, 1, '2021-05-16 21:30:29');

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
  `bodyPublication` varchar(260) NOT NULL,
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
(90, 45, 'stop (edited66) 4', '2021-04-19 16:28:43', 1),
(109, 45, 'prrrrrrr', '2021-04-12 15:04:38', 0),
(113, 27, 'ppppppppppppppppppppppppppppppppppppp', '2021-05-01 16:03:57', 1),
(115, 1, 'Abarca los primeros ocho capÃ­tulos del primer tomo del libro. AquÃ­ comienza la supuesta locura de don Quijote. El protagonista decide convertirse en un caballero andante llevando a su viejo caballo Rocinante a recorrer EspaÃ±a.\n', '2021-05-01 16:56:58', 1),
(116, 45, 'Abarca los primeros ocho capÃ­tulos del primer tomo del libro. AquÃ­ comienza la supuesta locura de don Quijote. El protagonista decide convertirse en un caballero andante llevando a su viejo caballo Rocinante a recorrer EspaÃ±a.\n', '2021-05-01 16:58:51', 1),
(118, 36, 'qwerqrewqweqreqweq', '2021-05-01 16:17:58', 0),
(119, 1, 'Don Quijote de la Mancha, cuyo tÃ­tulo original es El ingenioso hidalgo don Quijote de La Mancha, es una novela del subgÃ©nero literario burlesco. Fue escrita por el espaÃ±ol Miguel de Cervantes Saavedra (1547-1616) y publicada en dos entregas: El primer tom', '2021-05-16 20:26:34', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `publication_comments`
--

CREATE TABLE `publication_comments` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `bodyComment` varchar(260) NOT NULL,
  `isEdited` int(11) NOT NULL,
  `comment_date` datetime NOT NULL,
  `id_publication` int(11) NOT NULL,
  `id_comment_reference` int(11) NOT NULL,
  `id_user_reference` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `publication_comments`
--

INSERT INTO `publication_comments` (`id`, `id_user`, `bodyComment`, `isEdited`, `comment_date`, `id_publication`, `id_comment_reference`, `id_user_reference`) VALUES
(72, 1, '1st comment', 0, '2021-04-23 11:55:38', 112, 0, NULL),
(74, 44, '2nd comment', 0, '2021-04-23 11:56:46', 112, 72, 1),
(95, 1, '3rd comment 2', 1, '2021-05-09 14:25:05', 112, 72, 1),
(96, 1, '4th comment', 0, '2021-04-26 11:48:13', 112, 72, 44),
(97, 1, '5th comment\n', 0, '2021-04-26 11:48:38', 112, 72, 44),
(98, 1, '6th comment', 0, '2021-04-26 11:48:53', 112, 72, 1),
(101, 1, '7th comment', 0, '2021-04-26 11:52:02', 112, 0, NULL),
(102, 1, '8th comment', 0, '2021-04-26 11:58:54', 112, 0, NULL),
(103, 1, '9th comment', 0, '2021-04-26 11:59:03', 112, 102, 1),
(104, 1, '10th comment', 0, '2021-04-26 11:59:19', 112, 102, 1),
(106, 1, '11th comment', 0, '2021-04-26 12:04:41', 112, 101, 1),
(107, 1, '12th comment', 0, '2021-04-26 12:04:55', 112, 0, NULL),
(108, 1, '13th comment', 0, '2021-04-26 12:05:17', 112, 107, 1),
(109, 1, '14th comment', 0, '2021-04-26 12:06:59', 112, 0, NULL),
(110, 1, '15th comment', 0, '2021-04-26 12:07:11', 112, 0, NULL),
(127, 1, 'Don Quijote de la Mancha, cuyo tÃ­tulo original es El ingenioso hidalgo don Quijote de La Mancha, es una novela del subgÃ©nero literario burlesco. Fue escrita por el espaÃ±ol Miguel de Cervantes Saavedra (1547-1616) y publicada en dos entregas: El primer toq', 1, '2021-05-16 12:50:28', 110, 0, NULL),
(139, 1, 'Don Quijote de la Mancha, cuyo tÃ­tulo original es El ingenioso hidalgo don Quijote de La Mancha, es una novela del subgÃ©nero literario burlesco. Fue escrita por el espaÃ±ol Miguel de Cervantes Saavedra (1547-1616) y publicada en dos entregas: El primer to8', 1, '2021-05-16 14:22:11', 110, 0, NULL),
(141, 1, 'Don Quijote de la Mancha, cuyo tÃ­tulo original es El ingenioso hidalgo don Quijote de La Mancha, es una novela del subgÃ©nero literario burlesco. Fue escrita por el espaÃ±ol Miguel de Cervantes Saavedra (1547-1616) y publicada en dos entregas: El primer tp3', 0, '2021-05-16 14:23:35', 110, 139, 1);

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
(1, 'Alec', 'Sung', 'Yang', 'alec@gmail.com', 'Ritter', '$2y$10$PevXAIXXbPdVBO/b5sD/HeWPxNt2fgxTOTDbecosExL7YkS3/VjH6', 1, 'defaultAvatarImg.png', 'Bienvenido a mi perfil!!!', '', 1, 0),
(24, 'usu1', 'usu1', 'usu1', 'usu1@gmail.com', 'usu1', '$2y$10$iOb/TzHoIshx2HBjKSY1hecuScSHzKnZ.zwyBLUcGFo/a0t4vCus.', 1, 'defaultAvatarImg.png', 'Bienvenido a mi perfil!!! ', '', 1, 0),
(27, 'usu3', 'usu3', 'usu3', 'usu3@gmail.com', 'usu3', '$2y$10$YRenRAON2qbVUBRrPvJW6eyc2DYM8QgtsXjL37v7JaMVLtNWK331G', 0, 'lolLogo.png', 'Bienvenido a mi perfil!!! ', '', 0, 0),
(34, 'Usu2', 'Usu2', 'Usu2', 'usu2@gmail.com', 'Usu2', '$2y$10$7A70dpWM17bjGzUp.GzSBu.cHpJV0l1JU1sZkKEy5DOokxCa54tDa', 1, 'defaultAvatarImg.png', 'Bienvenido a mi perfil!!!', '', 0, 0),
(36, 'Erik ', 'Sung ', 'Yang ', 'erik@gmail.com ', 'No salt added ', '$2y$10$luteHXzZULMCwJTV6Z/sgOnyzGaivSKFt11JL7DLpuaQgy84Fd9wa', 1, 'lolLogo.png', 'Bienvenido a mi perfil!!! ', '', 0, 0),
(44, 'q', 'q', 'q', 'q', 'q', '$2y$10$j9DMh1sMCNxA8RQzQtBX6eUKJ2cxpfSSOlLvIs8INoWW53Uei6SKi', 1, 'defaultAvatarImg.png', 'Bienvenido a mi perfil!!!', '', 0, 0),
(45, 'aa', 'aa', 'aa', 'a@a.com', 'aaaaa', '$2y$10$x0FSNiLIsKTDvXxTu8ruluiUeE6v2XTlFIrBTGjRcGXdn7RmItnde', 1, 'defaultAvatarImg.png', 'Bienvenido a mi perfil!!!', '', 1, 0),
(50, 'yy', 'yy', 'yy', 'y@y.com', 'yyyyy', '$2y$10$9CYw7LXPPHnCrRVx0wOxfeLVU2c4nx5OX4F6M8sFpiD7O2O3j6yRK', 1, 'defaultAvatarImg.png', 'Bienvenido a mi perfil!!!', '', 0, 0),
(92, 'ii', 'ii', 'ii', 'i@i.com', 'iiiii', '$2y$10$ZKjR6N79LWkUkWZaNOdx0.g8p/yvBWzXAtZdXKSa.KHrXA92FCDMa', 0, 'lolLogo.png', 'Bienvenido a mi perfil!!!', '', 0, 0),
(95, 'qq', 'qq', 'qq', 'q@q.com', 'qqqqq', '$2y$10$kDonB50bVxdHEUsNG1AeyO6iMAuF63gGTDuC0Qmw5enikVJQFIPW.', 1, 'defaultAvatarImg.png', 'Bienvenido a mi perfil!!!', '', 0, 0),
(97, 'mm', 'mm', 'mm', 'm@m.com', 'mmmmm', '$2y$10$PWlAn/Au13e9Km1Rwf4ASOWXqY3lhiUwpUV4l8iEPon6KVvBvNHt.', 1, 'defaultAvatarImg.png', 'Bienvenido a mi perfil!!!', '', 0, 0),
(98, 'rr', 'rr', 'rr', 'r@r.com', 'rrrrr', '$2y$10$GuR5.XyjXFv5DA5bMi3Gg./1XT1Sw6W0FS2YVM.hSpkZDiC6qCYiW', 1, 'defaultAvatarImg.png', 'Bienvenido a mi perfil!!!', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.ckByLmNvbS4kMnkkMTAkR3VSNS5YeWpYRnY1REE1Yk1pM0dnLi8xWFQxU3c2VzBGUzJZVk0uaFNwa1pEaUM2cUNZaVcuZmI5ZTI4YWJlOTE0ZDg3YWE1MDk1MDQ2MjhhYzQ4OTU1YTI5ZGZjY2U0NDIzZGRjNzU5YTA2M2NhNTY5OGQxY2U0OGIxMGMwM2ZkOGY5Zjc=.klQDUG7ZDm+gwqIk0xourNpDvnmzsO0ijgZptVNyqao=', 0, 0);

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
(89, 45, 1, 1),
(102, 95, 1, 3),
(103, 36, 50, 3),
(104, 24, 44, 3),
(105, 1, 45, 3),
(106, 24, 45, 3),
(107, 24, 92, 3),
(108, 36, 44, 3),
(109, 36, 27, 3),
(114, 1, 92, 1),
(118, 1, 44, 1),
(121, 1, 27, 1),
(122, 98, 36, 1);

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
(32, 36, 119, 1),
(33, 92, 119, 1),
(34, 97, 119, 1),
(35, 92, 2, 1),
(36, 97, 2, 1),
(37, 97, 118, 1),
(38, 1, 118, 1),
(39, 1, 116, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`id`);

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
  ADD KEY `id_user_reference` (`id_user_reference`);

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
-- AUTO_INCREMENT de la tabla `contact`
--
ALTER TABLE `contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `imgs_publications`
--
ALTER TABLE `imgs_publications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT de la tabla `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de la tabla `notifications_type`
--
ALTER TABLE `notifications_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `publications`
--
ALTER TABLE `publications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123;

--
-- AUTO_INCREMENT de la tabla `publication_comments`
--
ALTER TABLE `publication_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=144;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- AUTO_INCREMENT de la tabla `users_followed`
--
ALTER TABLE `users_followed`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123;

--
-- AUTO_INCREMENT de la tabla `users_followed_status`
--
ALTER TABLE `users_followed_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `users_likes_publications`
--
ALTER TABLE `users_likes_publications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

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
