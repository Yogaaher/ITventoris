@php
$nomor = ($users->currentPage() - 1) * $users->perPage() + 1;
@endphp

@forelse ($users as $user)
<div class="products-row" data-user-id="{{ $user->id }}">
    <div class="product-cell cell-no">{{ $nomor++ }}</div>
    <div class="product-cell cell-username">{{ $user->name }}</div>
    <div class="product-cell cell-email">{{ $user->email }}</div>
    <div class="product-cell cell-role">{{ Str::title(str_replace('_', ' ', $user->role)) }}</div>
    <div class="product-cell cell-tanggal-dibuat">{{ $user->created_at->format('d M Y') }}</div>
    <div class="product-cell cell-aksi">
        @php
        $canEdit = false;
        $canDelete = false;

        if ($currentUser->isSuperAdmin()) {
        $canEdit = true;
        $canDelete = ($currentUser->id !== $user->id); // Bisa hapus semua kecuali diri sendiri
        } elseif ($currentUser->isAdmin()) {
        if ($user->isUser()) {
        $canEdit = true;
        $canDelete = true;
        }
        }
        @endphp

        @if($canEdit)
        <button class="action-btn-table edit-btn" title="Edit User">
            <i class="fas fa-edit"></i> Edit
        </button>
        @endif
        <button class="action-btn-table delete-btn"
            {{-- Tambahkan 'disabled' jika $canDelete adalah false --}}
            {{ !$canDelete ? 'disabled' : '' }}
            {{-- Ganti judul tombolnya juga biar informatif --}}
            title="{{ $canDelete ? 'Hapus User' : 'Aksi tidak diizinkan' }}">
            <i class="fas fa-trash"></i> Hapus
        </button>
    </div>
</div>
@empty
<div class="products-row">
    <div class="product-cell" style="text-align:center; flex-basis:100%; padding: 20px;">
        Tidak ada data pengguna yang ditemukan.
    </div>
</div>
@endforelse