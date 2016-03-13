<?php
include_once '../Model/domeneModell.php';
include_once '../DAL/bankDatabaseStubSqlite.php';
include_once '../BLL/bankLogikk.php';
/**
 * Created by PhpStorm.
 * User: S163472
 * Date: 08.03.2016
 * Time: 22:10
 */
class utforBetalingTest extends PHPUnit_Framework_TestCase
{
    public function testUtførEnGyldigBetaling(){
        //Arrange
        $bank = new Bank(new DBStubSqlite());
        $TxID=11;
        //Act
        $OK = $bank->utforBetaling($TxID);
        //Assert
        $this->assertEquals("OK", $OK);
    }

    public function testUtførBetalingUgyldigTxID(){
        //Arrange
        $bank = new Bank(new DBStubSqlite());
        $TxID=-1;
        //Act
        $OK = $bank->utforBetaling($TxID);
        //Assert
        $this->assertEquals("Feil", $OK);
    }

    public function testUtførBetalingUgyldigKontonummer(){
        //Arrange
        $bank = new Bank(new DBStubSqlite());
        $TxID=12;
        //Act
        $OK = $bank->utforBetaling($TxID);
        //Assert
        $this->assertEquals("Feil", $OK);
    }

    public function testUtførBetalingMedDatabaseFeil(){
        //Arrange
        $bank = new Bank(new DBStubSqlite());
        $TxID=6;
        //Act
        $OK = $bank->utforBetaling($TxID);
        //Assert
        $this->assertEquals("Feil", $OK);
    }

}
