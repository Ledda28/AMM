CREATE TABLE IF NOT EXISTS `serie` (
`id` int(10) unsigned NOT NULL,
  `utente` int(10) unsigned NOT NULL,
  `nome` varchar(30) NOT NULL,
  `genere` enum('animazione','avventura','azione','commedia','drammatico','fantascienza','fantasy','horror','thriller') NOT NULL,
  `anno` year(4) NOT NULL,
  `descrizione` text NOT NULL,
  `approvata` tinyint(1) NOT NULL,
  `banner` varchar(400) NOT NULL,
  `immagine` varchar(180) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `utenti` (
`id` int(10) unsigned NOT NULL,
  `nome` varchar(16) CHARACTER SET latin1 NOT NULL,
  `cognome` varchar(16) CHARACTER SET latin1 NOT NULL,
  `nickname` varchar(16) CHARACTER SET latin1 NOT NULL,
  `email` varchar(50) CHARACTER SET latin1 NOT NULL,
  `confirmed` tinyint(1) NOT NULL DEFAULT '0',
  `password` varchar(32) CHARACTER SET latin1 NOT NULL,
  `ban` tinyint(1) NOT NULL DEFAULT '0',
  `amministratore` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `utenti` (`nickname`,`password`,`amministratore`) VALUES ("admin",MD5("123"),1);

ALTER TABLE `serie`
 ADD UNIQUE KEY `nome_unico` (`utente`,`nome`), ADD KEY `utente` (`utente`);

ALTER TABLE `serie`
ADD CONSTRAINT `utente_key` FOREIGN KEY (`utente`) REFERENCES `utenti` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

