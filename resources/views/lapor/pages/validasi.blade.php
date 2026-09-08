@extends('lapor.layouts.app')

@section('body-class', 'bg-white')

@section('content')
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 py-6" x-data="{
        reports: [
            { id: 1, title: 'Lampu koridor mati', lokasi: 'Gedung A, Lantai 2', waktu: '10:32', status: 'Pending', img: 'https://images.unsplash.com/photo-1509395176047-4a66953fd231?w=1200&q=80' },
            { id: 2, title: 'Jalan kampus berlubang', lokasi: 'Gerbang Utama', waktu: '09:15', status: 'Valid', img: 'https://images.unsplash.com/photo-1504164996022-09080787b6b3?w=1200&q=80' },
            { id: 3, title: 'AC ruang kelas bocor', lokasi: 'Gedung B, R.203', waktu: 'Kemarin', status: 'Proses', img: 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=1200&q=80' },
            { id: 4, title: 'Toilet mampet', lokasi: 'Perpustakaan', waktu: '2 hari lalu', status: 'Ditolak', img: 'https://images.unsplash.com/photo-1523246123097-71f3e5899b95?w=1200&q=80' },
        ],
        tag(s) {
            return ({
                'Valid': 'bg-neutral-900 text-white border border-neutral-900',
                'Pending': 'bg-white text-neutral-900 border border-neutral-300',
                'Proses': 'bg-white text-neutral-900 border border-neutral-300',
                'Ditolak': 'bg-white text-neutral-700 border border-neutral-300 line-through'
            })[s] || 'bg-white text-neutral-900 border border-neutral-300';
        },
        modalOpen: false,
        selected: null,
        vote: null,
        comment: '',
        submitting: false,
        flash: '',
        openModal(item) {
            this.selected = item;
            this.modalOpen = true;
            this.vote = null;
            this.comment = '';
        },
        valid() { return this.vote && this.comment.trim().length >= 5 },
        submit() {
            if (!this.valid()) return;
            this.submitting = true;
            setTimeout(() => {
                this.submitting = false;
                this.modalOpen = false;
                this.flash = 'Voting tersimpan (mock)';
                setTimeout(() => this.flash = '', 1500)
            }, 500);
        }
    }" @keydown.escape.window="modalOpen=false">
        <style>
            [x-cloak] {
                display: none !important
            }
        </style>

        <!-- HEADER -->
        <div class="flex items-center gap-3 mb-5">
            <a href="{{ url()->previous() }}"
                class="inline-flex items-center justify-center w-10 h-10 rounded-md border border-neutral-500 bg-white hover:-translate-y-0.5 hover:translate-x-0.5 shadow-[3px_3px_0_#9ca3af] transition">
                <svg class="w-5 h-5 text-black" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div class="flex flex-col">
                <span
                    class="text-[11px] tracking-widest uppercase border border-neutral-300 text-neutral-800 px-2 py-0.5 rounded">
                    VOTING
                </span>
                <h1 class="text-2xl font-bold text-neutral-900 leading-tight">lapor Masuk</h1>
            </div>
        </div>

        {{-- MAP LEAFLET --}}
        <div class="mb-6 rounded-xl overflow-hidden border border-neutral-300" style="height: 320px;" id="map"></div>

        <div class="text-center mb-8">
            <p class="mt-2 text-neutral-700">Daftar lapor kerusakan terbaru</p>
        </div>

        <!-- GRID CARDS (flat, no shadow) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <template x-for="item in reports" :key="item.id">
                <article class="bg-white border border-neutral-300 rounded-lg overflow-hidden">
                    <img :src="item.img" alt="" class="w-full h-44 object-cover">
                    <div class="p-5 space-y-2">
                        <h3 class="text-base sm:text-lg font-semibold text-neutral-900" x-text="item.title"></h3>

                        <div class="flex flex-wrap items-center gap-2">
                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded"
                                :class="tag(item.status)" x-text="item.status"></span>
                            <span class="text-xs text-neutral-600" x-text="item.waktu"></span>
                        </div>

                        <p class="text-sm text-neutral-700" x-text="item.lokasi"></p>

                        <button @click="openModal(item)"
                            class="mt-3 inline-flex w-full items-center justify-center h-10 px-4 rounded-lg bg-neutral-900 text-white text-sm font-semibold border border-neutral-900 hover:bg-neutral-800 focus:outline-none focus:ring-2 focus:ring-neutral-300">
                            Lihat & Voting
                        </button>
                    </div>
                </article>
            </template>
        </div>

        <!-- TOAST (flat) -->
        <div x-show="flash" x-transition
            class="fixed bottom-6 left-1/2 -translate-x-1/2 px-4 py-2 bg-neutral-900 text-white text-sm rounded-md border border-neutral-900">
            <span x-text="flash"></span>
        </div>

        <!-- MODAL (flat, like screenshot) -->
        <template x-teleport="body">
            <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center">
                <!-- Backdrop -->
                <div x-show="modalOpen" x-transition:enter="ease-out duration-150" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-150"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="modalOpen=false"
                    class="absolute inset-0 bg-black/50">
                </div>

                <!-- Dialog -->
                <div x-show="modalOpen" x-trap.inert.noscroll="modalOpen" x-transition:enter="ease-out duration-150"
                    x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-1"
                    class="relative mx-4 w-full sm:max-w-lg bg-white border border-neutral-300 rounded-xl">

                    <!-- Header -->
                    <div class="flex items-start justify-between px-6 pt-5">
                        <div>
                            <h3 class="text-lg font-semibold text-neutral-900">Cast Your Vote</h3>
                            <p class="text-sm text-neutral-600 mt-1">Apakah kamu setuju dengan usulan ini?</p>
                        </div>
                        <button @click="modalOpen=false"
                            class="ml-2 inline-flex items-center justify-center w-9 h-9 rounded-full border border-neutral-300 bg-white text-neutral-900 hover:bg-neutral-50 focus:outline-none focus:ring-2 focus:ring-neutral-300"
                            aria-label="Tutup">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="px-6 pb-6 pt-4">
                        <!-- Lokasi & waktu kecil, biar bersih -->
                        <p class="text-xs text-neutral-600 mb-4">
                            <span class="font-medium text-neutral-800" x-text="selected?.title || 'Detail lapor'"></span>
                            · <span x-text="selected?.lokasi || '-'"></span> ·
                            Dilaporkan: <span x-text="selected?.waktu || '-'"></span>
                        </p>

                        <!-- Your Vote -->
                        <label class="text-sm font-medium mb-2 block">Pilihan kamu</label>
                        <div class="inline-flex w-full rounded-lg border border-neutral-300 overflow-hidden mb-4">
                            <!-- Setuju -->
                            <button type="button" @click="vote='setuju'"
                                :class="vote === 'setuju' ? 'bg-neutral-900 text-white' :
                                    'bg-white text-neutral-900 hover:bg-neutral-50'"
                                class="flex-1 h-10 text-sm font-medium inline-flex items-center justify-center gap-2 focus:outline-none focus:ring-2 focus:ring-neutral-300 border-r border-neutral-300">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 9l-3 6-2-2-3 3" />
                                </svg>
                                Setuju
                            </button>

                            <!-- Tidak Setuju -->
                            <button type="button" @click="vote='tidak'"
                                :class="vote === 'tidak' ? 'bg-neutral-900 text-white' :
                                    'bg-white text-neutral-900 hover:bg-neutral-50'"
                                class="flex-1 h-10 text-sm font-medium inline-flex items-center justify-center gap-2 focus:outline-none focus:ring-2 focus:ring-neutral-300">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Tidak Setuju
                            </button>
                        </div>

                        <!-- Comment -->
                        <label class="text-sm font-medium mb-1 block">Tambah komentar <span
                                class="text-red-600">(wajib)</span></label>
                        <textarea x-model="comment" rows="4" placeholder="Jelaskan alasanmu di sini..."
                            class="mt-1 w-full rounded-lg border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-neutral-300"
                            :class="comment.trim().length < 5 ? 'border-red-300' : 'border-neutral-300'"></textarea>
                        <p class="mt-1 text-xs" :class="comment.trim().length < 5 ? 'text-red-600' : 'text-neutral-600'">
                            Minimal 5 karakter.
                        </p>

                        <!-- Actions -->
                        <div class="mt-4 flex flex-col sm:flex-row sm:justify-end gap-2">
                            <button @click="modalOpen=false" type="button"
                                class="inline-flex justify-center items-center px-4 h-10 text-sm font-medium rounded-lg border border-neutral-300 bg-white hover:bg-neutral-50 focus:outline-none focus:ring-2 focus:ring-neutral-300">
                                Batal
                            </button>
                            <button type="button" @click="submit()" :disabled="!valid() || submitting"
                                :class="(!valid() || submitting) ? 'bg-neutral-300 cursor-not-allowed border-neutral-300' :
                                'bg-[#2563EB] hover:brightness-95 border-[#2563EB]'"
                                class="inline-flex justify-center items-center px-4 h-10 text-sm font-semibold text-white rounded-lg border focus:outline-none focus:ring-2 focus:ring-neutral-300 w-full sm:w-auto">
                                <span x-show="!submitting">Submit Vote</span>
                                <span x-show="submitting">Mengirim...</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>

    </div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var map = L.map('map').setView([-5.147665, 119.432731], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Marker contoh
        L.marker([-5.147665, 119.432731])
            .addTo(map)
            .bindPopup('<b>Lokasi Kampus</b>')
            .openPopup();
    });
</script>
@endpush
@endsection
