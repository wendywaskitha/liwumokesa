<div>
    <div 
        id="route-map-{{ uniqid() }}" 
        class="w-full h-96 rounded-lg shadow-md"
        data-destinations="{{ json_encode($getState()['destinations'] ?? []) }}"
        data-package-name="{{ $getState()['package_name'] ?? 'Travel Package' }}"
    ></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Find all map elements
            const mapElements = document.querySelectorAll('[id^="route-map-"]');
            
            mapElements.forEach(function(mapElement) {
                // Get data from element attributes
                const destinationsData = JSON.parse(mapElement.dataset.destinations || '[]');
                const packageName = mapElement.dataset.packageName;
                
                if (destinationsData.length < 1) {
                    mapElement.innerHTML = '<div class="flex items-center justify-center h-full bg-gray-100 text-gray-500">Tidak ada data destinasi untuk ditampilkan</div>';
                    return;
                }
                
                // Initialize map with center on first destination
                const map = L.map(mapElement.id).setView([
                    destinationsData[0].latitude, 
                    destinationsData[0].longitude
                ], 10);
                
                // Add tile layer (OpenStreetMap)
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);
                
                // Add markers for each destination
                const routePoints = [];
                
                destinationsData.forEach((destination, index) => {
                    // Skip if coordinates are not provided
                    if (!destination.latitude || !destination.longitude) {
                        return;
                    }
                    
                    const coords = [destination.latitude, destination.longitude];
                    routePoints.push(coords);
                    
                    // Create custom icon with number
                    const customIcon = L.divIcon({
                        className: 'custom-marker-icon',
                        html: `<div class="bg-primary-500 text-white rounded-full w-6 h-6 flex items-center justify-center font-medium">${index + 1}</div>`,
                        iconSize: [24, 24],
                    });
                    
                    // Add marker
                    const marker = L.marker(coords, {icon: customIcon}).addTo(map)
                        .bindPopup(`<strong>${destination.name}</strong><br>Stop #${index + 1}`);
                    
                    if (index === 0) {
                        marker.setIcon(L.divIcon({
                            className: 'custom-marker-icon',
                            html: '<div class="bg-success-600 text-white rounded-full w-6 h-6 flex items-center justify-center font-medium">S</div>',
                            iconSize: [24, 24],
                        }));
                    } else if (index === destinationsData.length - 1) {
                        marker.setIcon(L.divIcon({
                            className: 'custom-marker-icon',
                            html: '<div class="bg-danger-600 text-white rounded-full w-6 h-6 flex items-center justify-center font-medium">F</div>',
                            iconSize: [24, 24],
                        }));
                    }
                });
                
                // Create a polyline connecting all destinations
                if (routePoints.length > 1) {
                    L.polyline(routePoints, {
                        color: '#3b82f6', 
                        weight: 4,
                        opacity: 0.7,
                        dashArray: '10, 10',
                        lineJoin: 'round'
                    }).addTo(map);
                    
                    // Fit map to show all points
                    map.fitBounds(routePoints);
                }
                
                // Add package name as title
                const title = L.control({position: 'topright'});
                title.onAdd = function(map) {
                    const div = L.DomUtil.create('div', 'map-info-box');
                    div.innerHTML = `<div class="bg-white p-2 rounded-md shadow-md text-sm font-medium">${packageName}</div>`;
                    return div;
                };
                title.addTo(map);
            });
        });
    </script>

    @pushOnce('scripts')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        .custom-marker-icon {
            display: flex !important;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
    </style>
    @endPushOnce
</div>
