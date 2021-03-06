<?php
include_once '../Model/domeneModell.php';

class DBStubSqlite extends PHPUnit_Extensions_Database_TestCase
{

    protected $pdo = null;
    private $db;

    function __construct()
    {
        $this->db = $this->db();
    }

    public function db()
    {
        $this->getDataSet();
        parent::setUp();
        return $this->getConnection()->getConnection();
    }

    public function getDataSet()
    {
        return $this->createMySQLXMLDataSet('../bank-seed-mysql.xml');
    }

    public function getConnection()
    {
        if (null === $this->pdo) {
            $this->pdo = new PDO('sqlite:../bank.db');
            $this->pdo->exec("DROP TABLE 'Transaksjon'");
            $this->pdo->exec('CREATE TABLE `Transaksjon` (  `TxID` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
              `FraTilKontonummer` VARCHAR(20) NOT NULL,  `Belop` FLOAT NOT NULL,
              `Dato` DATE NOT NULL,  `Melding` VARCHAR(100) NOT NULL,
              `Kontonummer` VARCHAR(20) NOT NULL,  `Avventer` TINYINT(1) NOT NULL) ');
            $this->pdo->exec("CREATE TABLE `Poststed` (  `Postnr` TEXT PRIMARY KEY  NOT NULL,
                           `Poststed` TEXT NOT NULL)");
            $this->pdo->exec("CREATE TABLE `Konto` (  `Kontonummer` VARCHAR(20) NOT NULL,  `Personnummer` VARCHAR(11) NOT NULL,
              `Saldo` FLOAT NOT NULL,  `Type` VARCHAR(20) NOT NULL,  `Valuta` VARCHAR(3) NOT NULL, PRIMARY KEY (`Kontonummer`))");
            $this->pdo->exec("CREATE TABLE IF NOT EXISTS `Kunde` (  `Personnummer` VARCHAR(11) NOT NULL,  `Fornavn` VARCHAR(30) NOT NULL,
              `Etternavn` VARCHAR(30) NOT NULL,  `Adresse` VARCHAR(50) NOT NULL,  `Postnr` VARCHAR(4) NOT NULL,  `Telefonnr` VARCHAR(8) NOT NULL,
              `Passord` VARCHAR(500) NOT NULL,  PRIMARY KEY (`Personnummer`));");

        }
        return $this->createDefaultDBConnection($this->pdo, '../bank.db');
    }

    function hentTransaksjoner($kontonr, $fraDato, $tilDato)
    {
        if ($fraDato == "") {
            $fraDato = "2000-01-01";
        }
        if ($tilDato == "") {
            $tilDato = "2100-01-01";
        }
        $konto = new konto();
        $sql = "Select * from Konto Where Kontonummer = '$kontonr'";
        $resultat = $this->db->query($sql);
        $rad = $resultat->fetchObject();
        $konto->kontonummer = $kontonr;
        $konto->personnummer = $rad->Personnummer;
        $konto->saldo = $rad->Saldo;
        $konto->type = $rad->Type;
        $konto->valuta = $rad->Valuta;

        $sql = "SELECT DISTINCT FraTilKontonummer,Belop,Dato,Melding "
            . "FROM Konto, Transaksjon "
            . "WHERE Transaksjon.Kontonummer = '$kontonr' "
            . "AND Transaksjon.Dato >= '$fraDato' "
            . "AND Transaksjon.Dato <= '$tilDato' "
            . "AND Transaksjon.Avventer != 1";

        $resultat = $this->db->query($sql);
        $transaksjoner = array();
        while ($rad = $resultat->fetchObject()) {
            $tx = new transaksjon();
            $tx->fraTilKontonummer = $rad->FraTilKontonummer;
            $tx->dato = $rad->Dato;
            $tx->melding = $rad->Melding;
            $tx->transaksjonBelop = $rad->Belop;
            $transaksjoner[] = $tx;
        }
        $konto->transaksjoner = $transaksjoner;
        return $konto;
    }

    function sjekkLoggInn($personnummer, $passord)
    {
        $sql = "Select * from Kunde Where personnummer = '$personnummer' AND "
            . "passord = '$passord'";
        $resultat = $this->db->query($sql);
        $rad = $resultat->fetchObject();
        if ($rad == !null) {
            return "OK";
        } else {
            return "Feil";
        }
    }


    function registrerBetaling($kontoNr, $transaksjon)
    {

        if ($transaksjon->belop === "fail") {
            return "Feil";
        }
        $sql = "INSERT INTO Transaksjon (FraTilKontonummer,Belop,Dato,Melding,Kontonummer,Avventer) "
            . "Values ('$transaksjon->fraTilKontonummer','$transaksjon->belop','$transaksjon->dato','$transaksjon->melding','$kontoNr','1')";
        $resultat = $this->db->query($sql);
        if ($resultat->rowCount() == 1) {
            return "OK";
        } else {
            return "Feil";//@codeCoverageIgnore
        }
    }

    function hentBetalinger($personnummer)
    {
        // hent alle betalinger for kontonummer som avventer betaling (lik 1)
        $sql = "Select * from Transaksjon Join Konto On "
            . "Transaksjon.Kontonummer = Konto.Kontonummer Where "
            . "Personnummer='$personnummer'"
            . "AND Avventer='1' Order By Transaksjon.Kontonummer";
        $resultat = $this->db->query($sql);
        $betalinger = array();
        while ($rad = $resultat->fetchObject()) {
            $betalinger[] = $rad;
        }
        return $betalinger;
    }

    //Metoden har fått en del endringer siden BankDatabase pga konvertering til PDO for ease of access, uten å måtte endre logikken FOR mye.
    function utforBetaling($TxID)
    {
        $this->db->beginTransaction();
        $feil = false;
        $belop = 0;
        $kontonummer = "";
        $nySaldo = 0;

        // hent Belop og Kontonummer fra Transaksjonenen
        $sql = "SELECT Belop, Kontonummer FROM Transaksjon WHERE TxID ='" . $TxID . "'";
        $resultat = $this->db->query($sql);
        $resultat->setFetchMode(PDO::FETCH_INTO, new transaksjon());
        $rad = $resultat->fetchObject();
        if (empty($rad) || count($rad) != 1) {
            $feil = true;
        }

        if (!$feil) {
            $belop = $rad->Belop;
            $kontonummer = $rad->Kontonummer;
        }


        // hent Saldo fra Konto
        $sql = "SELECT Saldo FROM Konto WHERE kontonummer ='" . $kontonummer . "'";
        $resultat = $this->db->query($sql);
        $resultat->setFetchMode(PDO::FETCH_INTO, new konto());
        $rad = $resultat->fetchObject();

        if (empty($rad) || count($rad) != 1) {
            $feil = true;
        }

        if (!$feil) {
            $gammelSaldo = $rad->Saldo;
            $nySaldo = $gammelSaldo - $belop;
        }

        if (!$feil) {
            // sett "Avventer" på TXiD til 0
            if ($TxID === 6)
                $TxID = -1;
            $sql = "Update Transaksjon Set Avventer = '0' Where TxID = '$TxID'";
            $resultat = $this->db->query($sql);
            if ($resultat->rowCount() == 1) {
                // oppdater Saldo på Konto
                $sql = "Update Konto Set Saldo = " . $nySaldo . " Where kontonummer = '$kontonummer'";
                $resultat = $this->db->query($sql);
                if ($resultat->rowCount() == 1) {
                    $this->db->commit();
                    return "OK";
                }
            }//@codeCoverageIgnore
        }
        $this->db->rollback();
        return "Feil";
    }

    function endreKundeInfo($kunde)
    {
        $this->db->beginTransaction();
        // Sjekk om nytt postnr ligger i Poststeds-tabellen, dersom ikke legg det inn
        $sql = "Select * from Poststed Where Postnr = '$kunde->postnr'";
        $resultat = $this->db->query($sql);
        $resultat->setFetchMode(PDO::FETCH_INTO, new kunde());
        $rad = $resultat->fetchObject();

        if (empty($rad) || count($rad) != 1) {
            // ligger ikke i poststedstabellen
            $sql = "Insert Into Poststed (Postnr, Poststed) Values ('$kunde->postnr','$kunde->poststed')";
            $resultat = $this->db->query($sql);
            $resultat->setFetchMode(PDO::FETCH_INTO, new postSted());
            $rad = $resultat->fetchObject();

            //@codeCoverageIgnoreStart
            if (count($rad) < 1) {
                $this->db->rollback();
                return "Feil";
            }
            //@codeCoverageIgnoreEnd
        }
        // oppdater Kunde-tabellen
        $sql = "Update Kunde Set Fornavn = '$kunde->fornavn', Etternavn = '$kunde->etternavn',";
        $sql .= " Adresse = '$kunde->adresse', Postnr = '$kunde->postnr',";
        $sql .= " Telefonnr = '$kunde->telefonnr', Passord ='$kunde->passord'";
        $sql .= " Where Personnummer = '$kunde->personnummer'";
        $resultat = $this->db->query($sql);
        $this->db->commit();
        return "OK";
    }


    function hentKundeInfo($personnummer)
    {
        $kunde = new kunde();
        $sql = "Select * from Kunde Where Personnummer = '$personnummer'";
        $resultat = $this->db->query($sql);
        $resultat->setFetchMode(PDO::FETCH_INTO, new kunde());
        $rad = $resultat->fetchObject();

        if (empty($rad) || count($rad) != 1) {
            return "Feil";
        }

        if (!empty($rad)) {
            $kunde->personnummer = $rad->Personnummer;
            $kunde->fornavn = $rad->Fornavn;
            $kunde->etternavn = $rad->Etternavn;
            $kunde->adresse = $rad->Adresse;
            $kunde->telefonnr = $rad->Telefonnr;
            $kunde->passord = $rad->Passord;
            $kunde->postnr = $rad->Postnr;
        }

        $sql = "Select Poststed from Poststed Where Postnr = '$kunde->postnr'";
        $resultat = $this->db->query($sql);
        $resultat->setFetchMode(PDO::FETCH_INTO, new postSted());
        $rad = $resultat->fetchObject();
        if (empty($rad) || count($rad) != 1) {
            return "Feil";// @codeCoverageIgnore
        }
        $kunde->poststed = $rad->Poststed;
        return $kunde;
    }
}