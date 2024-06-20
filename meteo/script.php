<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "meteo";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexiune eșuată: " . $conn->connect_error);
}

if(isset($_POST["Temperatura"]) && isset($_POST["Umiditate"]) && isset($_POST["Viteza"]) && isset($_POST["Directie"])) {

	$t = $_POST["Temperatura"];
	$h = $_POST["Umiditate"];
    $v = $_POST["Viteza"];
    $d = $_POST["Directie"];

	$sql = "INSERT INTO prognoza (Temperatura, Umiditate, Viteza, Directie) VALUES (".$t.", ".$h.", ".$v.", ".$d.")"; 

	if (mysqli_query($conn, $sql)) { 
		echo "\nNew record created successfully"; 
	} else { 
		echo "Error: " . $sql . "<br>" . mysqli_error($conn); 
	}
}

$sql = "SELECT * FROM Prognoza ORDER BY Id DESC LIMIT 1";
$result = $conn->query($sql);
$response = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $response['temperatura'] = $row["Temperatura"]; 
        $response['umiditate'] = $row["Umiditate"];
        $response['viteza_vant'] = $row["Viteza"];
        $response['direcția_vant'] = $row["Directie"];
    }
} else {
    $response['error'] = "Nu există date meteo disponibile.";
}

header('Content-Type: application/json');
echo json_encode($response);
$conn->close();
?>



