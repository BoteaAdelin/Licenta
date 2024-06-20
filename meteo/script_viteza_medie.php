<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "meteo";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexiune eșuată: " . $conn->connect_error);
}

$sql = "SELECT Viteza FROM prognoza";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $sumOfSpeed = 0;
    while($row = $result->fetch_assoc()) {
        $sumOfSpeed += $row["Viteza"];
    }
    $averageSpeed = $sumOfSpeed / $result->num_rows;
    echo "Viteza medie a vântului din toate înregistrările: " . round($averageSpeed, 2) . " m/s";
} else {
    echo "Nu există date meteo disponibile.";
}
$conn->close();
?>