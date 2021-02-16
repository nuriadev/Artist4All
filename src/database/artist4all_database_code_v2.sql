#create database artist4alldb;

create table usuarios (
id_usuario int primary key auto_increment,
tipo int not null,
nombre varchar(50) not null,
apellido1 varchar(50) not null,
apellido2 varchar(50) not null,
email varchar(90) not null,
username varchar(50) not null,
passwd varchar(120) not null
);

create table artistas (
id_artista int primary key auto_increment,
tipo varchar(50) not null,
nombre_artistico varchar(50) not null,
id_usuario int not null,
constraint idArtista_idUsuario foreign key (id_usuario) references usuarios(id_usuario)
); 

create table logs (
id_log int primary key auto_increment,
ip varchar(11) not null,
fecha datetime not null,
tipo varchar(40) not null,
id_usuario int not null,
constraint idLog_idUsuario foreign key (id_usuario) references usuarios(id_usuario)
); 

create table media_usuarios (
id_media_usu int primary key auto_increment,
url varchar(150) not null,
id_usuario int not null,
constraint idMediaUsu_idUsuario foreign key (id_usuario) references usuarios(id_usuario)
); 

create table valoraciones_usuarios (
id_valoracion int primary key auto_increment,
comentario varchar(255),
valoracion int not null,
id_usuario int not null,
constraint idValoracionUsuario_idUsuario foreign key (id_usuario) references usuarios(id_usuario)
); 

create table mensajes (
id_mensaje int primary key auto_increment,
contenido varchar(255) not null,
fecha datetime not null,
leido boolean not null,
id_emisor int not null,
id_receptor int not null,
constraint idMensaje_idEmisor foreign key (id_emisor) references usuarios(id_usuario),
constraint idMensaje_idReceptor foreign key (id_receptor) references usuarios(id_usuario)
); 

create table productos (
id_producto int primary key auto_increment,
nombre varchar(70) not null,
precio double not null,
stock int not null,
descripcion varchar(255),
fecha datetime not null,
id_propietario int not null,
constraint idProducto_idPropietario foreign key (id_propietario) references usuarios(id_usuario)
);

create table valoraciones_productos (
id_valoracion int primary key auto_increment,
comentario varchar(255),
valoracion int not null,
id_producto int not null,
id_usuario int not null,
constraint idValoracionProducto_idProducto foreign key (id_producto) references productos(id_producto),
constraint idValoracionProducto_idUsuario foreign key (id_usuario) references usuarios(id_usuario)
); 

create table pedidos_details (
id_pedido_detail int primary key auto_increment,
cantidad int not null,
id_producto int not null,
constraint idPedidoDetail_idProducto foreign key (id_producto) references productos(id_producto)
);

create table pedidos (
id_pedido int primary key auto_increment,
precio double not null,
estado varchar(30) not null,
fecha datetime not null,
id_comprador int not null,
id_detail int not null,
constraint idPedido_idComprador foreign key (id_comprador) references usuarios(id_usuario),
constraint idPedido_idDetail foreign key (id_detail) references pedidos_details(id_pedido_detail)
); 

create table historico (
id_registro int primary key auto_increment,
fecha datetime not null,
precio double not null,
metodo_pago varchar(60) not null,
id_comprador int not null,
id_vendedor int not null,
id_producto int not null,
constraint idHistorico_idComprador foreign key (id_comprador) references usuarios(id_usuario),
constraint idHistorico_idVendedor foreign key (id_vendedor) references usuarios(id_usuario),
constraint idHistorico_idProducto foreign key (id_producto) references productos(id_producto)
);

create table media_productos (
id_media_usu int primary key auto_increment,
url varchar(150) not null,
id_producto int not null,
constraint idMediaProducto_idProducto foreign key (id_producto) references productos(id_producto)
); 
