@extends('admin.master')

@section('content')
    <style>
        .finset-card {
            border-radius: 24px !important;
            border: 1px solid #e2e8f0 !important;
            background-color: #ffffff !important;
        }
        .input-stock {
            background-color: #f8fafc !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 10px !important;
            padding: 6px 12px !important;
            font-size: 13px !important;
            font-weight: 600 !important;
            color: #1e293b !important;
            width: 80px;
        }
        .input-search, .select-filter {
            background-color: #f8fafc !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 12px !important;
            padding: 10px 16px !important;
            font-size: 13px !important;
            font-weight: 500 !important;
            color: #334155 !important;
            outline: none;
            transition: all 0.2s;
        }
        .input-search:focus, .select-filter:focus {
            border-color: #3b82f6 !important;
            background-color: #ffffff !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
        }
        /* Style kustom untuk tombol submit agar tampil polosan indah */
        .btn-submit-stock {
            background-color: #2563eb !important;
            color: #ffffff !important;
            font-size: 11px !important;
            font-weight: 700 !important;
            padding: 6px 12px !important;
            border-radius: 8px !important;
            border: none !important;
            cursor: pointer !important;
            transition: background-color 0.2s;
        }
        .btn-submit-stock:hover {
            background-color: #3b82f6 !important;
        }
    </style>

    @if(session('success'))
        <div class="p-4 mb-4 text-sm font-bold text-emerald-700 bg-emerald-50 rounded-2xl border border-emerald-200 flex items-center gap-2">
            {{ session('success') }}
        </div>
    @endif

    <div class="finset-card p-6 shadow-sm flex flex-col overflow-hidden">
        
        <div class="pb-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-5">
            <div>
                <h4 class="font-extrabold text-base text-slate-900">Manajemen Stok Barang</h4>
                <p class="text-[11px] text-slate-400 font-medium mt-0.5">Atur kuantitas sisa unit rental secara real-time tanpa mengganggu data master barang.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 mb-6 relative">
            <div class="sm:col-span-8 flex flex-col">
                <input type="text" id="searchBarang" placeholder="Cari nama unit kendaraan..." class="input-search w-full">
            </div>
            <div class="sm:col-span-4 flex flex-col">
                <select id="filterKategori" class="select-filter w-full cursor-pointer">
                    <option value="all">Semua Kategori</option>
                    @foreach($items->unique('category_name') as $cat)
                        <option value="{{ strtolower($cat->category_name) }}">{{ $cat->category_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 text-xs font-bold text-slate-400 uppercase tracking-wider">
                        <th class="pb-3 pl-2">Unit Kendaraan</th>
                        <th class="pb-3">Kategori</th>
                        <th class="pb-3 text-center">Jumlah Stok</th>
                        <th class="pb-3 text-center">Aksi Manajemen</th>
                    </tr>
                </thead>
                <tbody id="stockTableBody" class="text-sm font-medium text-slate-700 divide-y divide-slate-50">
                    @forelse($items as $item)
                        <tr class="stock-row" data-name="{{ strtolower($item->name) }}" data-category="{{ strtolower($item->category_name) }}">
                            <td class="py-4 pl-2 flex items-center gap-3">
                                <img src="{{ asset($item->image) }}" class="w-10 h-10 object-cover rounded-xl border border-slate-100 shadow-sm">
                                <span class="font-bold text-slate-800">{{ $item->name }}</span>
                            </td>
                            <td class="py-4 text-slate-500 text-xs uppercase">{{ $item->category_name }}</td>
                            
                            <td class="py-4 text-center">
                                <form method="POST" action="{{ route('admin.stock.update', $item->id) }}" class="flex items-center justify-center gap-2">
                                    @csrf
                                    <input type="number" name="stock" value="{{ $item->stock ?? 0 }}" class="input-stock text-center" min="0">
                                    <input type="submit" value="Simpan" class="btn-submit-stock">
                                </form>
                            </td>

                            <td class="py-4 text-center">
                                <form method="POST" action="{{ route('admin.stock.delete', $item->id) }}" onsubmit="return confirm('Apakah kamu yakin ingin mengosongkan stok unit ini?')">
                                    @csrf
                                    <button type="submit" class="bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 text-xs font-bold px-3 py-1.5 rounded-xl transition cursor-pointer">
                                        Kosongkan Stok
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr id="emptyRowGlobal">
                            <td colspan="4" class="py-8 text-center text-slate-400 font-semibold text-xs">Belum ada data unit kendaraan terdaftar.</td>
                        </tr>
                    @endforelse
                    
                    <tr id="noResultsRow" class="hidden">
                        <td colspan="4" class="py-8 text-center text-slate-400 font-semibold text-xs">Data unit tidak ditemukan.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const searchInput = document.getElementById('searchBarang');
            const filterSelect = document.getElementById('filterKategori');
            const rows = document.querySelectorAll('.stock-row');
            const noResultsRow = document.getElementById('noResultsRow');

            function filterTable() {
                const searchValue = searchInput.value.toLowerCase().trim();
                const filterValue = filterSelect.value.toLowerCase();
                let visibleCount = 0;

                rows.forEach(row => {
                    const itemName = row.getAttribute('data-name');
                    const itemCategory = row.getAttribute('data-category');

                    const matchesSearch = itemName.includes(searchValue);
                    const matchesCategory = (filterValue === 'all' || itemCategory === filterValue);

                    if (matchesSearch && matchesCategory) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                if (visibleCount === 0 && rows.length > 0) {
                    noResultsRow.classList.remove('hidden');
                } else {
                    noResultsRow.classList.add('hidden');
                }
            }

            searchInput.addEventListener('input', filterTable);
            filterSelect.addEventListener('change', filterTable);
        });
    </script>
@endsection