create database if not exists lamp;

use lamp;

create table configuration
(
    id int unsigned auto_increment primary key,
    name varchar(264) not null,
    value text null,
    date_add datetime default current_timestamp() not null,
    date_upd datetime default current_timestamp() not null on update current_timestamp(),
    constraint configuration_name_uindex unique (name)
) collate = utf8mb4_unicode_ci;

create table contact
(
    id int unsigned auto_increment primary key,
    title varchar(254) not null,
    message text not null,
    email varchar(254) not null,
    date_add datetime default current_timestamp() not null on update current_timestamp()
) collate = utf8mb4_unicode_ci;

create table post
(
    id int unsigned auto_increment primary key,
    slug text null,
    title text not null,
    description text null,
    body text not null,
    date_add datetime default current_timestamp() not null on update current_timestamp(),
    date_upd datetime default current_timestamp() not null on update current_timestamp(),
    views int null,
    id_user int unsigned not null
) collate = utf8mb4_unicode_ci;

create index FK_POSTUSER on post (id_user);

create table user
(
    id int unsigned auto_increment primary key,
    username varchar(254) not null,
    lastname varchar(254) not null,
    firstname varchar(254) not null,
    email varchar(254) not null,
    password text null,
    roles text default '{}' not null,
    date_add datetime default current_timestamp() not null,
    date_upd datetime default current_timestamp() not null on update current_timestamp(),
    expire_session datetime null,
    forgotten_password text null,
    active int unsigned null
);

create table comment
(
    id int unsigned auto_increment primary key,
    title varchar(254)  not null,
    body text not null,
    date_add datetime default current_timestamp() not null on update current_timestamp(),
    date_upd datetime default current_timestamp() null,
    valid int(1) unsigned null,
    id_parent int unsigned null,
    id_post int unsigned not null,
    id_user int unsigned not null,
    constraint FK_COMMENTPARENT foreign key (id_parent) references comment (id) on delete set null,
    constraint FK_COMMENTPOST foreign key (id_post) references post (id),
    constraint FK_COMMENTUSER foreign key (id_user) references user (id)
) collate = utf8mb4_unicode_ci;


