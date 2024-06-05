<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Карта</title>
    <style>
        #myMap {
            width: 100%;
            height: 500px;
        }
    </style>
    <script type='text/javascript' src='https://www.bing.com/api/maps/mapcontrol?callback=loadMapScenario' async defer></script>
    <script>
        function loadMapScenario() {
            var map = new Microsoft.Maps.Map(document.getElementById('myMap'), {
                credentials: 'Your_Bing_Maps_Key',
                center: new Microsoft.Maps.Location(47.6062, -122.3321),
                zoom: 8
            });
        }
    </script>
</head>
<body>
    <div id='myMap'></div>
</body>
</html>
