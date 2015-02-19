create database trytolistenme;

CREATE USER 'trytolistenme'@'localhost' IDENTIFIED BY 'trytolistenme';
GRANT ALL PRIVILEGES ON *.* TO 'trytolistenme'@'localhost' WITH GRANT OPTION;

flush privileges;

DROP TABLE `users`;
DROP TABLE `chat`;
DROP TABLE `history`;
DROP TABLE `setting`;
DROP TABLE `friends`;


CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(70) NOT NULL,
  `fingerprint` varchar(255) NOT NULL,
  `key` text NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `chat` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fromuser` varchar(255) NOT NULL,
  `friend` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `date`    date NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `history` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `to` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `setting` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `fingerprint` varchar(255) NOT NULL,
  `settings` text NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `friends` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `of` varchar(255) NOT NULL,
  `friends` text NOT NULL,
  PRIMARY KEY (`id`)
);
