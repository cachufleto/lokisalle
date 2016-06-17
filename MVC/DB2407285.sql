-- phpMyAdmin SQL Dump
-- version 4.2.12
-- http://www.phpmyadmin.net
--
-- Client :  rdbms
-- Généré le :  Dim 12 Juin 2016 à 23:17
-- Version du serveur :  5.5.48-log
-- Version de PHP :  5.5.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `DB2407285`
--

-- --------------------------------------------------------

--
-- Structure de la table `article`
--

CREATE TABLE IF NOT EXISTS `article` (
`id_article` int(5) NOT NULL,
  `reference` int(15) NOT NULL,
  `categorie` varchar(70) NOT NULL,
  `titre` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `couleur` varchar(10) NOT NULL,
  `taille` varchar(2) NOT NULL,
  `sexe` enum('m','f') NOT NULL,
  `photo` varchar(250) NOT NULL,
  `prix` double(7,2) NOT NULL,
  `stock` int(4) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

--
-- Contenu de la table `article`
--

INSERT INTO `article` (`id_article`, `reference`, `categorie`, `titre`, `description`, `couleur`, `taille`, `sexe`, `photo`, `prix`, `stock`) VALUES
(4, 202, 'tee-shirt', 'Tshirt blanc', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam purus leo, sodales a urna at, malesuada consectetur tellus. Ut eget purus vel diam varius sollicitudin. ', 'blanc', 'S', 'm', './photo/202-tshirt_blanc.jpg', 40.00, 100),
(5, 203, 'tee-shirt', 'Tshirt rouge', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam purus leo, sodales a urna at, malesuada consectetur tellus. Ut eget purus vel diam varius sollicitudin. ', 'rouge', 'S', 'm', './photo/203-tshort_rouge.jpg', 42.00, 100),
(6, 301, 'polo', 'Polo rouge', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam purus leo, sodales a urna at, malesuada consectetur tellus. Ut eget purus vel diam varius sollicitudin. ', 'rouge', 'S', 'm', './photo/301-polo_rouge.jpg', 50.00, 100),
(7, 302, 'polo', 'Polo noir', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam purus leo, sodales a urna at, malesuada consectetur tellus. Ut eget purus vel diam varius sollicitudin. ', 'noir', 'S', 'm', './photo/302-polo_noir.jpg', 50.00, 100),
(8, 303, 'polo', 'Polo blanc', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam purus leo, sodales a urna at, malesuada consectetur tellus. Ut eget purus vel diam varius sollicitudin. ', 'blanc', 'L', 'm', './photo/303-polo_blanc.jpg', 50.00, 100),
(9, 401, 'echarpe', 'Echarpe rose', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam purus leo, sodales a urna at, malesuada consectetur tellus. Ut eget purus vel diam varius sollicitudin. ', 'rose', 'L', 'm', './photo/401-echarpe_rose.jpg', 30.00, 100),
(10, 402, 'echarpe', 'Echarpe bleu', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam purus leo, sodales a urna at, malesuada consectetur tellus. Ut eget purus vel diam varius sollicitudin. ', 'bleu', 'L', 'm', './photo/402-echarpe_bleu.jpg', 30.00, 100),
(11, 403, 'echarpe', 'Echarpe carreau', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam purus leo, sodales a urna at, malesuada consectetur tellus. Ut eget purus vel diam varius sollicitudin. ', 'bleu', 'L', 'm', './photo/403-echarpe_carreau.jpg', 30.00, 100),
(12, 404, 'echarpe', 'Echarpe grise', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam purus leo, sodales a urna at, malesuada consectetur tellus. Ut eget purus vel diam varius sollicitudin. ', 'gris', 'L', 'm', './photo/404-echarpe_gris.jpg', 30.00, 100),
(13, 501, 'jean', 'Jean fonc&eacute;', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam purus leo, sodales a urna at, malesuada consectetur tellus. Ut eget purus vel diam varius sollicitudin. ', 'bleu', 'L', 'm', './photo/501-jean_sombre.jpg', 70.00, 100),
(14, 502, 'jean', 'Jean bleu', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam purus leo, sodales a urna at, malesuada consectetur tellus. Ut eget purus vel diam varius sollicitudin. ', 'bleu', 'L', 'm', './photo/502-jean_bleu.jpg', 70.00, 100),
(15, 503, 'jean', 'Jean clair', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam purus leo, sodales a urna at, malesuada consectetur tellus. Ut eget purus vel diam varius sollicitudin. ', 'bleu', 'L', 'm', './photo/503-jean_clair.jpg', 70.00, 100),
(16, 365544, 'test', 'test', '', '', 'S', 'm', './photo/365544-echarpe_gris.jpg', 0.00, 0),
(17, 601, 'ceinture', 'Ceinture marron', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam purus leo, sodales a urna at, malesuada consectetur tellus. Ut eget purus vel diam varius sollicitudin. ', 'marron', 'L', 'm', './photo/601-ceinture_marron.jpg', 40.00, 70);

-- --------------------------------------------------------

--
-- Structure de la table `checkinscription`
--

CREATE TABLE IF NOT EXISTS `checkinscription` (
  `id_membre` int(11) NOT NULL,
  `checkinscription` varchar(250) NOT NULL,
  `inscription` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE IF NOT EXISTS `commande` (
`id_commande` int(6) NOT NULL,
  `id_membre` int(5) DEFAULT NULL,
  `montant` double(7,2) NOT NULL,
  `date` datetime NOT NULL,
  `etat` enum('en cours de traitement','envoyé','livré') NOT NULL DEFAULT 'en cours de traitement'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `details_commande`
--

CREATE TABLE IF NOT EXISTS `details_commande` (
`id_details_commande` int(5) NOT NULL,
  `id_commande` int(6) NOT NULL,
  `id_article` int(5) DEFAULT NULL,
  `quantite` int(4) NOT NULL,
  `prix` double(7,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `membres`
--

CREATE TABLE IF NOT EXISTS `membres` (
`id_membre` int(5) NOT NULL,
  `pseudo` varchar(15) NOT NULL,
  `mdp` varchar(250) NOT NULL,
  `nom` varchar(20) NOT NULL,
  `prenom` varchar(20) NOT NULL,
  `email` varchar(30) NOT NULL,
  `sexe` enum('m','f') DEFAULT NULL,
  `telephone` varchar(10) DEFAULT NULL,
  `gsm` varchar(10) DEFAULT NULL,
  `ville` varchar(20) DEFAULT NULL,
  `cp` int(10) DEFAULT NULL,
  `adresse` varchar(30) DEFAULT NULL,
  `statut` set('MEM','COL','ADM') NOT NULL DEFAULT 'MEM',
  `inscription` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active` int(1) unsigned NOT NULL DEFAULT '2' COMMENT 'suppression'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `membres`
--

INSERT INTO `membres` (`id_membre`, `pseudo`, `mdp`, `nom`, `prenom`, `email`, `sexe`, `telephone`, `gsm`, `ville`, `cp`, `adresse`, `statut`, `inscription`, `active`) VALUES
(1, 'Admin', '$2y$10$gRCkhbidbCKCKA7OchNKXOXNQim025U8QwZBiuwa4CFxn8UdTd16m', 'Paz', 'Carlos', 'carlos.paz.dupriez@gmail.com', 'm', '0606060606', '0662474323', 'Boulogne-Billancourt', 92100, 'Rue escuder', 'ADM', '2016-05-25 13:02:02', 1);

-- --------------------------------------------------------

--
-- Structure de la table `salles`
--

CREATE TABLE IF NOT EXISTS `salles` (
`id_salle` int(10) unsigned NOT NULL,
  `pays` varchar(20) NOT NULL,
  `ville` varchar(20) NOT NULL,
  `adresse` text NOT NULL,
  `cp` varchar(10) NOT NULL,
  `titre` varchar(25) NOT NULL,
  `telephone` varchar(10) DEFAULT NULL,
  `gsm` varchar(10) DEFAULT NULL,
  `description` text NOT NULL,
  `photo` varchar(200) NOT NULL,
  `capacite` int(3) unsigned NOT NULL,
  `categorie` enum('R','C','F') NOT NULL DEFAULT 'R',
  `active` int(1) DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `salles`
--

INSERT INTO `salles` (`id_salle`, `pays`, `ville`, `adresse`, `cp`, `titre`, `telephone`, `gsm`, `description`, `photo`, `capacite`, `categorie`, `active`) VALUES
(1, 'France', 'Boulogne-Billancourt', 'Ou j&#039;habite', '92100', 'MonTh', '0660474300', NULL, 'Un v&Atilde;&copy;ritable th&Atilde;&copy;&Atilde;&cent;tre est a votre disposition pour les &Atilde;&copy;v&Atilde;&copy;nements culturels.', '_____5745ab427a580.jpg', 150, 'F', 0),
(2, 'Colombie', 'Bogota', 'C&#039;est la mienne', '89895', 'Diviel', '12023024', NULL, 'Una berraquera.', '_____574626fc8673a.jpg', 89, 'C', 1),
(3, 'France', 'Boulogne-Billancourt', '46 rue Escudier', '92100', 'Carlos Paz Dupriez', '0662474323', NULL, 'erazevr aez faef \r\naef aerf aerg aerg zdfg zv&#039;(-&Atilde;&uml;(e- erzerf\r\nvzer gz&#039;( ytfvsfdb\r\n sdfb dfng dghn dghj&Atilde;&nbsp;\r\nxfb sdf \r\n&Atilde;&copy;\r\nf gsdfbf', 'France_Boulogne-Billancourt_Carlos_Paz_Dupriez_574beadf3f21b.gif', 6, 'C', 1),
(4, 'France', 'Boulogne-Billancourt', '46 rue Escudier', '92100', 'Mon Th&Atilde;&copy;&amp;', '0662474323', NULL, 'Dans de nombreuses installations, ces polices sont souvent absentes. Les polices cursives sont alors remplac&Atilde;&copy;es par une des polices g&Atilde;&copy;n&Atilde;&copy;riques par d&Atilde;&copy;faut (avec ou sans empattement) du moteur de rendu (et &Atilde;&copy;ventuellement configur&Atilde;&copy;e par l&acirc;€™utilisateur dans les r&Atilde;&copy;glages du navigateur). Dans certains cas (par exemple les polices d&Atilde;&copy;coratives dites &Acirc;&laquo; bris&Atilde;&copy;es &Acirc;&raquo;, il peut &Atilde;&ordf;tre utile de pr&Atilde;&copy;ciser une famille g&Atilde;&copy;n&Atilde;&copy;rique pr&Atilde;&copy;f&Atilde;&copy;r&Atilde;&copy;e &Atilde;&nbsp; celle de leur classification normale, afin de pr&Atilde;&copy;server leur lisibilit&Atilde;&copy; si elles sont substitu&Atilde;&copy;es par une autre dans leur utilisation dans un texte normal.', 'France_Boulogne-Billancourt_Mon_ThAtilde;copy;Atil_574afc013d41d.jpg', 6, 'C', 1),
(6, 'rep dom', 'sto dgo', '50 rue saint-sebastien', '75011', 'L. Deprez', '+336437385', NULL, ' Le Hall, grand espace ouvert, lumineux et convivial, est un lieu de rencontre et d&acirc;€™&Atilde;&copy;change, agr&Atilde;&copy;able &Atilde;&nbsp; fr&Atilde;&copy;quenter les soirs de spectacle autour d&acirc;€™un verre, d&acirc;€™un repas ou tout simplement en journ&Atilde;&copy;e pour acc&Atilde;&copy;der aux diff&Atilde;&copy;rents &Atilde;&copy;quipements du Toboggan, prendre vos places de spectacles &Atilde;&nbsp; l&acirc;€™accueil billetterie. Des animations en lien avec les diff&Atilde;&copy;rentes programmations donnent vie au hall tout au long de la saison.', 'rep dom_sto dgo_paz pazrep_dom_sto_dgo_paz_paz_574b40711dd69.jpg', 150, 'F', 1),
(5, 'France', 'Levallois Perret', '157 rue Jules guesde', '92300', 'Frias Garcia', '+336437385', NULL, ' Le Hall, grand espace ouvert, lumineux et convivial, est un lieu de rencontre et d&acirc;€™&Atilde;&copy;change, agr&Atilde;&copy;able &Atilde;&nbsp; fr&Atilde;&copy;quenter les soirs de spectacle autour d&acirc;€™un verre, d&acirc;€™un repas ou tout simplement en journ&Atilde;&copy;e pour acc&Atilde;&copy;der aux diff&Atilde;&copy;rents &Atilde;&copy;quipements du Toboggan, prendre vos places de spectacles &Atilde;&nbsp; l&acirc;€™accueil billetterie. Des animations en lien avec les diff&Atilde;&copy;rentes programmations donnent vie au hall tout au long de la saison.', 'France_Levallois Perret_Frias GarciaFrance_Levallois_Perret_Frias_Garcia_574a175181f40.jpg', 6, 'R', 1),
(7, 'France', 'Boulogne-Billancourt', '56 Aristide briant', '92100', 'test', '0662474323', NULL, 'grand salle', 'France_Boulogne-Billancourt_test_574ca864ccfde.png', 6, 'C', 1);

--
-- Index pour les tables exportées
--

--
-- Index pour la table `article`
--
ALTER TABLE `article`
 ADD PRIMARY KEY (`id_article`), ADD UNIQUE KEY `reference` (`reference`);

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
 ADD PRIMARY KEY (`id_commande`), ADD KEY `id_membre` (`id_membre`);

--
-- Index pour la table `details_commande`
--
ALTER TABLE `details_commande`
 ADD PRIMARY KEY (`id_details_commande`), ADD KEY `id_article` (`id_article`);

--
-- Index pour la table `membres`
--
ALTER TABLE `membres`
 ADD PRIMARY KEY (`id_membre`), ADD UNIQUE KEY `email` (`email`), ADD UNIQUE KEY `pseudo` (`pseudo`);

--
-- Index pour la table `salles`
--
ALTER TABLE `salles`
 ADD PRIMARY KEY (`id_salle`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `article`
--
ALTER TABLE `article`
MODIFY `id_article` int(5) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
MODIFY `id_commande` int(6) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `details_commande`
--
ALTER TABLE `details_commande`
MODIFY `id_details_commande` int(5) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `membres`
--
ALTER TABLE `membres`
MODIFY `id_membre` int(5) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `salles`
--
ALTER TABLE `salles`
MODIFY `id_salle` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
