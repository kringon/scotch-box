<?php
include_once '../Model/domeneModell.php';
include_once '../DAL/bankDatabaseStub.php';
include_once '../DAL/bankDatabaseStubSqlite.php';
include_once '../BLL/bankLogikk.php';

class hentTransaksjonerTest extends PHPUnit_Framework_TestCase {

    
    public function testDatoFeilTransaksjoner() 
    {
        // arrange
        $kontoNr = "10502023523";
        $fraDato = '2015-03-27';
        $tilDato = '2015-03-22';
        $bank=new Bank(new DBStub());
        // act
        $konto= $bank->hentTransaksjoner($kontoNr, $fraDato, $tilDato);
        // assert
        $this->assertEquals("Fra dato må være større enn tildato",$konto); 
    }
    
    public function testIngenTransaksjoner() 
    {
        // arrange
        $kontoNr = "10502023523";
        $fraDato = '2015-03-20';
        $tilDato = '2015-03-22';
        $bank=new Bank(new DBStub());
        // act
        $konto= $bank->hentTransaksjoner($kontoNr, $fraDato, $tilDato);
        // assert
        $this->assertEquals("010101234567",$konto->personnummer); 
        $this->assertEquals($kontoNr,$konto->kontonummer);
        $this->assertEquals("Sparekonto",$konto->type);
        $this->assertEquals(2300.34,$konto->saldo); 
        $this->assertEquals("NOK",$konto->valuta); 
        $tomtArray = array();
        $this->assertEquals($tomtArray,$konto->transaksjoner);
    }
     
    public function testEnTransaksjon() 
    {
        // arrange
        $kontoNr = "10502023523";
        $fraDato = '2015-03-26';
        $tilDato = '2015-03-26';
        $bank=new Bank(new DBStub());
        // act
        $konto= $bank->hentTransaksjoner($kontoNr, $fraDato, $tilDato);
        // assert
        $this->assertEquals("010101234567",$konto->personnummer); 
        $this->assertEquals($kontoNr,$konto->kontonummer);
        $this->assertEquals("Sparekonto",$konto->type);
        $this->assertEquals(2300.34,$konto->saldo); 
        $this->assertEquals("NOK",$konto->valuta); 
        $this->assertEquals('2015-03-26',$konto->transaksjoner[0]->dato);
        $this->assertEquals(134.4,$konto->transaksjoner[0]->transaksjonBelop);
        $this->assertEquals("22342344556",$konto->transaksjoner[0]->fraTilKontonummer);
        $this->assertEquals("Meny Holtet",$konto->transaksjoner[0]->melding);
    }
    public function testToTransaksjoner() 
    {
        // arrange
        $kontoNr = "10502023523";
        $fraDato = '2015-03-27';
        $tilDato = '2015-03-30';
        $bank=new Bank(new DBStub());
        // act
        $konto= $bank->hentTransaksjoner($kontoNr, $fraDato, $tilDato);
        // assert
        $this->assertEquals("010101234567",$konto->personnummer); 
        $this->assertEquals($kontoNr,$konto->kontonummer);
        $this->assertEquals("Sparekonto",$konto->type);
        $this->assertEquals(2300.34,$konto->saldo); 
        $this->assertEquals("NOK",$konto->valuta); 
        $this->assertEquals('2015-03-27',$konto->transaksjoner[0]->dato);
        $this->assertEquals(-2056.45,$konto->transaksjoner[0]->transaksjonBelop);
        $this->assertEquals("114342344556",$konto->transaksjoner[0]->fraTilKontonummer);
        $this->assertEquals("Husleie",$konto->transaksjoner[0]->melding);
        $this->assertEquals('2015-03-29',$konto->transaksjoner[1]->dato);
        $this->assertEquals(1454.45,$konto->transaksjoner[1]->transaksjonBelop);
        $this->assertEquals("114342344511",$konto->transaksjoner[1]->fraTilKontonummer);
        $this->assertEquals("Lekeland",$konto->transaksjoner[1]->melding);
    }
    public function testAlleTransaksjoner() 
    {
        // arrange
        $kontoNr = "105010123456";
        $fraDato = '';
        $tilDato = '';
        $bank=new Bank(new DBStubSqlite());
        // act
        $konto= $bank->hentTransaksjoner($kontoNr, $fraDato, $tilDato);
        // assert
        $this->assertEquals(6,count($konto->transaksjoner));
        $this->assertEquals("01010110523",$konto->personnummer);
        $this->assertEquals($kontoNr,$konto->kontonummer);
        $this->assertEquals("Lønnskonto",$konto->type);
        $this->assertEquals(720.0,$konto->saldo);
        $this->assertEquals("NOK",$konto->valuta);
        $this->assertEquals('2012-12-12',$konto->transaksjoner[0]->dato);
        $this->assertEquals(125.0,$konto->transaksjoner[0]->transaksjonBelop);
        $this->assertEquals("1234254365",$konto->transaksjoner[0]->fraTilKontonummer);
        $this->assertEquals("Hopp",$konto->transaksjoner[0]->melding);
        $this->assertEquals('2015-03-15',$konto->transaksjoner[1]->dato);
        $this->assertEquals(-100.5,$konto->transaksjoner[1]->transaksjonBelop);
        $this->assertEquals("20102012345",$konto->transaksjoner[1]->fraTilKontonummer);
        $this->assertEquals("Meny Storo",$konto->transaksjoner[1]->melding);
        $this->assertEquals('2015-03-20',$konto->transaksjoner[2]->dato);
        $this->assertEquals(400.4,$konto->transaksjoner[2]->transaksjonBelop);
        $this->assertEquals("20102012345",$konto->transaksjoner[2]->fraTilKontonummer);
        $this->assertEquals("Innebtaling",$konto->transaksjoner[2]->melding);
        $this->assertEquals('2015-03-30',$konto->transaksjoner[3]->dato);
        $this->assertEquals(-5000.5,$konto->transaksjoner[3]->transaksjonBelop);
        $this->assertEquals("20102012347",$konto->transaksjoner[3]->fraTilKontonummer);
        $this->assertEquals("Skatt",$konto->transaksjoner[3]->melding);
        $this->assertEquals('2012-12-12',$konto->transaksjoner[4]->dato);
        $this->assertEquals(15,$konto->transaksjoner[4]->transaksjonBelop);
        $this->assertEquals("234534678",$konto->transaksjoner[4]->fraTilKontonummer);
        $this->assertEquals("Hei",$konto->transaksjoner[4]->melding);
        $this->assertEquals('2012-12-12',$konto->transaksjoner[5]->dato);
        $this->assertEquals(3000.0,$konto->transaksjoner[5]->transaksjonBelop);
        $this->assertEquals("345678908",$konto->transaksjoner[5]->fraTilKontonummer);
        $this->assertEquals("",$konto->transaksjoner[5]->melding);
    }
}
