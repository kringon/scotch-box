<?php
include_once '../Model/domeneModell.php';
include_once '../DAL/bankDatabaseStubSqlite.php';
include_once '../BLL/bankLogikk.php';

/**
 * Created by PhpStorm.
 * User: S163472
 * Date: 09.03.2016
 * Time: 00:17
 */
class endreKundeInfoBLTest extends PHPUnit_Framework_TestCase
{
    public function testEndreGyldigKundeEttFelt()
    {
        //Arrange
        $bank = new Bank(new DBStubSqlite());
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
        $OK = $bank->endreKundeInfo($kunde);
        $oppdatertKunde = $bank->hentKundeInfo($kunde->personnummer);
        //Assert
        $this->assertEquals($oppdatertKunde->etternavn, "Olsen");
    }

    public function testEndreGyldigKundeFlereFelter()
    {
        //Arrange
        $bank = new Bank(new DBStubSqlite());
        $kunde = new kunde();
        $kunde->personnummer = "01010110523";
        $kunde = $bank->hentKundeInfo("01010110523");
        $oppdatertKunde = clone $kunde;
        $oppdatertKunde->fornavn = "Lina";
        $oppdatertKunde->etternavn = "Jonvik";
        $oppdatertKunde->adresse = "Slaskebakken 22";
        //Act
        $OK = $bank->endreKundeInfo($oppdatertKunde);
        $oppdatertKunde = $bank->hentKundeInfo($kunde->personnummer);
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
        $bank = new Bank(new DBStubSqlite());
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
        $OK = $bank->hentKundeInfo($kunde->personnummer);
        $this->assertEquals("Feil", $OK);
        $oppdatertKunde = clone $kunde;
        $oppdatertKunde->fornavn = "Lina";
        $oppdatertKunde->etternavn = "Jonvik";
        $oppdatertKunde->adresse = "Slaskebakken 22";
        $OK = $bank->endreKundeInfo($oppdatertKunde);
        $this->assertEquals("OK", $OK);
        $OK = $bank->hentKundeInfo($kunde->personnummer);
        $this->assertEquals("Feil", $OK);
    }
}
