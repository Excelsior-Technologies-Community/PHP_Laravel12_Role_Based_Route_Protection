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
                class="inline-flex items-center px-4 py-2 bg-gray-800 text-white rounded-md text-xs font-semibold uppercase tracking-widest hover:bg-gray-700"
            >
                Manage Users
            </a>

        </div>

    </x-slot>


    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">


            {{-- Welcome --}}

            <div class="bg-white shadow-sm sm:rounded-lg mb-6">

                <div class="p-6">

                    <h3 class="text-lg font-semibold text-gray-900">

                        Welcome,
                        {{ auth()->user()->name }}
                        👋

                    </h3>

                    <p class="text-gray-600 mt-1">

                        You are logged in with
                        <span class="font-semibold">
                            {{ ucfirst(auth()->user()->role) }}
                        </span>
                        role.

                    </p>

                </div>

            </div>


            {{-- Statistics --}}

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">


                <div class="bg-white shadow-sm sm:rounded-lg">

                    <div class="p-6">

                        <p class="text-sm text-gray-500">
                            Total Users
                        </p>

                        <p class="text-3xl font-bold text-gray-900 mt-2">
                            {{ $totalUsers }}
                        </p>

                    </div>

                </div>


                <div class="bg-white shadow-sm sm:rounded-lg">

                    <div class="p-6">

                        <p class="text-sm text-gray-500">
                            Administrators
                        </p>

                        <p class="text-3xl font-bold text-purple-700 mt-2">
                            {{ $totalAdmins }}
                        </p>

                    </div>

                </div>


                <div class="bg-white shadow-sm sm:rounded-lg">

                    <div class="p-6">

                        <p class="text-sm text-gray-500">
                            Customers
                        </p>

                        <p class="text-3xl font-bold text-blue-700 mt-2">
                            {{ $totalCustomers }}
                        </p>

                    </div>

                </div>


                <div class="bg-white shadow-sm sm:rounded-lg">

                    <div class="p-6">

                        <p class="text-sm text-gray-500">
                            Active Users
                        </p>

                        <p class="text-3xl font-bold text-green-700 mt-2">
                            {{ $activeUsers }}
                        </p>

                    </div>

                </div>


                <div class="bg-white shadow-sm sm:rounded-lg">

                    <div class="p-6">

                        <p class="text-sm text-gray-500">
                            Inactive Users
                        </p>

                        <p class="text-3xl font-bold text-red-700 mt-2">
                            {{ $inactiveUsers }}
                        </p>

                    </div>

                </div>


                <div class="bg-white shadow-sm sm:rounded-lg">

                    <div class="p-6">

                        <p class="text-sm text-gray-500">
                            Audit Logs Today
                        </p>

                        <p class="text-3xl font-bold text-indigo-700 mt-2">
                            {{ $todayAuditLogs }}
                        </p>

                    </div>

                </div>

            </div>


            {{-- Recent Users --}}

            <div class="bg-white shadow-sm sm:rounded-lg mb-6">

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
                            class="text-sm text-indigo-600 hover:text-indigo-800"
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
                                        Status
                                    </th>

                                </tr>

                            </thead>


                            <tbody class="divide-y divide-gray-200">

                                @forelse($recentUsers as $user)

                                    <tr>

                                        <td class="px-6 py-4 text-sm font-medium">
                                            {{ $user->name }}
                                        </td>

                                        <td class="px-6 py-4 text-sm text-gray-600">
                                            {{ $user->email }}
                                        </td>

                                        <td class="px-6 py-4">

                                            {{ ucfirst($user->role) }}

                                        </td>

                                        <td class="px-6 py-4">

                                            @if($user->is_active)

                                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                                    Active
                                                </span>

                                            @else

                                                <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">
                                                    Inactive
                                                </span>

                                            @endif

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td
                                            colspan="4"
                                            class="px-6 py-6 text-center text-gray-500"
                                        >
                                            No users found.
                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>


            {{-- Audit Statistics --}}

            <div class="bg-white shadow-sm sm:rounded-lg mb-6">

                <div class="p-6">

                    <div class="flex items-center justify-between">

                        <div>

                            <h3 class="text-lg font-semibold text-gray-900">
                                Audit Statistics
                            </h3>

                            <p class="text-sm text-gray-500">
                                Track role and account status changes
                            </p>

                        </div>

                        <div class="text-2xl">
                            📝
                        </div>

                    </div>


                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-5">

                        <div class="p-4 bg-gray-50 rounded-lg">

                            <p class="text-sm text-gray-500">
                                Total Audit Logs
                            </p>

                            <p class="text-2xl font-bold">
                                {{ $totalAuditLogs }}
                            </p>

                        </div>


                        <div class="p-4 bg-gray-50 rounded-lg">

                            <p class="text-sm text-gray-500">
                                Today's Logs
                            </p>

                            <p class="text-2xl font-bold">
                                {{ $todayAuditLogs }}
                            </p>

                        </div>


                        <div class="p-4 bg-gray-50 rounded-lg">

                            <p class="text-sm text-gray-500">
                                Filtered Logs
                            </p>

                            <p class="text-2xl font-bold">
                                {{ $filteredAuditLogs }}
                            </p>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Audit Date Filter --}}

            <div class="bg-white shadow-sm sm:rounded-lg mb-6">

                <div class="p-6">

                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        Audit Date Statistics
                    </h3>

                    <form
                        method="GET"
                        action="{{ route('admin.dashboard') }}"
                        class="grid grid-cols-1 md:grid-cols-3 gap-4"
                    >

                        <div>

                            <label class="block text-sm text-gray-600 mb-1">
                                From Date
                            </label>

                            <input
                                type="date"
                                name="audit_from"
                                value="{{ $auditFrom }}"
                                class="w-full border-gray-300 rounded-md"
                            >

                        </div>


                        <div>

                            <label class="block text-sm text-gray-600 mb-1">
                                To Date
                            </label>

                            <input
                                type="date"
                                name="audit_to"
                                value="{{ $auditTo }}"
                                class="w-full border-gray-300 rounded-md"
                            >

                        </div>


                        <div class="flex items-end gap-2">

                            <button
                                type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md"
                            >
                                Filter
                            </button>

                            <a
                                href="{{ route('admin.dashboard') }}"
                                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md"
                            >
                                Reset
                            </a>

                        </div>

                    </form>

                </div>

            </div>


            {{-- Recent Audit Logs --}}

            <div class="bg-white shadow-sm sm:rounded-lg">

                <div class="p-6">

                    <h3 class="text-lg font-semibold text-gray-900 mb-5">
                        Recent Audit Activity
                    </h3>


                    <div class="overflow-x-auto">

                        <table class="min-w-full divide-y divide-gray-200">

                            <thead class="bg-gray-50">

                                <tr>

                                    <th class="px-6 py-3 text-left text-xs uppercase text-gray-500">
                                        User
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs uppercase text-gray-500">
                                        Action
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs uppercase text-gray-500">
                                        Description
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs uppercase text-gray-500">
                                        Date
                                    </th>

                                </tr>

                            </thead>


                            <tbody class="divide-y divide-gray-200">

                                @forelse($recentAuditLogs as $log)

                                    <tr>

                                        <td class="px-6 py-4 text-sm">

                                            {{ $log->user?->name ?? 'Deleted User' }}

                                        </td>

                                        <td class="px-6 py-4">

                                            <span class="px-2 py-1 text-xs rounded-full bg-indigo-100 text-indigo-800">

                                                {{ str_replace(
                                                    '_',
                                                    ' ',
                                                    ucfirst($log->action)
                                                ) }}

                                            </span>

                                        </td>

                                        <td class="px-6 py-4 text-sm text-gray-600">

                                            {{ $log->description }}

                                        </td>

                                        <td class="px-6 py-4 text-sm text-gray-500">

                                            {{ $log->created_at->format('d M Y H:i') }}

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td
                                            colspan="4"
                                            class="px-6 py-6 text-center text-gray-500"
                                        >
                                            No audit activity yet.
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