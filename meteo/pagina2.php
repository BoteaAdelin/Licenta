<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Stație Meteo</title>
</head>
<body>
<div class="bg"></div>
<div class="container">
    <div class="menu">
        <ul>
            <li><a href="index.php">Pagina principală</a></li>
            <li><a href="pagina2.php">Viteză vânt</a></li>
            <li><a href="istoric.php">Istoric</a></li>
        </ul>
    </div>
    <div id="chartContainer" style="height: 370px; width: 100%;"></div>
    <button id="calculateAverageBtn">Viteza medie vânt</button>
    <div class="average-speed-container" id="averageSpeedContainer"></div>
</div>

<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>

<?php include 'script_pagina2.php'; ?>

<script>
document.getElementById('calculateAverageBtn').onclick = function() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'script_viteza_medie.php', true);
    xhr.onload = function() {
        if (xhr.status == 200) {
            document.getElementById('averageSpeedContainer').innerHTML = xhr.responseText;
        }
    };
    xhr.send();
};
</script>

</body>
</html>
