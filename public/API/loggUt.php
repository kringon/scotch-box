<?php
session_start();
unset($_SESSION["loggetInn"]);
header("Location:/TestingBank/View/loggInn.php");
