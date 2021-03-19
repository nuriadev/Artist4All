CREATE TABLE `users` (
  `id_user` int(11) PRIMARY KEY AUTO_INCREMENT,
  `name_user` varchar(50) NOT NULL,
  `surname1` varchar(50) NOT NULL,
  `surname2` varchar(50) NOT NULL,
  `email` varchar(90) NOT NULL,
  `username` varchar(50) NOT NULL,
  `passwd` varchar(120) NOT NULL,
  `n_followers` int NOT NULL,
  `type_user` int(11) NOT NULL,
  `img` varchar(300) NOT NULL,
  `aboutMe` varchar(155) NOT NULL,
  `token` varchar(600) NOT NULL,
  `deleted` tinyint(1) NOT NULL
);
