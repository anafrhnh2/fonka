@extends('layouts.admin')

@section('title', 'Manage Avatars')

@section('page_title')
    Manage <span class="text-[#ff2a85] neon-text">Avatars</span>
@endsection

@section('page_subtitle', 'Add, remove, or modify custom reward store avatars for child profiles.')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
    
    <div class="bg-[#121212] border border-[#333] rounded-[2rem] p-6 shadow-xl animate-enter delay-100">
        <h3 class="text-base font-bold text-white uppercase tracking-wider mb-5 flex items-center gap-2">
            <i class="fa-solid fa-wand-magic-sparkles text-[#ff2a85]"></i> Add New Avatar
        </h3>
        
        <form method="POST" action="{{ route('admin.avatars.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            
            <div>
                <label class="block text-[#ff99d6] text-xs font-bold uppercase tracking-wider mb-2 ml-1">Avatar Name</label>
                <input type="text" name="name" required placeholder="e.g. Cutie Pie"
                       class="w-full bg-black text-white px-4 py-3 rounded-xl border border-gray-800 focus:outline-none focus:border-[#ff2a85] transition-all font-semibold text-sm">
            </div>

            <div>
                <label class="block text-[#ff99d6] text-xs font-bold uppercase tracking-wider mb-2 ml-1">Price (Points)</label>
                <input type="number" name="price" required min="0" value="0"
                       class="w-full bg-black text-white px-4 py-3 rounded-xl border border-gray-800 focus:outline-none focus:border-[#ff2a85] transition-all font-semibold text-sm">
                <span class="text-[10px] text-gray-500 mt-1 block ml-1">*Set to 0 to make it FREE for starter users</span>
            </div>

            <div>
                <label class="block text-[#ff99d6] text-xs font-bold uppercase tracking-wider mb-2 ml-1">Upload Graphic (.png)</label>
                <div class="w-full bg-black rounded-xl border border-gray-800 p-4 flex flex-col items-center justify-center border-dashed border-zinc-700 hover:border-[#ff2a85]/50 transition-colors relative cursor-pointer group">
                    <input type="file" name="image" id="avatarInput" accept="image/*" required class="absolute inset-0 opacity-0 cursor-pointer z-10">
                    <div class="text-center space-y-1 pointer-events-none" id="uploadPlaceholder">
                        <i class="fa-solid fa-cloud-arrow-up text-xl text-gray-500 group-hover:text-[#ff2a85] transition-colors"></i>
                        <p class="text-xs text-gray-400 font-medium">Click to select image file</p>
                    </div>
                    <img id="avatarPreview" class="hidden max-h-20 object-contain rounded-lg shadow-md z-0">
                </div>
            </div>

            <button type="submit" 
                    class="w-full bg-[#ff2a85] text-white font-bold text-xs py-3.5 rounded-xl uppercase tracking-widest hover:bg-[#ff4da3] transition-all duration-300 shadow-[0_0_15px_rgba(255,42,133,0.3)] transform hover:-translate-y-0.5 active:scale-95 flex justify-center items-center gap-2">
                <i class="fa-solid fa-plus"></i> Deploy Avatar
            </button>
        </form>
    </div>

    <div class="lg:col-span-2 space-y-6">
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 animate-enter delay-200">
            @forelse($avatars as $avatar)
                <div class="stat-card border rounded-2xl p-4 bg-[#121212] flex flex-col justify-between items-center text-center transition-all duration-300 {{ $avatar->is_active ? 'border-zinc-800/80' : 'border-dashed border-red-500/20 opacity-50' }}">
                    
                    <div class="w-20 h-20 rounded-full bg-black/40 border border-zinc-800 flex items-center justify-center p-2 relative mb-3 group">
                        <img src="{{ asset($avatar->image) }}" alt="{{ $avatar->name }}" 
                             class="max-h-full max-w-full object-contain transition-transform duration-300 group-hover:scale-110"
                             onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=' + encodeURIComponent('{{ $avatar->name }}') + '&background=ff2a85&color=ffffff';">
                    </div>

                    <div class="w-full mb-3 space-y-0.5">
                        <h4 class="font-bold text-white text-sm truncate">{{ $avatar->name }}</h4>
                        <p class="text-xs font-bold {{ $avatar->price > 0 ? 'text-yellow-400' : 'text-emerald-400' }}">
                            {{ $avatar->price > 0 ? $avatar->price . ' Pts' : 'FREE' }}
                        </p>
                    </div>

                    <div class="w-full pt-2 border-t border-zinc-800/60 flex flex-col gap-2 mt-auto">
                        <div class="flex items-center justify-between w-full gap-2">
                            <form action="{{ route('admin.avatars.toggle', $avatar->id) }}" method="POST" class="m-0">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        class="px-2 py-1 rounded text-[10px] font-extrabold uppercase tracking-wider transition-colors {{ $avatar->is_active ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 hover:bg-emerald-500/20' : 'bg-zinc-800 text-zinc-500 border border-zinc-700/50 hover:bg-zinc-700' }}">
                                    {{ $avatar->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </form>

                            <div class="flex gap-1.5">
                                <button type="button" onclick="openEditAvatarModal({{ json_encode($avatar) }})"
                                        class="w-6 h-6 rounded bg-zinc-800 text-gray-400 hover:text-cyan-400 hover:bg-cyan-500/10 transition-colors flex items-center justify-center text-xs focus:outline-none"
                                        title="Edit Avatar">
                                    <i class="fa-solid fa-pen"></i>
                                </button>

                                <form id="delete-avatar-{{ $avatar->id }}" action="{{ route('admin.avatars.destroy', $avatar->id) }}" method="POST" class="m-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmAvatarDelete({{ $avatar->id }}, '{{ $avatar->name }}')"
                                            class="w-6 h-6 rounded bg-zinc-900 text-zinc-500 hover:text-red-500 hover:bg-red-500/10 transition-colors flex items-center justify-center text-xs focus:outline-none"
                                            title="Delete Avatar">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 bg-[#121212] rounded-2xl border border-dashed border-zinc-800 text-center text-gray-500 text-sm font-medium">
                    🛸 No system shop avatars found inside database.
                </div>
            @endforelse
        </div>

        @if($avatars->hasPages())
            <div class="bg-[#121212] rounded-2xl border border-[#333] p-4 custom-pagination">
                {{ $avatars->links() }}
            </div>
        @endif
    </div>
</div>

<div id="editAvatarModal" class="fixed inset-0 z-50 hidden overflow-y-auto flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm transition-opacity duration-300">
    <div class="bg-[#121212] border-2 border-[#ff2a85]/40 rounded-[2rem] w-full max-w-sm overflow-hidden shadow-[0_0_30px_rgba(255,42,133,0.2)] transform scale-95 transition-transform duration-300">
        <div class="px-6 py-4 border-b border-zinc-800 flex justify-between items-center bg-zinc-900/50">
            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                <i class="fa-solid fa-user-gear text-[#ff2a85]"></i> Edit Avatar Config
            </h3>
            <button type="button" onclick="closeEditAvatarModal()" class="text-gray-400 hover:text-[#ff2a85] transition-colors focus:outline-none">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        
        <form id="editAvatarForm" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
            @csrf
            @method('PUT')

            <div class="flex justify-center py-1">
                <div class="w-20 h-20 rounded-full bg-black/40 border-2 border-[#ff2a85]/30 overflow-hidden flex items-center justify-center p-1">
                    <img id="modalAvatarPreview" src="" class="max-h-full max-w-full object-contain">
                </div>
            </div>

            <div>
                <label class="block text-[#ff99d6] text-xs font-bold uppercase tracking-wider mb-2 ml-1">Avatar Name</label>
                <input type="text" name="name" id="modalAvatarName" required
                       class="w-full bg-black text-white px-4 py-3 rounded-xl border border-zinc-800 focus:outline-none focus:border-[#ff2a85] transition-all font-semibold text-sm">
            </div>

            <div>
                <label class="block text-[#ff99d6] text-xs font-bold uppercase tracking-wider mb-2 ml-1">Price (Points)</label>
                <input type="number" name="price" id="modalAvatarPrice" required min="0"
                       class="w-full bg-black text-white px-4 py-3 rounded-xl border border-zinc-800 focus:outline-none focus:border-[#ff2a85] transition-all font-semibold text-sm">
            </div>

            <div>
                <label class="block text-[#ff99d6] text-xs font-bold uppercase tracking-wider mb-2 ml-1">Replace Image (Optional)</label>
                <div class="w-full bg-black rounded-xl border border-zinc-800 p-3 flex flex-col items-center justify-center border-dashed border-zinc-700 hover:border-[#ff2a85]/50 transition-colors relative cursor-pointer group">
                    <input type="file" name="image" id="editAvatarFileInput" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer z-10">
                    <div class="text-center space-y-0.5 pointer-events-none" id="modalUploadPlaceholder">
                        <i class="fa-solid fa-file-image text-xl text-gray-500 group-hover:text-[#ff2a85] transition-colors"></i>
                        <p class="text-[11px] text-gray-400 font-medium">Click to upload new graphic file</p>
                    </div>
                </div>
            </div>

            <div class="flex gap-3 pt-4 border-t border-zinc-800/60 justify-end">
                <button type="button" onclick="closeEditAvatarModal()" 
                        class="px-5 py-2.5 rounded-xl bg-zinc-900 border border-zinc-800 text-gray-400 font-bold hover:bg-zinc-800 transition-colors text-xs uppercase tracking-wider">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-5 py-2.5 rounded-xl bg-[#ff2a85] text-white font-bold hover:bg-[#ff4da3] transition-all duration-300 shadow-[0_0_15px_rgba(255,42,133,0.3)] text-xs uppercase tracking-wider">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Preview Image untuk Form ADD
        document.getElementById('avatarInput').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('uploadPlaceholder').classList.add('hidden');
                    const preview = document.getElementById('avatarPreview');
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        });

        // --- MANAGE EDIT MODAL INTERACTION ---
        const modal = document.getElementById('editAvatarModal');
        const modalContent = modal.querySelector('.transform');

        function openEditAvatarModal(avatar) {
            document.getElementById('modalAvatarName').value = avatar.name;
            document.getElementById('modalAvatarPrice').value = avatar.price;
            document.getElementById('modalAvatarPreview').src = `/${avatar.image}`;
            
            // Set dinamik form action URL ke AdminController update route anda
            // Contoh URL: /admin/avatars/update/5
            document.getElementById('editAvatarForm').action = `/admin/avatars/update/${avatar.id}`;

            modal.classList.remove('hidden');
            setTimeout(() => {
                modalContent.classList.remove('scale-95');
                modalContent.classList.add('scale-100');
            }, 10);
        }

        function closeEditAvatarModal() {
            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
                document.getElementById('editAvatarFileInput').value = '';
            }, 150);
        }

        // Preview Image untuk Form EDIT di dalam Modal
        document.getElementById('editAvatarFileInput').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('modalAvatarPreview').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });

        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeEditAvatarModal();
        });

        // SweetAlert2 Pengesahan Padam (Delete)
        function confirmAvatarDelete(avatarId, avatarName) {
            Swal.fire({
                title: 'Delete Avatar?',
                text: `Are you absolute sure you want to permanently delete "${avatarName}" from the ecosystem store?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ff2a85',
                cancelButtonColor: '#27272a',
                confirmButtonText: 'Yes, shred it!',
                cancelButtonText: 'Cancel',
                background: '#121212',
                color: '#ffffff',
                customClass: { popup: 'border-2 border-[#ff2a85]/30 rounded-3xl' }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-avatar-${avatarId}`).submit();
                }
            });
        }

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: "{{ session('success') }}",
                confirmButtonColor: '#ff2a85',
                background: '#121212',
                color: '#ffffff',
                customClass: { popup: 'border-2 border-[#ff2a85]/30 rounded-3xl' }
            });
        @endif
    </script>
@endpush