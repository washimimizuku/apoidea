----------------------
-- Apoidea Database --
----------------------
------------------------------------------
-- mysql -u webuser -p < bd_apoidea.sql --
------------------------------------------

--drop database apoidea;
create database apoidea;
use apoidea;

-- Tables for users --
create table tribe (
   id_tribe tinyint unsigned not null unique auto_increment,
   name varchar(255),
   primary key (id_tribe)
);

-- Everything
insert into tribe values ('1', 'Administrator');
-- Manage Site
insert into tribe values ('2', 'Manager');
-- Publish Content
insert into tribe values ('3', 'Publisher');
-- Edit Content
insert into tribe values ('4', 'Editor');
-- Create content
insert into tribe values ('5', 'Author');
-- Frontend access
insert into tribe values ('6', 'User');

create table user (
   id_user mediumint unsigned not null unique auto_increment,
   id_tribe tinyint unsigned not null,
   name varchar(255),
   username varchar(30) not null,
   password char(32) not null,
   primary key (id_user),
   key (id_tribe)
);

insert into user values('1', '1', 'Nuno Barreto', 'nbarr', '78b21db081f166277f645bf06131a8a1');

create table rel_site_user (
   id_site smallint unsigned not null,
   id_user mediumint unsigned not null,
   key (id_site, id_user)
);

insert into rel_site_user values ('0', '1');
insert into rel_site_user values ('1', '2');

-- Table for sites --

create table site (
   id_site smallint unsigned not null unique auto_increment,
   name varchar(255) not null,
   root varchar(255) not null,
   num_columns smallint unsigned,
   header bool,
   footer bool,
   primary key (id_site)
);

-- insert into site values ('1', 'Movimento Vida Nova', '/movimentovidanova.com');

-- Table for categories --

create table category (
   id_category smallint unsigned not null unique auto_increment,
   id_root_category smallint unsigned,
   id_site smallint unsigned,
   name varchar(255) not null,
   stub varchar(255) not null,
   active bool,
   feed bool,
   primary key (id_category),
   key (id_root_category, id_site)
);

-- Table for articles --

-- ATENÇÃO! A(s) categoria(s) do(s) artigo(s) são colocadas na tabela rel_category_article
create table article (
   id_article mediumint unsigned not null unique auto_increment,
   id_site smallint unsigned,
   id_user mediumint unsigned,
   creation_date timestamp,
   title varchar(255),
   subtitle varchar(255),
   teaser blob,
   text blob,
   link varchar(255),
   begin_date timestamp,
   end_date timestamp,
   active bool,
   primary key (id_article),
--   key (id_site, id_user)
   key (id_user)
);

-- Table for article tags --

create table tag (
   id_tag mediumint unsigned not null unique auto_increment,
   name varchar(255) not null unique,
   primary key (id_tag)
);

create table rel_tag_article (
   id_tag mediumint unsigned not null,
   id_article mediumint unsigned not null,
   key (id_tag, id_article)
);

-- Table for article comments --

create table comment (
   id_comment int unsigned not null unique auto_increment,
   id_parent int unsigned,
   id_article mediumint unsigned,
   date timestamp,
   title varchar(255),
   text blob,
   name varchar(255),
   email varchar(255),
   link varchar(255),
   active bool,
   primary key (id_comment),
   key (id_parent, id_article)
);

-- Table to relate articles to categories --

create table rel_category_article (
   id_category smallint unsigned not null,
   id_article mediumint unsigned not null,
   key (id_category, id_article)
);

-- Table to relate articles to media --

create table rel_article_media (
   id_article mediumint unsigned not null,
   id_media mediumint unsigned not null,
   key (id_article, id_media)
);

-- Table to relate articles to articles --

create table rel_article_article (
   id_article mediumint unsigned not null,
   id_related_article mediumint unsigned not null,
   key (id_article, id_related_article)
);

-- Tables for media --

create table media (
   id_media mediumint unsigned not null unique auto_increment,
   id_site smallint unsigned,
   media_type varchar(255),
   name varchar(255),
   location varchar(255),
   key(id_site),
   primary key (id_media)
);

-- Tables for Layout --

create table blueprint (
   id int unsigned not null unique auto_increment,
   id_site smallint unsigned,
   id_blueprint_type smallint unsigned,
   name varchar(255),
   primary key (id),
   key (id_site, id_blueprint_type)
);

create table blueprint_type (
   id smallint unsigned not null unique auto_increment,
   name varchar(255),
   primary key (id)
);

insert into blueprint_type values ('1', 'Home');
insert into blueprint_type values ('2', 'Categoria');
insert into blueprint_type values ('3', 'Artigo');

create table frame (
   id int unsigned not null unique auto_increment,
   id_blueprint smallint unsigned,
   id_frame_type smallint unsigned,
   name varchar(255),
   position smallint unsigned,
   primary key (id),
   key (id_blueprint)
);

create table frame_type (
   id smallint unsigned not null unique auto_increment,
   name varchar(255),
   primary key (id)
);

insert into frame_type values ('1', 'Cabeçalho');
insert into frame_type values ('2', 'Coluna');
insert into frame_type values ('3', 'Rodapé');

create table element (
   id int unsigned not null unique auto_increment,
   id_frame int unsigned,
   id_component int unsigned,
   name varchar(255),
   primary key (id),
   key(id_frame, id_component)
);

create table component (
   id int unsigned not null unique auto_increment,
   name varchar(255),
   primary key (id)
);

insert into component values ('1', 'Poll');
insert into component values ('2', 'Menu');
insert into component values ('3', 'Imagem');
insert into component values ('4', 'Pesquisa');
insert into component values ('5', 'Resumo Artigo');
insert into component values ('6', 'Lista Artigos');
insert into component values ('7', 'Artigo');
insert into component values ('8', 'Publicidade');
insert into component values ('9', 'Comentários');
insert into component values ('10', 'Trackback');
insert into component values ('11', 'Widget');

-- Definir quais os componentes que o site pode usar

create table rel_site_component (
   id_site smallint unsigned not null,
   id_component mediumint unsigned not null,
   key (id_site, id_component)
);


-- Tables for Voting Component --

create table poll (
   id_poll int unsigned not null unique auto_increment,
   id_site smallint unsigned,
   title varchar(255),
   question varchar(255) not null,
   multiple_answer bool,
   view_stats bool,
   begin_date timestamp,
   end_date timestamp,
   primary key (id_poll),
   key (id_site)
);

create table poll_answer (
   id_poll_answer int unsigned not null unique auto_increment,
   id_poll int unsigned not null,
   answer varchar(255) not null,
   num_answers mediumint unsigned,
   position smallint unsigned,
   key (id_poll),
   primary key (id_poll_answer)
);

create table poll_unique_answer (
   id_poll int unsigned not null,
   id_poll_answer int unsigned not null,
   who varchar(255),
   time timestamp,
   key (id_poll, id_poll_answer)  
);

-- Tables for Link List component --

create table link_list (
  id int unsigned not null unique auto_increment,
  title varchar(255),
  primary key (id)
);

create table link (
  id int unsigned not null unique auto_increment,
  id_link_list int unsigned not null,
  name varchar(255),
  url varchar(255),
  target varchar(255),
  position smallint unsigned,
  key (id_link_list),
  primary key (id) 
);

--------------------------------------------------------------------------------------
-- Sapo stuff (may be usefull in the future) --

-- Feeds --

create table end_point (
  id bigint unsigned not null unique auto_increment,
  id_category bigint unsigned,
  id_feed bigint unsigned,
  template varchar(255),
  ttl int unsigned,
  max_entries int unsigned,
  primary key (id),
  key (id_feed, id_category)
);

create table feed (
  id bigint unsigned not null unique auto_increment,
  title varchar(255),
  url varchar(255),
  subtitle varchar(255),
  language varchar(255),
  pub_date datetime,
  last_build_date datetime,
  docs_url varchar(255),
  generator varchar(255),
  managing_editor varchar(255),
  webmaster varchar(255),
  copyright varchar(255),
  guid varchar(255),
  source_type varchar(255),
  source_description varchar(255),
  ttl int unsigned,
  auto_ttl int unsigned,
  primary key (id)
);

create table repository (
  id bigint unsigned not null unique auto_increment,
  id_feed bigint unsigned,
  title varchar(255),
  description blob,
  pub_date datetime,
  author varchar(255),
  category varchar(255),
  comments_url varchar(255),
  guid varchar(255),
  primary key (id),
  key (id_feed)
);

create table enclosure (
  id bigint unsigned not null unique auto_increment,
  id_repository bigint unsigned,
  mime_type varchar(255),
  length varchar(255),
  url  varchar(255),
  primary key (id),
  key (id_repository)
);

create table source_type (
  id int unsigned not null unique auto_increment,
  name varchar(255) not null,
  driver varchar(255) not null,
  primary key (id)
);

insert into source_type values (1, 'Cyclops', 'CyclopsCollector');
insert into source_type values (2, 'Bricolage', 'BricolageCollector');
insert into source_type values (3, 'RSS2', 'RSS2Collector');
insert into source_type values (4, 'RSSX','RSSXCollector');
insert into source_type values (5, 'Atom','AtomCollector');


