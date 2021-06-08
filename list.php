<?php
$servername = "localhost";
$username = "twitch_daemon";
$password = "PASSWORD";
$dbname = "twitch";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$full_list = $conn->query("SELECT * FROM quotes");
  while($row = $full_list->fetch_assoc()) {
      printf("%s. %s<br>", $row["q_id"], $row["quote"]);
  }
?>