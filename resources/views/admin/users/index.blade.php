<x-app-layout>

    <x-slot name="header">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

            <div>

                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    User & Role Management
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Manage users, roles, account status and bulk actions
                </p>

            </div>

            <div class="flex gap-2">

                <a
                    href="{{ route('admin.dashboard') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 text-white rounded-md text-xs font-semibold uppercase tracking-widest hover:bg-gray-700"
                >
                    Dashboard
                </a>

                <a
                    href="{{ route('admin.users.export', request()->query()) }}"
                    class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md text-xs font-semibold uppercase tracking-widest hover:bg-green-700"
                >
                    Export CSV
                </a>

            </div>

        </div>

    </x-slot>

    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Success --}}

            @if(session('success'))

                <div class="mb-6 rounded-md bg-green-50 border border-green-200 p-4">

                    <p class="text-sm text-green-700">
                        {{ session('success') }}
                    </p>

                </div>

            @endif

            {{-- Error --}}

            @if(session('error'))

                <div class="mb-6 rounded-md bg-red-50 border border-red-200 p-4">

                    <p class="text-sm text-red-700">
                        {{ session('error') }}
                    </p>

                </div>

            @endif

            {{-- Validation Errors --}}

            @if($errors->any())

                <div class="mb-6 rounded-md bg-red-50 border border-red-200 p-4">

                    <ul class="list-disc list-inside text-sm text-red-700">

                        @foreach($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            @endif


            {{-- Search / Filters --}}

            <div class="bg-white shadow-sm sm:rounded-lg mb-6">

                <div class="p-6">

                    <form
                        method="GET"
                        action="{{ route('admin.users') }}"
                        class="grid grid-cols-1 md:grid-cols-5 gap-4"
                    >

                        <div class="md:col-span-2">

                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Search
                            </label>

                            <input
                                type="text"
                                name="search"
                                value="{{ $search }}"
                                placeholder="Name or email..."
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >

                        </div>


                        <div>

                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Role
                            </label>

                            <select
                                name="role"
                                class="w-full border-gray-300 rounded-md shadow-sm"
                            >

                                <option value="">
                                    All Roles
                                </option>

                                <option
                                    value="admin"
                                    {{ $role === 'admin' ? 'selected' : '' }}
                                >
                                    Admin
                                </option>

                                <option
                                    value="customer"
                                    {{ $role === 'customer' ? 'selected' : '' }}
                                >
                                    Customer
                                </option>

                            </select>

                        </div>


                        <div>

                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Status
                            </label>

                            <select
                                name="status"
                                class="w-full border-gray-300 rounded-md shadow-sm"
                            >

                                <option value="">
                                    All Status
                                </option>

                                <option
                                    value="active"
                                    {{ $status === 'active' ? 'selected' : '' }}
                                >
                                    Active
                                </option>

                                <option
                                    value="inactive"
                                    {{ $status === 'inactive' ? 'selected' : '' }}
                                >
                                    Inactive
                                </option>

                            </select>

                        </div>


                        <div class="flex items-end gap-2">

                            <button
                                type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700"
                            >
                                Search
                            </button>

                            <a
                                href="{{ route('admin.users') }}"
                                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300"
                            >
                                Reset
                            </a>

                        </div>

                    </form>

                </div>

            </div>


            {{-- Bulk Actions --}}

            <div class="bg-white shadow-sm sm:rounded-lg mb-6">

                <div class="p-6">

                    <form
                        id="bulkForm"
                        method="POST"
                        action="{{ route('admin.users.bulk-activate') }}"
                    >

                        @csrf

                        <div class="flex flex-col md:flex-row md:items-center gap-3">

                            <span class="text-sm font-semibold text-gray-700">
                                Bulk Actions:
                            </span>

                            <button
                                type="button"
                                onclick="submitBulk('{{ route('admin.users.bulk-activate') }}')"
                                class="px-4 py-2 bg-green-600 text-white rounded-md text-sm hover:bg-green-700"
                            >
                                Activate Selected
                            </button>

                            <button
                                type="button"
                                onclick="submitBulk('{{ route('admin.users.bulk-deactivate') }}')"
                                class="px-4 py-2 bg-red-600 text-white rounded-md text-sm hover:bg-red-700"
                            >
                                Deactivate Selected
                            </button>

                            <select
                                id="bulkRole"
                                class="border-gray-300 rounded-md text-sm"
                            >

                                <option value="">
                                    Select Role
                                </option>

                                <option value="admin">
                                    Admin
                                </option>

                                <option value="customer">
                                    Customer
                                </option>

                            </select>

                            <button
                                type="button"
                                onclick="submitBulkRole()"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm hover:bg-indigo-700"
                            >
                                Apply Role
                            </button>

                        </div>

                    </form>

                </div>

            </div>


            {{-- Users Table --}}

            <div class="bg-white shadow-sm sm:rounded-lg">

                <div class="p-6">

                    <div class="flex items-center justify-between mb-5">

                        <div>

                            <h3 class="text-lg font-semibold text-gray-900">
                                All Users
                            </h3>

                            <p class="text-sm text-gray-500">
                                {{ $users->total() }} user(s) found
                            </p>

                        </div>

                    </div>


                    <div class="overflow-x-auto">

                        <table class="min-w-full divide-y divide-gray-200">

                            <thead class="bg-gray-50">

                                <tr>

                                    <th class="px-4 py-3">

                                        <input
                                            type="checkbox"
                                            id="selectAll"
                                            class="rounded border-gray-300"
                                        >

                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        #
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        User
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Email
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Role
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Status
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Role
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Account
                                    </th>

                                </tr>

                            </thead>


                            <tbody class="bg-white divide-y divide-gray-200">

                                @forelse($users as $user)

                                    <tr class="hover:bg-gray-50">

                                        {{-- Checkbox --}}

                                        <td class="px-4 py-4">

                                            @if($user->id !== auth()->id())

                                                <input
                                                    type="checkbox"
                                                    name="user_ids[]"
                                                    value="{{ $user->id }}"
                                                    form="bulkForm"
                                                    class="user-checkbox rounded border-gray-300"
                                                >

                                            @endif

                                        </td>


                                        {{-- ID --}}

                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            {{ $user->id }}
                                        </td>


                                        {{-- User --}}

                                        <td class="px-6 py-4 whitespace-nowrap">

                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $user->name }}
                                            </div>

                                            @if($user->id === auth()->id())

                                                <span class="text-xs text-indigo-600">
                                                    Current User
                                                </span>

                                            @endif

                                        </td>


                                        {{-- Email --}}

                                        <td class="px-6 py-4 whitespace-nowrap">

                                            <div class="text-sm text-gray-600">
                                                {{ $user->email }}
                                            </div>

                                        </td>


                                        {{-- Current Role --}}

                                        <td class="px-6 py-4 whitespace-nowrap">

                                            @if($user->role === 'admin')

                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                                    Admin
                                                </span>

                                            @else

                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    Customer
                                                </span>

                                            @endif

                                        </td>


                                        {{-- Status --}}

                                        <td class="px-6 py-4 whitespace-nowrap">

                                            @if($user->is_active)

                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                    Active
                                                </span>

                                            @else

                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                    Inactive
                                                </span>

                                            @endif

                                        </td>


                                        {{-- Change Role --}}

                                        <td class="px-6 py-4 whitespace-nowrap">

                                            @if($user->id === auth()->id())

                                                <span class="text-sm text-gray-500">
                                                    Protected
                                                </span>

                                            @else

                                                <form
                                                    method="POST"
                                                    action="{{ route('admin.users.update-role', $user) }}"
                                                    class="flex items-center gap-2"
                                                >

                                                    @csrf
                                                    @method('PATCH')

                                                    <select
                                                        name="role"
                                                        class="border-gray-300 rounded-md shadow-sm text-sm"
                                                    >

                                                        <option
                                                            value="admin"
                                                            {{ $user->role === 'admin' ? 'selected' : '' }}
                                                        >
                                                            Admin
                                                        </option>

                                                        <option
                                                            value="customer"
                                                            {{ $user->role === 'customer' ? 'selected' : '' }}
                                                        >
                                                            Customer
                                                        </option>

                                                    </select>

                                                    <button
                                                        type="submit"
                                                        class="px-3 py-2 bg-indigo-600 text-white text-xs rounded-md hover:bg-indigo-700"
                                                    >
                                                        Update
                                                    </button>

                                                </form>

                                            @endif

                                        </td>


                                        {{-- Account Status --}}

                                        <td class="px-6 py-4 whitespace-nowrap">

                                            @if($user->id === auth()->id())

                                                <span class="text-sm text-gray-500">
                                                    Protected
                                                </span>

                                            @elseif($user->is_active)

                                                <form
                                                    method="POST"
                                                    action="{{ route('admin.users.deactivate', $user) }}"
                                                    onsubmit="return confirm('Deactivate this user?');"
                                                >

                                                    @csrf
                                                    @method('PATCH')

                                                    <button
                                                        type="submit"
                                                        class="px-3 py-2 bg-red-600 text-white text-xs rounded-md hover:bg-red-700"
                                                    >
                                                        Deactivate
                                                    </button>

                                                </form>

                                            @else

                                                <form
                                                    method="POST"
                                                    action="{{ route('admin.users.activate', $user) }}"
                                                >

                                                    @csrf
                                                    @method('PATCH')

                                                    <button
                                                        type="submit"
                                                        class="px-3 py-2 bg-green-600 text-white text-xs rounded-md hover:bg-green-700"
                                                    >
                                                        Activate
                                                    </button>

                                                </form>

                                            @endif

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td
                                            colspan="8"
                                            class="px-6 py-10 text-center text-gray-500"
                                        >
                                            No users found.
                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>


                    {{-- Pagination --}}

                    @if($users->hasPages())

                        <div class="mt-6">

                            {{ $users->links() }}

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>


    <script>

        document
            .getElementById('selectAll')
            .addEventListener('change', function () {

                document
                    .querySelectorAll('.user-checkbox')
                    .forEach(function (checkbox) {

                        checkbox.checked = this.checked;

                    }, this);

            });


        function selectedUsers() {

            return Array
                .from(
                    document.querySelectorAll('.user-checkbox:checked')
                )
                .map(function (checkbox) {
                    return checkbox.value;
                });

        }


        function submitBulk(action) {

            const users = selectedUsers();

            if (users.length === 0) {

                alert('Please select at least one user.');

                return;

            }

            if (
                action.includes('bulk-deactivate') &&
                !confirm('Deactivate selected users?')
            ) {
                return;
            }

            const form = document.getElementById('bulkForm');

            form.action = action;

            form.submit();

        }


        function submitBulkRole() {

            const users = selectedUsers();

            const role =
                document.getElementById('bulkRole').value;

            if (users.length === 0) {

                alert('Please select at least one user.');

                return;

            }

            if (!role) {

                alert('Please select a role.');

                return;

            }

            if (
                !confirm(
                    'Change selected users to ' +
                    role +
                    '?'
                )
            ) {
                return;
            }

            const form =
                document.getElementById('bulkForm');

            form.action =
                "{{ route('admin.users.bulk-role') }}";

            let roleInput =
                document.getElementById('bulk-role-input');

            if (!roleInput) {

                roleInput =
                    document.createElement('input');

                roleInput.type = 'hidden';

                roleInput.name = 'role';

                roleInput.id = 'bulk-role-input';

                form.appendChild(roleInput);

            }

            roleInput.value = role;

            form.submit();

        }

    </script>

</x-app-layout>