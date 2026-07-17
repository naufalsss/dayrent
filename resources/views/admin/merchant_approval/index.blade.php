@extends('admin.master')

@section('content')
    @if(session('success'))
        <div class="p-4 mb-6 text-xs font-bold text-emerald-600 bg-emerald-50 rounded-2xl border border-emerald-200 flex items-center gap-2 max-w-5xl">
            <span>✨</span> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 mb-6 text-xs font-bold text-rose-600 bg-rose-50 rounded-2xl border border-rose-200 flex items-center gap-2 max-w-5xl">
            <span>❌</span> {{ session('error') }}
        </div>
    @endif

    <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm w-full max-w-5xl">
        
        <div class="pb-4 border-b border-slate-100 mb-5">
            <h4 class="font-extrabold text-xs text-slate-800 uppercase tracking-wider">Workspace Persetujuan Merchant</h4>
            <p class="text-[11px] text-slate-400 font-medium mt-1">Tinjau formulir pengajuan registrasi mitra merchant dan kelola hak akses role penjualan secara real-time.</p>
        </div>

        <div class="overflow-x-auto rounded-xl border border-slate-200/60">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-[10px] font-bold uppercase tracking-widest text-slate-400 bg-slate-50 border-b border-slate-200/60">
                        <th class="py-3.5 px-5">Nama Pemilik</th>
                        <th class="py-3.5 px-5">Nama Toko</th>
                        <th class="py-3.5 px-5">Tipe Usaha</th>
                        <th class="py-3.5 px-5 text-center">Status</th>
                        <th class="py-3.5 px-5 text-center w-56">Tindakan Analisis</th>
                    </tr>
                </thead>
                <tbody class="text-xs font-medium text-slate-600 divide-y divide-slate-100">
                    @forelse($applications as $app)
                        <tr class="hover:bg-slate-50/40 transition duration-100">
                            <td class="py-4 px-5 font-bold text-slate-800">{{ $app->name }}</td>
                            <td class="py-4 px-5 text-blue-600 font-semibold">{{ $app->shop_name }}</td>
                            <td class="py-4 px-5">
                                @if($app->business_type === 'company')
                                    <span class="text-[10px] bg-indigo-50 text-indigo-600 border border-indigo-100 px-2 py-0.5 rounded-md font-bold uppercase tracking-wide">🏢 Badan Usaha</span>
                                @else
                                    <span class="text-[10px] bg-slate-100 text-slate-600 border border-slate-200/60 px-2 py-0.5 rounded-md font-bold uppercase tracking-wide">👤 Perorangan</span>
                                @endif
                            </td>
                            <td class="py-4 px-5 text-center">
                                @if($app->status === 'pending')
                                    <span class="bg-amber-50 text-amber-600 border border-amber-200 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wide">Pending</span>
                                @elseif($app->status === 'approved')
                                    <span class="bg-emerald-50 text-emerald-600 border border-emerald-200 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wide">Approved</span>
                                @else
                                    <span class="bg-rose-50 text-rose-600 border border-rose-200 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wide">Declined</span>
                                @endif
                            </td>
                            <td class="py-4 px-5 flex items-center justify-center gap-2">
                                <!-- Tombol Tinjau Form Data Komplet (Membawa Data Legalitas Baru) -->
                                <button type="button" 
                                        onclick="openDetailModal({
                                            name: '{{ $app->name }}',
                                            email: '{{ $app->email }}',
                                            shop_name: '{{ $app->shop_name }}',
                                            phone: '{{ $app->phone }}',
                                            description: '{{ e($app->shop_description) }}',
                                            business_type: '{{ $app->business_type }}',
                                            ktp_number: '{{ $app->ktp_number }}',
                                            npwp_personal: '{{ $app->npwp_personal }}',
                                            nib_number: '{{ $app->nib_number ?? '-' }}',
                                            akta_number: '{{ $app->akta_number ?? '-' }}',
                                            npwp_business: '{{ $app->npwp_business ?? '-' }}'
                                        })"
                                        class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-[10px] font-bold uppercase tracking-wider px-2.5 py-1.5 rounded-lg transition cursor-pointer border-0">
                                    Tinjau Berkas
                                </button>

                                @if($app->status === 'pending')
                                    <!-- Form Approve -->
                                    <form method="POST" action="{{ route('admin.merchant-approval.approve', $app->id) }}" onsubmit="return confirm('Setujui pengajuan toko {{ $app->shop_name }}?')">
                                        @csrf
                                        <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white text-[10px] font-bold uppercase tracking-wider px-2.5 py-1.5 rounded-lg transition cursor-pointer border-0">
                                            Terima
                                        </button>
                                    </form>

                                    <!-- Form Decline -->
                                    <form method="POST" action="{{ route('admin.merchant-approval.decline', $app->id) }}" onsubmit="return confirm('Tolak pengajuan toko {{ $app->shop_name }}?')">
                                        @csrf
                                        <button type="submit" class="bg-rose-50 hover:bg-rose-100 border border-rose-200/40 text-rose-600 px-2.5 py-1.5 rounded-lg font-bold transition cursor-pointer text-[10px] uppercase tracking-wider border-0">
                                            Tolak
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-16 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <span class="text-3xl opacity-50">🛡️</span>
                                    <p class="font-extrabold text-slate-700 text-xs tracking-tight uppercase">Belum ada pengajuan merchant baru</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL BOX REVIEW FORM DETAIL MERCHANT + LEGALITAS DOKUMEN (POP UP CLEAN) -->
    <div id="detailModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm hidden items-center justify-center z-[100] p-4 transition-all duration-300">
        <div class="bg-white border border-slate-200 rounded-2xl p-6 max-w-lg w-full shadow-2xl space-y-4 transform scale-95 transition-transform duration-300 text-left overflow-y-auto max-h-[90vh] custom-scroll">
            <div class="pb-3 border-b border-slate-100 flex justify-between items-start">
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-tight">Detail Dokumen Pengajuan</h3>
                    <p class="text-[11px] text-slate-400 font-medium">Informasi bisnis retail & berkas hukum pendaftaran akun.</p>
                </div>
                <span id="badgeTipeUrusan"></span>
            </div>
            
            <div class="space-y-3.5 text-xs">
                <!-- Data Akun Dasaran -->
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="text-[10px] uppercase font-bold tracking-wider text-slate-400 block mb-0.5">Nama Pemilik Akun</label>
                        <p id="modalName" class="font-bold text-slate-800 bg-slate-50 px-3 py-2 rounded-xl border border-slate-100"></p>
                    </div>
                    <div>
                        <label class="text-[10px] uppercase font-bold tracking-wider text-slate-400 block mb-0.5">Email Terdaftar</label>
                        <p id="modalEmail" class="font-semibold text-slate-700 bg-slate-50 px-3 py-2 rounded-xl border border-slate-100 overflow-hidden text-ellipsis whitespace-nowrap"></p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="text-[10px] uppercase font-bold tracking-wider text-slate-400 block mb-0.5">Nama Toko Bisnis</label>
                        <p id="modalShopName" class="font-bold text-blue-600 bg-blue-50/30 px-3 py-2 rounded-xl border border-blue-100/40"></p>
                    </div>
                    <div>
                        <label class="text-[10px] uppercase font-bold tracking-wider text-slate-400 block mb-0.5">Kontak Telepon</label>
                        <p id="modalPhone" class="font-bold text-slate-800 bg-slate-50 px-3 py-2 rounded-xl border border-slate-100"></p>
                    </div>
                </div>

                <div>
                    <label class="text-[10px] uppercase font-bold tracking-wider text-slate-400 block mb-0.5">Deskripsi Profil Usaha</label>
                    <div id="modalDescription" class="text-slate-600 bg-slate-50 px-3 py-2 rounded-xl border border-slate-100 leading-relaxed min-h-12 whitespace-pre-line font-medium"></div>
                </div>

                <!-- 1. BLOK VALIDASI IDENTITAS PRIBADI -->
                <div class="border-t border-slate-100 pt-3">
                    <p class="text-[10px] font-extrabold text-slate-800 uppercase tracking-wide mb-2 flex items-center gap-1">🆔 Berkas Identitas Pribadi</p>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="text-[9px] uppercase font-bold tracking-wider text-slate-400 block mb-0.5">No. KTP Pemilik</label>
                            <p id="modalKtp" class="font-mono font-bold text-slate-800 bg-slate-50 px-3 py-2 rounded-xl border border-slate-100"></p>
                        </div>
                        <div>
                            <label class="text-[9px] uppercase font-bold tracking-wider text-slate-400 block mb-0.5">NPWP Pribadi</label>
                            <p id="modalNpwpPersonal" class="font-mono font-bold text-slate-800 bg-slate-50 px-3 py-2 rounded-xl border border-slate-100"></p>
                        </div>
                    </div>
                </div>

                <!-- 2. BLOK VALIDASI LEGALITAS PERUSAHAAN (CV/PT) -->
                <div id="modalCorporateSection" class="border-t border-slate-100 pt-3 hidden">
                    <p class="text-[10px] font-extrabold text-amber-600 uppercase tracking-wide mb-2 flex items-center gap-1">💼 Berkas Hukum Perusahaan (CV / PT)</p>
                    <div class="space-y-2.5">
                        <div>
                            <label class="text-[9px] uppercase font-bold tracking-wider text-slate-400 block mb-0.5">Nomor Induk Berusaha (NIB)</label>
                            <p id="modalNib" class="font-mono font-bold text-slate-800 bg-amber-50/20 px-3 py-2 rounded-xl border border-amber-100/40"></p>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="text-[9px] uppercase font-bold tracking-wider text-slate-400 block mb-0.5">Nomor Akta Perusahaan</label>
                                <p id="modalAkta" class="font-mono font-bold text-slate-800 bg-amber-50/20 px-3 py-2 rounded-xl border border-amber-100/40 overflow-x-auto whitespace-nowrap custom-scroll"></p>
                            </div>
                            <div>
                                <label class="text-[9px] uppercase font-bold tracking-wider text-slate-400 block mb-0.5">NPWP Badan Usaha</label>
                                <p id="modalNpwpBusiness" class="font-mono font-bold text-slate-800 bg-amber-50/20 px-3 py-2 rounded-xl border border-amber-100/40"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-2">
                <button type="button" onclick="closeDetailModal()" class="w-full bg-slate-800 hover:bg-slate-700 text-white text-xs font-bold py-2.5 rounded-xl transition cursor-pointer border-0 uppercase tracking-wider text-center">
                    Tutup Tinjauan
                </button>
            </div>
        </div>
    </div>

    <!-- JAVASCRIPT MODAL INTERACTION ENGINE -->
    <script>
        window.openDetailModal = function(data) {
            document.getElementById("modalName").innerText = data.name;
            document.getElementById("modalEmail").innerText = data.email;
            document.getElementById("modalShopName").innerText = data.shop_name;
            document.getElementById("modalPhone").innerText = data.phone;
            document.getElementById("modalDescription").innerText = data.description;
            document.getElementById("modalKtp").innerText = data.ktp_number;
            document.getElementById("modalNpwpPersonal").innerText = data.npwp_personal;

            const badge = document.getElementById("badgeTipeUrusan");
            const corpSection = document.getElementById("modalCorporateSection");

            if (data.business_type === 'company') {
                badge.className = "text-[9px] bg-indigo-50 text-indigo-600 border border-indigo-100 px-2.5 py-1 rounded-full font-bold uppercase tracking-wide";
                badge.innerText = "CV / PT";
                
                // Isi berkas legalitas PT/CV
                document.getElementById("modalNib").innerText = data.nib_number;
                document.getElementById("modalAkta").innerText = data.akta_number;
                document.getElementById("modalNpwpBusiness").innerText = data.npwp_business;
                
                corpSection.classList.remove("hidden");
            } else {
                badge.className = "text-[9px] bg-slate-100 text-slate-600 border border-slate-200 px-2.5 py-1 rounded-full font-bold uppercase tracking-wide";
                badge.innerText = "Perorangan";
                
                corpSection.classList.add("hidden");
            }

            const modal = document.getElementById("detailModal");
            modal.classList.remove("hidden");
            modal.classList.add("flex");
        }

        window.closeDetailModal = function() {
            const modal = document.getElementById("detailModal");
            modal.classList.add("hidden");
            modal.classList.remove("flex");
        }
    </script>
@endsection