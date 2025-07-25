@forelse ($companies as $index => $company)
<div class="products-row">
    <div class="product-cell cell-no" data-label="No">{{ $loop->iteration + ($companies->currentPage() - 1) * $companies->perPage() }}</div>
    <div class="product-cell cell-nama-perusahaan" data-label="Nama Perusahaan">{{ $company->nama_perusahaan }}</div>
    <div class="product-cell cell-singkatan" data-label="Singkatan">{{ $company->singkatan }}</div>
    <div class="product-cell cell-dibuat-pada" data-label="Dibuat Pada">{{ $company->created_at->format('d M Y') }}</div>

    @if(auth()->user()->isSuperAdmin())
    <div class="product-cell cell-aksi" data-label="Aksi">
        <div class="action-buttons-wrapper">
            <button class="action-btn-table edit-btn edit-company-btn" title="Edit Perusahaan" data-id="{{ $company->id }}">
                <i class="fas fa-edit"></i>
                <span>Edit</span>
            </button>
            <button class="action-btn-table delete-btn delete-company-btn" title="Hapus Perusahaan" data-id="{{ $company->id }}">
                <i class="fas fa-trash"></i>
                <span>Hapus</span>
            </button>
        </div>
    </div>
    @endif
</div>
@empty
<div class="products-row" style="justify-content: center; padding: 2rem; text-align: center; width: 100%;">
    Tidak ada data perusahaan yang ditemukan.
</div>
@endforelse 