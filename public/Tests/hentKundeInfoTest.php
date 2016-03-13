<?php

include_once '../Model/domeneModell.php';
include_once '../DAL/bankDatabaseStubSqlite.php';
include_once '../BLL/bankLogikk.php';

/**
 * Created by PhpStorm.
 * User: S163472
 * Date: 08.03.2016
 * Time: 23:27
 */
class hentKundeInfoTest extends PHPUnit_Framework_TestCase
{
    public function testHentKundeUgyldigPersonnummer(){
        //Arrange
        $bank = new Bank(new DBStubSqlite());
        $personnummer = "100184384578";
        //Act
        $OK = $bank->hentKundeInfo($personnummer);
        //Assert
        $this->assertNotInstanceOf("kunde",$OK);
        $this->assertEquals("Feil",$OK);

    }

    public function testHentKundeForLangtPersonnummer(){
        //Arrange
        $bank = new Bank(new DBStubSqlite());
        $personnummer = "10018438457812";
        //Act
        $OK = $bank->hentKundeInfo($personnummer);
        //Assert
        $this->assertEquals("Feil",$OK);

    }

    public function testHentKundeForkortPersonnummer(){
        //Arrange
        $bank = new Bank(new DBStubSqlite());
        $personnummer = "100184";
        //Act
        $OK = $bank->hentKundeInfo($personnummer);
        //Assert
        $this->assertEquals("Feil",$OK);

    }

    public function testHentGyldigKunde(){
        //Arrange
        $bank = new Bank(new DBStubSqlite());
        $personnummer = "09048433711";
        //Act
        $kunde = $bank->hentKundeInfo($personnummer);
        //Assert
        $this->assertInstanceOf("kunde",$kunde);

    }
}
