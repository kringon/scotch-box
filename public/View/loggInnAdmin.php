<?php
include_once 'minNavigasjon.php';
?>
<script type="text/javascript">
    $(function(){
        "use strict"; 
        
        $("#loggInn").click(function(){
            // dersom innloggingen var vellykket bør vi gå til adminKunde.php
            // dersom innloggingen ikke var vellykket står vi på samme sted
            var bruker = $("#bruker").val();
            var passord = $("#passord").val();
            var url = "../API/loggInnAdmin.php?bruker="+bruker+"&passord="+passord;

            $.getJSON(url,function(retur)
            {
                if(retur ==="Admin")
                {
                    $(location).attr('href', 'adminKunde.php');
                }
                else
                {
                    $("#feilMelding").html("Feil personnummer / passord!");
                }
            });
        });
        // input validering
        $("#bruker").change(function(){
            $('#feilBruker').html("");
            var regex = /[a-zøæåA-ZØÆÅ]{2,20}/;
            var personNr = $("#bruker").val();
            if(!regex.test(personNr))
            {
                $('#feilBruker').html("Bruker må være 2 til 20 tegn");
            }
        });
        
        $("#passord").change(function(){
            $('#feilPassord').html("");
            var regex = /.{4,30}/;
            var personNr = $("#passord").val();
            if(!regex.test(personNr))
            {
                $('#feilPassord').html("Minimum 4 tegn");
            }
        });
    });
</script>
<div class="container">
    <br/><br/>
    <h2>Logg inn Admin</h2>
    <p>Vennligst oppgi bruker og passord for å logge inn</p>
    <div class="row">
        <div class="col-md-3">
            <label >Bruker:</label>
            <input type="text" class="form-control" id="bruker"> 
            <span style="color: red" id="feilBruker"></span>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <label >Passord:</label>
            <input type="text" class="form-control" id="passord">
            <span style="color: red" id="feilPassord"></span>
        </div>
    </div>
    <br/>
    <button class="btn btn-success" id="loggInn">Logg inn</button>
    <br/> <br/>
    <div id="feilMelding"></div>
 </div>
     


