@extends('lapor.layouts.app')

@section('body-class', 'bg-white')

@section('content')
    <div class="mx-auto max-w-6xl py-8 px-6 space-y-16">

        <!-- HERO -->
        <section class="text-center">
            <div class="inline-block px-4 py-1 border border-neutral-500 text-xs tracking-widest uppercase">WebGIS Kampus
            </div>
            <h1 class="mt-4 text-5xl font-extrabold text-black uppercase tracking-widest">
                TUGAS AKHIR
            </h1>
            <p class="mt-3 text-base text-gray-700">
                Pelapor kerusakan fasilitas berbasis lokasi dengan validasi partisipatif.
            </p>
            <div class="mt-6 flex items-center justify-center gap-3 text-xs text-gray-600">
                <div class="flex items-center gap-2"><span class="inline-block h-2 w-2 rounded-full bg-black"></span>
                    Real-time</div>
                <div class="flex items-center gap-2"><span class="inline-block h-2 w-2 rounded-full bg-black"></span> Akurat
                </div>
                <div class="flex items-center gap-2"><span class="inline-block h-2 w-2 rounded-full bg-black"></span>
                    Terukur</div>
            </div>
        </section>

        <!-- HIGHLIGHT FITUR (monokrom, border tipis, shadow abu) -->
        <section class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div
                class="relative bg-white border border-neutral-400 rounded-md p-6 text-left shadow-[4px_4px_0_#9ca3af] hover:translate-x-0.5 hover:-translate-y-0.5 transition">
                <div class="text-3xl">📸</div>
                <h3 class="mt-3 font-extrabold text-lg uppercase tracking-wide">Foto + GPS</h3>
                <p class="mt-2 text-gray-700">Foto wajib. Lokasi otomatis. Bukti kuat.</p>
            </div>
            <div
                class="relative bg-white border border-neutral-400 rounded-md p-6 text-left shadow-[4px_4px_0_#9ca3af] hover:translate-x-0.5 hover:-translate-y-0.5 transition">
                <div class="text-3xl">📍</div>
                <h3 class="mt-3 font-extrabold text-lg uppercase tracking-wide">Area Kampus</h3>
                <p class="mt-2 text-gray-700">Filter geofence. Hanya dalam kampus.</p>
            </div>
            <div
                class="relative bg-white border border-neutral-400 rounded-md p-6 text-left shadow-[4px_4px_0_#9ca3af] hover:translate-x-0.5 hover:-translate-y-0.5 transition">
                <div class="text-3xl">✅</div>
                <h3 class="mt-3 font-extrabold text-lg uppercase tracking-wide">Validasi Publik</h3>
                <p class="mt-2 text-gray-700">Voting sederhana. Transparan.</p>
            </div>
            <div
                class="relative bg-white border border-neutral-400 rounded-md p-6 text-left shadow-[4px_4px_0_#9ca3af] hover:translate-x-0.5 hover:-translate-y-0.5 transition">
                <div class="text-3xl">🧾</div>
                <h3 class="mt-3 font-extrabold text-lg uppercase tracking-wide">Audit & Ekspor</h3>
                <p class="mt-2 text-gray-700">Jejak perubahan. Ekspor CSV/PDF.</p>
            </div>
        </section>

        <!-- CTA -->
        <section class="text-center">
            <a href=""
                class="relative inline-block px-10 py-4 border border-neutral-700 bg-black text-white text-lg font-extrabold uppercase rounded-md shadow-[4px_4px_0_#9ca3af] hover:translate-x-0.5 hover:-translate-y-0.5 transition">
                Buat lapor
                <span
                    class="absolute -top-3 -right-3 bg-white border border-neutral-500 px-2 py-0.5 text-[10px] font-bold text-black rotate-3 shadow-[2px_2px_0_#9ca3af]">
                    lapor
                </span>
            </a>
        </section>

        <!-- MAP + STATS -->
        <section x-data="statSection" x-init="startCount()" class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
            <!-- Map -->
            <div id="map"
                class="lg:col-span-2 h-[420px] rounded-md border border-neutral-400 shadow-[4px_4px_0_#9ca3af]">
            </div>

            <!-- Stats -->
            <div class="space-y-4">
                <template x-for="item in stats" :key="item.label">
                    <div class="bg-white border border-neutral-400 rounded-md p-5 shadow-[3px_3px_0_#9ca3af]">
                        <div class="flex items-center justify-between">
                            <p class="text-xs tracking-wider uppercase text-gray-700" x-text="item.label"></p>
                            <span class="text-[10px] px-2 py-0.5 border border-neutral-400 rounded">Live</span>
                        </div>
                        <p class="text-3xl font-extrabold mt-1 text-black" x-text="item.display"></p>
                        <div class="mt-3 h-2 w-full bg-gray-100 border border-neutral-400 rounded">
                            <div class="h-full bg-black" :style="`width:${item.progress}%`"></div>
                        </div>
                    </div>
                </template>
            </div>
        </section>

        <!-- LEGEND (abu, bukan hitam pekat) -->
        <section class="flex flex-wrap justify-center gap-5 text-sm text-gray-700">
            <div class="flex items-center gap-2">
                <span class="inline-block"
                    style="width:14px;height:14px;border-radius:50%;background:#000;border:1px solid #fff"></span> Valid
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-block"
                    style="width:14px;height:14px;border-radius:50%;background:#fff;border:1px solid #9ca3af"></span>
                Pending
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-block"
                    style="width:12px;height:12px;background:#000;transform:rotate(45deg);border:1px solid #fff"></span>
                Proses
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-block align-middle"
                    style="width:14px;height:14px;background:
                    linear-gradient(45deg,transparent 45%,#000 45%,#000 55%,transparent 55%),
                    linear-gradient(-45deg,transparent 45%,#000 45%,#000 55%,transparent 55%);
                    border:1px solid #9ca3af;background-color:#fff"></span>
                Ditolak
            </div>
        </section>

        <!-- FOOTER MINI -->
        <footer class="pt-6 border-t border-neutral-400 text-xs text-gray-600">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-2">
                <div>&copy; {{ date('Y') }} WebGIS Pelaporan Kerusakan fasilitas kampus berbasis location-based services
                    (LBS) dengan validasi partisipatif di zona kampus</div>
                <div class="flex items-center gap-3">
                    <a href="" class="underline underline-offset-4">Lapor</a>
                    <span class="text-gray-300">|</span>
                    <a href="" class="underline underline-offset-4">Validasi</a>
                    <span class="text-gray-300">|</span>
                    <a href="" class="underline underline-offset-4">Beranda</a>
                </div>
            </div>
        </footer>
    </div>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="anonymous" />
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- Inisialisasi Map + Alpine (MONOKROM) -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const map = L.map('map', {
                zoomControl: true
            }).setView([-0.056, 109.343], 16);

            // Grayscale tiles
            L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; OpenStreetMap & CartoDB'
            }).addTo(map);

            const reports = [{
                    lat: -0.0562,
                    lng: 109.345,
                    status: 'Valid',
                    shape: 'dot-solid'
                },
                {
                    lat: -0.0555,
                    lng: 109.342,
                    status: 'Pending',
                    shape: 'dot-outline'
                },
                {
                    lat: -0.0571,
                    lng: 109.344,
                    status: 'Proses',
                    shape: 'diamond'
                },
                {
                    lat: -0.0558,
                    lng: 109.346,
                    status: 'Ditolak',
                    shape: 'x'
                },
            ];

            const shapeHtml = (shape) => {
                if (shape === 'dot-solid') {
                    return `<div style="width:16px;height:16px;border-radius:50%;background:#000;border:1px solid #fff;box-shadow:0 0 4px rgba(0,0,0,0.35)"></div>`;
                }
                if (shape === 'dot-outline') {
                    return `<div style="width:16px;height:16px;border-radius:50%;background:#fff;border:1px solid #9ca3af;box-shadow:0 0 4px rgba(0,0,0,0.25)"></div>`;
                }
                if (shape === 'diamond') {
                    return `<div style="width:14px;height:14px;background:#000;transform:rotate(45deg);border:1px solid #fff;box-shadow:0 0 4px rgba(0,0,0,0.25)"></div>`;
                }
                // 'x'
                return `<div style="width:16px;height:16px;background:
                        linear-gradient(45deg,transparent 45%,#000 45%,#000 55%,transparent 55%),
                        linear-gradient(-45deg,transparent 45%,#000 45%,#000 55%,transparent 55%);
                        border:1px solid #9ca3af;background-color:#fff;box-shadow:0 0 3px rgba(0,0,0,0.15)"></div>`;
            };

            reports.forEach(r => {
                const icon = L.divIcon({
                    className: "custom-marker",
                    html: shapeHtml(r.shape)
                });
                const m = L.marker([r.lat, r.lng], {
                    icon
                }).addTo(map);
                m.bindTooltip(`<b>${r.status}</b>`, {
                    direction: 'top'
                });
            });

            // Posisi user (ring abu)
            let userMarker = null;
            if (navigator.geolocation) {
                navigator.geolocation.watchPosition(pos => {
                    const {
                        latitude: lat,
                        longitude: lng
                    } = pos.coords;
                    const icon = L.divIcon({
                        html: `<div style="width:18px;height:18px;border-radius:50%;
                               background:#fff;border:3px solid #9ca3af;box-shadow:0 0 6px rgba(0,0,0,0.25)"></div>`
                    });
                    if (!userMarker) {
                        userMarker = L.marker([lat, lng], {
                            icon
                        }).addTo(map).bindTooltip('Posisi Saya');
                    } else {
                        userMarker.setLatLng([lat, lng]);
                    }
                }, err => console.warn('Gagal ambil lokasi:', err.message), {
                    enableHighAccuracy: true
                });
            }
        });

        // Alpine: stats monokrom
        document.addEventListener('alpine:init', () => {
            Alpine.data('statSection', () => ({
                stats: [{
                        label: 'Total Reports',
                        value: 1254,
                        display: 0,
                        progress: 85
                    },
                    {
                        label: 'Validated Reports',
                        value: 983,
                        display: 0,
                        progress: 78
                    },
                    {
                        label: 'Pending Reports',
                        value: 120,
                        display: 0,
                        progress: 40
                    },
                    {
                        label: 'In Process',
                        value: 87,
                        display: 0,
                        progress: 32
                    },
                    {
                        label: 'Rejected Reports',
                        value: 42,
                        display: 0,
                        progress: 20
                    },
                ],
                startCount() {
                    this.stats.forEach(item => {
                        let start = 0,
                            step = Math.max(1, Math.ceil(item.value / 60));
                        const t = setInterval(() => {
                            start += step;
                            if (start >= item.value) {
                                item.display = item.value.toLocaleString();
                                clearInterval(t);
                            } else {
                                item.display = start.toLocaleString();
                            }
                        }, 20);
                    });
                }
            }))
        });
    </script>
@endsection
