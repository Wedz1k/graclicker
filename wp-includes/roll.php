<?php
include('user.php');
//include('wp-content/themes/twentytwentyone/page-47.php');
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



// Pobierz identyfikator sesji


// Jeśli użytkownik nie jest zalogowany, przekieruj go do strony logowania
//if (!$user_id) {
//    header('Location: login.php');
//    exit();
//}

// Pobierz dane postępu użytkownika
$select_query = "SELECT * FROM $db_table WHERE user_id = $user_id";
$result = $conn->query($select_query);


// Pobierz dane postępu użytkownika
$select_query = "SELECT * FROM $db_table WHERE user_id = $user_id";
$result = $conn->query($select_query);
$row = $result->fetch_assoc();

$points = $row['points'];
$autoclickerLevel = $row['autoclickerLevel'];
$autoclickerCost = $row['autoclickerCost'];
$clickerUprCost = $row['clickerUprCost'];
$autoclickerRate = $row['autoclickerRate'];
$clickerLvl = $row['clickerLvl'];
$clickerRate = $row['clickerRate'];
$pointsalltime = $row['pointsalltime'];
$acclvl = $row['acclvl'];
$last_update = $row['last_update'];
$count = $row['count'];
$stick1 = $row['stick1'];
$stick2 = $row['stick2'];
$stick3 = $row['stick3'];
$stick4 = $row['stick4'];
$stick5 = $row['stick5'];
$stick6 = $row['stick6'];



// Czas od ostatniej aktualizacji w sekundach
$time_since_last_update = time() - strtotime($last_update);

// Zwiększ punkty na podstawie czasu od ostatniej aktualizacji
//$points += $autoclickerLevel * $time_since_last_update;


// Aktualizuj czas ostatniej aktualizacji
$update_last_update_query = "UPDATE $db_table SET last_update = CURRENT_TIMESTAMP WHERE user_id = $user_id";
$conn->query($update_last_update_query);






// Obsługa zakupu losowania naklejki
if (isset($_POST['buy_sticker'])) {
    $stick1 = $row['stick1'];
    $stick2 = $row['stick2'];
    $stick3 = $row['stick3'];
    $stick4 = $row['stick4'];
    $stick5 = $row['stick5'];
    $stick6 = $row['stick6'];
    $cost=150;
    if ($pointsalltime >= $cost) {
        $pointsalltime -= $cost;
        $count++;
        if ($count == 10) {
          $acclvl++;
          $count = 0;          
        }
        
        $stick = (rand( 0,  5));
        switch($stick+1){
            case 1:
                $update_query = "UPDATE $db_table SET pointsalltime = $pointsalltime, acclvl = $acclvl, count = $count, stick1=$stick1+1 WHERE user_id = $user_id";
                break;
            case 2:
                $update_query = "UPDATE $db_table SET pointsalltime = $pointsalltime, acclvl = $acclvl, count = $count, stick2=$stick2+1 WHERE user_id = $user_id";
                break;
            case 3:
                $update_query = "UPDATE $db_table SET pointsalltime = $pointsalltime, acclvl = $acclvl, count = $count, stick3=$stick3+1 WHERE user_id = $user_id";
                break;
            case 4:
                $update_query = "UPDATE $db_table SET pointsalltime = $pointsalltime, acclvl = $acclvl, count = $count, stick4=$stick4+1 WHERE user_id = $user_id";
                break;
            case 5:
                $update_query = "UPDATE $db_table SET pointsalltime = $pointsalltime, acclvl = $acclvl, count = $count, stick5=$stick5+1 WHERE user_id = $user_id";
                break;
            case 6:
                $update_query = "UPDATE $db_table SET pointsalltime = $pointsalltime, acclvl = $acclvl, count = $count, stick6=$stick6+1 WHERE user_id = $user_id";
                break;
        }
        $conn->query($update_query);
        echo json_encode(['points' => $pointsalltime, 'acclvl' => $acclvl, 'count'=>$count, 'num'=>$stick]);
    } else {
        echo json_encode(['error' => 'Niewystarczająca ilość punktów!']);
    }
}
// Pobierz postęp
if (isset($_POST['get_progress'])) {
    $select_query = "SELECT * FROM $db_table WHERE user_id = $user_id";
    $result = $conn->query($select_query);
    $row = $result->fetch_assoc();

    $pointsalltime = $row['pointsalltime'];
    $acclvl=$row['acclvl'];
    $count = $row['count'];

    $response = array(
    'acclvl' => $acclvl,
    'points'=> $pointsalltime,
    'count' => $count,
    'num' => $stick);
    exit(json_encode($response));
}


$conn->close();


function get_pointsalltime($user_id)
{
    global $conn, $db_table;
    $select_query = "SELECT pointsalltime FROM $db_table WHERE user_id = $user_id";
    $result = $conn->query($select_query);
    $row = $result->fetch_assoc();
    return $row['pointsalltime'];
}
?>    		