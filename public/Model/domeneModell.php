<?php
    class kunde
    {
        public $personnummer;
        public $fornavn;
        public $etternavn;
        public $adresse;
        public $postnr;
        public $poststed; 
        public $telefonnr;
        public $passord;

        public function testFornavn(){
            $this->fornavn = "Jørgen";
        }
    }
    class konto
    {
        public $personnummer;
        public $kontonummer;
        public $saldo;
        public $type;
        public $valuta;
        public $transaksjoner = array(); // av transaksjon
    }        
    class transaksjon
    {
        public $fraTilKontonummer;
        public $transaksjonBelop;
        public $belop;
        public $dato;
        public $melding;
        public $avventer;
    }
    class postSted
    {
        public $Postnr;
        public $Poststed;
    }
    
  

