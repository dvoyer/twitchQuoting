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
        
        $quote = trim(rawurldecode($arr[0]));
        $game = $arr[2];
        $stream_name = $arr[1];
        $date = $arr[3];
        
        $sql = $conn->prepare("INSERT INTO quotes (quote, date, game, stream_name) VALUES (?, ?, ?, ?)");
        $sql->bind_param("ssss", $quote, $date, $game, $stream_name);
        $sql->execute();

        //here's the backup nothing touches, just in case
        $sql = $conn->prepare("INSERT INTO back_quotes (quote, date, game, stream_name) VALUES (?, ?, ?, ?)");
        $sql->bind_param("ssss", $quote, $date, $game, $stream_name);
        $sql->execute();


        $full_list = $conn->query("SELECT * FROM quotes");
        $nrow = $full_list->num_rows;

        echo "Added quote " . $nrow . ".";

        $sql->close();
        $conn->close();
    }
}
?>