@extends('layouts.appF')

@section('title', 'Tahap 3: Jadwal Proyek')

@push('styles')
<style>
    .countdown-warning {
        color: #e53e3e;
        font-weight: bold;
    }
    .countdown-active {
        color: #2b6cb0;
        font-weight: bold;
    }
</style>
@endpush

@section('content')
    <div class="bg-white min-h-screen flex items-center justify-center py-10">
        <div class="w-full max-w-3xl mt-5">
            <div class="bg-white shadow-lg rounded-lg mt-3">
                <div class="bg-gray-200 px-6 py-4 rounded-t-lg">
                    <center>
                        <h3 class="text-xl font-semibold text-gray-800">Tahap 3: Jadwal Proyek</h3>
                    </center>
                    <a href="{{ url('user/materi/' . $materi->slug . '/sintaks') }}"
                        class="flex items-center float-right gap-1 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 text-sm rounded mt-5 w-max">
                        <x-heroicon-o-arrow-long-left class="w-5" />
                        Kembali ke Daftar Sintaks
                    </a>
                </div>
                <br>
                <div class="p-6">
                    <!-- Menampilkan pesan sukses atau error -->
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
                            <strong>{{ session('success') }}</strong>
                        </div>
                    @elseif(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
                            <strong>{{ session('error') }}</strong>
                        </div>
                    @endif

                    <!-- Cek apakah tahap sudah divalidasi atau belum -->
                    @if ($tahapTiga && $tahapTiga->status_validasi == 'valid')
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
                            <strong>Status Validasi:</strong> <span class="font-semibold">Valid</span>
                            <p class="mt-2"><strong>Feedback Guru:</strong> {{ $tahapTiga->feedback_guru ?? 'Belum ada feedback' }}</p>
                        </div>

                        <!-- Menampilkan data yang sudah divalidasi -->
                        <div class="space-y-4">
                            <div class="mb-4">
                                <p><strong>Tanggal Mulai:</strong> {{ \Carbon\Carbon::parse($tahapTiga->tanggal_mulai)->format('d-m-Y') }}</p>
                                <p><strong>Tanggal Selesai:</strong> {{ \Carbon\Carbon::parse($tahapTiga->tanggal_selesai)->format('d-m-Y') }}</p>
                            </div>

                            @if($tahapTiga->file_jadwal)
                            <div class="mb-4">
                                <p><strong>File Jadwal Proyek:</strong></p>
                                <div class="flex mt-2">
                                    <a href="{{ asset('storage/' . $tahapTiga->file_jadwal) }}" target="_blank" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm mr-2">
                                        <i class="fas fa-download mr-1"></i> Download
                                    </a>
                                    <a href="{{ asset('storage/' . $tahapTiga->file_jadwal) }}" target="_blank" class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded text-sm">
                                        <i class="fas fa-eye mr-1"></i> Lihat
                                    </a>
                                </div>
                            </div>
                            @endif

                            <button type="button" class="bg-blue-500 text-white px-6 py-2 rounded-lg cursor-not-allowed opacity-50" disabled>
                                Sudah Divalidasi
                            </button>
                        </div>
                    @else
                        <!-- Form Input Tahap 3 -->
                        <form action="{{ url('user/materi/' . $materi->slug . '/sintaks/tahap3') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="space-y-4">
                                <div>
                                    <label for="tanggal_mulai" class="block text-sm font-semibold text-gray-700">Tanggal Mulai</label>
                                    <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="border border-gray-300 p-2 w-full rounded-lg shadow-sm" value="{{ $tahapTiga->tanggal_mulai ? date('Y-m-d', strtotime($tahapTiga->tanggal_mulai)) : '' }}" required>
                                    @error('tanggal_mulai')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="tanggal_selesai" class="block text-sm font-semibold text-gray-700">Tanggal Selesai</label>
                                    <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="border border-gray-300 p-2 w-full rounded-lg shadow-sm" value="{{ $tahapTiga->tanggal_selesai ? date('Y-m-d', strtotime($tahapTiga->tanggal_selesai)) : '' }}" required min="{{ $tahapTiga->tanggal_mulai ? date('Y-m-d', strtotime($tahapTiga->tanggal_mulai)) : date('Y-m-d') }}">
                                    @error('tanggal_selesai')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                @if($tahapTiga->tanggal_selesai)
                                <div class="mt-4 bg-blue-50 p-4 rounded-lg border border-blue-200">
                                    <h3 class="text-lg font-semibold text-blue-800 mb-2">Status Pengerjaan Proyek</h3>
                                    <div id="countdown-container" class="mb-2">
                                        <div class="flex items-center">
                                            <span class="font-medium">Sisa Waktu:</span>
                                            <div id="countdown" class="ml-2 px-3 py-1 bg-blue-100 rounded-md text-blue-800 font-bold"></div>
                                        </div>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                                        <div id="progress-bar" class="bg-blue-600 h-2.5 rounded-full" style="width: 0%"></div>
                                    </div>
                                </div>
                                @endif

                                <div>
                                    <label for="file_jadwal" class="block text-sm font-semibold text-gray-700">File Jadwal Proyek</label>
                                    <p class="text-sm text-gray-600 mb-2">Upload file jadwal dan daftar tugas anggota dalam bentuk PDF, Word, atau Excel</p>
                                    <input type="file" name="file_jadwal" id="file_jadwal" class="border border-gray-300 p-2 w-full rounded-lg shadow-sm" accept=".pdf,.doc,.docx,.xls,.xlsx" {{ $tahapTiga->file_jadwal ? '' : 'required' }}>
                                    @error('file_jadwal')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                    
                                    @if($tahapTiga && $tahapTiga->file_jadwal)
                                    <div class="mt-2">
                                        <p><strong>File saat ini:</strong></p>
                                        <div class="flex mt-1">
                                            <a href="{{ asset('storage/' . $tahapTiga->file_jadwal) }}" target="_blank" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm mr-2">
                                                <i class="fas fa-download mr-1"></i> Download
                                            </a>
                                            <a href="{{ asset('storage/' . $tahapTiga->file_jadwal) }}" target="_blank" class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded text-sm">
                                                <i class="fas fa-eye mr-1"></i> Lihat
                                            </a>
                                        </div>
                                        <p class="text-sm text-gray-600 mt-1">Upload file baru untuk mengganti file yang ada</p>
                                        <input type="hidden" name="has_file" value="1">
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg shadow-md transition duration-300">
                                    Simpan
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

@if($tahapTiga && $tahapTiga->tanggal_selesai)
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ambil tanggal selesai dari database
        const tanggalSelesai = new Date("{{ date('Y-m-d 23:59:59', strtotime($tahapTiga->tanggal_selesai)) }}");
        const tanggalMulai = new Date("{{ date('Y-m-d 00:00:00', strtotime($tahapTiga->tanggal_mulai)) }}");
        const now = new Date();
        
        // Hitung total durasi proyek (dalam milidetik)
        const totalDuration = tanggalSelesai - tanggalMulai;
        
        // Hitung sisa waktu
        const timeRemaining = tanggalSelesai - now;
        
        // Hitung persentase waktu yang sudah berlalu
        let percentComplete = 0;
        if (now > tanggalMulai) {
            const elapsedTime = now - tanggalMulai;
            percentComplete = Math.min(100, Math.round((elapsedTime / totalDuration) * 100));
        }
        
        // Update progress bar
        const progressBar = document.getElementById('progress-bar');
        if (progressBar) {
            progressBar.style.width = percentComplete + '%';
        }
        
        // Setup countdown timer
        const countdownElement = document.getElementById('countdown');
        
        function updateCountdown() {
            const now = new Date();
            const diff = tanggalSelesai - now;
            
            // Jika waktu sudah habis
            if (diff <= 0) {
                if (countdownElement) {
                    countdownElement.textContent = "Waktu telah habis!";
                    countdownElement.classList.add('countdown-warning');
                }
                
                // Auto-validate jika status validasi masih pending
                @if($tahapTiga->status_validasi === 'pending')
                    autoValidate();
                @endif
                
                return;
            }
            
            // Hitung hari, jam, menit, detik
            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
            const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((diff % (1000 * 60)) / 1000);
            
            // Update tampilan countdown
            if (countdownElement) {
                countdownElement.textContent = `${days} hari ${hours} jam ${minutes} menit ${seconds} detik`;
                
                // Ubah warna jika waktu sudah sedikit
                if (diff < 86400000) { // kurang dari 1 hari
                    countdownElement.classList.add('countdown-warning');
                    countdownElement.classList.remove('countdown-active');
                } else {
                    countdownElement.classList.add('countdown-active');
                    countdownElement.classList.remove('countdown-warning');
                }
            }
        }
        
        // Auto validate function
        function autoValidate() {
            // Kirim request ke server untuk update status validasi
            fetch('{{ route("user.materi.autoValidasiTahap3", ["slug" => $materi->slug]) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    sintaks_id: '{{ $sintaks->id }}',
                    tahap_id: '{{ $tahapTiga->id }}'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Refresh halaman untuk menampilkan status baru
                    window.location.reload();
                }
            })
            .catch(error => console.error('Error:', error));
        }
        
        // Update countdown setiap detik
        updateCountdown();
        setInterval(updateCountdown, 1000);
    });
</script>
@endif
@endsection