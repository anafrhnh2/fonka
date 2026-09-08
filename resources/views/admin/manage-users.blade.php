@extends('layouts.admin')

@section('title', 'Manage Users')

@section('page_title')
    Manage <span class="text-[#ff2a85] neon-text">Users</span>
@endsection

@section('page_subtitle', 'View, search, and manage system parent and admin accounts.')

@section('content')
    <div class="bg-[#121212] rounded-2xl border border-[#333] p-5 mb-8 animate-enter delay-100">
        <form method="GET" action="{{ route('admin.manage-users') }}" class="flex flex-col md:flex-row gap-4 justify-between items-center">
            <div class="relative w-full md:w-96">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-500">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="w-full bg-black text-white pl-11 pr-4 py-3 rounded-xl border border-gray-800 focus:outline-none focus:border-[#ff2a85] transition-all placeholder-gray-600 font-medium text-sm"
                       placeholder="Search users...">
            </div>
        </form>
    </div>

    <div class="bg-[#121212] rounded-2xl border border-[#333] p-6 animate-enter delay-200 shadow-2xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-gray-400 text-sm uppercase border-b border-[#333] tracking-wider">
                        <th class="pb-4 font-bold pl-2">Parents</th>
                        <th class="pb-4 font-bold">Email</th>
                        <th class="pb-4 font-bold">Children</th>
                        <th class="pb-4 font-bold">Joined Date</th>
                        <th class="pb-4 font-bold text-right pr-2">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-[#222]/40">
                    @forelse($users as $user)
                        <tr class="hover:bg-[#1a1a1a]/60 transition-colors duration-200">
                            <td class="py-4 pl-2 flex items-center gap-3">
                                @if($user->profile)
                                    <img src="{{ asset('storage/' . $user->profile) }}" alt="Profile" class="w-10 h-10 rounded-full object-cover border border-[#ff2a85]/40 shadow-sm">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-[#ff2a85]/10 text-[#ff2a85] flex items-center justify-center font-bold text-base uppercase border border-[#ff2a85]/20">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <span class="font-bold text-white block leading-tight">{{ $user->name }}</span>
                                    <span class="text-xs text-gray-500">ID: #{{ $user->id }}</span>
                                </div>
                            </td>

                            <td class="py-4 text-gray-300 font-medium">{{ $user->email }}</td>

                            <td class="py-4">
                                @if($user->children_count > 0)
                                    <div class="flex flex-wrap gap-1.5 max-w-[200px]">
                                        @if($user->children && $user->children->count() > 0)
                                            @foreach($user->children as $child)
                                                <span class="inline-block px-2 py-0.5 rounded text-[11px] font-semibold bg-black text-cyan-400 border border-cyan-500/20">
                                                    {{ $child->name }} ({{ $child->age }}y)
                                                </span>
                                            @endforeach
                                        @else
                                            <span class="px-2.5 py-1 rounded-md bg-cyan-500/10 text-cyan-400 text-xs font-bold border border-cyan-500/20 shadow-sm">
                                                 {{ $user->children_count }} Child(ren)
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-gray-600 text-xs italic ml-2">No child profiles</span>
                                @endif
                            </td>

                            <td class="py-4 text-gray-400 font-medium">
                                {{ $user->created_at->format('d M Y') }}
                                <span class="block text-xs text-gray-600 mt-0.5">{{ $user->created_at->diffForHumans() }}</span>
                            </td>

                            <td class="py-4 text-right pr-2">
                                <div class="flex gap-2 justify-end">
                                    <button type="button" 
                                            class="w-8 h-8 rounded-lg bg-zinc-800 text-gray-400 hover:text-cyan-400 hover:bg-cyan-500/10 border border-transparent hover:border-cyan-500/30 transition-all flex items-center justify-center text-sm focus:outline-none"
                                            title="View Details"
                                            onclick="openViewModal({{ json_encode($user) }}, {{ json_encode($user->children) }})">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>

                                    <form id="delete-form-{{ $user->id }}" action="{{ route('admin.manage-users.destroy', $user->id) }}" method="POST" class="m-0 inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" 
                                                onclick="confirmDelete({{ $user->id }}, '{{ $user->name }}')"
                                                class="w-8 h-8 rounded-lg bg-zinc-800 text-gray-400 hover:text-red-500 hover:bg-red-500/10 border border-transparent hover:border-red-500/30 transition-all flex items-center justify-center text-sm focus:outline-none"
                                                title="Delete User">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-gray-500 font-medium">
                                <div class="text-3xl mb-2">🔍</div>
                                No user data found matching your search.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="mt-6 pt-4 border-t border-[#222]/60 custom-pagination">
                {{ $users->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

    <div id="viewUserModal" class="fixed inset-0 z-50 hidden overflow-y-auto hidden flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm transition-opacity duration-300">
        <div class="bg-[#121212] border-2 border-[#ff2a85]/40 rounded-[2rem] w-full max-w-2xl overflow-hidden shadow-[0_0_30px_rgba(255,42,133,0.2)] transform scale-95 transition-transform duration-300">
            <div class="px-6 py-4 border-b border-zinc-800 flex justify-between items-center bg-zinc-900/50">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-id-card text-[#ff2a85]"></i> User Detailed Account
                </h3>
                <button onclick="closeViewModal()" class="text-gray-400 hover:text-[#ff2a85] transition-colors focus:outline-none">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            
            <div class="p-6 space-y-6">
                <div class="bg-black/40 rounded-xl p-4 border border-zinc-800 flex flex-col sm:flex-row gap-4 items-center">
                    <div id="modal-avatar-box" class="w-16 h-16 rounded-full flex items-center justify-center font-bold text-2xl border border-[#ff2a85]/30 text-[#ff2a85] bg-[#ff2a85]/10"></div>
                    <div class="flex-1 text-center sm:text-left space-y-1">
                        <h4 id="modal-user-name" class="text-xl font-bold text-white leading-tight"></h4>
                        <p id="modal-user-email" class="text-gray-400 text-sm"></p>
                        <p class="text-xs text-gray-500">Registered On: <span id="modal-user-date" class="text-gray-400 font-medium"></span></p>
                    </div>
                </div>

                <div>
                    <h5 class="text-sm font-bold text-[#ff99d6] uppercase tracking-wider mb-3 flex items-center gap-2">
                        <i class="fa-solid fa-children"></i> Registered Children Profiles
                    </h5>
                    <div id="modal-children-container" class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-h-60 overflow-y-auto pr-1">
                        </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Memperkemas rupa pagination Laravel Tailwind agar serasi dengan mod gelap/neon */
        .custom-pagination nav svg {
            width: 1.25rem;
            height: 1.25rem;
            display: inline;
        }
        .custom-pagination nav div {
            color: #64748b !important;
        }
        .custom-pagination nav span[aria-current="page"] span {
            background-color: #ff2a85 !important;
            border-color: #ff2a85 !important;
            color: white !important;
        }
        .custom-pagination nav a, .custom-pagination nav span {
            background-color: #121212 !important;
            border-color: #333 !important;
            color: #a1a1aa !important;
            border-radius: 0.5rem;
        }
        .custom-pagination nav a:hover {
            border-color: #ff2a85 !important;
            color: #ff2a85 !important;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // 1. POPUP PENGESAHAN PADAM BERGAYA NEON MERAH JAMBU (SWEETALERT2)
        function confirmDelete(userId, userName) {
            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to delete ${userName}'s account. This will permanently remove all related children data!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ff2a85', // Tema Neon Pink
                cancelButtonColor: '#27272a',  // Zinc 800
                confirmButtonText: '<i class="fa-solid fa-trash-can"></i> Yes, delete it!',
                cancelButtonText: 'Cancel',
                background: '#121212',
                color: '#ffffff',
                customClass: {
                    popup: 'border-2 border-[#ff2a85]/30 rounded-3xl',
                    title: 'font-bold tracking-wide text-white',
                    htmlContainer: 'text-gray-400 font-medium'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-form-${userId}`).submit();
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
                customClass: {
                    popup: 'border-2 border-[#ff2a85]/30 rounded-3xl'
                }
            });
        @endif

        // 3. FUNGSI MENAMPILKAN MODAL DETAIL IBU BAPA & SENARAI ANAK
        const modal = document.getElementById('viewUserModal');
        const modalContent = modal.querySelector('.transform');

        function openViewModal(user, children) {
            // Isi kandungan utama maklumat user
            document.getElementById('modal-user-name').innerText = user.name;
            document.getElementById('modal-user-email').innerText = user.email;
            
            // Format Tarikh Berdaftar
            const date = new Date(user.created_at);
            document.getElementById('modal-user-date').innerText = date.toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' });

            // Sediakan kotak avatar huruf pertama nama
            const avatarBox = document.getElementById('modal-avatar-box');
            if(user.profile) {
                avatarBox.innerHTML = `<img src="/storage/${user.profile}" class="w-full h-full rounded-full object-cover">`;
                avatarBox.className = "w-16 h-16 rounded-full overflow-hidden border border-[#ff2a85]/30";
            } else {
                avatarBox.innerText = user.name.substring(0, 1).toUpperCase();
                avatarBox.className = "w-16 h-16 rounded-full flex items-center justify-center font-bold text-2xl border border-[#ff2a85]/30 text-[#ff2a85] bg-[#ff2a85]/10";
            }

            // Bersihkan senarai kontena anak sebelum ini
            const container = document.getElementById('modal-children-container');
            container.innerHTML = '';

            // Janakan kad bagi setiap profil anak
            if (children && children.length > 0) {
                children.forEach(child => {
                    const childCard = `
                        <div class="p-3 bg-black/60 rounded-xl border border-zinc-800 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-cyan-500/10 border border-cyan-500/20 flex items-center justify-center text-cyan-400 text-lg">
                                <i class="fa-solid fa-child"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h6 class="font-bold text-white truncate text-sm">${child.name}</h6>
                                <p class="text-xs text-gray-500">Age: <span class="text-gray-300 font-medium">${child.age} Years Old</span></p>
                                <div class="flex gap-3 mt-1 text-[10px] font-bold uppercase tracking-wider">
                                    <span class="text-pink-400">Lv: ${child.current_level}</span>
                                    <span class="text-yellow-400">Pts: ${child.total_point}</span>
                                </div>
                            </div>
                        </div>
                    `;
                    container.insertAdjacentHTML('beforeend', childCard);
                });
            } else {
                container.innerHTML = `
                    <div class="col-span-2 py-6 text-center text-gray-600 text-xs italic bg-black/20 rounded-xl border border-zinc-900 border-dashed">
                        No child profile registered under this account yet.
                    </div>
                `;
            }

            // Paparkan modal dengan animasi mikro
            modal.classList.remove('hidden');
            setTimeout(() => {
                modalContent.classList.remove('scale-95');
                modalContent.classList.add('scale-100');
            }, 10);
        }

        function closeViewModal() {
            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 150);
        }

        // Tutup modal apabila pengguna klik di luar kawasan kotak modal
        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeViewModal();
        });
    </script>
@endpush