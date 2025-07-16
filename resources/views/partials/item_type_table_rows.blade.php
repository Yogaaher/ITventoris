    @forelse($itemTypes as $item)
    <div class="products-row">
        <div class="product-cell cell-no" data-label="No">{{ $loop->iteration + ($itemTypes->currentPage() - 1) * $itemTypes->perPage() }}</div>
        <div class="product-cell cell-nama-perusahaan" data-label="Nama Jenis">{{ $item->nama_jenis }}</div>
        <div class="product-cell cell-singkatan" data-label="Singkatan">{{ $item->singkatan }}</div>
        <div class="product-cell cell-icon" data-label="Icon">
            @if($item->icon)
            <i class="fas {{ $item->icon }}" style="font-size: 1.8rem;" title="{{ $item->icon }}"></i>
            @else
            <span>-</span>
            @endif
        </div>
        <div class="product-cell cell-dibuat-pada" data-label="Dibuat Pada">{{ $item->created_at->format('d M Y') }}</div>
        @if(auth()->user()->isSuperAdmin())
        <div class="product-cell cell-aksi" data-label="Aksi">
            <div class="action-buttons-wrapper">
                <button class="action-btn-table edit-btn" data-id="{{ $item->id }}" title="Edit Jenis Barang">
                    <i class="fas fa-edit"></i><span>Edit</span>
                </button>
                <button class="action-btn-table delete-btn" data-id="{{ $item->id }}" title="Hapus Jenis Barang">
                    <i class="fas fa-trash"></i><span>Hapus</span>
                </button>
            </div>
        </div>
        @endif

    </div>
    @empty
    <div class="products-row" style="justify-content: center; padding: 2rem; text-align: center;">
        Tidak ada data jenis barang ditemukan.
    </div>
    @endforelse