<div>
    <x-filament::section :aside="$aside">
        <x-slot name="heading">
            {{__('filament-two-factor-authentication::section.header')}}
        </x-slot>

        <x-slot name="description">
            {{__('filament-two-factor-authentication::section.description')}}
        </x-slot>

        <div class="">
            @if($this->isConfirmingSetup)
                <x-filament-two-factor-authentication::setup-confirmation />
            @elseif($this->enableTwoFactorAuthentication->isVisible())
                <x-filament-two-factor-authentication::enable />
            @elseif($this->disableTwoFactorAuthentication->isVisible())
                <x-filament-two-factor-authentication::enabled />

                {{-- HARDENING ENS: Recovery codes y botón Regenerar eliminados por política de seguridad --}}
                {{-- HARDENING ENS: Botón "Deshabilitar 2FA" eliminado por política de seguridad obligatoria --}}
                <p class="mt-4 text-sm text-gray-500 dark:text-gray-400 italic">
                    🔒 La autenticación de dos factores es obligatoria por normativa ENS y no puede ser desactivada. Si pierde su dispositivo, contacte con el Administrador.
                </p>
            @endif
        </div>
    </x-filament::section>

    <x-filament-actions::modals />

    @if(str(url()->current())->contains('two-factor-setup'))
        @if(!filament('filament-two-factor-authentication')->hasEnforcedTwoFactorSetup() || filament()->auth()->user()?->hasEnabledTwoFactorAuthentication())
            <div class="my-4 text-center">
                <x-filament::link :href="filament()->getCurrentPanel()->getUrl(filament()->getTenant())"
                                  weight="semibold">
                    {{__('filament-two-factor-authentication::section.dashboard')}}
                </x-filament::link>
            </div>
        @endif

        @if($this->enableTwoFactorAuthentication->isVisible())
            <div class="my-4 text-center">
                <x-filament-two-factor-authentication::logout />
            </div>
        @endif
    @endif
</div>
