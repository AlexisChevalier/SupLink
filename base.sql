-- phpMyAdmin SQL Dump
-- version 3.3.9.2
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Dim 24 Février 2013 à 13:01
-- Version du serveur: 5.5.9
-- Version de PHP: 5.3.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Base de données: `2WEB-utf8`
--

-- --------------------------------------------------------

--
-- Structure de la table `2web_stats`
--

CREATE TABLE `2web_stats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_clicked` date NOT NULL,
  `id_url` int(11) NOT NULL,
  `referrer` varchar(200) NOT NULL,
  `ip_address` varchar(41) NOT NULL,
  `country_code` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_url` (`id_url`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `2web_urls`
--

CREATE TABLE `2web_urls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url_long` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `date_created` int(11) NOT NULL,
  `enabled` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `clicked_count` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Structure de la table `2web_users`
--

CREATE TABLE `2web_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(250) NOT NULL,
  `password` varchar(250) NOT NULL,
  `valid` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
