drop database artist4alldb;
create database artist4alldb;
use artist4alldb;

create table usuarios (
id_usuario int primary key auto_increment,
tipo int not null,
nombre varchar(50) not null,
apellido1 varchar(50) not null,
apellido2 varchar(50) not null,
email varchar(90) not null,
username varchar(50) not null,
passwd varchar(120) not null,
deleted int(1) not null
);

create table publicaciones (
id_publicacion int primary key auto_increment,
id_usuario int not null,
url_post varchar(255) not null,
descripcion varchar(255) not null,
fecha_subida datetime not null,
FOREIGN KEY (id_usuario)
			REFERENCES usuarios (id_usuario)
            ON DELETE CASCADE ON UPDATE CASCADE
);

create table usuarios_seguidos (
id_logfollow int primary key auto_increment,
id_seguidor int not null,
id_persona_seguida int not null,
FOREIGN KEY (id_seguidor) 
	        REFERENCES usuarios (id_usuario) 
	        ON DELETE CASCADE ON UPDATE CASCADE,
FOREIGN KEY (id_persona_seguida) 
	        REFERENCES usuarios (id_usuario) 
	        ON DELETE CASCADE ON UPDATE CASCADE
);

create table artistas (
id_artista int primary key auto_increment,
tipo varchar(50) not null,
nombre_artistico varchar(50) not null,
id_usuario int not null,
FOREIGN KEY (id_usuario) 
	        REFERENCES usuarios (id_usuario) 
	        ON DELETE CASCADE ON UPDATE CASCADE
); 

create table logs (
id_log int primary key auto_increment,
ip varchar(11) not null,
fecha datetime not null,
tipo varchar(40) not null,
id_usuario int not null,
FOREIGN KEY (id_usuario) 
	        REFERENCES usuarios (id_usuario) 
	        ON UPDATE CASCADE
); 

create table media_usuarios (
id_media_usu int primary key auto_increment,
url varchar(150) not null,
id_usuario int not null,
FOREIGN KEY (id_usuario) 
	        REFERENCES usuarios (id_usuario) 
	        ON DELETE CASCADE ON UPDATE CASCADE
); 

create table valoraciones_usuarios (
id_valoracion int primary key auto_increment,
comentario varchar(255),
valoracion int not null,
id_usuario int not null,
FOREIGN KEY (id_usuario) 
	        REFERENCES usuarios (id_usuario) 
	        ON DELETE CASCADE ON UPDATE CASCADE
); 

create table mensajes (
id_mensaje int primary key auto_increment,
contenido varchar(255) not null,
fecha datetime not null,
leido boolean not null,
id_emisor int not null,
id_receptor int not null,
FOREIGN KEY (id_emisor) 
	        REFERENCES usuarios (id_usuario) 
	        ON UPDATE CASCADE,
FOREIGN KEY (id_receptor) 
	        REFERENCES usuarios (id_usuario) 
	        ON UPDATE CASCADE
); 

create table productos (
id_producto int primary key auto_increment,
nombre varchar(70) not null,
precio double not null,
stock int not null,
descripcion varchar(255),
fecha datetime not null,
id_propietario int not null,
FOREIGN KEY (id_propietario) 
	        REFERENCES usuarios (id_usuario) 
	        ON DELETE CASCADE ON UPDATE CASCADE
);

create table valoraciones_productos (
id_valoracion int primary key auto_increment,
comentario varchar(255),
valoracion int not null,
id_producto int not null,
id_usuario int not null,
FOREIGN KEY (id_producto) 
	        REFERENCES productos (id_producto) 
	        ON DELETE CASCADE ON UPDATE CASCADE,
FOREIGN KEY (id_usuario) 
	        REFERENCES usuarios (id_usuario) 
	        ON UPDATE CASCADE
); 

create table pedidos_details (
id_pedido_detail int primary key auto_increment,
cantidad int not null,
id_producto int not null,
FOREIGN KEY (id_producto) 
	        REFERENCES productos (id_producto) 
	        ON UPDATE CASCADE
);

create table pedidos (
id_pedido int primary key auto_increment,
precio double not null,
estado varchar(30) not null,
fecha datetime not null,
id_comprador int not null,
id_detail int not null,
FOREIGN KEY (id_comprador) 
	        REFERENCES usuarios (id_usuario) 
	        ON UPDATE CASCADE,
FOREIGN KEY (id_detail) 
	        REFERENCES pedidos_details (id_pedido_detail) 
	        ON DELETE CASCADE ON UPDATE CASCADE
); 

create table historico (
id_registro int primary key auto_increment,
fecha datetime not null,
precio double not null,
metodo_pago varchar(60) not null,
id_comprador int not null,
id_vendedor int not null,
id_producto int not null,
FOREIGN KEY (id_comprador) 
	        REFERENCES usuarios (id_usuario) 
	        ON UPDATE CASCADE,
FOREIGN KEY (id_vendedor) 
	        REFERENCES usuarios (id_usuario) 
	        ON UPDATE CASCADE,
FOREIGN KEY (id_producto) 
	        REFERENCES productos (id_producto) 
	        ON UPDATE CASCADE
);

create table media_productos (
id_media_usu int primary key auto_increment,
url varchar(150) not null,
id_producto int not null,
FOREIGN KEY (id_producto) 
	        REFERENCES productos (id_producto) 
	        ON DELETE CASCADE ON UPDATE CASCADE
); 