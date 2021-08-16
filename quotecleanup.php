<?php

//error_log(print_r($_SERVER, true));
if(!$_SERVER['HTTP_NIGHTBOT_USER'])
{
    echo "You're not Nightbot.";
}
else
{
    $query = $_GET['QUERY'];
    $servername = "localhost";
    $username = "twitch_daemon";
    $password = "PASSWORD";
    $dbname = "twitch";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }
        
    $conn->query("SET @var_name = 0");
    $conn->query("UPDATE quotes SET q_id = (@var_name := @var_name +1)");
    $conn->query("ALTER TABLE quotes AUTO_INCREMENT = 1");
    echo "Successfully renumbered quotes.";
    $sql->close();
    $conn->close();
}
?>
