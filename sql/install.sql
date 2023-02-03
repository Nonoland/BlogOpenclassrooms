create database if not exists lamp;

use lamp;

create table configuration
(
    id int unsigned auto_increment primary key,
    name varchar(264) not null,
    value text,
    date_add datetime not null default current_timestamp on update current_timestamp,
    date_upd datetime not null default current_timestamp on update current_timestamp
) collate = utf8mb4_unicode_ci;

create table contact
(
    id int unsigned auto_increment primary key,
    title varchar(254) not null,
    message text not null,
    email varchar(254) not null,
    date_add datetime not null default current_timestamp on update current_timestamp
) collate = utf8mb4_unicode_ci;

create table user
(
    id int unsigned auto_increment primary key,
    username varchar(254) not null,
    lastname varchar(254) not null,
    firstname varchar(254) not null,
    email varchar(254) not null,
    role text not null,
    date_add datetime not null default current_timestamp on update current_timestamp,
    date_upd datetime not null default current_timestamp on update current_timestamp
);

create table user_ip
(
    ip text not null,
    date_add datetime not null default current_timestamp on update current_timestamp,
    id_user int(10) unsigned,
    constraint FK_USERIP foreign key (id_user) references user(id)
) collate = utf8mb4_unicode_ci;

create table post
(
    id int(10) unsigned auto_increment primary key,
    title text not null,
    body text not null,
    date_add datetime not null default current_timestamp on update current_timestamp,
    date_upd datetime not null default current_timestamp on update current_timestamp,
    views int,
    id_user int unsigned not null,
    constraint FK_POSTUSER foreign key (id_user) references user(id)
) collate = utf8mb4_unicode_ci;

create table comment
(
    id int unsigned auto_increment primary key,
    title varchar(254) not null,
    body text not null,
    date_add datetime not null default current_timestamp on update current_timestamp,
    valid int(1) unsigned,
    id_parent int unsigned,
    id_post int unsigned not null,
    id_user int unsigned not null,
    constraint FK_COMMENTPARENT foreign key (id_parent) references comment(id),
    constraint FK_COMMENTPOST foreign key (id_post) references post(id),
    constraint FK_COMMENTUSER foreign key (id_user) references user(id)
) collate = utf8mb4_unicode_ci;
