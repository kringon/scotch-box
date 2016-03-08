<?php

include_once '../Model/domeneModell.php';
include_once '../DAL/bankDatabaseStubSqlite.php';
include_once '../BLL/bankLogikk.php';

class registrerBetalingTest extends PHPUnit_Framework_TestCase
{
    public function testRegistrerGyldigBetaling(){
        //Arrange
        $bank = new Bank(new DBStubSqlite());
        $transaksjon = new transaksjon();
        $transaksjon->belop=666;
        $transaksjon->dato='2015-03-26';
        $transaksjon->fraTilKontonummer="22342344556";
        $transaksjon->melding="Det er en fin dag for betaling av skatt";

        $kontonr="234567";
        //Act
        $OK = $bank->registrerBetaling($kontonr,$transaksjon);
        //Assert
        $this->assertEquals("OK", $OK);
   }

    public function testRegistrerUgyldigBetaling(){
        //Arrange
        $bank = new Bank(new DBStubSqlite());
        $transaksjon = new transaksjon();
        $transaksjon->belop= 'fail';
        $transaksjon->dato='2015-03-26';
        $transaksjon->fraTilKontonummer="22342344556";
        $transaksjon->melding="Det er en fin dag for betaling av skatt";

        $kontonr="234567";
        //Act
        $OK = $bank->registrerBetaling($kontonr,$transaksjon);
        //Assert
        $this->assertEquals("Feil", $OK);
    }

}