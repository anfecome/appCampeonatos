<section>
    <header class="mb-5">
        <div class="flex items-center gap-3 mb-1">
            <div class="w-8 h-8 bg-red-50 rounded-lg flex items-center justify-center text-base">⚠️</div>
            <h2 class="text-lg font-bold text-gray-800">Eliminar cuenta</h2>
        </div>
        <p class="text-sm text-gray-500">
            Una vez eliminada, todos los datos se borrarán permanentemente.
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">
        Eliminar mi cuenta
    </x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-bold text-gray-800">
                ¿Estás seguro de que quieres eliminar tu cuenta?
            </h2>
            <p class="mt-2 text-sm text-gray-500">
                Esta acción es irreversible. Ingresa tu contraseña para confirmar.
            </p>

            <div class="mt-5">
                <x-input-label for="password" value="Contraseña" class="sr-only" />
                <x-text-input id="password" name="password" type="password"
                    class="mt-1 block w-3/4"
                    placeholder="Tu contraseña actual" />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Cancelar
                </x-secondary-button>
                <x-danger-button>
                    Sí, eliminar cuenta
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>