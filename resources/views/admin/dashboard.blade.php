<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Admin Dashboard
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Role-based administration and user management
                </p>
            </div>

            <a
                href="{{ route('admin.users') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700"
            >
                Manage Users
            </a>
        </div>
    </x-slot>

    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Welcome --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">

                    <h3 class="text-lg font-semibold text-gray-900">
                        Welcome, {{ auth()->user()->name }} 👋
                    </h3>

                    <p class="text-gray-600 mt-1">
                        You are logged in with
                        <span class="font-semibold text-gray-900">
                            {{ ucfirst(auth()->user()->role) }}
                        </span>
                        role.
                    </p>

                </div>
            </div>

            {{-- Statistics --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

                {{-- Total Users --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">

                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">
                                    Total Users
                                </p>

                                <p class="text-3xl font-bold text-gray-900 mt-2">
                                    {{ $totalUsers }}
                                </p>
                            </div>

                            <div class="text-3xl">
                                👥
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Admins --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">

                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">
                                    Administrators
                                </p>

                                <p class="text-3xl font-bold text-gray-900 mt-2">
                                    {{ $totalAdmins }}
                                </p>
                            </div>

                            <div class="text-3xl">
                                🛡️
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Customers --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">

                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">
                                    Customers
                                </p>

                                <p class="text-3xl font-bold text-gray-900 mt-2">
                                    {{ $totalCustomers }}
                                </p>
                            </div>

                            <div class="text-3xl">
                                🧑‍💼
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            {{-- Recent Users --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <div class="p-6">

                    <div class="flex items-center justify-between mb-5">

                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">
                                Recent Users
                            </h3>

                            <p class="text-sm text-gray-500">
                                Recently registered users
                            </p>
                        </div>

                        <a
                            href="{{ route('admin.users') }}"
                            class="text-sm font-medium text-indigo-600 hover:text-indigo-800"
                        >
                            View All
                        </a>

                    </div>

                    <div class="overflow-x-auto">

                        <table class="min-w-full divide-y divide-gray-200">

                            <thead class="bg-gray-50">

                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Name
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Email
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Role
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Registered
                                    </th>
                                </tr>

                            </thead>

                            <tbody class="bg-white divide-y divide-gray-200">

                                @forelse($recentUsers as $user)

                                    <tr>

                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $user->name }}
                                            </div>
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-600">
                                                {{ $user->email }}
                                            </div>
                                        </td>

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

                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $user->created_at->format('d M Y') }}
                                        </td>

                                    </tr>

                                @empty

                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                            No users found.
                                        </td>
                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>