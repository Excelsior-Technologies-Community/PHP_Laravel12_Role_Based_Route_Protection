<x-app-layout>

    <x-slot name="header">

        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Customer Dashboard
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Your customer-only protected area
            </p>
        </div>

    </x-slot>

    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Welcome Card --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">

                <div class="p-8">

                    <div class="flex items-center gap-4">

                        <div class="text-5xl">
                            👋
                        </div>

                        <div>

                            <h3 class="text-2xl font-bold text-gray-900">
                                Welcome, {{ $user->name }}!
                            </h3>

                            <p class="text-gray-600 mt-1">
                                You have successfully accessed the customer-only dashboard.
                            </p>

                        </div>

                    </div>

                </div>

            </div>

            {{-- Role Information --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                    <div class="p-6">

                        <h3 class="text-lg font-semibold text-gray-900">
                            Account Information
                        </h3>

                        <div class="mt-5 space-y-4">

                            <div>

                                <p class="text-sm text-gray-500">
                                    Name
                                </p>

                                <p class="font-medium text-gray-900">
                                    {{ $user->name }}
                                </p>

                            </div>

                            <div>

                                <p class="text-sm text-gray-500">
                                    Email
                                </p>

                                <p class="font-medium text-gray-900">
                                    {{ $user->email }}
                                </p>

                            </div>

                            <div>

                                <p class="text-sm text-gray-500">
                                    Role
                                </p>

                                <span class="mt-1 inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ ucfirst($user->role) }}
                                </span>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                    <div class="p-6">

                        <h3 class="text-lg font-semibold text-gray-900">
                            Access Information
                        </h3>

                        <div class="mt-5">

                            <div class="p-4 rounded-lg bg-blue-50 border border-blue-100">

                                <p class="text-sm text-blue-800">
                                    🛡️ Your account is protected by
                                    <strong>Role-Based Route Protection</strong>.
                                </p>

                                <p class="text-sm text-blue-700 mt-2">
                                    Only users with the customer role can access
                                    this dashboard.
                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>