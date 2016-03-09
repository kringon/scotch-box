<?php
include_once '../Model/domeneModell.php';
include_once '../DAL/adminDatabaseStubSqlite.php';
include_once '../BLL/adminLogikk.php';


/**
 * Created by PhpStorm.
 * User: T820082
 * Date: 09.03.2016
 * Time: 00:17
 */
class endreKundeInfoTest extends PHPUnit_Framework_TestCase
{

    public function testEndreGyldigKundeEttFelt()
    {
        //Arrange
        $admin = new Admin(new adminDBStubSqlite());


        $kunde = new kunde();
        $kunde->personnummer = "01010110523";
        $kunde->fornavn = "Lene";
        $kunde->etternavn = "Jensen";
        $kunde->adresse = "Askerveien 22";
        $kunde->postnr = "1387";
        $kunde->poststed = "Asker";
        $kunde->telefonnr = "22224444";
        $kunde->passord = "01010110523";

        //Act
        $kunde->etternavn = "Olsen";
        $OK = $admin->endreKundeInfo($kunde);
        $oppdatertKunde = $admin->hentKundeInfo($kunde->personnummer);
        //Assert
        $this->assertEquals($oppdatertKunde->etternavn, "Olsen");
    }

    public function testEndreGyldigKundeFlereFelter()
    {
        //Arrange
        $admin = new Admin(new adminDBStubSqlite());
        $kunde = new kunde();
        $kunde->personnummer = "01010110523";
        $kunde = $admin->hentKundeInfo("01010110523");
        $oppdatertKunde = clone $kunde;
        $oppdatertKunde->fornavn = "Lina";
        $oppdatertKunde->etternavn = "Jonvik";
        $oppdatertKunde->adresse = "Slaskebakken 22";
        //Act
        $OK = $admin->endreKundeInfo($oppdatertKunde);
        $oppdatertKunde = $admin->hentKundeInfo($kunde->personnummer);
        //Assert
        $this->assertNotEquals($oppdatertKunde->etternavn, $kunde->etternavn);
        $this->assertNotEquals($oppdatertKunde->adresse, $kunde->adresse);
        $this->assertNotEquals($oppdatertKunde->fornavn, $kunde->fornavn);
        $this->assertEquals('Lina', $oppdatertKunde->fornavn);
        $this->assertEquals('Jonvik', $oppdatertKunde->etternavn);
        $this->assertEquals('Slaskebakken 22', $oppdatertKunde->adresse);
    }

    public function testEndreUgyldigKunde()
    {
        //Arrange
        $admin = new Admin(new adminDBStubSqlite());


        $kunde = new kunde();
        $kunde->personnummer = "11122233312";
        $kunde->fornavn = "Ugyldig";
        $kunde->etternavn = "Person";
        $kunde->adresse = "Luftslåttveien 22";
        $kunde->postnr = "1387";
        $kunde->poststed = "Asker";
        $kunde->telefonnr = "22224444";
        $kunde->passord = "11122233312";

        //Act
        $OK = $admin->hentKundeInfo($kunde->personnummer);
        $this->assertEquals("Feil", $OK);
        $oppdatertKunde = clone $kunde;
        $oppdatertKunde->fornavn = "Lina";
        $oppdatertKunde->etternavn = "Jonvik";
        $oppdatertKunde->adresse = "Slaskebakken 22";

        $OK = $admin->endreKundeInfo($oppdatertKunde);
        $this->assertEquals("OK", $OK);
        $OK = $admin->hentKundeInfo($kunde->personnummer);
        $this->assertEquals("Feil", $OK);

    }

}
