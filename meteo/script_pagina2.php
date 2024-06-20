<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "meteo";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexiune eșuată: " . $conn->connect_error);
}

$sql = "SELECT DateTime, Viteza FROM prognoza ORDER BY Id DESC LIMIT 10";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $dataPoints = array();
    while($row = $result->fetch_assoc()) {
        $dataPoints[] = array("label" => $row["DateTime"], "y" => $row["Viteza"]);
    }
} else {
    echo "Nu există date meteo disponibile pentru ultimele 10 înregistrări.";
}

$dataPoints = array_reverse($dataPoints);

$conn->close();

echo '<script>
window.onload = function () {
    var chart = new CanvasJS.Chart("chartContainer", {
        animationEnabled: true,
        title: {
            text: "Viteza vântului pentru ultimele 10 înregistrări"
        },
        axisY: {
            title: "Viteză Vânt (m/s)",
            valueFormatString: "#0",
        },
        axisX: {
            title: "Data/Ora",
            interval: 1,
            labelAngle: -50,
        },
        data: [{
            type: "line",
            markerSize: 5,
            xValueFormatString: "YYYY-MM-DD HH:mm:ss",
            yValueFormatString: "#0 m/s",
            dataPoints: ' . json_encode($dataPoints, JSON_NUMERIC_CHECK) . ',
            // Adăugăm opacitatea pentru a face diagrama transparentă
            lineAlpha: 0.5, // opacitatea liniei
            markerAlpha: 0.5 // opacitatea markerului
        }]        
        
    });

    chart.render();
}
</script>';
?>
