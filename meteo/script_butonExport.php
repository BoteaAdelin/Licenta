<?php
require_once 'phpspreadsheet/vendor/autoload.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "meteo";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexiune eșuată: " . $conn->connect_error);
}
$sql = "SELECT * FROM prognoza";
$result = $conn->query($sql);

$filename = "date_meteo.xlsx";
$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$rowCount = 1;
while ($row = $result->fetch_assoc()) {
    $col = 'A';
    foreach ($row as $key => $value) {
        $sheet->setCellValue($col . $rowCount, $value);
        $col++;
    }
    $rowCount++;
}
$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
$writer->save($filename);
echo "Fișierul Excel a fost creat cu succes: $filename";
$conn->close();
?>
