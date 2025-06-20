@forelse ($companies as $company)
<div class="products-row" data-company-id="{{ $company->id }}">
    <div class="product-cell cell-no" data-label="No">{{ $companies->firstItem() + $loop->index }}</div>
    <div class="product-cell cell-nama-perusahaan" data-label="Nama Perusahaan">{{ $company->nama_perusahaan }}</div>
    <div class="product-cell cell-singkatan" data-label="Singkatan">{{ $company->singkatan }}</div>
    <div class="product-cell cell-dibuat-pada" data-label="Dibuat Pada">{{ $company->created_at->format('d M Y') }}</div>
    <div class="product-cell cell-aksi" data-label="Aksi">
    </div>
</div>
@empty
<div class="products-row">
    <div class="product-cell" style="grid-column: 1 / -1; text-align: center; padding: 2rem;">
        Tidak ada data perusahaan ditemukan.
    </div>
</div>
@endforelse