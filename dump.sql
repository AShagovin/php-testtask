-- phpMyAdmin SQL Dump
-- version 3.3.10deb1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Авг 11 2011 г., 07:35
-- Версия сервера: 5.1.54
-- Версия PHP: 5.3.5-1ubuntu7.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `testtask`
--

-- --------------------------------------------------------

--
-- Структура таблицы `events`
--

CREATE TABLE IF NOT EXISTS `events` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `event_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `object_name` varchar(20) NOT NULL,
  `object_id` int(11) NOT NULL,
  `action` enum('new','change','delete') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MEMORY  DEFAULT CHARSET=utf8 AUTO_INCREMENT=50 ;

--
-- Дамп данных таблицы `events`
--

INSERT INTO `events` (`id`, `event_on`, `object_name`, `object_id`, `action`) VALUES
(1, '2011-08-11 04:39:40', 'posts', 1, 'new'),
(2, '2011-08-11 04:42:52', 'posts', 2, 'new'),
(3, '2011-08-11 04:42:57', 'posts', 2, 'delete'),
(4, '2011-08-11 04:43:03', 'posts', 1, 'delete'),
(5, '2011-08-11 05:06:11', 'posts', 3, 'new'),
(6, '2011-08-11 05:06:11', 'posts_attachments', 1, 'new'),
(7, '2011-08-11 05:06:13', 'posts', 3, 'change'),
(8, '2011-08-11 05:07:00', 'posts', 4, 'new'),
(9, '2011-08-11 05:07:00', 'posts_attachments', 2, 'new'),
(10, '2011-08-11 05:07:07', 'posts_attachments', 3, 'new'),
(11, '2011-08-11 05:07:08', 'posts', 4, 'change'),
(12, '2011-08-11 05:07:53', 'posts', 4, 'delete'),
(13, '2011-08-11 05:07:53', 'posts_attachments', 2, 'delete'),
(14, '2011-08-11 05:07:53', 'posts_attachments', 3, 'delete'),
(15, '2011-08-11 05:07:55', 'posts', 3, 'delete'),
(16, '2011-08-11 05:07:55', 'posts_attachments', 1, 'delete'),
(17, '2011-08-11 05:51:38', 'posts', 5, 'new'),
(18, '2011-08-11 05:51:38', 'posts_attachments', 4, 'new'),
(19, '2011-08-11 05:52:48', 'posts_attachments', 5, 'new'),
(20, '2011-08-11 05:54:13', 'posts_attachments', 6, 'new'),
(21, '2011-08-11 05:55:32', 'posts_attachments', 7, 'new'),
(22, '2011-08-11 05:55:55', 'posts', 5, 'change'),
(23, '2011-08-11 05:58:33', 'posts_like_members', 1, 'new'),
(24, '2011-08-11 06:09:12', 'posts', 6, 'new'),
(25, '2011-08-11 06:10:16', 'posts', 6, 'delete'),
(26, '2011-08-11 06:10:19', 'posts', 7, 'new'),
(27, '2011-08-11 06:11:12', 'posts', 7, 'delete'),
(28, '2011-08-11 06:11:14', 'posts', 8, 'new'),
(29, '2011-08-11 06:11:18', 'posts', 8, 'delete'),
(30, '2011-08-11 06:11:23', 'posts', 9, 'new'),
(31, '2011-08-11 06:11:28', 'posts', 9, 'delete'),
(32, '2011-08-11 06:16:41', 'posts', 10, 'new'),
(33, '2011-08-11 06:16:49', 'posts', 10, 'delete'),
(34, '2011-08-11 06:16:57', 'posts', 11, 'new'),
(35, '2011-08-11 06:17:15', 'posts', 11, 'delete'),
(36, '2011-08-11 06:17:34', 'posts', 12, 'new'),
(37, '2011-08-11 06:19:09', 'posts', 13, 'new'),
(38, '2011-08-11 06:25:20', 'posts', 14, 'new'),
(39, '2011-08-11 06:25:23', 'posts', 14, 'delete'),
(40, '2011-08-11 06:25:24', 'posts', 13, 'delete'),
(41, '2011-08-11 06:25:25', 'posts', 12, 'delete'),
(42, '2011-08-11 06:25:28', 'posts', 15, 'new'),
(43, '2011-08-11 06:25:31', 'posts', 15, 'delete'),
(44, '2011-08-11 06:25:45', 'posts', 16, 'new'),
(45, '2011-08-11 06:26:51', 'posts', 16, 'delete'),
(46, '2011-08-11 06:26:55', 'posts', 17, 'new'),
(47, '2011-08-11 06:27:06', 'posts', 17, 'delete'),
(48, '2011-08-11 06:27:25', 'posts', 18, 'new'),
(49, '2011-08-11 06:27:50', 'posts', 18, 'delete');

-- --------------------------------------------------------

--
-- Структура таблицы `posts`
--

CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `text` text NOT NULL,
  `create_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('active','draft') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

--
-- Дамп данных таблицы `posts`
--

INSERT INTO `posts` (`id`, `uid`, `text`, `create_on`, `status`) VALUES
(5, 1, '', '2011-08-11 05:51:38', 'active');

--
-- Триггеры `posts`
--
DROP TRIGGER IF EXISTS `post_insert`;
DELIMITER //
CREATE TRIGGER `post_insert` AFTER INSERT ON `posts`
 FOR EACH ROW BEGIN    
   INSERT INTO events Set 
object_name = 'posts',
object_id = NEW.id,
action = 'new';            
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `post_update`;
DELIMITER //
CREATE TRIGGER `post_update` AFTER UPDATE ON `posts`
 FOR EACH ROW BEGIN
    INSERT INTO events Set 
object_name = 'posts',
object_id = NEW.id,
action = 'change';   
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `post_delete`;
DELIMITER //
CREATE TRIGGER `post_delete` AFTER DELETE ON `posts`
 FOR EACH ROW BEGIN    
   INSERT INTO events Set object_name = 'posts',object_id = OLD.id,action = 'delete';            
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблицы `posts_attachments`
--

CREATE TABLE IF NOT EXISTS `posts_attachments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int(11) unsigned NOT NULL,
  `type` enum('picture','video','link') NOT NULL,
  `url` text NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Дамп данных таблицы `posts_attachments`
--

INSERT INTO `posts_attachments` (`id`, `post_id`, `type`, `url`, `description`) VALUES
(4, 5, 'video', 'http://www.youtube.com/watch?v=14RFYXMXGqQ', 'Node.js + Socket.io = CPU activity monitor'),
(5, 5, 'video', 'http://www.youtube.com/watch?v=lhzvTUwT0wI', 'Installing Node.js Video Tutorial '),
(6, 5, 'link', 'http://nodejs.ru/', 'документация на русском языке'),
(7, 5, 'link', 'https://github.com/demchenkoe/njs-freeswitch-esc', 'мой первый модуль на Node.js');

--
-- Триггеры `posts_attachments`
--
DROP TRIGGER IF EXISTS `posts_attachments_insert`;
DELIMITER //
CREATE TRIGGER `posts_attachments_insert` AFTER INSERT ON `posts_attachments`
 FOR EACH ROW BEGIN
    INSERT INTO events Set object_name = 'posts_attachments',object_id = NEW.id,action = 'new';   
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `posts_attachments_delete`;
DELIMITER //
CREATE TRIGGER `posts_attachments_delete` AFTER DELETE ON `posts_attachments`
 FOR EACH ROW BEGIN
    INSERT INTO events Set 
object_name = 'posts_attachments',
object_id = OLD.id,
action = 'delete';   
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблицы `posts_like_members`
--

CREATE TABLE IF NOT EXISTS `posts_like_members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `post_id` (`post_id`,`uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `posts_like_members`
--

INSERT INTO `posts_like_members` (`id`, `post_id`, `uid`) VALUES
(1, 5, 1);

--
-- Триггеры `posts_like_members`
--
DROP TRIGGER IF EXISTS `posts_like_members_insert`;
DELIMITER //
CREATE TRIGGER `posts_like_members_insert` AFTER INSERT ON `posts_like_members`
 FOR EACH ROW BEGIN
    INSERT INTO events Set object_name = 'posts_like_members',object_id = NEW.id,action = 'new';   
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `posts_like_members_delete`;
DELIMITER //
CREATE TRIGGER `posts_like_members_delete` AFTER DELETE ON `posts_like_members`
 FOR EACH ROW BEGIN
    INSERT INTO events Set object_name = 'posts_like_members',object_id = OLD.id,action = 'delete';   
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `uid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(40) NOT NULL,
  `name` varchar(40) NOT NULL,
  `public_email` varchar(1) NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`uid`, `email`, `name`, `public_email`) VALUES
(1, 'test@example.com', 'Demchenko Eugene', 'Y'),
(2, 'test2@example.com', 'user1', ''),
(3, 'test3@example.com', 'user2', '');
