----------------------
-- Apoidea Database --
----------------------
------------------------------------------
-- mysql -u webuser -p < bd_apoidea.sql --
------------------------------------------

drop database apoidea2;
create database apoidea2;
use apoidea2;

create table tribe (
	id tinyint unsigned not null unique auto_increment,
	name varchar(255),
	primary key (id)
);

create table user (
	id smallint unsigned not null unique auto_increment,
	tribeID tinyint unsigned not null,
	name varchar(255),
	username varchar(255) not null,
	password varchar(255) not null,
	primary key (id),
	key (tribeID)
);

create table article (
	id mediumint unsigned not null unique auto_increment,
	userID mediumint unsigned,
	creationDate timestamp,
	title varchar(255),
	subtitle blob,
	teaser blob,
	text blob,
	link varchar(255),
	beginDate timestamp,
	endDate timestamp,
	active bool,
	primary key (id),
	key (userID)
);

create table category (
	id smallint unsigned not null unique auto_increment,
	parentCategoryID smallint unsigned,
	name varchar(255) not null,
	stub varchar(255) not null,
	active bool,
	feed bool,
	primary key (id),
	key (parentCategoryID)
);

create table endPoint (
	id bigint unsigned not null unique auto_increment,
	categoryID bigint unsigned,
	feedID bigint unsigned,
	template varchar(255),
	ttl int unsigned,
	max_entries int unsigned,
	primary key (id),
	key (categoryID, feedID)
);

create table feed (
	id bigint unsigned not null unique auto_increment,
	title varchar(255),
	subtitle varchar(255),
	url varchar(255),
	language varchar(255),
	publishDate datetime,
	lastBuildDate datetime,
	documentationUrl varchar(255),
	generator varchar(255),
	managingEditor varchar(255),
	webmaster varchar(255),
	copyright varchar(255),
	guid varchar(255),
	sourceTypeID smallint unsigned,
	sourceDescription varchar(255),
	ttl int unsigned,
	autoTTL int unsigned,
	primary key (id),
	key (sourceTypeID)
);

create table repository (
	id bigint unsigned not null unique auto_increment,
	feedID bigint unsigned,
	title varchar(255),
	description blob,
	publishDate datetime,
	author varchar(255),
	category varchar(255),
	commentsURL varchar(255),
	guid varchar(255),
	primary key (id),
	key (feedID)
);

create table enclosure (
	id bigint unsigned not null unique auto_increment,
	repositoryID bigint unsigned,
	mimeType varchar(255),
	length varchar(255),
	url varchar(255),
	primary key (id),
	key (repositoryID)
);

create table sourceType (
	id bigint unsigned not null unique auto_increment,
	name varchar(255) not null,
	driver varchar(255) not null,
	primary key (id)
);

