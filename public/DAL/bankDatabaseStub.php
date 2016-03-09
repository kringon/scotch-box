<?php
include_once '../Model/domeneModell.php';

class DBStub
{

    function hentKonti($personnummer)
    {
        //Lage konto1
        $konto1 = new konto();
        $konto1->kontonummer = '105010123456';
        $konto1->personnummer = '01010110523';


        //Lage konto2
        $konto2 = new konto();
        $konto2->kontonummer = '105020123456';
        $konto2->personnummer = '01010110523';


        //Lage konto3
        $konto3 = new konto();
        $konto3->kontonummer = '22334412345';
        $konto3->personnummer = '12345678901';


        $konti = array($konto1, $konto2, $konto3);
        $valgteKonti=array();
        foreach($konti as $konto){
            if($konto->personnummer == $personnummer)
                array_push($valgteKonti,$konto);
        }
        return $valgteKonti;


    }

    function hentSaldi($personnummer){
        //Lage konto1
        $konto1 = new konto();
        $konto1->kontonummer = '105010123456';
        $konto1->personnummer = '01010110523';
        $konto1->saldo = 720;
        $konto1->type = 'Lønnskonto';
        $konto1->valuta = 'NOK';

        //Lage konto2
        $konto2 = new konto();
        $konto2->kontonummer = '105020123456';
        $konto2->personnummer = '01010110523';
        $konto2->saldo = 100500;
        $konto2->type = 'Sparekonto';
        $konto2->valuta = 'NOK';

        //Lage konto3
        $konto3 = new konto();
        $konto3->kontonummer = '22334412345';
        $konto3->personnummer = '12345678901';
        $konto3->saldo = 10234.5;
        $konto3->type = 'Brukskonto';
        $konto3->valuta = 'NOK';

        $konti = array($konto1, $konto2, $konto3);

        $valgteKonti=array();
        foreach($konti as $konto){
            if($konto->personnummer == $personnummer)
                array_push($valgteKonti,$konto);
        }
        return $valgteKonti;
    }



    function hentTransaksjoner($kontoNr, $fraDato, $tilDato)
    {
        date_default_timezone_set("Europe/Oslo");
        $fraDato = strtotime($fraDato);
        $tilDato = strtotime($tilDato);
        if ($fraDato > $tilDato) {
            return "Fra dato må være større enn tildato";
        }
        $konto = new konto();
        $konto->personnummer = "010101234567";
        $konto->kontonummer = $kontoNr;
        $konto->type = "Sparekonto";
        $konto->saldo = 2300.34;
        $konto->valuta = "NOK";
        if ($tilDato < strtotime('2015-03-26')) {
            return $konto;
        }
        $dato = $fraDato;
        while ($dato <= $tilDato) {
            switch ($dato) {
                case strtotime('2015-03-26') :
                    $transaksjon1 = new transaksjon();
                    $transaksjon1->dato = '2015-03-26';
                    $transaksjon1->transaksjonBelop = 134.4;
                    $transaksjon1->fraTilKontonummer = "22342344556";
                    $transaksjon1->melding = "Meny Holtet";
                    $konto->transaksjoner[] = $transaksjon1;
                    break;
                case strtotime('2015-03-27') :
                    $transaksjon2 = new transaksjon();
                    $transaksjon2->dato = '2015-03-27';
                    $transaksjon2->transaksjonBelop = -2056.45;
                    $transaksjon2->fraTilKontonummer = "114342344556";
                    $transaksjon2->melding = "Husleie";
                    $konto->transaksjoner[] = $transaksjon2;
                    break;
                case strtotime('2015-03-29') :
                    $transaksjon3 = new transaksjon();
                    $transaksjon3->dato = '2015-03-29';
                    $transaksjon3->transaksjonBelop = 1454.45;
                    $transaksjon3->fraTilKontonummer = "114342344511";
                    $transaksjon3->melding = "Lekeland";
                    $konto->transaksjoner[] = $transaksjon3;
                    break;
            }
            $dato += (60 * 60 * 24); // en dag i sekunder
        }
        return $konto;
    }
}