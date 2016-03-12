<?php

try {
    $con=mysqli_connect("localhost","root","root","bank");
    $sql = file_get_contents('DAL/Bank.sql');
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    if ($result = mysqli_multi_query($con,$sql)){

        if (count($result) == 1) {
            echo json_encode("OK");
        }else{
            echo json_encode("Feil ved databasereset");
        }
    }
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
