<x-app-layout>

    <x-slot name="header">

        <div class="flex items-center justify-between">

            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    User & Role Management
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Manage user roles and access permissions
                </p>
            </div>

            <a
                href="{{ route('admin.dashboard') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700"
            >
                Admin Dashboard
            </a>

        </div>

    </x-slot>

    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Success Message --}}
            @if(session('success'))

                <div class="mb-6 rounded-md bg-green-50 border border-green-200 p-4">

                    <div class="flex">

                        <div class="text-green-700 text-sm">
                            {{ session('success') }}
                        </div>

                    </div>

                </div>

            @endif

            {{-- Error Message --}}
            @if(session('error'))

                <div class="mb-6 rounded-md bg-red-50 border border-red-200 p-4">

                    <div class="flex">

                        <div class="text-red-700 text-sm">
                            {{ session('error') }}
                        </div>

                    </div>

                </div>

            @endif

            {{-- Validation Errors --}}
            @if($errors->any())

                <div class="mb-6 rounded-md bg-red-50 border border-red-200 p-4">

                    <ul class="list-disc list-inside text-sm text-red-700">

                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach

                    </ul>

                </div>

            @endif

            {{-- Search & Filter --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">

                <div class="p-6">

                    <form
                        method="GET"
                        action="{{ route('admin.users') }}"
                        class="grid grid-cols-1 md:grid-cols-4 gap-4"
                    >

                        {{-- Search --}}
                        <div class="md:col-span-2">

                            <label
                                for="search"
                                class="block text-sm font-medium text-gray-700 mb-1"
                            >
                                Search Users
                            </label>

                            <input
                                type="text"
                                id="search"
                                name="search"
                                value="{{ $search }}"
                                placeholder="Search by name or email..."
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >

                        </div>

                        {{-- Role --}}
                        <div>

                            <label
                                for="role"
                                class="block text-sm font-medium text-gray-700 mb-1"
                            >
                                Filter by Role
                            </label>

                            <select
                                id="role"
                                name="role"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
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

                        {{-- Buttons --}}
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

            {{-- Users Table --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

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
                                        Current Role
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Change Role
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Registered
                                    </th>

                                </tr>

                            </thead>

                            <tbody class="bg-white divide-y divide-gray-200">

                                @forelse($users as $user)

                                    <tr class="hover:bg-gray-50">

                                        {{-- ID --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
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

                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                                    Admin
                                                </span>

                                            @else

                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    Customer
                                                </span>

                                            @endif

                                        </td>

                                        {{-- Change Role --}}
                                        <td class="px-6 py-4 whitespace-nowrap">

                                            @if($user->id === auth()->id())

                                                <span class="text-sm text-gray-500">
                                                    Own role protected
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
                                                        class="border-gray-300 rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500"
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
                                                        class="px-3 py-2 bg-indigo-600 text-white text-xs font-semibold rounded-md hover:bg-indigo-700"
                                                    >
                                                        Update
                                                    </button>

                                                </form>

                                            @endif

                                        </td>

                                        {{-- Date --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">

                                            {{ $user->created_at->format('d M Y') }}

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td
                                            colspan="6"
                                            class="px-6 py-10 text-center text-gray-500"
                                        >
                                            No users found matching your search/filter.
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

</x-app-layout>