drop database artist4alldb;
create database artist4alldb;
use artist4alldb;

create table users (
id_user int primary key auto_increment,
name_user varchar(50) not null,
surname1 varchar(50) not null,
surname2 varchar(50) not null,
email varchar(90) not null,
username varchar(50) not null,
passwd varchar(120) not null,
type_user int not null,
deleted int(1) not null
);

create table publications (
id_publication int primary key auto_increment,
id_user int not null,
url_post varchar(255) not null,
description_publication varchar(255) not null,
upload_date datetime not null,
FOREIGN KEY (id_user)
			REFERENCES users (id_user)
            ON DELETE CASCADE ON UPDATE CASCADE
);

create table users_followed (
id_logfollow int primary key auto_increment,
id_follower int not null,
id_followed int not null,
FOREIGN KEY (id_follower)
	        REFERENCES users (id_user)
	        ON DELETE CASCADE ON UPDATE CASCADE,
FOREIGN KEY (id_followed)
	        REFERENCES users (id_user)
	        ON DELETE CASCADE ON UPDATE CASCADE
);

create table artists (
id_artist int primary key auto_increment,
type_artist varchar(50) not null,
artistic_name varchar(50) not null,
id_user int not null,
FOREIGN KEY (id_user)
	        REFERENCES users (id_user)
	        ON DELETE CASCADE ON UPDATE CASCADE
);

create table logs (
id_log int primary key auto_increment,
ip varchar(11) not null,
date_log datetime not null,
type_log varchar(40) not null,
id_user int not null,
FOREIGN KEY (id_user)
	        REFERENCES users (id_user)
	        ON UPDATE CASCADE
);

create table media_users (
id_media int primary key auto_increment,
url_media varchar(150) not null,
id_user int not null,
FOREIGN KEY (id_user)
	        REFERENCES users (id_user)
	        ON DELETE CASCADE ON UPDATE CASCADE
);

create table valuations_of_users (
id_valuation int primary key auto_increment,
comment varchar(255),
valuation int not null,
id_user int not null,
FOREIGN KEY (id_user)
	        REFERENCES users (id_user)
	        ON DELETE CASCADE ON UPDATE CASCADE
);

create table messages (
id_message int primary key auto_increment,
content varchar(255) not null,
date_message datetime not null,
read_message boolean not null,
id_emisor int not null,
id_receiver int not null,
FOREIGN KEY (id_emisor)
	        REFERENCES users (id_user)
	        ON UPDATE CASCADE,
FOREIGN KEY (id_receiver)
          REFERENCES users (id_user)
	        ON UPDATE CASCADE
);

create table products (
id_product int primary key auto_increment,
name_product varchar(70) not null,
price double not null,
stock int not null,
description_product varchar(255),
date_upload datetime not null,
id_owner int not null,
FOREIGN KEY (id_owner)
	        REFERENCES users (id_user)
	        ON DELETE CASCADE ON UPDATE CASCADE
);

create table valuations_of_products (
id_valuation int primary key auto_increment,
comment varchar(255),
valuation int not null,
id_product int not null,
id_user int not null,
FOREIGN KEY (id_product)
	        REFERENCES products (id_product)
	        ON DELETE CASCADE ON UPDATE CASCADE,
FOREIGN KEY (id_user)
	        REFERENCES users (id_user)
	        ON UPDATE CASCADE
);

create table order_details (
id_order_detail int primary key auto_increment,
quantity int not null,
id_product int not null,
FOREIGN KEY (id_product)
	        REFERENCES products (id_product)
	        ON UPDATE CASCADE
);

create table orders (
id_order int primary key auto_increment,
price double not null,
state_order varchar(30) not null,
date_upload datetime not null,
id_purchaser int not null,
id_order_detail int not null,
FOREIGN KEY (id_purchaser)
	        REFERENCES users (id_user)
	        ON UPDATE CASCADE,
FOREIGN KEY (id_order_detail)
	        REFERENCES order_details (id_order_detail)
	        ON DELETE CASCADE ON UPDATE CASCADE
);

create table historical (
id_register int primary key auto_increment,
date_register datetime not null,
price double not null,
payment_method varchar(60) not null,
id_purchaser int not null,
id_seller int not null,
id_product int not null,
FOREIGN KEY (id_purchaser)
	       REFERENCES users (id_user)
	        ON UPDATE CASCADE,
FOREIGN KEY (id_seller)
	      REFERENCES users (id_user)
	        ON UPDATE CASCADE,
FOREIGN KEY (id_product)
	        REFERENCES products (id_product)
	        ON UPDATE CASCADE
);

create table media_products (
id_media int primary key auto_increment,
url_media varchar(150) not null,
id_product int not null,
FOREIGN KEY (id_product)
	        REFERENCES products (id_product)
	        ON DELETE CASCADE ON UPDATE CASCADE
);
