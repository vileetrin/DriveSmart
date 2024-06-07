<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="stylesheet" href="./css/map.css">
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
                credentials: 'AkV7s60cu9DkUy7nj_Js5CwSAERkhnYNXi-MrfMqad6kw7J8GVkUqx0n10a-dznH',
                center: new Microsoft.Maps.Location(50.4501, 30.5234),
                zoom: 15 
            });

            var polygonVertices1 = [
                new Microsoft.Maps.Location(50.4450, 30.5200),
                new Microsoft.Maps.Location(50.4450, 30.5300), 
                new Microsoft.Maps.Location(50.4550, 30.5300), 
                new Microsoft.Maps.Location(50.4550, 30.5200), 
                new Microsoft.Maps.Location(50.4500, 30.5150), 
                new Microsoft.Maps.Location(50.4400, 30.5200) 
            ];

            var polygonVertices2 = [
                new Microsoft.Maps.Location(50.4500, 30.5050), 
                new Microsoft.Maps.Location(50.4500, 30.5150), 
                new Microsoft.Maps.Location(50.4600, 30.5150),
                new Microsoft.Maps.Location(50.4600, 30.5050),
            ];

            var polygon1 = new Microsoft.Maps.Polygon(polygonVertices1, {
                fillColor: 'orange',
                strokeColor: 'orange',
                strokeThickness: 2
            });

            var polygon2 = new Microsoft.Maps.Polygon(polygonVertices2, {
                fillColor: 'blue',
                strokeColor: 'blue',
                strokeThickness: 2
            });

            map.entities.push(polygon1);
            map.entities.push(polygon2);

            var centerPoint1 = Microsoft.Maps.LocationRect.fromLocations(polygonVertices1[0], polygonVertices1[2]).getCenter();
            
            var centerPoint2 = Microsoft.Maps.LocationRect.fromLocations(polygonVertices2[0], polygonVertices2[2]).getCenter();
        }
    </script>
</head>
<body>
    <h1 class="maps-header">Мапа зон каршерингу та вільних авто</h1>
    <div id='myMap'></div>
</body>
</html>
