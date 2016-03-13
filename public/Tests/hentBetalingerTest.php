<?php
include_once '../Model/domeneModell.php';
include_once '../DAL/bankDatabaseStubSqlite.php';
include_once '../BLL/bankLogikk.php';

class hentBetalingerTest extends PHPUnit_Framework_TestCase
{

    public function testHentIngenBetalinger(){
        //Arrange
        $bank = new Bank(new DBStubSqlite());
        $personnummer = "12345678901";
        //Act
        $betalinger = $bank->hentBetalinger($personnummer);
        //Assert
        $this->assertEquals(0, count($betalinger));
        $this->assertEmpty($betalinger);
    }
    public function testHentEnBetaling(){
        //Arrange
        $bank = new Bank(new DBStubSqlite());
        $personnummer = "01010110523";
        //Act
        $betalinger = $bank->hentBetalinger($personnummer);
        //Assert
        $this->assertEquals(1, count($betalinger));
        $this->assertEquals("10",$betalinger[0]->TxID);
        $this->assertEquals("20102012345",$betalinger[0]->FraTilKontonummer);
        $this->assertEquals("24000",$betalinger[0]->Belop);
        $this->assertEquals("2012-12-12",$betalinger[0]->Dato);
        $this->assertEquals("Betaling til lånehai",$betalinger[0]->Melding);
        $this->assertEquals("105010123456",$betalinger[0]->Kontonummer);
        $this->assertEquals("1",$betalinger[0]->Avventer);
    }

    public function testHentFlereBetalinger(){
        //Arrange
        $bank = new Bank(new DBStubSqlite());
        $personnummer = "11223344556";
        //Act
        $betalinger = $bank->hentBetalinger($personnummer);
        //Assert
        $this->assertEquals(3, count($betalinger));
        $this->assertEquals("6",$betalinger[0]->TxID);
        $this->assertEquals("12312345",$betalinger[0]->FraTilKontonummer);
        $this->assertEquals("1234",$betalinger[0]->Belop);
        $this->assertEquals("2012-12-12",$betalinger[0]->Dato);
        $this->assertEquals("Melding",$betalinger[0]->Melding);
        $this->assertEquals("234567",$betalinger[0]->Kontonummer);
        $this->assertEquals("1",$betalinger[0]->Avventer);

        $this->assertEquals("11",$betalinger[1]->TxID);
        $this->assertEquals("12312345",$betalinger[1]->FraTilKontonummer);
        $this->assertEquals("99.99",$betalinger[1]->Belop);
        $this->assertEquals("2012-12-11",$betalinger[1]->Dato);
        $this->assertEquals("Avgift på smørbrød til lucifer selv",$betalinger[1]->Melding);
        $this->assertEquals("234567",$betalinger[1]->Kontonummer);
        $this->assertEquals("1",$betalinger[1]->Avventer);

        $this->assertEquals("3",$betalinger[2]->TxID);
        $this->assertEquals("20102012345",$betalinger[2]->FraTilKontonummer);
        $this->assertEquals("-1400.7",$betalinger[2]->Belop);
        $this->assertEquals("2015-03-13",$betalinger[2]->Dato);
        $this->assertEquals("Husleie",$betalinger[2]->Melding);
        $this->assertEquals("55551166677",$betalinger[2]->Kontonummer);
        $this->assertEquals("1",$betalinger[2]->Avventer);
    }
}
