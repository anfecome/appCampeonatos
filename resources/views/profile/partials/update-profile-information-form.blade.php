<section>
    <header class="mb-5">
        <div class="flex items-center gap-3 mb-1">
            <div class="w-8 h-8 bg-green-50 rounded-lg flex items-center justify-center text-base">👤</div>
            <h2 class="text-lg font-bold text-gray-800">Información del perfil</h2>
        </div>
        <p class="text-sm text-gray-500">Actualiza tu nombre y correo electrónico.</p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">@csrf</form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" value="Nombre completo" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" value="Correo electrónico" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2 p-3 bg-yellow-50 border border-yellow-100 rounded-lg">
                    <p class="text-sm text-yellow-700">
                        Tu correo no está verificado.
                        <button form="send-verification" class="underline font-medium hover:text-yellow-800 transition">
                            Reenviar verificación
                        </button>
                    </p>
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-1 text-sm text-green-600 font-medium">
                            Se envió un nuevo enlace a tu correo.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-1">
            <x-primary-button class="bg-green-600 hover:bg-green-700">Guardar cambios</x-primary-button>
            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition
                   x-init="setTimeout(() => show = false, 2000)"
                   class="text-sm text-green-600 font-medium">
                    ✓ Guardado
                </p>
            @endif
        </div>
    </form>
</section>