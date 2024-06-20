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
    <div class="widget">
        <div class="left">
            <img src="images/weather.svg" alt="Imagine SVG" width="70" height="70" class=icon>
            <h5 class="weather-status">Date Meteo</h5>
        </div>
        <div class="right">
            <h5 class="temperatura">Temperatură</h5>
            <h5 class="degree" id="temperatura">--&#176;C</h5>
        </div>
        <div class="bottom">
            <div class="Umiditate">
                 Umiditate <span id="umiditate">--%</span>
            </div>
            <div class="Viteza">
                 Viteza vântului <span id="viteza_vant">-- m/s</span>
            </div>
            <div class="Directie">
                 Direcţia vântului <span id="direcția_vant">--°</span>
            </div>
        </div>
    </div>
</div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function afiseazaDate() {
            $.ajax({
                url: 'script.php',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (!response.error) {
                        $('#temperatura').text(response.temperatura + '°C');
                        $('#umiditate').text(response.umiditate + '%');
                        $('#viteza_vant').text(response.viteza_vant + 'm/s');
                        $('#direcția_vant').text(response.direcția_vant + '°');
                    } else {
                        console.error(response.error);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Eroare la preluarea datelor meteo:", error);
                }
            });
        }
        $(document).ready(function() {
            afiseazaDate();
            setInterval(afiseazaDate, 5000);
        });
    </script>
    
</body>
</html>
