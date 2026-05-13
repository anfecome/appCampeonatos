<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Mi perfil
        </h2>
    </x-slot>

    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="p-6 bg-white shadow-sm rounded-2xl border border-gray-100">
                @include('profile.partials.update-profile-information-form')
            </div>
            <div class="p-6 bg-white shadow-sm rounded-2xl border border-gray-100">
                @include('profile.partials.update-password-form')
            </div>
            <div class="p-6 bg-white shadow-sm rounded-2xl border border-gray-100">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>