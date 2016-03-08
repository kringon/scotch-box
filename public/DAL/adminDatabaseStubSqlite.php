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
    
    function hentAlleKunder()
    {
        $sql = "Select * from Kunde Left Join Poststed On Kunde.postnr = Poststed.postnr ";
        $resultat = $this->db->query($sql);
        $kunder = array();
        while($rad = $resultat->fetchObject())
        {
            $kunder[]=$rad;
        }
        return $kunder;
    }
    
    function endreKundeInfo($kunde)
    {
        $this->db->autocommit(false);
        // Sjekk om nytt postnr ligger i Poststeds-tabellen, dersom ikke legg det inn
        $sql = "Select * from Poststed Where Postnr = '$kunde->postnr'";
        $resultat = $this->db->query($sql);
        if($this->db->affected_rows!=1)
        {
            // ligger ikke i poststedstabellen 
            $sql = "Insert Into Poststed (Postnr, Poststed) Values ('$kunde->postnr','$kunde->poststed')";
            $resultat = $this->db->query($sql);
            if($this->db->affected_rows < 1)
            {
                $this->db->rollback();
                return "Feil";
            }
        }
        // oppdater Kunde-tabellen
        $sql =  "Update Kunde Set Fornavn = '$kunde->fornavn', Etternavn = '$kunde->etternavn',";
        $sql .= " Adresse = '$kunde->adresse', Postnr = '$kunde->postnr',";
        $sql .= " Telefonnr = '$kunde->telefonnr', Passord ='$kunde->passord'";
        $sql .= " Where Personnummer = '$kunde->personnummer'";
        $resultat = $this->db->query($sql);
        $this->db->commit();
        return "OK";
    }
    
    function registrerKunde($kunde)
    {
        $this->db->autocommit(false);
        // Sjekk om nytt postnr ligger i Poststeds-tabellen, dersom ikke legg det inn
        $sql = "Select * from Poststed Where Postnr = '$kunde->postnr'";
        $resultat = $this->db->query($sql);
        if($this->db->affected_rows!=1)
        {
            // ligger ikke i poststedstabellen 
            $sql = "Insert Into Poststed (Postnr, Poststed) Values ('$kunde->postnr','$kunde->poststed')";
            $resultat = $this->db->query($sql);
            if($this->db->affected_rows < 1)
            {
                $this->db->rollback();
                return "Feil";
            }
        }
        
        $sql = "Insert into Kunde (Personnummer,Fornavn,Etternavn,Adresse,Postnr,Telefonnr,Passord)";
        $sql .= "Values ('$kunde->personnummer','$kunde->fornavn','$kunde->etternavn',"
                . "'$kunde->adresse','$kunde->postnr','$kunde->telefonnr','$kunde->passord')";
        $resultat = $this->db->query($sql);
        if($this->db->affected_rows==1)
        {
            $this->db->commit();
            return "OK";
        }
        else
        {
            $this->db->rollback();
            return "Feil";
        }
    }
    
    function slettKunde($personnummer)
    {
        $sql = "Delete From Kunde Where Personnummer = '$personnummer'";
        $resultat = $this->db->query($sql);
        if($this->db->affected_rows==1)
        {
            return "OK";
        }
        else
        {
            return "Feil";
        }    
    }
    
    function registerKonto($konto)
    {
        $sql = "Select * from Kunde Where Personnummer = '$konto->personnummer'";
        $resultat = $this->db->query($sql);
        if($this->db->affected_rows!=1)
        {
            echo json_encode("Feil i personnummer");
            die();
        }
        $sql = "Insert into Konto (Personnummer, Kontonummer, Saldo, Type, Valuta)";
        $sql .= "Values ('$konto->personnummer','$konto->kontonummer','$konto->saldo',"
                . "'$konto->type','$konto->valuta')";
        $resultat = $this->db->query($sql);
        if($this->db->affected_rows==1)
        {
            return "OK";
        }
        else
        {
            return "Feil";
        } 
    }
    
    function endreKonto($konto)
    {
        $sql = "Select * from Kunde Where Personnummer = '$konto->personnummer'";
        $resultat = $this->db->query($sql);
        if($this->db->affected_rows!=1)
        {
            echo json_encode("Feil i personnummer");
            die();
        }
        $sql = "Select * from Konto Where Kontonummer = '$konto->kontonummer'";
        $resultat = $this->db->query($sql);
        if($this->db->affected_rows!=1)
        {
            echo json_encode("Feil i kontonummer");
            die();
        } 
        
        $sql =  "Update Konto Set Personnummer = '$konto->personnummer', "
                . "Kontonummer = '$konto->kontonummer', Type = '$konto->type', "
                . "Saldo = '$konto->saldo', Valuta = '$konto->valuta' "
                . "Where Kontonummer = '$konto->kontonummer'";
        $resultat = $this->db->query($sql);
        return "OK";
    }
    
    function hentAlleKonti()
    {
        $sql = "Select * from Konto";
        $resultat = $this->db->query($sql);
        $konti=array();
        while($rad = $resultat->fetch_object())
        {
            $konti[]=$rad;
        }
        return $konti;
    }
    function slettKonto($kontonummer)
    {
        $sql = "Delete from Konto Where Kontonummer = '$kontonummer'";
        $resultat = $this->db->query($sql);
        if($this->db->affected_rows!=1)
        {
            echo json_encode("Feil kontonummer");
            die();
        }
        return "OK";
    }
}
