<?php
$servername = "localhost";
$username = "twitch_daemon";
$password = "PASSWORD";
$dbname = "twitch";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$query = $_GET['QUERY'];

if($query == "list")
{
    echo "http://[HOST]/list.php";
}
else
{
    $full_list = $conn->query("SELECT * FROM quotes");
    $nrow = $full_list->num_rows;
    $qn = 0;
    $output = false;

    if(is_numeric($query) && $query != "")
    {
        $querynum = intval($query);
        if ($querynum <= $nrow)
        {
            $qn = $querynum;
        }
    }
    if($qn)
    {
        $sql = "SELECT * FROM quotes WHERE q_id = $qn";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        echo "Quote #" . $qn . " was made while playing " . $row["game"] . " on " . $row["date"] . " during stream \"" . $row["stream_name"] . "\".";
    }
    else
    {
        echo "No matching quote found.";
    }
}
?>