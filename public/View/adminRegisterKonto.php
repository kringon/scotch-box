<?php
include_once 'adminNavigasjon.php';
?>
<script type="text/javascript">
    "use strict"; 
    $(function(){
       $("#regKonto").click(function(){
            var url = "../API/adminRegistrerKonto.php";
            var data = {
                kontonummer     : $("#kontoNr").val(),
                type            : $("#type").val(),
                valuta          : $("#valuta").val(), 
                personnummer    : $("#personnummer").val()
            }
            $.post(url,data,function(data)
            {
                $("#feilPersonnr").html("");
                if(data === "Feil i personnummer")
                {
                    $("#feilPersonnr").html("Personnummer finnes ikke!");
                }
                else if(data ==="Feil innlogging")
                {
                    $(location).attr('href', 'loggInnAdmin.php');
                }
                else // OK!
                {
                    $(location).attr('href', 'adminKunde.php');
                } 
            });
           
        }); 
    });    
        
</script>
<div class="container">
    <br/><br/>
    <h2>Register konto</h2>
    <div class="row">
            <div class="col-md-5">
                <label >Kontonummer:</label>
                <input type="text" class="form-control" id="kontoNr">
            </div>
            <div class="col-md-5">
                <label >Type: </label>
                <input type="text" class="form-control" id="type">
            </div>
            <div class="col-md-5">
                <label >Valuta:</label>
                <input type="text" class="form-control" id="valuta">
            </div>
            <div class="col-md-5">
                <label >Personnummer:</label>
                <input type="text" class="form-control" id="personnummer">
            </div>
    </div>
    <br/>
    <button class="btn btn-success" id="regKonto">Registrer konto</button>
    <br/> <br/>
    <div id="feilPersonnr"></div>
</div>


