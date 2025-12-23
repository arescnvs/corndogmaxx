<div class="min-h-screen bg-white flex flex-col items-center w-full"> 
    <div class="w-full max-w-xl bg-white shadow-md rounded-2xl">
        <div class="flex items-center justify-between p-4 border-b border-gray-200">
            <button wire:click="kembali" class="text-blue-500 text-lg font-semibold">&larr;</button>
            <h1 class="text-xl font-extrabold text-blue-600">Kirim ke Alamat</h1>
            <div class="w-6"></div>
        </div>

        <div class="px-4 mt-4 space-y-3">
            <label class="block text-gray-700 text-sm font-medium">Nama Pelanggan:</label>
            <input type="text" wire:model="namaPelanggan" 
                   class="w-full border border-gray-300 rounded-md p-3 focus:ring-2 focus:ring-blue-500"
                   placeholder="Masukkan nama Anda">
            @error('namaPelanggan') 
                <p class="text-red-600 text-sm">{{ $message }}</p> 
            @enderror
        </div>

        <div class="px-4 mt-2">
            <div id="map" wire:ignore class="w-full h-56 bg-gray-200 rounded-2xl shadow-sm overflow-hidden"></div>
        </div>

        <div class="flex-1 w-full px-6 mt-4">
            <p class="text-gray-700 text-sm mb-3">
                Tuliskan detail alamat (nomor rumah, jalan, dll):
            </p>

            <textarea wire:model="alamat" rows="2"
                class="w-full border border-gray-300 rounded-md p-3 focus:ring-2 focus:ring-blue-500 mb-1"
                placeholder="Contoh: Jl. Merdeka No. 123"></textarea>

            @error('alamat')
                <p class="text-red-600 text-sm mb-3">{{ $message }}</p>
            @enderror
            @error('lokasi')
                <p class="text-red-600 text-sm mb-3">{{ $message }}</p>
            @enderror

            <button wire:click="konfirmasiAlamat"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl shadow-md mb-6 transition">
                Konfirmasi Alamat
            </button>
        </div>
    </div>

    {{-- Map JS --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                let map;
                if (!window._corndogMap) {
                    map = L.map('map').setView([-6.295219745529388, 106.73510450931035], 17);
                    window._corndogMap = map;
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '&copy; OpenStreetMap'
                    }).addTo(map);
                } else {
                    map = window._corndogMap;
                }

                const tokoMarker = L.marker([-6.295219745529388, 106.73510450931035], {
                    icon: L.icon({
                        iconUrl: 'https://cdn-icons-png.flaticon.com/512/684/684908.png',
                        iconSize: [36, 36],
                        iconAnchor: [18, 36]
                    })
                }).addTo(map).bindPopup("Lokasi Toko Corndog Maxx");

                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition((pos) => {
                        const lat = pos.coords.latitude;
                        const long = pos.coords.longitude;

                        @this.call('setLokasi', lat, long);

                        const userMarker = L.marker([lat, long], {
                            icon: L.icon({
                                iconUrl: 'https://cdn-icons-png.flaticon.com/512/854/854878.png',
                                iconSize: [36, 36],
                                iconAnchor: [18, 36]
                            })
                        }).addTo(map).bindPopup("Lokasi Anda").openPopup();

                        const bounds = L.latLngBounds([[-6.2088, 106.8456], [lat, long]]);
                        map.fitBounds(bounds, { padding: [40, 40] });
                    });
                }
            }, 300);
        });
    </script>
</div>
