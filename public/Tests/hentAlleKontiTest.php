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
class hentAlleKontiTest extends PHPUnit_Framework_TestCase
{

    public function testEndreGyldigKonto()
    {
        //Arrange
        $admin = new Admin(new adminDBStubSqlite());
        //Act
        $konti = $admin->hentAlleKonti();
        //Assert
        $this->assertCount(5, $konti);
        $this->assertInstanceOf("konto", $konti[0]);
        $this->assertInstanceOf("konto", $konti[1]);
        $this->assertInstanceOf("konto", $konti[2]);
        $this->assertInstanceOf("konto", $konti[3]);
        $this->assertInstanceOf("konto", $konti[4]);
    }


}
