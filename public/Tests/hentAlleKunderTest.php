<?php
include_once '../Model/domeneModell.php';
include_once '../DAL/adminDatabaseStubSqlite.php';
include_once '../BLL/adminLogikk.php';
/**
 * Created by PhpStorm.
 * User: T820082
 * Date: 09.03.2016
 * Time: 00:17
 */
class hentAlleKunderTest extends PHPUnit_Framework_TestCase
{

    public function testHentAlleKunder(){
        //Arrange
        $admin = new Admin(new DBStubSqlite());
        //Act
        $kunder = $admin->hentAlleKunder();
        //Assert
        $this->assertEquals(3, count($kunder));
        $this->assertObjectHasAttribute("Personnummer",$kunder[0]);
        $this->assertObjectHasAttribute("Fornavn",$kunder[0]);
        $this->assertObjectHasAttribute("Etternavn",$kunder[0]);
        $this->assertObjectHasAttribute("Adresse",$kunder[0]);
        $this->assertObjectHasAttribute("Postnr",$kunder[0]);
        $this->assertObjectHasAttribute("Telefonnr",$kunder[0]);
        $this->assertObjectHasAttribute("Passord",$kunder[0]);
        $this->assertObjectHasAttribute("Poststed",$kunder[0]);

    }
}
