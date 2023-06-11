<?php


include('user.php');
include('wp-content/themes/twentytwentyone/page-47.php');
session_start();

$user_id=$_SESSION['userid'];
//$user_id2=$_SESSION['userid2'];

// Połączenie z bazą danych MySQL
$db_host = 'localhost';
$db_user = 'bazaclicker';
$db_password = 'hP6pUptQcc';
$db_name = 'dedmenxd';
$db_table = 'clicker';


$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Sprawdź, czy udało się połączyć z bazą danych
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$select_query = "SELECT * FROM $db_table WHERE user_id = $user_id";
$result = $conn->query($select_query);
$row = $result->fetch_assoc();

$stick1 = $row['stick1'];
$stick2 = $row['stick2'];
$stick3 = $row['stick3'];
$stick4 = $row['stick4'];
$stick5 = $row['stick5'];
$stick6 = $row['stick6'];



if (isset($_POST['load_gallery'])) {
    $select_query = "SELECT * FROM $db_table WHERE user_id = $user_id";
    $result = $conn->query($select_query);
    $row = $result->fetch_assoc();
    
    $stick1 = $row['stick1'];
    $stick2 = $row['stick2'];
    $stick3 = $row['stick3'];
    $stick4 = $row['stick4'];
    $stick5 = $row['stick5'];
    $stick6 = $row['stick6'];
    exit(json_encode(['stick1' => $stick1,'stick2' => $stick2,'stick3' => $stick3,'stick4' => $stick4,'stick5' => $stick5,'stick6' => $stick6
    ]));
}
?>