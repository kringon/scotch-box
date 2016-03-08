<?php
/**
 * Created by PhpStorm.
 * User: T820082
 * Date: 08.03.2016
 * Time: 12:10
 */


include_once '../Model/domeneModell.php';
include_once '../DAL/bankDatabaseStub.php';
include_once '../BLL/bankLogikk.php';

class hentSaldiTest extends \PHPUnit_Framework_TestCase
{
    public function testHentFlereSaldi()
    {
        //Arrange
        $bank = new Bank(new DBStub());
        $personnummer = '01010110523';
        //Act
        $saldi = $bank->hentSaldi($personnummer);
        //Assert
        $this->assertCount(2, $saldi);

        $this->assertEquals('105010123456', $saldi[0]->kontonummer);
        $this->assertEquals(720, $saldi[0]->saldo);
        $this->assertEquals('Lønnskonto', $saldi[0]->type);
        $this->assertEquals('NOK', $saldi[0]->valuta);

        $this->assertEquals('105020123456', $saldi[1]->kontonummer);
        $this->assertEquals(100500, $saldi[1]->saldo);
        $this->assertEquals('Sparekonto', $saldi[1]->type);
        $this->assertEquals('NOK', $saldi[1]->valuta);

    }

    public function testHentEnSaldo()
    {
        //Arrange
        $bank = new Bank(new DBStub());
        $personnummer = '12345678901';
        //Act
        $saldo = $bank->hentSaldi($personnummer);
        //Assert
        $this->assertCount(1, $saldo);
        $this->assertEquals('22334412345', $saldo[0]->kontonummer);
        $this->assertEquals(10234.5, $saldo[0]->saldo);
        $this->assertEquals('Brukskonto', $saldo[0]->type);
        $this->assertEquals('NOK', $saldo[0]->valuta);
    }

    public function testHentIngenSaldo(){
        //Arrange
        $bank = new Bank(new DBStub());
        $personnummer = '10987654321';
        //Act
        $saldo = $bank->hentSaldi($personnummer);
        //Assert
        $this->assertEmpty($saldo);
    }
    


}
