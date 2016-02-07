<?php
include_once 'adminNavigasjon.php';
?>
<script type="text/javascript">
    "use strict"; 
    $(function(){
        
        $("#regKunde").click(function(){
            var url = "../API/adminRegistrerKunde.php";
            var data = {
                personnummer    : $("#personnummer").val(),
                fornavn         : $("#fornavn").val(),
                etternavn       : $("#etternavn").val(), 
                adresse         : $("#adresse").val(),
                postnr          : $("#postnr").val(),
                poststed        : $("#poststed").val(),
                telefonnr       : $("#telefonnr").val(),
                passord         : $("#passord").val()
            }
            $.post(url,data,function(data)
            {
                if(data ==="Feil innlogging")
                {
                    $(location).attr('href', 'logInnAdmin.php');
                }
                $(location).attr('href', 'adminKunde.php');  
            });
           
        });
    });
 </script>       
        
<div class="container">
    <br/><br/>
    <h2>Registrer Kunde</h2>
        <div class="row">
            <div class="col-md-5">
                <label >Personnummer:</label>
                <input type="text" class="form-control" id="personnummer">
            </div>
            <div class="col-md-5">
                <label >Fornavn: </label>
                <input type="text" class="form-control" id="fornavn">
            </div>
            <div class="col-md-5">
                <label >Etternavn:</label>
                <input type="text" class="form-control" id="etternavn">
            </div>
            <div class="col-md-5">
                <label >Adresse:</label>
                <input type="text" class="form-control" id="adresse">
            </div>
            <div class="col-md-5">
                <label >Postnr:</label>
                <input type="text" class="form-control" id="postnr">
            </div>
            <div class="col-md-5">
                <label >Poststed:</label>
                <input type="text" class="form-control" id="poststed">
            </div>
            <div class="col-md-5">
                <label >Telefonnr:</label>
                <input type="text" class="form-control" id="telefonnr">
            </div>
            <div class="col-md-5">
                <label >Passord:</label>
                <input type="text" class="form-control" id="passord">
            </div>
        </div>
        <br/>
        <button class="btn btn-success" id="regKunde">Registrer kunde</button>
        <br/> <br/>
</div>