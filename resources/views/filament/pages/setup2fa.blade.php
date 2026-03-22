<x-filament-panels::page>
    <x-filament::section>
        <div class="flex flex-col items-center justify-center p-4 space-y-6">
            <div class="text-center">
                <h2 class="text-xl font-bold tracking-tight">Vincular Dispositivo de Seguridad</h2>
                <p class="mt-1 text-sm text-gray-500">Escanee el código QR con su aplicación de autenticación (Google Authenticator, Authy, etc.)</p>
            </div>

            <div class="p-4 bg-white rounded-lg shadow-sm border border-gray-100">
                {!! $this->getQrCodeSvg() !!}
            </div>

            <div class="w-full max-w-sm">
                <form wire:submit.prevent="verify" class="space-y-4">
                    {{ $this->form }}

                    <x-filament::button type="submit" class="w-full" size="lg">
                        Confirmar y Activar 2FA
                    </x-filament::button>
                </form>
            </div>

            <div class="text-xs text-gray-400 italic">
                🛡️ Protección ENS: Este es un canal seguro y obligatorio para todos los pilotos UAS.
            </div>
        </div>
    </x-filament::section>
</x-filament-panels::page>
