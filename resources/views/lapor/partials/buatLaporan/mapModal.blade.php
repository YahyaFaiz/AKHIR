<div x-data="pinesForm()" x-init="init()">
    <button @click="openMapModal()" class="px-3 py-1 bg-black text-white text-sm">Perbaiki lokasi</button>

    <!-- MAP MODAL -->
    <template x-if="map.open">
        <!-- MAP MODAL -->
        <div x-show="map.open" x-transition.opacity class="fixed inset-0 z-50 bg-black/60">
            <div
                class="absolute inset-x-4 md:inset-x-20 inset-y-16 md:inset-y-12 bg-white rounded-md overflow-hidden border border-neutral-500 shadow-[8px_8px_0_#9ca3af] flex flex-col">
                <div class="p-3 flex items-center justify-between border-b border-neutral-500">
                    <h3 class="text-sm font-extrabold tracking-wider uppercase">Perbaiki Lokasi</h3>
                    <button @click="closeMapModal()"
                        class="p-2 rounded-md border border-neutral-500 bg-white hover:bg-neutral-50">
                        <svg class="w-5 h-5 text-black" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- area map -->
                <div class="flex-1 relative">
                    <!-- leaflet -->
                    <div id="mapPicker" class="absolute inset-0"></div>

                    <!-- PIN TETAP DI TENGAH -->
                    <div class="pointer-events-none absolute inset-0 flex items-center justify-center">
                        <div class="relative">
                            <div class="w-9 h-9 rounded-full bg-white border-4 border-black shadow-lg"></div>
                            <div class="w-1 h-6 bg-black/40 mx-auto mt-1 rounded-full"></div>
                        </div>
                    </div>
                </div>

                <div class="p-3 border-t border-neutral-500 flex items-center justify-between text-sm bg-white">
                    <div class="text-black">
                        Lat: <span class="font-semibold" x-text="gps.lat"></span>,
                        Lng: <span class="font-semibold" x-text="gps.lng"></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <button @click="saveMapLocation()"
                            class="px-4 py-2 rounded-md bg-black text-white font-extrabold border border-neutral-700 hover:-translate-y-0.5 hover:translate-x-0.5 shadow-[3px_3px_0_#9ca3af] transition">
                            Simpan
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </template>
</div>


<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>

<script>
    function pinesForm() {
        const base = {
            gps: {
                lat: -6.200000,
                lng: 106.816666
            },
            async init() {
                // kalau mau auto ambil GPS
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(pos => {
                        this.gps.lat = pos.coords.latitude.toFixed(6);
                        this.gps.lng = pos.coords.longitude.toFixed(6);
                    });
                }
            },
            // misal kamu punya ini di kode asli
            resolveLocation() {
                // panggil backend di sini kalau perlu
            }
        };

        return {
            ...base,
            ...mapHelper(base)
        };
    }

    function mapHelper(root) {
        return {
            map: {
                open: false,
                instance: null
            },
            openMapModal() {
                this.map.open = true;
                this.$nextTick(() => {
                    const lat = Number(root.gps.lat || 0);
                    const lng = Number(root.gps.lng || 0);

                    if (!this.map.instance) {
                        const m = L.map('mapPicker', {
                            zoomControl: true
                        }).setView([lat, lng], 18);

                        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                            maxZoom: 20,
                            attribution: '&copy; OpenStreetMap & CartoDB'
                        }).addTo(m);

                        // MODEL GOJEK: marker diem di tengah, yang gerak map
                        m.on('moveend', () => {
                            const c = m.getCenter();
                            root.gps.lat = c.lat.toFixed(6);
                            root.gps.lng = c.lng.toFixed(6);
                        });

                        this.map.instance = m;
                    } else {
                        this.map.instance.setView([lat, lng], 18);
                    }
                });
            },
            closeMapModal() {
                this.map.open = false;
            },
            saveMapLocation() {
                root.resolveLocation?.();
                this.closeMapModal();
            }
        };
    }
</script>
