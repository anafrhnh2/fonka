@extends('layouts.admin')

@section('title', 'Manage Reading Modules')

@section('page_title')
    Reading <span class="text-[#ff2a85] neon-text">Modules</span>
@endsection

@section('page_subtitle', 'Update thumbnail images and main titles.')

@section('content')


    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4 gap-6 mb-8 animate-enter delay-200">
        @forelse($readingModules as $module)
            <div class="stat-card neon-border rounded-3xl p-5 flex flex-col justify-between h-full bg-[#121212]">
                <div>
                    <div class="w-full h-36 rounded-2xl bg-black/50 border border-zinc-800/80 mb-4 overflow-hidden flex items-center justify-center p-2 relative group">
                        <img src="{{ asset('images/games/thumbnail/' . ($module->image ?? 'default_read.png')) }}" alt="Module Cover" 
                             class="max-h-full max-w-full object-contain transition-transform duration-300 group-hover:scale-110"
                             onerror="this.onerror=null; this.src='https://placehold.co/180x140/121212/ff2a85?text=Module+' + {{ $module->module_number }};">       
                    </div>

                    <div class="mb-4">
                        <h4 class="font-bold text-white text-base leading-snug line-clamp-2" title="{{ $module->title }}">
                            {{ $module->title }}
                        </h4>
                    </div>
                </div>

                <div class="flex gap-2 pt-3 border-t border-zinc-800/60 justify-between items-center mt-auto">                    
                    <button type="button" 
                            onclick="openEditReadModal({{ json_encode($module) }})"
                            class="px-3 py-1.5 rounded-lg bg-zinc-800 text-gray-400 hover:text-purple-400 hover:bg-purple-500/10 border border-transparent hover:border-purple-500/30 transition-all flex items-center gap-1.5 text-xs font-bold focus:outline-none">
                        <i class="fa-solid fa-sliders"></i> Edit Module
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 bg-[#121212] rounded-3xl border border-[#333] text-center text-gray-500 font-medium">
                <div class="text-4xl mb-2"></div>
                No phonics reading modules registered in the database template.
            </div>
        @endforelse
    </div>

    @if($readingModules->hasPages())
        <div class="bg-[#121212] rounded-2xl border border-[#333] p-4 custom-pagination">
            {{ $readingModules->appends(request()->query())->links() }}
        </div>
    @endif

    <div id="editReadModal" class="fixed inset-0 z-50 hidden overflow-y-auto flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm transition-opacity duration-300">
        <div class="bg-[#121212] border-2 border-[#ff2a85]/40 rounded-[2rem] w-full max-w-md overflow-hidden shadow-[0_0_30px_rgba(255,42,133,0.2)] transform scale-95 transition-transform duration-300">
            <div class="px-6 py-4 border-b border-zinc-800 flex justify-between items-center bg-zinc-900/50">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-book-open text-[#ff2a85]"></i> Modify Chapter <span id="modal-read-title" class="text-purple-400"></span>
                </h3>
                <button type="button" onclick="closeEditReadModal()" class="text-gray-400 hover:text-[#ff2a85] transition-colors focus:outline-none">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            
            <form id="editReadForm" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-gray-500 text-xs font-bold uppercase tracking-wider mb-2 ml-1">Module Position Index</label>
                    <input type="text" id="modal-read-number" disabled
                           class="w-full bg-zinc-900 text-gray-500 px-4 py-3 rounded-xl border border-zinc-800 font-bold focus:outline-none cursor-not-allowed text-sm">
                </div>

                <div>
                    <label class="block text-[#ff99d6] text-xs font-bold uppercase tracking-wider mb-2 ml-1">Syllabus Title Name</label>
                    <textarea name="title" id="modal-read-name" required rows="2"
                              class="w-full bg-black text-white px-4 py-3 rounded-xl border border-zinc-800 focus:outline-none focus:border-[#ff2a85] transition-all font-semibold text-sm resize-none leading-relaxed"></textarea>
                </div>

                <div>
                    <label class="block text-[#ff99d6] text-xs font-bold uppercase tracking-wider mb-2 ml-1">Replace Illustrated Artwork</label>
                    <div class="w-full bg-black rounded-xl border border-zinc-800 p-4 flex flex-col items-center justify-center border-dashed border-zinc-700 hover:border-[#ff2a85]/50 transition-colors relative cursor-pointer group">
                        <input type="file" name="image" id="readImageInput" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer z-10">
                        <div class="text-center space-y-1 pointer-events-none" id="uploadPlaceholder">
                            <i class="fa-solid fa-images text-2xl text-gray-500 group-hover:text-[#ff2a85] transition-colors"></i>
                            <p class="text-xs text-gray-400 font-medium">Click to pick new thumbnail image</p>
                        </div>
                        <img id="readImagePreview" class="hidden max-h-24 object-contain rounded-lg shadow-md z-0 mt-1">
                    </div>
                </div>

                <div class="flex gap-3 pt-4 border-t border-zinc-800/60 justify-end">
                    <button type="button" onclick="closeEditReadModal()" 
                            class="px-5 py-2.5 rounded-xl bg-zinc-900 border border-zinc-800 text-gray-400 font-bold hover:bg-zinc-800 transition-colors text-xs uppercase tracking-wider">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-5 py-2.5 rounded-xl bg-[#ff2a85] text-white font-bold hover:bg-[#ff4da3] transition-all duration-300 shadow-[0_0_15px_rgba(255,42,133,0.3)] text-xs uppercase tracking-wider">
                        Update Core Module
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const modal = document.getElementById('editReadModal');
        const modalContent = modal.querySelector('.transform');

        function openEditReadModal(module) {
            // Pasangkan data parameter pangkalan data ke entiti elemen input HTML
            document.getElementById('modal-read-title').innerText = `#${module.module_number}`;
            document.getElementById('modal-read-number').value = `Chapter ${module.module_number} Module Node`;
            document.getElementById('modal-read-name').value = module.title;

            // Integrasikan endpoint API form action kemas kini mengikut ID sasaran secara dinamik
            document.getElementById('editReadForm').action = `/admin/reading-modules/update/${module.id}`;

            const preview = document.getElementById('readImagePreview');
            const placeholder = document.getElementById('uploadPlaceholder');
            
            if (module.image) {
                preview.src = `images/games/thumbnail/${module.image}`;
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

        function closeEditReadModal() {
            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
                document.getElementById('readImageInput').value = ''; 
            }, 150);
        }

        document.getElementById('readImageInput').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('uploadPlaceholder').classList.add('hidden');
                    const preview = document.getElementById('readImagePreview');
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        });

        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeEditReadModal();
        });

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Core System Synchronized!',
                text: "{{ session('success') }}",
                confirmButtonColor: '#ff2a85',
                background: '#121212',
                color: '#ffffff',
                customClass: { popup: 'border-2 border-[#ff2a85]/30 rounded-3xl' }
            });
        @endif
    </script>
@endpush