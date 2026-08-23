@extends('lapor.layouts.app')

@section('content')
    <div class="max-w-xl w-full mx-auto px-4 py-8 md:py-5" x-data="pinesForm()" x-init="init()" x-cloak>

        <!-- HEADER -->
        @include('lapor.partials.buatLaporan.header')

        <!-- CARD -->
        <form x-ref="form" method="POST" action="#" enctype="multipart/form-data"
            @submit.prevent="if (validate()) $refs.form.submit()"
            class="bg-white rounded-md border border-neutral-400 shadow-[4px_4px_0_#9ca3af] p-5 md:p-6 space-y-6">
            @csrf

            <!-- INFO STRIP -->
            @include('lapor.partials.buatLaporan.info')

            <!-- JUDUL -->
            <div class="space-y-2">
                <label class="text-sm font-semibold text-black" for="judul">Judul laporan</label>
                <input id="judul" name="judul" type="text" required maxlength="60"
                    placeholder="Contoh: Lampu taman mati"
                    class="w-full rounded-md border border-neutral-500 bg-white px-3 py-3 text-sm focus:outline-none focus:ring-0">
                {{-- <p class="text-[11px] text-neutral-600">Singkat, padat, jelas. Bukan novel.</p> --}}
            </div>

            <!-- MEDIA -->
            <div class="space-y-2">
                <label class="text-sm font-semibold text-black">Media</label>
                <div class="flex flex-wrap items-center gap-3">
                    <button type="button" @click="openCamera()"
                        class="inline-flex items-center gap-2 rounded-md border border-neutral-700 bg-black text-white px-4 py-3 text-sm hover:-translate-y-0.5 hover:translate-x-0.5 shadow-[3px_3px_0_#9ca3af] transition">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Ambil Foto</span>
                    </button>

                    <button type="button" @click="openMapModal()" :disabled="!gps.lat || !gps.lng"
                        class="inline-flex items-center gap-2 rounded-md border border-neutral-500 bg-white px-4 py-3 text-sm hover:bg-neutral-50 disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="w-5 h-5 text-black" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 7l9-4 9 4-9 4-9-4z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 17l9 4 9-4" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9 4 9-4" />
                        </svg>
                        <span>Perbaiki Lokasi</span>
                    </button>
                </div>

                <!-- PREVIEW -->
                <template x-if="photoData">
                    <div class="mt-3 rounded-md border border-neutral-500 p-3 flex gap-3">
                        <img :src="photoData" alt="preview"
                            class="w-24 h-24 rounded-sm object-cover border border-neutral-500">
                        <div class="text-sm space-y-1">
                            <div class="text-black">Lat: <span class="font-semibold" x-text="gps.lat || '-'"></span>,
                                Lng: <span class="font-semibold" x-text="gps.lng || '-'"></span></div>
                            <div class="text-black">Fakultas: <span class="font-semibold" x-text="faculty || '-'"></span>
                            </div>
                            <div class="text-black">Gedung: <span class="font-semibold" x-text="building || '-'"></span>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- KATEGORI (muncul otomatis setelah klik Gunakan) -->
            <!-- KATEGORI (muncul otomatis setelah klik Gunakan) -->
            <div class="space-y-2" x-show="categoryOpen" x-transition>
                <label class="text-sm font-semibold text-black">Kategori Kerusakan</label>

                <!-- Switch group -->
                <div class="flex items-center gap-2">
                    <button type="button" @click="categoryGroup='kelas'; resetCategory()"
                        :class="categoryGroup === 'kelas' ? 'bg-black text-white' : 'bg-white text-black'"
                        class="inline-flex items-center gap-2 rounded-md border border-neutral-700 px-3 py-2 text-sm">
                        Internal
                    </button>
                    <button type="button" @click="categoryGroup='luar'; resetCategory()"
                        :class="categoryGroup === 'luar' ? 'bg-black text-white' : 'bg-white text-black'"
                        class="inline-flex items-center gap-2 rounded-md border border-neutral-700 px-3 py-2 text-sm">
                        External
                    </button>
                </div>

                <!-- Pilihan kategori (dari DB) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                    <template x-for="opt in (categories[categoryGroup] || [])" :key="opt.value">
                        <label
                            class="cursor-pointer rounded-md border border-neutral-500 bg-white hover:bg-neutral-50 p-3 flex items-start gap-3">
                            <input class="hidden" type="radio" name="category" :value="opt.value" x-model="category"
                                @change="setCategory(opt)">
                            <span
                                class="inline-grid place-items-center w-5 h-5 rounded-full border border-neutral-600 mt-0.5">
                                <span class="w-2.5 h-2.5 rounded-full"
                                    :class="category === opt.value ? 'bg-black' : 'bg-transparent'"></span>
                            </span>
                            <span class="text-sm">
                                <span class="font-semibold text-black" x-text="opt.label"></span>
                                <span class="block text-[11px] text-neutral-600" x-text="opt.hint || ''"></span>
                            </span>
                        </label>
                    </template>
                </div>

                <!-- Input manual jika kategori mengizinkan -->
                <div x-show="categoryAllowManual" x-transition>
                    <input type="text" x-model="categoryOther" placeholder="Tulis detail kategori…"
                        class="w-full rounded-md border border-neutral-500 bg-white px-3 py-3 text-sm focus:outline-none focus:ring-0">
                </div>

                <!-- SUBKATEGORI (dari DB) -->
                <div class="space-y-2" x-show="Array.isArray(subs[category]) && (subs[category] || []).length" x-transition>
                    <label class="text-sm font-semibold text-black">Detail Kategori</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        <template x-for="opt in (subs[category] || [])" :key="opt.value">
                            <label
                                class="cursor-pointer rounded-md border border-neutral-500 bg-white hover:bg-neutral-50 p-3 flex items-start gap-3">
                                <input class="hidden" type="radio" name="subcategory" :value="opt.value"
                                    x-model="subcategory" @change="setSubcategory(opt)">
                                <span
                                    class="inline-grid place-items-center w-5 h-5 rounded-full border border-neutral-600 mt-0.5">
                                    <span class="w-2.5 h-2.5 rounded-full"
                                        :class="subcategory === opt.value ? 'bg-black' : 'bg-transparent'"></span>
                                </span>
                                <span class="text-sm">
                                    <span class="font-semibold text-black" x-text="opt.label"></span>
                                </span>
                            </label>
                        </template>
                    </div>

                    <!-- Manual jika subkategori 'lainnya' atau flag -->
                    <div x-show="subAllowManual" x-transition>
                        <input type="text" x-model="subcategoryOther" placeholder="Tulis detail subkategori…"
                            class="w-full rounded-md border border-neutral-500 bg-white px-3 py-3 text-sm focus:outline-none focus:ring-0">
                    </div>
                </div>

                <!-- Safety reset -->
                <div
                    x-effect="if(!Array.isArray(subs[category])) { subcategory=''; subcategoryOther=''; subAllowManual=false; }">
                </div>
            </div>

            <!-- CATATAN -->
            <div class="space-y-2">
                <label class="text-sm font-semibold text-black" for="catatan">Catatan</label>
                <textarea id="catatan" name="catatan" rows="3" placeholder="Tambahkan detail penting"
                    class="w-full rounded-md border border-neutral-500 bg-white px-3 py-3 text-sm focus:outline-none focus:ring-0"></textarea>
            </div>

            <!-- HIDDEN -->
            <input type="hidden" name="photo_data" :value="photoData">
            <input type="hidden" name="lat" :value="gps.lat">
            <input type="hidden" name="lng" :value="gps.lng">
            <input type="hidden" name="faculty" :value="faculty">
            <input type="hidden" name="building" :value="building">
            <input type="hidden" name="category_group" :value="categoryGroup">
            <input type="hidden" name="category_id" :value="category">
            <input type="hidden" name="category_name" :value="categoryName">
            <input type="hidden" name="category_other" :value="categoryOther">
            <input type="hidden" name="subcategory_id" :value="subcategory">
            <input type="hidden" name="subcategory_name" :value="subcategoryName">
            <input type="hidden" name="subcategory_other" :value="subcategoryOther">

            <!-- ACTIONS -->
            <div class="flex items-center justify-between gap-3 pt-1">
                <button type="submit"
                    class="w-[100%] px-6 py-2 h-11 text-sm font-extrabold rounded-md text-white bg-black border border-neutral-700 hover:-translate-y-0.5 hover:translate-x-0.5 shadow-[3px_3px_0_#9ca3af] transition">
                    Kirim
                </button>
            </div>
        </form>

        <!-- CAMERA MODAL -->
        <div x-show="camera.open" x-transition.opacity class="fixed inset-0 z-50 bg-black">
            <!-- HEADER: taruh di atas segalanya -->
            <div class="absolute top-0 left-0 right-0 p-3 flex justify-between items-center z-20">
                <span class="text-white text-xs px-2 py-0.5 rounded-sm border border-white/30">Kamera</span>
                <button type="button" @click="closeCamera()" class="text-white/90 hover:text-white">
                    <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- CENTER: jangan halangi klik di header -->
            <div class="absolute inset-0 flex items-center justify-center z-10 pointer-events-none">
                <video x-ref="video" autoplay playsinline class="max-h-full max-w-full pointer-events-auto"
                    x-show="!camera.captured"></video>
                <img :src="photoData" alt="preview" class="max-h-full max-w-full pointer-events-auto"
                    x-show="camera.captured">
            </div>

            <!-- BOTTOM CONTROLS: juga di atas layer video -->
            <div class="absolute bottom-10 inset-x-0 flex items-center justify-center gap-8 z-20">
                <button x-show="!camera.captured" @click="capturePhoto()"
                    class="w-20 h-20 rounded-full bg-white shadow-[0_0_0_8px_rgba(255,255,255,0.25)] active:scale-95 transition"></button>
                <div x-show="camera.captured" class="flex items-center gap-4">
                    <button @click="retake()"
                        class="px-4 py-2 rounded-md border border-white/30 text-white/90 bg-white/10">Ulangi</button>
                    <button @click="usePhoto()"
                        class="px-4 py-2 rounded-md bg-white text-black font-semibold border border-neutral-600">Gunakan</button>
                </div>
            </div>
        </div>


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

                <!-- dibikin LAZY, cuma ada pas map.open -->
                <template x-if="map.open">
                    <div class="flex-1 relative">
                        <!-- map di bawah -->
                        <div id="mapPicker" class="absolute inset-0 z-0"></div>

                        <!-- marker di atas map -->
                        <div class="pointer-events-none absolute inset-0 flex items-center justify-center z-[700]">
                            <!-- pulsasi -->
                            <div class="absolute w-16 h-16 rounded-full bg-black/5 border border-black/10 animate-ping">
                            </div>

                            <!-- badan pin -->
                            <div class="relative flex flex-col items-center -translate-y-3">
                                <div
                                    class="w-8 h-8 rounded-full bg-black border-2 border-white shadow-[0_8px_18px_rgba(0,0,0,0.35)] flex items-center justify-center">
                                    <div class="w-2.5 h-2.5 rounded-full bg-white/95"></div>
                                </div>
                                <div class="w-1 h-6 bg-black/85 rounded-b-full"></div>
                            </div>

                            <!-- bayangan -->
                            <div class="absolute bottom-[18%] w-10 h-3 bg-black/10 rounded-full blur-sm"></div>
                        </div>
                    </div>

                </template>

                <div class="p-3 border-t border-neutral-500 flex items-center justify-between text-sm">
                    <div class="text-black">Lat: <span class="font-semibold" x-text="gps.lat"></span>, Lng:
                        <span class="font-semibold" x-text="gps.lng"></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <button @click="closeMapModal()"
                            class="px-4 py-2 rounded-md bg-black text-white font-extrabold border border-neutral-700 hover:-translate-y-0.5 hover:translate-x-0.5 shadow-[3px_3px_0_#9ca3af] transition">
                            Simpan
                        </button>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <!-- JS: kamera, geolokasi, peta -->
    <script>
        const RESOLVE_ENDPOINT = '/api/geo/resolve';

        function pinesForm() {
            return {
                impact: 'tidak',
                photoData: '',
                gps: {
                    lat: '',
                    lng: ''
                },
                faculty: '',
                building: '',

                // DB-driven
                categoryOpen: false,
                categoryGroup: 'kelas', // 'kelas' | 'luar'
                categories: {
                    kelas: [],
                    luar: []
                }, // [{value,label,hint,allow_manual}]
                subs: {}, // { [categoryId]: [{value,label,allow_manual}] }

                category: '',
                categoryName: '',
                categoryOther: '',
                categoryAllowManual: false,

                subcategory: '',
                subcategoryName: '',
                subcategoryOther: '',
                subAllowManual: false,

                camera: {
                    open: false,
                    stream: null,
                    captured: false
                },
                map: {
                    open: false,
                    instance: null
                },


                async init() {
                    // load kategori dari backend
                    try {
                        const res = await fetch('/api/categories', {
                            headers: {
                                'Accept': 'application/json'
                            }
                        });
                        if (!res.ok) throw new Error('Bad response');
                        const data = await res.json();

                        // Ekspektasi data:
                        // [
                        //  { id, group: 'kelas'|'luar'|'internal'|'external', name, hint, allow_manual: boolean,
                        //    subcategories: [ { id, name, allow_manual: boolean } ] }
                        // ]

                        const byGroup = {
                            kelas: [],
                            luar: []
                        };
                        const subsMap = {};

                        for (const c of data) {
                            const groupKey = c.group === 'internal' ? 'kelas' :
                                c.group === 'external' ? 'luar' :
                                (c.group || 'kelas');

                            byGroup[groupKey].push({
                                value: c.id,
                                label: c.name,
                                hint: c.hint || '',
                                allow_manual: !!c.allow_manual
                            });

                            subsMap[c.id] = (c.subcategories || []).map(s => ({
                                value: s.id,
                                label: s.name,
                                allow_manual: !!s.allow_manual
                            }));
                        }

                        // Urutkan dikit biar rapi
                        for (const k of Object.keys(byGroup)) {
                            byGroup[k].sort((a, b) => a.label.localeCompare(b.label, 'id'));
                        }

                        this.categories = byGroup;
                        this.subs = subsMap;

                        // fallback kalau group sekarang kosong
                        if (!(this.categories[this.categoryGroup] || []).length) {
                            this.categoryGroup = (this.categories.kelas || []).length ? 'kelas' : 'luar';
                        }
                    } catch (e) {
                        console.error(e);
                        alert('Kategori gagal dimuat dari server.');
                    }
                },

                // Helpers kategori/subkategori
                resetCategory() {
                    this.category = '';
                    this.categoryName = '';
                    this.categoryOther = '';
                    this.categoryAllowManual = false;
                    this.subcategory = '';
                    this.subcategoryName = '';
                    this.subcategoryOther = '';
                    this.subAllowManual = false;
                },
                setCategory(opt) {
                    this.category = opt.value;
                    this.categoryName = opt.label || '';
                    this.categoryAllowManual = !!opt.allow_manual || /lainnya/i.test(opt.label || '');
                    // reset sub
                    this.subcategory = '';
                    this.subcategoryName = '';
                    this.subcategoryOther = '';
                    this.subAllowManual = false;
                },
                setSubcategory(opt) {
                    this.subcategory = opt.value;
                    this.subcategoryName = opt.label || '';
                    this.subAllowManual = !!opt.allow_manual || /lainnya/i.test(opt.label || '');
                    if (!this.subAllowManual) this.subcategoryOther = '';
                },

                // CAMERA
                async openCamera(isRetake = false) {
                    // tutup map dulu biar gak ganggu kamera
                    this.map.open = false;

                    try {
                        if (!isRetake) {
                            this.photoData = '';
                            this.camera.captured = false;
                        }
                        this.camera.open = true;

                        const stream = await navigator.mediaDevices.getUserMedia({
                            video: {
                                facingMode: {
                                    ideal: 'environment'
                                }
                            },
                            audio: false
                        });

                        this.camera.stream = stream;
                        this.$refs.video.srcObject = stream;
                    } catch (e) {
                        alert('Kamera gagal dibuka: ' + (e.name || e.message || e));
                        this.camera.open = false;
                    }
                },

                capturePhoto() {
                    const v = this.$refs.video,
                        c = document.createElement('canvas');
                    const w = v.videoWidth || 1280,
                        h = v.videoHeight || 720;
                    c.width = w;
                    c.height = h;
                    c.getContext('2d').drawImage(v, 0, 0, w, h);
                    this.photoData = c.toDataURL('image/jpeg', 0.9);
                    this.camera.captured = true;
                    this.getGPS().then(() => this.resolveLocation()).catch(() => {});
                },
                retake() {
                    this.camera.captured = false;
                    this.photoData = '';
                },
                usePhoto() {
                    this.closeCamera();
                    this.categoryOpen = true;
                    if (!this.category) this.categoryGroup = 'kelas';
                },
                closeCamera() {
                    if (this.camera.stream) this.camera.stream.getTracks().forEach(t => t.stop());
                    this.camera.stream = null;
                    this.camera.open = false;
                },

                // GEOLOCATION
                async getGPS() {
                    return new Promise((resolve, reject) => {
                        if (!('geolocation' in navigator)) return reject('GPS tidak didukung.');
                        navigator.geolocation.getCurrentPosition(
                            pos => {
                                this.gps.lat = pos.coords.latitude.toFixed(6);
                                this.gps.lng = pos.coords.longitude.toFixed(6);
                                resolve();
                            },
                            err => {
                                console.warn(err);
                                reject(err?.message || 'Gagal ambil GPS');
                            }, {
                                enableHighAccuracy: true,
                                timeout: 10000,
                                maximumAge: 0
                            }
                        );
                    });
                },

                // BACKEND RESOLVE (opsional)
                async resolveLocation() {
                    try {
                        if (!this.gps.lat || !this.gps.lng) return;
                        const res = await fetch(`${RESOLVE_ENDPOINT}?lat=${this.gps.lat}&lng=${this.gps.lng}`, {
                            headers: {
                                'Accept': 'application/json'
                            }
                        });
                        if (!res.ok) throw new Error('resolve failed');
                        const data = await res.json();
                        this.faculty = data?.faculty || '';
                        this.building = data?.building || '';
                    } catch (_) {
                        this.faculty = this.faculty || '';
                        this.building = this.building || '';
                    }
                },

                // MAP MODAL (Leaflet)
                // MAP MODAL (Leaflet)
                async openMapModal() {
                    if (!this.gps.lat || !this.gps.lng) {
                        try {
                            await this.getGPS();
                        } catch (_) {}
                    }

                    this.map.open = true;

                    this.$nextTick(() => {
                        const lat = Number(this.gps.lat || 0);
                        const lng = Number(this.gps.lng || 0);

                        // kalau belum ada map (atau barusan di-remove) bikin baru
                        if (!this.map.instance) {
                            this.map.instance = L.map('mapPicker', {
                                zoomControl: true
                            }).setView([lat, lng], 18);

                            L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                                maxZoom: 20,
                                attribution: '&copy; OpenStreetMap & CartoDB'
                            }).addTo(this.map.instance);

                            // update lat lng pas geser
                            this.map.instance.on('move', () => {
                                const center = this.map.instance.getCenter();
                                this.gps.lat = center.lat.toFixed(6);
                                this.gps.lng = center.lng.toFixed(6);
                            });

                            // resolve pas selesai geser
                            let timer = null;
                            this.map.instance.on('moveend', () => {
                                clearTimeout(timer);
                                timer = setTimeout(() => this.resolveLocation(), 400);
                            });
                        } else {
                            // kalau entah gimana masih ada
                            this.map.instance.setView([lat, lng], 18);
                        }

                        // biar gak gepeng
                        setTimeout(() => {
                            this.map.instance && this.map.instance.invalidateSize();
                        }, 100);
                    });
                },

                closeMapModal() {
                    this.map.open = false;
                    // penting: hancurkan map lama biar pas buka lagi bikin baru
                    if (this.map.instance) {
                        this.map.instance.remove();
                        this.map.instance = null;
                    }
                },


                // VALIDATION
                validate() {
                    if (!this.photoData) {
                        alert('Ambil foto dulu.');
                        return false;
                    }
                    if (!this.gps.lat || !this.gps.lng) {
                        alert('Lokasi belum ada.');
                        return false;
                    }
                    if (!this.category) {
                        alert('Pilih kategori.');
                        this.categoryOpen = true;
                        return false;
                    }

                    const hasSubs = Array.isArray(this.subs[this.category]) && this.subs[this.category].length > 0;
                    if (hasSubs && !this.subcategory) {
                        alert('Pilih subkategori.');
                        return false;
                    }
                    if (this.categoryAllowManual && !this.categoryName && !this.categoryOther.trim()) {
                        alert('Isi detail kategori.');
                        return false;
                    }
                    if (this.subAllowManual && !this.subcategoryOther.trim()) {
                        alert('Isi detail subkategori.');
                        return false;
                    }
                    return true;
                }
            };
        }
    </script>

    <!-- Leaflet (peta) -->
    @include('lapor.partials.mapLeaflet')
@endsection
