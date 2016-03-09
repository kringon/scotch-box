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
class registrerKontoTest extends PHPUnit_Framework_TestCase
{

    public function testRegistrereGyldigKonto()
    {
        //Arrange
        $admin = new Admin(new adminDBStubSqlite());
        $konto = new konto();
        $konto->kontonummer="666777";
        $konto->personnummer="09048433711";
        $konto->saldo=1000666.99;
        $konto->type="Momsunderdragelse";
        $konto->valuta="DKK";
        //Act
        $OK = $admin->registrerKonto($konto);
        //Assert
        $this->assertEquals("OK", $OK);
    }

    public function testRegistrereGyldigKontoPåUgyldigKunde()
    {
        //Arrange
        $admin = new Admin(new adminDBStubSqlite());
        $konto = new konto();
        $konto->kontonummer="666777";
        $konto->personnummer="666887";
        $konto->saldo=1000666.99;
        $konto->type="Momsunderdragelse";
        $konto->valuta="DKK";
        //Act
        $OK = $admin->registrerKonto($konto);
        //Assert
        $this->assertEquals("Feil", $OK);
    }


    /**
     * @expectedException PDOException
     * @expectedExceptionCode 23000
     * @expectedExceptionMessage Integrity constraint violation: 19 NOT NULL constraint failed: Konto.Kontonummer
     *
     */
    public function testRegistrereUgyldigKontoPåGyldigKunde()
    {
        //Arrange
        $admin = new Admin(new adminDBStubSqlite());
        $konto = new konto();

        $konto->personnummer="09048433711";

        //Act
        $OK = $admin->registrerKonto($konto);
    }
}
