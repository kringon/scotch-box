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
class slettKontoTest extends PHPUnit_Framework_TestCase
{

    public function testSlettGyldigKonto()
    {
        //Arrange
        $admin = new Admin(new adminDBStubSqlite());
        $kontonummer = "234567";
        //Act
        $OK = $admin->slettKonto($kontonummer);

        //Assert
        $this->assertEquals("OK", $OK);
    }

    public function testSlettUgyldigKonto()
    {
        //Arrange
        $admin = new Admin(new adminDBStubSqlite());
        $kontonummer = "6666666";
        //Act
        $OK = $admin->slettKonto($kontonummer);

        //Assert
        $this->assertEquals("Feil", $OK);
    }


}
