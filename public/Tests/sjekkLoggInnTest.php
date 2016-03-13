<?php

/**
 * Created by PhpStorm.
 * User: S163472
 * Date: 07.03.2016
 * Time: 22:24
 */
include_once '../Model/domeneModell.php';
include_once '../DAL/bankDatabaseStubSqlite.php';
include_once '../BLL/bankLogikk.php';

class loggInnTest extends PHPUnit_Framework_TestCase
{
    public function testFeilInputPersonnummer(){
        $personnummer = "0101012234";
        $passord ="123456";
        $bank=new Bank(new DBStubSqlite());
        $OK = $bank->sjekkLoggInn($personnummer,$passord);
        $this->assertEquals("Feil i personnummer",$OK);
    }

    public function testFeilInputPassord(){
        $personnummer = "01010122344";
        $passord ="12345";
        $bank=new Bank(new DBStubSqlite());
        $OK = $bank->sjekkLoggInn($personnummer,$passord);
        $this->assertEquals("Feil i passord",$OK);
    }

    public function testRiktigInnlogging(){
        $personnummer = "09048433711";
        $passord ="09048433711";
        $bank=new Bank(new DBStubSqlite());
        $OK = $bank->sjekkLoggInn($personnummer,$passord);
        $this->assertEquals("OK",$OK);
    }

    public function testfeilInnlogging(){
        $personnummer = "01010122344";
        $passord ="1234566";
        $bank=new Bank(new DBStubSqlite());
        $OK = $bank->sjekkLoggInn($personnummer,$passord);
        $this->assertEquals("Feil",$OK);
    }
}
