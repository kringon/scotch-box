<?php
include_once '../Model/domeneModell.php';

class adminDBStubSqlite extends PHPUnit_Extensions_Database_TestCase
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

    function hentAlleKunder()
    {
        $sql = "Select * from Kunde Left Join Poststed On Kunde.postnr = Poststed.postnr ";
        $resultat = $this->db->query($sql);
        $kunder = array();
        while ($rad = $resultat->fetchObject()) {
            $kunder[] = $rad;
        }
        return $kunder;
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

    function registrerKunde($kunde)
    {
        $this->db->beginTransaction();
        // Sjekk om nytt postnr ligger i Poststeds-tabellen, dersom ikke legg det inn
        $sql = "Select * from Poststed Where Postnr = '$kunde->postnr'";
        $resultat = $this->db->query($sql);
        $resultat->setFetchMode(PDO::FETCH_INTO, new postSted());
        $rad = $resultat->fetchObject();

        if (empty($rad) || count($rad != 1)) {

            // ligger ikke i poststedstabellen
            $query = $this->db->prepare("Insert Into Poststed (Postnr, Poststed) Values (?,?)");
            $query->execute(array($kunde->postnr, $kunde->poststed));
            $resultat = $query->rowCount();

            if ($resultat < 1) {
                //@codeCoverageIgnoreStart
                $this->db->rollback();
                return "Feil";
                //@codeCoverageIgnoreEnd
            }

        }
        $query = $this->db->prepare("Insert into Kunde (Personnummer,Fornavn,Etternavn,Adresse,Postnr,Telefonnr,Passord) Values (?,?,?,?,?,?,?)");
        $query->execute(array($kunde->personnummer, $kunde->fornavn, $kunde->etternavn, $kunde->adresse, $kunde->postnr, $kunde->telefonnr, $kunde->passord));
        $resultat = $query->rowCount();
        if ($resultat == 1) {
            $this->db->commit();
            return "OK";
        } else {
            //@codeCoverageIgnoreStart
            $this->db->rollback();
            return "Feil";
            //@codeCoverageIgnoreEnd
        }
    }

    function slettKunde($personnummer)
    {
        $query = $this->db->prepare("Delete From Kunde Where Personnummer = '$personnummer'");
        $query->execute();
        $resultat = $query->rowCount();

        if ($resultat == 1) {
            return "OK";
        } else {
            return "Feil";
        }
    }

    function registerKonto($konto)
    {
        $sql = "Select * from Kunde Where Personnummer = '$konto->personnummer'";
        $resultat = $this->db->query($sql);
        $resultat->setFetchMode(PDO::FETCH_INTO, new kunde());
        $rad = $resultat->fetchObject();

        if (empty($rad) || count($rad) != 1) {
            return "Feil";
        }
        $query = $this->db->prepare("Insert into Konto (Personnummer, Kontonummer, Saldo, Type, Valuta) Values (?,?,?,?,?)");
        $query->execute(array($konto->personnummer, $konto->kontonummer, $konto->saldo, $konto->type, $konto->valuta));
        $resultat = $query->rowCount();
        if ($resultat == 1) {
            return "OK";
            //@codeCoverageIgnoreStart
        } else {
            return "Feil";
        }
        //@codeCoverageIgnoreEnd
    }

    function endreKonto($konto)
    {
        $sql = "Select * from Kunde Where Personnummer = '$konto->personnummer'";
        $resultat = $this->db->query($sql);
        $resultat->setFetchMode(PDO::FETCH_INTO, new kunde());
        $rad = $resultat->fetchObject();

        if (empty($rad) || count($rad) != 1) {
            echo json_encode("Feil i personnummer");
            return "Feil";
        }//@codeCoverageIgnore

        $sql = "Select * from Konto Where Kontonummer = '$konto->kontonummer'";
        $resultat = $this->db->query($sql);
        $resultat->setFetchMode(PDO::FETCH_INTO, new konto());
        $rad = $resultat->fetchObject();
        if (empty($rad) || count($rad) != 1) {
            echo json_encode("Feil i kontonummer");
            return "Feil";
        }//@codeCoverageIgnore

        $sql = "Update Konto Set Personnummer = '$konto->personnummer', "
            . "Kontonummer = '$konto->kontonummer', Type = '$konto->type', "
            . "Saldo = '$konto->saldo', Valuta = '$konto->valuta' "
            . "Where Kontonummer = '$konto->kontonummer'";
        $resultat = $this->db->query($sql);
        return "OK";
    }//@codeCoverageIgnore

    function hentAlleKonti()
    {
        $sql = "Select * from Konto";
        $resultat = $this->db->query($sql);

        $sql = "Select * from Konto";
        $resultat = $this->db->query($sql);
        $resultat->setFetchMode(PDO::FETCH_INTO, new konto());
        $konti = $resultat->fetchAll();

        return $konti;
    }

    function slettKonto($kontonummer)
    {
        $query = $this->db->prepare("Delete from Konto Where Kontonummer = '$kontonummer'");
        $query->execute();
        $resultat = $query->rowCount();

        if ($resultat != 1) {
            echo json_encode("Feil kontonummer");
            return "Feil";
        }
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
            return "Feil";//@codeCoverageIgnore
        }
        $kunde->poststed = $rad->Poststed;
        return $kunde;
    }
}
