<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    echo "Usuário não está logado.";
    exit();
}

$id_usuario = $_SESSION['id_usuario'];
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home-Center</title>
    <link rel="stylesheet" href="navegacao.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
    <link rel="icon" href="../logo.jpg" type="image/png">
</head>

<body>
    <div class="containeMenu">
        <div class="containeLink">
            <div class="header">
                <button onclick="window.location.href='http://localhost/bairro_alerta/PerfilUsuario/Perfil.php';">
                    <img src="../TelaNavegacao/perfil.webp" width="40" alt="Perfil">
                </button>
            </div>
            <a href="http://localhost/bairro_alerta/TelaDeOcorrencia/correncia.html">Registre a ocorrência</a>
            <a href="http://localhost/bairro_alerta/Notificacoes/feed_ocorrencias.php">Notificações</a>
        </div>

        <div class="search-container">
            <input id="search-input" type="text" placeholder="Digite um endereço..." autocomplete="off">
            <div id="autocomplete-list" class="autocomplete-items"></div>
            <button id="search-btn" type="button">Buscar</button>
            <button id="my-location-btn" type="button">Minha Localização</button>
        </div>
    </div>

    <div id="map"></div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let map, userLat, userLon;
            let routeControl = null;
            let currentMarkers = [];

            function initMap(lat, lon) {
                if (!map) {
                    map = L.map('map').setView([lat, lon], 13);

                    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                    }).addTo(map);
                } else {
                    map.setView([lat, lon], 13);
                }

                clearMapElements();

                const marker = L.marker([lat, lon]).addTo(map).bindPopup('Você está aqui!').openPopup();
                currentMarkers.push(marker);
            }

            function clearMapElements() {
                currentMarkers.forEach(marker => map.removeLayer(marker));
                currentMarkers = [];

                if (routeControl) {
                    map.removeControl(routeControl);
                    routeControl = null;
                }
            }

            function getLocation() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        userLat = position.coords.latitude;
                        userLon = position.coords.longitude;
                        initMap(userLat, userLon);
                    }, function() {
                        alert('Não foi possível obter sua localização.');
                        initMap(-15.77972, -47.92972);
                    });
                } else {
                    alert('Geolocalização não é suportada pelo seu navegador.');
                    initMap(-15.77972, -47.92972);
                }
            }

            function drawRoute(start, end) {
                clearMapElements();

                routeControl = L.Routing.control({
                    waypoints: [
                        L.latLng(start[0], start[1]),
                        L.latLng(end[0], end[1])
                    ],
                    lineOptions: {
                        styles: [{
                            color: 'red',
                            weight: 5
                        }]
                    },
                    createMarker: function(i, wp) {
                        const marker = L.marker(wp.latLng);
                        currentMarkers.push(marker);
                        return marker;
                    },
                    routeWhileDragging: false,
                    show: true
                }).addTo(map);
            }

            function searchAddress(address) {
                if (!userLat || !userLon) {
                    alert('Localização atual ainda não foi obtida.');
                    return;
                }

                const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}&limit=1&countrycodes=br`;

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        if (data.length > 0) {
                            const destLat = parseFloat(data[0].lat);
                            const destLon = parseFloat(data[0].lon);
                            drawRoute([userLat, userLon], [destLat, destLon]);
                        } else {
                            alert('Endereço não encontrado.');
                        }
                    })
                    .catch(error => {
                        console.error('Erro ao buscar o endereço:', error);
                        alert('Ocorreu um erro ao buscar o endereço.');
                    });
            }

            function autocomplete(input) {
                input.addEventListener("input", function() {
                    const val = this.value;
                    const list = document.getElementById("autocomplete-list");
                    list.innerHTML = '';

                    if (!val) return false;

                    const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(val)}&addressdetails=1&limit=5&countrycodes=br`;

                    fetch(url)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(function(item) {
                                const road = item.address.road || '';
                                const city = item.address.city || item.address.town || item.address.village || '';
                                const state = item.address.state || '';
                                const displayText = `${road} - ${city} - ${state}`;

                                const suggestion = document.createElement("div");
                                suggestion.classList.add("autocomplete-suggestion");
                                suggestion.innerHTML = displayText;

                                suggestion.addEventListener("click", function() {
                                    input.value = item.display_name;
                                    list.innerHTML = '';
                                    const lat = parseFloat(item.lat);
                                    const lon = parseFloat(item.lon);
                                    drawRoute([userLat, userLon], [lat, lon]);
                                });

                                list.appendChild(suggestion);
                            });
                        })
                        .catch(error => {
                            console.error('Erro ao obter sugestões:', error);
                        });
                });
            }

            document.getElementById('search-btn').addEventListener('click', function() {
                const address = document.getElementById('search-input').value;
                if (address) {
                    searchAddress(address);
                } else {
                    alert('Por favor, insira um endereço.');
                }
            });

            document.getElementById('my-location-btn').addEventListener('click', function() {
                if (userLat && userLon) {
                    initMap(userLat, userLon);
                } else {
                    alert('Localização atual não disponível.');
                }
            });

            autocomplete(document.getElementById('search-input'));
            getLocation();
        });
    </script>
</body>

</html>