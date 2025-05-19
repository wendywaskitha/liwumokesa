<div>
    <div 
        id="map-{{ uniqid() }}" 
        class="w-full h-96 rounded-lg shadow-md"
        data-latitude="{{ $getState()['latitude'] ?? '' }}"
        data-longitude="{{ $getState()['longitude'] ?? '' }}"
        data-name="{{ $getState()['name'] ?? '' }}"
        data-address="{{ $getState()['address'] ?? '' }}"
    ></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Find all map elements
            const mapElements = document.querySelectorAll('[id^="map-"]');
            
            mapElements.forEach(function(mapElement) {
                // Get data from element attributes
                const lat = parseFloat(mapElement.dataset.latitude);
                const lng = parseFloat(mapElement.dataset.longitude);
                const name = mapElement.dataset.name;
                const address = mapElement.dataset.address;
                
                // Ensure valid coordinates
                if (isNaN(lat) || isNaN(lng) || !lat || !lng) {
                    mapElement.innerHTML = '<div class="flex items-center justify-center h-full bg-gray-100 text-gray-500">Lokasi belum tersedia</div>';
                    return;
                }
                
                // Initialize map
                const map = L.map(mapElement.id).setView([lat, lng], 14);
                
                // Add tile layer (OpenStreetMap)
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);
                
                // Add marker
                const marker = L.marker([lat, lng]).addTo(map);
                
                // Add popup if name/address exists
                if (name || address) {
                    const popupContent = `
                        ${name ? '<strong>' + name + '</strong>' : ''}
                        ${name && address ? '<br>' : ''}
                        ${address ? address : ''}
                    `;
                    marker.bindPopup(popupContent).openPopup();
                }
            });
        });
    </script>

    @pushOnce('scripts')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    @endPushOnce
</div>
