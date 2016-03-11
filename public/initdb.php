<?php

$foo = true;

$DB = new mysqli("localhost", "root", "root", "bank");

    if ($db->connect_error) {

        $foo = false;
    }

$file = file_get_contents('DAL/bank.sql');

$query = $DB->multi_query($file);

$DB->close();

if (!$query)
    {
        print ('Error');
    }
?>
