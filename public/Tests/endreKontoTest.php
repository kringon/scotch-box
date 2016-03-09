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
class endreKontoTest extends PHPUnit_Framework_TestCase
{

    public function testEndreGyldigKonto()
    {
        //Arrange
        $admin = new Admin(new adminDBStubSqlite());
        $konto = new konto();
        $konto->personnummer="01010110523";
        $konto->kontonummer="105020123456";
        $konto->saldo=0;
        $konto->type="Sparekonto";
        $konto->valuta="NOK";

        //Act
        $OK = $admin->endreKonto($konto);
                //Assert
        $this->assertEquals("OK", $OK);
    }

    public function testEndreMedUgyldigPersonnummer()
    {
        //Arrange
        $admin = new Admin(new adminDBStubSqlite());
        $konto = new konto();
        $konto->personnummer="665587";
        $konto->kontonummer="234567";
        $konto->saldo=0;
        $konto->type="Sparekonto";
        $konto->valuta="NOK";

        //Act
        $OK = $admin->endreKonto($konto);
        //Assert
        $this->assertEquals("Feil", $OK);
    }

    public function testEndreMedUgyldigKontoNummer()
    {
        //Arrange
        $admin = new Admin(new adminDBStubSqlite());
        $konto = new konto();
        $konto->personnummer="01010110523";
        $konto->kontonummer="11223344556";
        $konto->saldo=0;
        $konto->type="Sparekonto";
        $konto->valuta="NOK";

        //Act
        $OK = $admin->endreKonto($konto);
        //Assert
        $this->assertEquals("Feil", $OK);
    }

}
