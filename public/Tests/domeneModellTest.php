<?php
include_once '../Model/domeneModell.php';
/**
 * Created by PhpStorm.
 * User: S163472
 * Date: 09.03.2016
 * Time: 00:17
 */
class testDomeneModell extends PHPUnit_Framework_TestCase
{

    public function testKundeModell(){
        //Arrange

        $kunde = new kunde();
        //Act
        $kunde->testFornavn();
        //Assert
        $this->assertObjectHasAttribute("personnummer",$kunde);
        $this->assertObjectHasAttribute("fornavn",$kunde);
        $this->assertObjectHasAttribute("etternavn",$kunde);
        $this->assertObjectHasAttribute("adresse",$kunde);
        $this->assertObjectHasAttribute("postnr",$kunde);
        $this->assertObjectHasAttribute("poststed",$kunde);
        $this->assertObjectHasAttribute("telefonnr",$kunde);
        $this->assertObjectHasAttribute("passord",$kunde);
    }
    public function testKontoModell(){
        //Arrange

        $konto = new konto();
        //Act
        //Not necesarry
        //Assert
        $this->assertObjectHasAttribute("personnummer",$konto);
        $this->assertObjectHasAttribute("kontonummer",$konto);
        $this->assertObjectHasAttribute("saldo",$konto);
        $this->assertObjectHasAttribute("type",$konto);
        $this->assertObjectHasAttribute("valuta",$konto);
        $this->assertObjectHasAttribute("transaksjoner",$konto);
    }

    public function testTransaksjonModell(){
        //Arrange

        $transaksjon = new transaksjon();
        //Act
        //Assert
        $this->assertObjectHasAttribute("fraTilKontonummer",$transaksjon);
        $this->assertObjectHasAttribute("transaksjonBelop",$transaksjon);
        $this->assertObjectHasAttribute("belop",$transaksjon);
        $this->assertObjectHasAttribute("dato",$transaksjon);
        $this->assertObjectHasAttribute("melding",$transaksjon);
        $this->assertObjectHasAttribute("avventer",$transaksjon);
    }
}
