<?php

/**
 * Created by PhpStorm.
 * User: S163472
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
        $this->assertCount(1, $konti);
        foreach ($konti as $konto) {
            $this->assertObjectHasAttribute("kontonummer", $konto);
            $this->assertEquals("22334412345", $konto->kontonummer);
        }
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
        //konto2
        $this->assertEquals('105020123456', $konti[1]->kontonummer);
    }

}
