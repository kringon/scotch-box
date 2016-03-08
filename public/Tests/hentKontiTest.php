<?php

/**
 * Created by PhpStorm.
 * User: T820082
 * Date: 07.03.2016
 * Time: 22:24
 */
include_once '../Model/domeneModell.php';
include_once '../DAL/bankDatabaseStub.php';
include_once '../BLL/bankLogikk.php';

class hentKontiTest extends PHPUnit_Framework_TestCase
{

    public function testHentEnKonto()
    {

        $personnummer = '12345678901';
        $kontonummer = '22334412345';
        $bank = new Bank(new DBStub());
        $konti = $bank->hentKonti($personnummer);

        foreach ($konti as $konto) {
            $this->assertObjectHasAttribute("kontonummer", $konto);
            $this->assertEquals("22334412345", $konto->kontonummer);
        }
        $this->assertCount(1, $konti);
    }

    public function testIngenKontoer()
    {

        $personnummer = '10987654321';
        $kontonummer = '22334412345';
        $bank = new Bank(new DBStub());
        $konti = $bank->hentKonti($personnummer);
        $this->assertCount(0, $konti);
    }

    public function testFlereKontoer()
    {
        $personnummer = '01010110523';
        $bank = new Bank(new DBStub());
        $konti = $bank->hentKonti($personnummer);

        //Check array span (can be substituted for count, but fun to try other methods
        $this->assertGreaterThan(1, count($konti));
        $this->assertLessThan(3, count($konti));

        //konto1
        $this->assertEquals('105010123456', $konti[0]->kontonummer);
        $this->assertEquals('01010110523', $konti[0]->personnummer);
        $this->assertEquals(720, $konti[0]->saldo);
        $this->assertEquals('Lønnskonto', $konti[0]->type);
        $this->assertEquals('NOK', $konti[0]->valuta);

        //konto2
        $this->assertEquals('105020123456', $konti[1]->kontonummer);
        $this->assertEquals('01010110523', $konti[1]->personnummer);
        $this->assertEquals(100500, $konti[1]->saldo);
        $this->assertEquals('Sparekonto', $konti[1]->type);
        $this->assertEquals('NOK', $konti[1]->valuta);

    }

}
