CREATE DATABASE alumnitalks;
USE alumnitalks;
GRANT ALL ON alumnitalks.* TO 'EnterUserName'@'localhost' IDENTIFIED BY 'EnterPassword';
GRANT ALL ON alumnitalks.* TO 'EnterUserName'@'127.0.0.1' IDENTIFIED BY 'EnterPassword';

CREATE TABLE `temp` (
  `process` varchar(32) NOT NULL,
  `fn` varchar(15) DEFAULT NULL,
  `ln` varchar(15) DEFAULT NULL,
  `gender` char(1) DEFAULT NULL,
  `category` char(1) DEFAULT NULL,
  `email` varchar(60) DEFAULT NULL,
  `pass` varchar(20) DEFAULT NULL,
   PRIMARY KEY (`process`),
  `dateCreated` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `fn` varchar(15) DEFAULT NULL,
  `ln` varchar(15) DEFAULT NULL,
  `gender` char(1) DEFAULT NULL,
  `category` char(1) DEFAULT NULL,
  `email` varchar(60) DEFAULT NULL,
  `ig` varchar(30) DEFAULT NULL,
  `pass` varchar(20) DEFAULT NULL,
  `dateCreated` timestamp NOT NULL DEFAULT current_timestamp(),
   PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `resetpassword` (
  `process` varchar(32) NOT NULL,
  `email` varchar(60) DEFAULT NULL,
  `dateCreated` timestamp NOT NULL DEFAULT current_timestamp(),
   PRIMARY KEY (`process`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `blocked` (
  `email` varchar(60) NOT NULL,
  `attempts` int(11) DEFAULT NULL,
   PRIMARY KEY (`email`),
  `dateCreated` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `changemail` (
  `process` varchar(32) NOT NULL,
  `email` varchar(60) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
   PRIMARY KEY (`process`),
  `dateCreated` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `datacontent` (
  `sr_no` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT NULL,
  `description` mediumtext DEFAULT NULL,
  `views` int(11) DEFAULT NULL,
  `likes` int(11) DEFAULT NULL,
  `dateCreated` timestamp NOT NULL DEFAULT current_timestamp(),
  `dateEdited` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`sr_no`),
  CONSTRAINT FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `likes` (
  `sr_no` int(10) UNSIGNED DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  CONSTRAINT FOREIGN KEY (`sr_no`) REFERENCES `datacontent` (`sr_no`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `views` (
  `sr_no` int(10) UNSIGNED DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  CONSTRAINT FOREIGN KEY (`sr_no`) REFERENCES `datacontent` (`sr_no`) ON DELETE CASCADE,
  CONSTRAINT FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
