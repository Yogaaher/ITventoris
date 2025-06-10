@forelse($users as $user)
    <div class="products-row" data-user-id="{{ $user->id }}">
        <div class="product-cell cell-no">{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</div>
        <div class="product-cell cell-username" title="{{ $user->name }}">{{ $user->name }}</div>
        <div class="product-cell cell-email" title="{{ $user->email }}">{{ $user->email }}</div>
        <div class="product-cell cell-role">{{ $user->role ?? 'User' }}</div>
        <div class="product-cell cell-tanggal-dibuat">{{ $user->created_at->format('d-m-Y') }}</div>
        <div class="product-cell cell-aksi">
            @if(strtolower($user->role) !== 'admin')
                <button class="action-btn-table edit-btn" title="Edit User"><i class="fas fa-edit"></i><span>Edit User</span></button>
                <button class="action-btn-table delete-btn" title="Hapus User"><i class="fas fa-trash-alt"></i><span>Hapus</span></button>
            @else
                <button class="action-btn-table edit-btn" disabled style="cursor: not-allowed; opacity: 0.5;"><i class="fas fa-edit"></i><span>Edit User</span></button>
                <button class="action-btn-table delete-btn" disabled style="cursor: not-allowed; opacity: 0.5;"><i class="fas fa-trash-alt"></i><span>Hapus</span></button>
            @endif
            
        </div>
    </div>
@empty
    <div class="products-row">
        <div class="product-cell" style="text-align:center; flex-basis:100%; padding: 20px;">Tidak ada data user ditemukan.</div>
    </div>
@endforelse