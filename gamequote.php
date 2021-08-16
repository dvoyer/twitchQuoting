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
    //$qn = mt_rand(1, $nrow);
    $output = false;

    if(is_string($query) && $query != "")
    {
        $arr = array();
        $regex = '/.*' . $query . '.*/i';
        while ($row = $full_list->fetch_assoc()) 
        {
            if(preg_match($regex, $row["game"]))
            {
                array_push($arr, $row["q_id"]);
            }
        }
        if(count($arr) > 0)
        {
            $qn = $arr[array_rand($arr,1)];
        }
    }
    if($qn)
    {
        $sql = "SELECT quote FROM quotes WHERE q_id = $qn";
        $result = $conn->query($sql);
        echo "$qn. ";
          while($row = $result->fetch_assoc()) {
          echo $row["quote"];   
          }
    }
    else
    {
        echo "No matching game found. (I don't like diacritics, try another word in the title)";
    }
}
?>