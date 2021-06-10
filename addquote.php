<?php
if(!$_SERVER['HTTP_NIGHTBOT_USER'])
{
    echo "You're not Nightbot.";
}
else
{
    $minstrlen = 10;
    $NAME = $_GET['QUERY'];
    $arr = explode("\x00", $NAME);
    if (strlen(rawurldecode($arr[0])) <= $minstrlen)
    {
        echo "If you want to add a quote, you need to add the quote.";
    }
    else
    {
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
        
        $checkQ = $conn->query("select * FROM `quotes` order by q_index desc limit 1");
        $check = $checkQ->fetch_array()[1];
        
        if($check != $nrow)
        {
            // the lists are out of sync, fix it
            $conn->query("SET @var_name = 0; UPDATE quotes SET q_id = (@var_name := @var_name +1); ALTER TABLE quotes AUTO_INCREMENT = 1");
            $full_list = $conn->query("SELECT * FROM quotes");
            $nrow = $full_list->num_rows;
        }

        $checkQ = $conn->query("select * FROM `quotes` order by q_index desc limit 1");
        $check = $checkQ->fetch_array()[1];
                
        $quote = trim(rawurldecode($arr[0]));
        $game = $arr[2];
        $stream_name = $arr[1];
        $date = $arr[3];
        
        $check = $check + 1;
        
        $sql = $conn->prepare("INSERT INTO quotes (q_id, quote, date, game, stream_name) VALUES (?, ?, ?, ?, ?)");
        $sql->bind_param("issss", $check, $quote, $date, $game, $stream_name);
        $sql->execute();

        //here's the backup nothing touches, just in case
        $sql = $conn->prepare("INSERT INTO back_quotes (quote, date, game, stream_name) VALUES (?, ?, ?, ?)");
        $sql->bind_param("ssss", $quote, $date, $game, $stream_name);
        $sql->execute();

        $checkQ = $conn->query("select * FROM `quotes` order by q_id desc limit 1");
        $check = $checkQ->fetch_array()[1];

        echo "Added quote " . $check . ".";

        $sql->close();
        $conn->close();
    }
}
?>