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
class registrerKundeTest extends PHPUnit_Framework_TestCase
{

    public function testRegistrerGyldigKunde()
    {
        //Arrange
        $admin = new Admin(new adminDBStubSqlite());
        $kunde = new kunde();

        $kunde->personnummer = "10018438454";
        $kunde->fornavn = "Maren";
        $kunde->etternavn = "Harnes";
        $kunde->adresse = "Hestadveien 6";
        $kunde->postnr = "3221";
        $kunde->poststed = "Sandefjord";
        $kunde->telefonnr = "91394658";
        $kunde->passord = "10018438454";

        //Act
        $OK = $admin->registrerKunde($kunde);
        //Assert
        $this->assertEquals("OK", $OK);
    }

    /**
     * @expectedException PDOException
     * @expectedExceptionCode 23000
     * @expectedExceptionMessage Integrity constraint violation: 19 UNIQUE constraint failed: Kunde.Personnummer
     *
     */
    public function testRegistrerUgyldigKunde()
    {
        //Arrange
        $admin = new Admin(new adminDBStubSqlite());
        $kunde = new kunde();

        $kunde->personnummer = "09048433711";
        $kunde->fornavn = "Maren";
        $kunde->etternavn = "Harnes";
        $kunde->adresse = "Hestadveien 6";
        $kunde->postnr = "3221";
        $kunde->poststed = "Sandefjord";
        $kunde->telefonnr = "91394658";
        $kunde->passord = "09048433711";

        //Act (Assert is in comments above)
        $OK = $admin->registrerKunde($kunde);
        //@codeCoverageIgnoreStart
    }
//@codeCoverageIgnoreEnd
    /**
     * @expectedException PDOException
     * @expectedExceptionCode 23000
     * @expectedExceptionMessage Integrity constraint violation: 19 NOT NULL constraint failed: Poststed.Postnr
     *
     */
    public function testRegistrereTomKunde()
    {
        //Arrange
        $admin = new Admin(new adminDBStubSqlite());
        $kunde = new kunde();//Act (Assert is in comments above)
        //Act
        $OK = $admin->registrerKunde($kunde);
        //@codeCoverageIgnoreStart
    }
//@codeCoverageIgnoreEnd

}
