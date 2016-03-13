<?php
include_once '../Model/domeneModell.php';
include_once '../DAL/adminDatabaseStubSqlite.php';
include_once '../BLL/adminLogikk.php';


/**
 * Created by PhpStorm.
 * User: S163472
 * Date: 09.03.2016
 * Time: 00:17
 */
class slettKundeTest extends PHPUnit_Framework_TestCase
{

    public function testSlettGyldigKunde()
    {
        //Arrange
        $admin = new Admin(new adminDBStubSqlite());
        $personnummer = "12345678901";
        //Act
        $OK = $admin->slettKunde($personnummer);
        //Assert
        $this->assertEquals("OK", $OK);
    }

    public function testSlettUgyldigKunde()
    {
        //Arrange
        $admin = new Admin(new adminDBStubSqlite());
        $personnummer = "987654321";
        //Act
        $OK = $admin->slettKunde($personnummer);
        //Assert
        $this->assertEquals("Feil", $OK);
    }




}
