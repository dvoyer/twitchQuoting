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
        
    $full_list = $conn->query("SELECT * FROM quotes");
    $nrow = $full_list->num_rows;
    if(is_numeric($query) && $query + 0 <= $nrow)
    {
        $index = $query + 0;
        $sql = $conn->prepare("DELETE FROM quotes WHERE q_id=?");
        $sql->bind_param("i", $index);
        $sql->execute();
        $conn->query("SET @var_name = 0");
        $conn->query("UPDATE quotes SET q_id = (@var_name := @var_name +1)");
        $conn->query("ALTER TABLE quotes AUTO_INCREMENT = 1");
        echo "Successfully deleted quote " . $index . ".";
        $sql->close();
    }
    else
    {
        echo "Invalid number: " . $query . ".";
    }

    $conn->close();
}
?>


