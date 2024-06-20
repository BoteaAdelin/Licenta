<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "meteo";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexiune eșuată: " . $conn->connect_error);
}

$sql = "SELECT * FROM (SELECT * FROM prognoza ORDER BY DateTime DESC LIMIT 10) sub ORDER BY DateTime ASC";
$result = $conn->query($sql);

$data = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode($data); 
} else {
    echo json_encode(array('error' => 'Nu există date meteo disponibile.'));
}

$conn->close();
?>



