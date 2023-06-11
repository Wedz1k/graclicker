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

// Utwórz tabelę clicker, jeśli nie istnieje
$create_table_query = "CREATE TABLE IF NOT EXISTS $db_table (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT(32) NOT NULL,
    points BIGINT(32) NOT NULL,
    autoclickerLevel BIGINT(32) NOT NULL,
    autoclickerCost BIGINT(32) NOT NULL,
    clickerUprCost BIGINT(32) NOT NULL,
    autoclickerRate BIGINT(32) NOT NULL,
    count BIGINT(32) NOT NULL,
    clickerLvl BIGINT(32) NOT NULL,
    clickerRate BIGINT(32) NOT NULL,
    pointsalltime BIGINT(32) NOT NULL,
    stick1 BIGINT(32) NOT NULL,
    stick2 BIGINT(32) NOT NULL,
    stick3 BIGINT(32) NOT NULL,
    stick4 BIGINT(32) NOT NULL,
    stick5 BIGINT(32) NOT NULL,
    stick6 BIGINT(32) NOT NULL,
    acclvl BIGINT(32) NOT NULL,
    last_update TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)";   
$conn->query($create_table_query);

// Pobierz identyfikator sesji


// Jeśli użytkownik nie jest zalogowany, przekieruj go do strony logowania
//if (!$user_id) {
//    header('Location: login.php');
//    exit();
//}

// Pobierz dane postępu użytkownika
$select_query = "SELECT * FROM $db_table WHERE user_id = $user_id";
$result = $conn->query($select_query);


// Jeśli nie ma żadnego rekordu dla użytkownika, utwórz nowy rekord
if ($result->num_rows === 0) {
    $insert_query = "INSERT INTO $db_table (user_id, points, autoclickerLevel, autoclickerCost, clickerUprCost, autoclickerRate,count, clickerLvl, clickerRate, pointsalltime, acclvl,stick1,stick2,stick3,stick4,stick5,stick6)
        VALUES ($user_id, 0, 0, 100, 5, 0, 0, 1, 1, 0, 1,0,0,0,0,0,0)";
    $conn->query($insert_query);
}

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



// Czas od ostatniej aktualizacji w sekundach
$time_since_last_update = time() - strtotime($last_update);

// Zwiększ punkty na podstawie czasu od ostatniej aktualizacji
//$points += $autoclickerLevel * $time_since_last_update;

// Obsługa kliknięcia przycisku
if (isset($_POST['click'])) {
    $points+=$clickerRate;
    $pointsalltime+=$clickerRate;
    $update_query = "UPDATE $db_table SET points = $points, pointsalltime=$pointsalltime WHERE user_id = $user_id";
    $conn->query($update_query);
    exit(json_encode(['points' => $points]));
}

//obsługa autoclikera
if (isset($_POST['autoclick'])) {
    $points+=$autoclickerRate;
    $pointsalltime+=$autoclickerRate;
    $update_query = "UPDATE $db_table SET points = $points, pointsalltime=$pointsalltime WHERE user_id = $user_id";
    $conn->query($update_query);
    exit(json_encode(['points' => $points]));
}

// Obsługa zakupu autoclickera
if (isset($_POST['buy_autoclicker'])) {
    if ($points >= $autoclickerCost) {
        $points -= $autoclickerCost;
        $autoclickerLevel++;
        if($autoclickerLevel==0){
            $autoclickerRate = 1;
            $autoclickerCost=20;
        }
        else{
            $autoclickerRate = 2**($autoclickerLevel);
            $autoclickerCost *= 2;
        }

        $update_query = "UPDATE $db_table SET points = $points, autoclickerLevel = $autoclickerLevel,
            autoclickerCost = $autoclickerCost, autoclickerRate=$autoclickerRate WHERE user_id = $user_id";
        $conn->query($update_query);
        exit(json_encode(['points' => $points, 'autoclickerLevel' => $autoclickerLevel, 'autoclickerCost' => $autoclickerCost]));
    } else {
        exit(json_encode(['error' => 'Insufficient points!']));
    }
}

// Obsługa zakupu ulepszenia clicker
if (isset($_POST['buy_clicker_upr'])) {
    if ($points >= $clickerUprCost) {
        $points -= $clickerUprCost;
        $clickerLvl++;
        $clickerRate=2**($clickerLvl-1);
        $clickerUprCost *= 3.75;
        

        $update_query = "UPDATE $db_table SET points = $points, clickerLvl = $clickerLvl,
            clickerUprCost = $clickerUprCost, clickerRate=$clickerRate WHERE user_id = $user_id";
        $conn->query($update_query);
        exit(json_encode(['points' => $points, 'clickerLvl' => $clickerLvl, 'clickerUprCost' => $clickerUprCost]));
    } else {
        exit(json_encode(['error' => 'Insufficient points!']));
    }
}

// Aktualizuj czas ostatniej aktualizacji
$update_last_update_query = "UPDATE $db_table SET last_update = CURRENT_TIMESTAMP WHERE user_id = $user_id";
$conn->query($update_last_update_query);


// Pobierz postęp
if (isset($_POST['get_progress'])) {
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
    $acclvl=$row['acclvl'];


    $response = array(
    'points' => $points, 
    'clickerLvl' => $clickerLvl,
    'clickerUprCost' => $clickerUprCost,
    'clickerRate' => $clickerRate,
    'autoclickerLevel' => $autoclickerLevel, 
    'autoclickerCost' => $autoclickerCost, 
    'autoclickerRate' => $autoclickerRate,
    'pointsalltime'=> $pointsalltime);
    exit(json_encode($response));
}

// Resetuj postęp
if (isset($_POST['reset_progress'])) {
    $select_query = "UPDATE $db_table SET points=0, autoclickerLevel=0, autoclickerCost=100, clickerUprCost=5, autoclickerRate=0, clickerLvl=1, clickerRate=1 where user_id=$user_id";
    $result = $conn->query($select_query);
    exit(json_encode(['error' => 'Postęp zresetowany!']));
}


$conn->close();

function get_points($user_id)
{
    global $conn, $db_table;
    $select_query = "SELECT points FROM $db_table WHERE user_id = $user_id";
    $result = $conn->query($select_query);
    $row = $result->fetch_assoc();
    return $row['points'];
}

function get_autoclickerLevel($user_id)
{
    global $conn, $db_table;
    $select_query = "SELECT autoclickerLevel FROM $db_table WHERE user_id = $user_id";
    $result = $conn->query($select_query);
    $row = $result->fetch_assoc();
    return $row['autoclickerLevel'];
}

function get_autoclickerCost($user_id)
{
    global $conn, $db_table;
    $select_query = "SELECT autoclickerCost FROM $db_table WHERE user_id = $user_id";
    $result = $conn->query($select_query);
    $row = $result->fetch_assoc();
    return $row['autoclickerCost'];

}

function get_clickerUprCost($user_id)
{
    global $conn, $db_table;
    $select_query = "SELECT clickerUprCost FROM $db_table WHERE user_id = $user_id";
    $result = $conn->query($select_query);
    $row = $result->fetch_assoc();
    return $row['clickerUprCost'];
}

function get_clickerLvl($user_id)
{
    global $conn, $db_table;
    $select_query = "SELECT clickerLvl FROM $db_table WHERE user_id = $user_id";
    $result = $conn->query($select_query);
    $row = $result->fetch_assoc();
    return $row['clickerLvl'];
}

function get_clickerRate($user_id)
{
    global $conn, $db_table;
    $select_query = "SELECT clickerRate FROM $db_table WHERE user_id = $user_id";
    $result = $conn->query($select_query);
    $row = $result->fetch_assoc();
    return $row['clickerRate'];
}
function get_pointsalltime($user_id)
{
    global $conn, $db_table;
    $select_query = "SELECT pointsalltime FROM $db_table WHERE user_id = $user_id";
    $result = $conn->query($select_query);
    $row = $result->fetch_assoc();
    return $row['pointsalltime'];
}
?>
