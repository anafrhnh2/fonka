@extends('layouts.admin')

@section('title', 'Manage Game Levels')

@section('page_title')
    Game <span class="text-[#ff2a85] neon-text">Levels</span>
@endsection

@section('page_subtitle', 'Update thumbnail images and display names for fixed interactive phonics game stages.')

@section('content')
    <div class="bg-[#121212] rounded-2xl border border-[#333] p-5 mb-8 animate-enter delay-100 flex flex-col sm:flex-row justify-between items-center gap-4">
        <div>
            <span class="text-sm font-semibold text-gray-400">
                <i class="fa-solid fa-triangle-exclamation text-amber-500 me-1"></i> Core game logic structure is fixed. You can only edit images & names.
            </span>
        </div>
       
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6 mb-8 animate-enter delay-200">
        @forelse($gameLevels as $level)
            <div class="stat-card neon-border rounded-3xl p-5 flex flex-col justify-between h-full bg-[#121212]">
                <div>
                    <div class="w-full h-32 rounded-2xl bg-black/50 border border-zinc-800/80 mb-4 overflow-hidden flex items-center justify-center p-2 relative group">
                        <img src="{{ asset('images/games/thumbnail/' . ($level->image ?? 'default_level.png')) }}" alt="Level Image" 
                             class="max-h-full max-w-full object-contain transition-transform duration-300 group-hover:scale-110"
                             onerror="this.onerror=null; this.src='https://placehold.co/150x120/121212/ff2a85?text=Level+' + {{ $level->level_number }};">
                        
                        <span class="absolute top-2 left-2 px-2.5 py-0.5 rounded-md bg-cyan-500 text-black font-extrabold text-xs shadow-md">
                            Level #{{ $level->level_number }}
                        </span>
                    </div>

                    <div class="space-y-1 mb-4">
                        <h4 class="font-bold text-white text-base truncate">{{ $level->name }}</h4>
                        <div class="flex items-center gap-4 text-xs font-semibold text-gray-400">
                            <span class="text-yellow-400 flex items-center gap-1">
                                <i class="fa-solid fa-star"></i> {{ $level->max_stars }} Stars Earned
                            </span>
            
                        </div>
                    </div>
                </div>

                <div class="flex gap-2 pt-2 border-t border-zinc-800/60 justify-between items-center mt-auto">                    
                    <button type="button" 
                            onclick="openEditLevelModal({{ json_encode($level) }})"
                            class="px-3 py-1.5 rounded-lg bg-zinc-800 text-gray-400 hover:text-cyan-400 hover:bg-cyan-500/10 border border-transparent hover:border-cyan-500/30 transition-all flex items-center gap-1.5 text-xs font-bold focus:outline-none">
                        <i class="fa-solid fa-pen"></i> Edit Asset
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 bg-[#121212] rounded-3xl border border-[#333] text-center text-gray-500 font-medium">
                <div class="text-4xl mb-2">🎮</div>
                No interactive game levels registered inside database yet.
            </div>
        @endforelse
    </div>

    @if($gameLevels->hasPages())
        <div class="bg-[#121212] rounded-2xl border border-[#333] p-4 custom-pagination">
            {{ $gameLevels->appends(request()->query())->links() }}
        </div>
    @endif

    <div id="editLevelModal" class="fixed inset-0 z-50 hidden overflow-y-auto flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm transition-opacity duration-300">
        <div class="bg-[#121212] border-2 border-[#ff2a85]/40 rounded-[2rem] w-full max-w-md overflow-hidden shadow-[0_0_30px_rgba(255,42,133,0.2)] transform scale-95 transition-transform duration-300">
            <div class="px-6 py-4 border-b border-zinc-800 flex justify-between items-center bg-zinc-900/50">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-gamepad text-[#ff2a85]"></i> Edit Level <span id="modal-level-title" class="text-cyan-400"></span>
                </h3>
                <button type="button" onclick="closeEditLevelModal()" class="text-gray-400 hover:text-[#ff2a85] transition-colors focus:outline-none">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            
            <form id="editLevelForm" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
                @csrf
                @method('PUT') <div>
                    <label class="block text-gray-500 text-xs font-bold uppercase tracking-wider mb-2 ml-1">Stage Level (Fixed Number)</label>
                    <input type="text" id="modal-level-number" disabled
                           class="w-full bg-zinc-900 text-gray-500 px-4 py-3 rounded-xl border border-zinc-800 font-bold focus:outline-none cursor-not-allowed">
                </div>

                <div>
                    <label class="block text-[#ff99d6] text-xs font-bold uppercase tracking-wider mb-2 ml-1">Display Name</label>
                    <input type="text" name="name" id="modal-level-name" required
                           class="w-full bg-black text-white px-4 py-3 rounded-xl border border-zinc-800 focus:outline-none focus:border-[#ff2a85] transition-all font-semibold text-sm">
                </div>

                <div>
                    <label class="block text-[#ff99d6] text-xs font-bold uppercase tracking-wider mb-2 ml-1">Replace Thumbnail Image</label>
                    <div class="w-full bg-black rounded-xl border border-zinc-800 p-4 flex flex-col items-center justify-center border-dashed border-zinc-700 hover:border-[#ff2a85]/50 transition-colors relative cursor-pointer group">
                        <input type="file" name="image" id="imageInput" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer z-10">
                        <div class="text-center space-y-1 pointer-events-none" id="uploadPlaceholder">
                            <i class="fa-solid fa-file-image text-2xl text-gray-500 group-hover:text-[#ff2a85] transition-colors"></i>
                            <p class="text-xs text-gray-400 font-medium">Click to upload new graphic</p>
                        </div>
                        <img id="imagePreview" class="hidden max-h-24 object-contain rounded-lg shadow-md z-0 mt-1">
                    </div>
                </div>

                <div class="flex gap-3 pt-4 border-t border-zinc-800/60 justify-end">
                    <button type="button" onclick="closeEditLevelModal()" 
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
        const modal = document.getElementById('editLevelModal');
        const modalContent = modal.querySelector('.transform');

        function openEditLevelModal(level) {
            document.getElementById('modal-level-title').innerText = `#${level.level_number}`;
            document.getElementById('modal-level-number').value = `Level ${level.level_number}`;
            document.getElementById('modal-level-name').value = level.name;

            document.getElementById('editLevelForm').action = `/admin/manage-game-levels/${level.id}`;

            const preview = document.getElementById('imagePreview');
            const placeholder = document.getElementById('uploadPlaceholder');
            
            if (level.image) {
                preview.src = `/images/games/thumbnail/${level.image}`;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            } else {
                preview.classList.add('hidden');
                placeholder.classList.remove('hidden');
            }

            modal.classList.remove('hidden');
            setTimeout(() => {
                modalContent.classList.remove('scale-95');
                modalContent.classList.add('scale-100');
            }, 10);
        }

        function closeEditLevelModal() {
            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
                document.getElementById('imageInput').value = ''; // Reset input fail
            }, 150);
        }

        document.getElementById('imageInput').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('uploadPlaceholder').classList.add('hidden');
                    const preview = document.getElementById('imagePreview');
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        });

        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeEditLevelModal();
        });

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Successful!',
                text: "{{ session('success') }}",
                confirmButtonColor: '#ff2a85',
                background: '#121212',
                color: '#ffffff',
                customClass: { popup: 'border-2 border-[#ff2a85]/30 rounded-3xl' }
            });
        @endif
    </script>
@endpush