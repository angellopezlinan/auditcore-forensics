<?php

namespace App\Filament\Pages;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Facades\DB;

class Setup2FA extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    protected static string $view = 'filament.pages.setup2fa';

    protected static ?string $title = 'Configuración de Seguridad (2FA)';
    
    protected static ?string $navigationLabel = 'Seguridad 2FA';

    protected static bool $shouldRegisterNavigation = false;

    public ?string $code = '';

    public function mount()
    {
        $user = auth()->user();
        $google2fa = new Google2FA();

        $secretFromDb = DB::table('users')->where('id', $user->id)->value('two_factor_secret');
        $rawSecret = null;

        if (empty($secretFromDb)) {
            // Si está vacío (porque se ha reseteado), generamos uno nuevo y lo guardamos encriptado
            $rawSecret = $google2fa->generateSecretKey();
            DB::table('users')->where('id', $user->id)->update([
                'two_factor_secret' => encrypt($rawSecret)
            ]);
        } else {
            // Si existe, intentamos desencriptarlo
            try {
                $rawSecret = decrypt($secretFromDb);
            } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                // Si explota, es porque era texto plano viejo. Lo asumimos como válido.
                $rawSecret = $secretFromDb;
                // Auto-reparación: lo encriptamos para la próxima vez
                DB::table('users')->where('id', $user->id)->update([
                    'two_factor_secret' => encrypt($rawSecret)
                ]);
            }
        }
    }

    public function getQrCodeSvg()
    {
        $user = auth()->user();
        $google2fa = new Google2FA();

        $secretFromDb = DB::table('users')->where('id', $user->id)->value('two_factor_secret');
        $rawSecret = null;

        try {
            $rawSecret = decrypt($secretFromDb);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            $rawSecret = $secretFromDb;
            // Auto-reparación al generar QR
            DB::table('users')->where('id', $user->id)->update([
                'two_factor_secret' => encrypt($rawSecret)
            ]);
        }

        $qrCodeUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $rawSecret
        );

        // Generamos el SVG usando los mismos parámetros que el trait del paquete por consistencia
        $renderer = new \BaconQrCode\Renderer\ImageRenderer(
            new \BaconQrCode\Renderer\RendererStyle\RendererStyle(192, 0, null, null, \BaconQrCode\Renderer\RendererStyle\Fill::uniformColor(new \BaconQrCode\Renderer\Color\Rgb(255, 255, 255), new \BaconQrCode\Renderer\Color\Rgb(45, 55, 72))),
            new \BaconQrCode\Renderer\Image\SvgImageBackEnd
        );
        $writer = new \BaconQrCode\Writer($renderer);
        $svg = $writer->writeString($qrCodeUrl);

        return trim(substr($svg, strpos($svg, "\n") + 1));
    }

    public function verify()
    {
        $user = auth()->user();
        $google2fa = new Google2FA();

        $secretFromDb = DB::table('users')->where('id', $user->id)->value('two_factor_secret');
        $rawSecret = null;

        try {
            $rawSecret = decrypt($secretFromDb);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            // Si explota en el verify, es porque era texto plano viejo. 
            // Lo usamos tal cual y lo re-encriptamos para la posteridad.
            $rawSecret = $secretFromDb;
            DB::table('users')->where('id', $user->id)->update([
                'two_factor_secret' => encrypt($rawSecret)
            ]);
        }

        $valid = $google2fa->verifyKey($rawSecret, $this->code);

        if ($valid) {
            DB::table('users')->where('id', $user->id)->update([
                'two_factor_confirmed_at' => now(),
            ]);

            session()->put('two_factor_authenticated', true);

            Notification::make()
                ->title('2FA Configurado con éxito')
                ->success()
                ->send();

            return redirect()->to('/admin');
        }

        Notification::make()
            ->title('Código inválido')
            ->body('Por favor, verifique el código e inténtelo de nuevo.')
            ->danger()
            ->send();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('code')
                    ->label('Código de Verificación')
                    ->placeholder('000000')
                    ->required()
                    ->maxLength(6),
            ]);
    }
}
