@extends('layouts.admin')

@section('title', 'Staff Management')

@section('content')
<div class="space-y-6" x-data="staffModal()">

    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-forest/10 dark:bg-forest/20">
                <x-lucide-shield class="h-5 w-5 text-forest dark:text-green-400" />
            </div>
            <div>
                <h1 class="adm-page-title">Staff Management</h1>
                <p class="text-xs adm-text-muted">{{ $staff->total() }} team members</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <span class="adm-page-count">
                <x-lucide-shield class="h-3 w-3" />
                {{ $staff->total() }}
            </span>
            <button @click="openCreate()" class="adm-btn-primary">
                <x-lucide-plus class="h-3.5 w-3.5" />
                Add Staff
            </button>
        </div>
    </div>

    {{-- Table --}}
    <div class="adm-table-wrap">
        {{-- Desktop Table --}}
        <div class="hidden overflow-x-auto md:block">
            <table class="adm-table">
                <thead>
                    <tr>
                        <th>Staff</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($staff as $member)
                        @php
                            $avatarColors = ['bg-forest', 'bg-blue-500', 'bg-purple-500', 'bg-amber-500', 'bg-rose-500', 'bg-teal-500', 'bg-indigo-500'];
                            $colorIdx = crc32($member->email) % count($avatarColors);
                            $ini = strtoupper(substr($member->name, 0, 1));

                            $roleColors = [
                                'admin'    => ['bg' => 'bg-red-50 dark:bg-red-500/10',     'text' => 'text-red-600 dark:text-red-400'],
                                'manager'  => ['bg' => 'bg-purple-50 dark:bg-purple-500/10','text' => 'text-purple-600 dark:text-purple-400'],
                                'staff'    => ['bg' => 'bg-blue-50 dark:bg-blue-500/10',   'text' => 'text-blue-600 dark:text-blue-400'],
                                'operator' => ['bg' => 'bg-amber-50 dark:bg-amber-500/10',  'text' => 'text-amber-600 dark:text-amber-400'],
                            ];
                        @endphp
                        <tr class="group">
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full {{ $avatarColors[$colorIdx] }} text-xs font-bold text-white shadow-sm">
                                        {{ $ini }}
                                    </div>
                                    <span class="text-sm font-semibold text-charcoal dark:text-white">{{ $member->name }}</span>
                                </div>
                            </td>
                            <td>{{ $member->email }}</td>
                            <td>{{ $member->phone ?? '—' }}</td>
                            <td>
                                @foreach($member->roles as $role)
                                    @php $rc = $roleColors[$role->name] ?? ['bg' => 'bg-gray-100 dark:bg-gray-600/30', 'text' => 'text-gray-600 dark:text-gray-400']; @endphp
                                    <span class="mr-1 inline-flex items-center rounded-full {{ $rc['bg'] }} px-2.5 py-0.5 text-xs font-semibold {{ $rc['text'] }}">{{ $role->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                <x-admin.status-badge :status="$member->is_active ? 'active' : 'inactive'" />
                            </td>
                            <td class="adm-text-muted">{{ $member->created_at->format('M d, Y') }}</td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button @click="openEdit({{ $member->id }}, '{{ addslashes($member->name) }}', '{{ addslashes($member->email) }}', '{{ addslashes($member->phone ?? '') }}', {{ $member->roles->pluck('id')->first() ?? 'null' }})"
                                        class="adm-action-link">
                                        <x-lucide-pencil class="h-3.5 w-3.5" />
                                        Edit
                                    </button>
                                    <form method="POST" action="{{ route('admin.staff.toggle', $member) }}" class="inline">
                                        @csrf
                                        <button type="submit"
                                            class="{{ $member->is_active ? 'adm-btn-danger' : 'adm-action-link' }}">
                                            @if($member->is_active)
                                                <x-lucide-user-x class="h-3.5 w-3.5" />
                                                Deactivate
                                            @else
                                                <x-lucide-user-check class="h-3.5 w-3.5" />
                                                Activate
                                            @endif
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <x-admin.empty-state
                                    icon="shield"
                                    title="No staff members"
                                    description="Add your first team member to get started." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Cards --}}
        <div class="divide-y divide-sage/10 md:hidden dark:divide-gray-700/50">
            @forelse($staff as $member)
                @php
                    $avatarColors = ['bg-forest', 'bg-blue-500', 'bg-purple-500', 'bg-amber-500', 'bg-rose-500', 'bg-teal-500', 'bg-indigo-500'];
                    $colorIdx = crc32($member->email) % count($avatarColors);
                    $ini = strtoupper(substr($member->name, 0, 1));

                    $roleColors = [
                        'admin'    => ['bg' => 'bg-red-50 dark:bg-red-500/10',     'text' => 'text-red-600 dark:text-red-400'],
                        'manager'  => ['bg' => 'bg-purple-50 dark:bg-purple-500/10','text' => 'text-purple-600 dark:text-purple-400'],
                        'staff'    => ['bg' => 'bg-blue-50 dark:bg-blue-500/10',   'text' => 'text-blue-600 dark:text-blue-400'],
                        'operator' => ['bg' => 'bg-amber-50 dark:bg-amber-500/10',  'text' => 'text-amber-600 dark:text-amber-400'],
                    ];
                @endphp
                <div class="p-4 transition hover:bg-charcoal/[0.02] dark:hover:bg-gray-700/30">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full {{ $avatarColors[$colorIdx] }} text-sm font-bold text-white shadow-sm">
                                {{ $ini }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-charcoal dark:text-white">{{ $member->name }}</p>
                                <p class="text-xs adm-text-muted">{{ $member->email }}</p>
                            </div>
                        </div>
                        <x-admin.status-badge :status="$member->is_active ? 'active' : 'inactive'" />
                    </div>
                    <div class="mt-2 flex flex-wrap items-center gap-2">
                        @foreach($member->roles as $role)
                            @php $rc = $roleColors[$role->name] ?? ['bg' => 'bg-gray-100 dark:bg-gray-600/30', 'text' => 'text-gray-600 dark:text-gray-400']; @endphp
                            <span class="inline-flex items-center rounded-full {{ $rc['bg'] }} px-2 py-0.5 text-[10px] font-bold {{ $rc['text'] }}">{{ $role->name }}</span>
                        @endforeach
                    </div>
                    <div class="mt-2.5 flex items-center gap-4 text-[11px] adm-text-muted">
                        @if($member->phone)
                            <span class="inline-flex items-center gap-1"><x-lucide-phone class="h-3 w-3" />{{ $member->phone }}</span>
                        @endif
                        <span class="inline-flex items-center gap-1"><x-lucide-calendar class="h-3 w-3" />{{ $member->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="mt-3 flex items-center gap-2">
                        <button @click="openEdit({{ $member->id }}, '{{ addslashes($member->name) }}', '{{ addslashes($member->email) }}', '{{ addslashes($member->phone ?? '') }}', {{ $member->roles->pluck('id')->first() ?? 'null' }})"
                            class="adm-action-link">
                            <x-lucide-pencil class="h-3.5 w-3.5" />
                            Edit
                        </button>
                        <form method="POST" action="{{ route('admin.staff.toggle', $member) }}" class="inline">
                            @csrf
                            <button type="submit"
                                class="{{ $member->is_active ? 'adm-btn-danger' : 'adm-action-link' }}">
                                @if($member->is_active)
                                    <x-lucide-user-x class="h-3.5 w-3.5" /> Deactivate
                                @else
                                    <x-lucide-user-check class="h-3.5 w-3.5" /> Activate
                                @endif
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <x-admin.empty-state
                    icon="shield"
                    title="No staff members"
                    description="Add your first team member to get started." />
            @endforelse
        </div>
    </div>

    {{-- Pagination --}}
    @if($staff->hasPages())
        <div class="flex justify-center">
            {{ $staff->withQueryString()->links() }}
        </div>
    @endif

    {{-- Create Modal --}}
    <x-admin.modal id="staff-create" title="Add Staff Member" size="md">
        <form method="POST" action="{{ route('admin.staff.store') }}" class="space-y-4">
            @csrf
            <div>
                <label for="create-name" class="adm-label">Full Name</label>
                <input type="text" id="create-name" name="name" required class="adm-input" />
            </div>
            <div class="adm-grid-2 gap-4">
                <div>
                    <label for="create-email" class="adm-label">Email</label>
                    <input type="email" id="create-email" name="email" required class="adm-input" />
                </div>
                <div>
                    <label for="create-phone" class="adm-label">Phone</label>
                    <input type="text" id="create-phone" name="phone" required class="adm-input" />
                </div>
            </div>
            <div>
                <label for="create-role" class="adm-label">Role</label>
                <select id="create-role" name="role" required class="adm-input">
                    <option value="">Select role</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="adm-grid-2 gap-4">
                <div>
                    <label for="create-password" class="adm-label">Password</label>
                    <input type="password" id="create-password" name="password" required class="adm-input" />
                </div>
                <div>
                    <label for="create-password-confirm" class="adm-label">Confirm Password</label>
                    <input type="password" id="create-password-confirm" name="password_confirmation" required class="adm-input" />
                </div>
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" id="create-active" name="is_active" value="1" checked
                    class="h-4 w-4 rounded border-sage/30 text-forest focus:ring-forest dark:border-gray-600 dark:bg-gray-700 dark:text-green-500" />
                <label for="create-active" class="adm-label mb-0">Active on creation</label>
            </div>

            <div class="adm-divider"></div>
            <div class="flex items-center justify-end gap-3">
                <button type="button" @click="$dispatch('close-staff-create')" class="adm-btn-outline">
                    Cancel
                </button>
                <button type="submit" class="adm-btn-primary">
                    <x-lucide-plus class="h-3.5 w-3.5" />
                    Create Staff
                </button>
            </div>
        </form>
    </x-admin.modal>

    {{-- Edit Modal --}}
    <x-admin.modal id="staff-edit" title="Edit Staff Member" size="md">
        <form id="editStaffForm" method="POST" class="space-y-4">
            @csrf
            @method('PATCH')
            <div>
                <label for="edit-name" class="adm-label">Full Name</label>
                <input type="text" id="edit-name" name="name" required class="adm-input" />
            </div>
            <div class="adm-grid-2 gap-4">
                <div>
                    <label for="edit-email" class="adm-label">Email</label>
                    <input type="email" id="edit-email" name="email" required class="adm-input" />
                </div>
                <div>
                    <label for="edit-phone" class="adm-label">Phone</label>
                    <input type="text" id="edit-phone" name="phone" class="adm-input" />
                </div>
            </div>
            <div>
                <label for="edit-role" class="adm-label">Role</label>
                <select id="edit-role" name="role" required class="adm-input">
                    <option value="">Select role</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="edit-password" class="adm-label">Password <span class="adm-text-muted">(leave blank to keep current)</span></label>
                <input type="password" id="edit-password" name="password" class="adm-input" />
            </div>

            <div class="adm-divider"></div>
            <div class="flex items-center justify-end gap-3">
                <button type="button" @click="$dispatch('close-staff-edit')" class="adm-btn-outline">
                    Cancel
                </button>
                <button type="submit" class="adm-btn-primary">
                    <x-lucide-check class="h-3.5 w-3.5" />
                    Update Staff
                </button>
            </div>
        </form>
    </x-admin.modal>
</div>
@endsection

@push('scripts')
<script>
function staffModal() {
    return {
        openCreate() {
            this.$dispatch('open-staff-create');
        },
        openEdit(id, name, email, phone, roleId) {
            document.getElementById('editStaffForm').action = '{{ url("admin/staff") }}/' + id;
            document.getElementById('edit-name').value = name;
            document.getElementById('edit-email').value = email;
            document.getElementById('edit-phone').value = phone || '';
            document.getElementById('edit-role').value = roleId || '';
            this.$dispatch('open-staff-edit');
        }
    };
}
</script>
@endpush
