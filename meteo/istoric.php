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
    <form method="post" action="script_butonExport.php" class="export-button">
        <button type="submit">Exportă în Excel</button>
    </form>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Temperatura (°C)</th>
                <th>Umiditatea (%)</th>
                <th>Viteza vântului (m/s)</th>
                <th>Direcţia vântului</th>
                <th>Data/ora</th>
            </tr>
        </thead>
        <tbody id="weather-data">
        </tbody>
</table>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
    function afiseazaDate() {
        $.ajax({
            url: 'script_istoric.php', 
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (!response.error) {
                    var rows = '';
                    $.each(response, function(index, data) {
                        rows += '<tr>';
                        rows += '<td>' + data.ID + '</td>';
                        rows += '<td>' + data.Temperatura + '</td>';
                        rows += '<td>' + data.Umiditate + '</td>';
                        rows += '<td>' + data.Viteza + '</td>';
                        rows += '<td>' + data.Directie + '</td>';
                        rows += '<td>' + data.DateTime + '</td>';
                        rows += '</tr>';
                    });
                    $('#weather-data').html(rows);
                } else {
                    console.error(response.error);
                }
            },
            error: function(xhr, status, error) {
                console.error("Eroare la preluarea datelor meteo:", error);
            }
        });
    }

    afiseazaDate();
    setInterval(afiseazaDate, 5000);
});
</script>

</body>
</html>
