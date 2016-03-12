--
-- Database: `Bank` DENNE MÅ VÆRE OPPRETTET FØR SCRIPTET KJØRES !!!
--

-- --------------------------------------------------------
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
--
-- Tabellstruktur for tabell `Konto`
--
DROP TABLE Konto;
CREATE TABLE IF NOT EXISTS `Konto` (
  `Kontonummer`  VARCHAR(20) NOT NULL,
  `Personnummer` VARCHAR(11) NOT NULL,
  `Saldo`        FLOAT       NOT NULL,
  `Type`         VARCHAR(20) NOT NULL,
  `Valuta`       VARCHAR(3)  NOT NULL,
  PRIMARY KEY (`Kontonummer`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

--
-- Dataark for tabell `Konto`
--

INSERT INTO `Konto` (`Kontonummer`, `Personnummer`, `Saldo`, `Type`, `Valuta`) VALUES
  ('105010123456', '01010110523', 720, 'Lønnskonto', 'NOK'),
  ('105020123456', '01010110523', 100500, 'Sparekonto', 'NOK'),
  ('22334412345', '01010110523', 10234.5, 'Brukskonto', 'NOK'),
  ('12345678912', '98765432198', 10658, 'Brukskonto', 'NOK');

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `Kunde`
--
DROP TABLE Kunde;
CREATE TABLE IF NOT EXISTS `Kunde` (
  `Personnummer` VARCHAR(11)  NOT NULL,
  `Fornavn`      VARCHAR(30)  NOT NULL,
  `Etternavn`    VARCHAR(30)  NOT NULL,
  `Adresse`      VARCHAR(50)  NOT NULL,
  `Postnr`       VARCHAR(4)   NOT NULL,
  `Telefonnr`    VARCHAR(8)   NOT NULL,
  `Passord`      VARCHAR(500) NOT NULL,
  PRIMARY KEY (`Personnummer`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

--
-- Dataark for tabell `Kunde`
--

INSERT INTO `Kunde` (`Personnummer`, `Fornavn`, `Etternavn`, `Adresse`, `Postnr`, `Telefonnr`, `Passord`) VALUES
  ('01010110523', 'Lene', 'Jensen', 'Askerveien 22', '3270', '22224444', 'Hei'),
  ('12345678901', 'Per', 'Hansen', 'Osloveien 82', '1234', '12345678', 'Hei'),
  ('98765432198', 'Test', 'Testesen', 'Osloveien 82', '1234', '12345678', '98765432198');

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `Poststed`
--
DROP TABLE `Poststed`;
CREATE TABLE IF NOT EXISTS `Poststed` (
  `Postnr`   VARCHAR(4)  NOT NULL,
  `Poststed` VARCHAR(30) NOT NULL,
  PRIMARY KEY (`Postnr`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

--
-- Dataark for tabell `Poststed`
--

INSERT INTO `Poststed` (`Postnr`, `Poststed`) VALUES
  ('', 'Asker'),
  ('3270', 'Asker'),
  ('1234', 'Oslo');

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `Transaksjon`
--
DROP TABLE Transaksjon;
CREATE TABLE IF NOT EXISTS `Transaksjon` (
  `TxID`              INT(11)      NOT NULL AUTO_INCREMENT,
  `FraTilKontonummer` VARCHAR(20)  NOT NULL,
  `Belop`             FLOAT        NOT NULL,
  `Dato`              DATE         NOT NULL,
  `Melding`           VARCHAR(100) NOT NULL,
  `Kontonummer`       VARCHAR(20)  NOT NULL,
  `Avventer`          TINYINT(1)   NOT NULL,
  PRIMARY KEY (`TxID`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  AUTO_INCREMENT = 10;

--
-- Dataark for tabell `Transaksjon`
--

INSERT INTO `Transaksjon` (`TxID`, `FraTilKontonummer`, `Belop`, `Dato`, `Melding`, `Kontonummer`, `Avventer`) VALUES
  (1, '20102012345', -100.5, '2015-03-15', 'Meny Storo', '105010123456', 0),
  (2, '20102012345', 400.4, '2015-03-20', 'Innebtaling', '105010123456', 0),
  (3, '20102012345', -1400.7, '2015-03-13', 'Husleie', '55551166677', 1),
  (4, '20102012347', -5000.5, '2015-03-30', 'Skatt', '105010123456', 0),
  (5, '20102012345', 345.56, '2015-03-13', 'Test', '55551166677', 0),
  (6, '12312345', 1234, '2012-12-12', 'Melding', '234567', 1),
  (7, '345678908', 3000, '2012-12-12', '', '105010123456', 0),
  (8, '234534678', 15, '2012-12-12', 'Hei', '105010123456', 0),
  (9, '1234254365', 125, '2012-12-12', 'Hopp', '105010123456', 0),
  (10, '45645645687', 6548, '2012-12-12', 'TestBetaling', '12345678912', 1),
  (11, '123', 125, '2012-12-12', 'TestTransaksjon1', '12345678912', 0),
  (12, '1234', 125, '2011-12-12', 'TestTransaksjon2', '12345678912', 0),
  (13, '12345', 125, '2013-12-12', 'TestTransaksjon3', '12345678912', 0),
  (14, '123456', 125, '2017-12-12', 'TestTransaksjon4', '12345678912', 0);

