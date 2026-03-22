<?php

return [
    'enable' => [
        'header' => 'No has habilitado la autenticación de dos factores.',
        'description' => 'Cuando la autenticación de dos factores está habilitada, se te solicitará un token seguro y aleatorio durante la autenticación. Puedes obtener este token desde la aplicación Google Authenticator de tu teléfono.',
    ],
    'logout' => [
        'button' => 'Cerrar sesión',
    ],
    'enabled' => [
        'header' => 'Has habilitado la autenticación de dos factores.',
        'description' => 'Guarda estos códigos de recuperación en un gestor de contraseñas seguro. Pueden ser utilizados para recuperar el acceso a tu cuenta si pierdes tu dispositivo de autenticación de dos factores.',
    ],
    'setup_confirmation' => [
        'header' => 'Terminar de habilitar la autenticación de dos factores.',
        'description' => 'Cuando la autenticación de dos factores está habilitada, se te solicitará un token seguro y aleatorio durante la autenticación. Puedes obtener este token desde la aplicación Google Authenticator de tu teléfono.',
        'scan_qr_code' => 'Para terminar de habilitar la autenticación de dos factores, escanea el siguiente código QR usando la aplicación de autenticación de tu teléfono o introduce la clave de configuración y proporciona el código OTP generado.',
    ],
    'base' => [
        'wrong_user' => 'El objeto de usuario autenticado debe ser un modelo de autenticación de Filament para permitir que la página de perfil lo actualice.',
        'rate_limit_exceeded' => 'Demasiadas solicitudes',
        'try_again' => 'Por favor, inténtalo de nuevo en :seconds segundos',
    ],
    '2fa' => [
        'confirm' => 'Confirmar',
        'cancel' => 'Cancelar',
        'enable' => 'Habilitar',
        'disable' => 'Deshabilitar',
        'confirm_password' => 'Confirmar contraseña',
        'wrong_password' => 'La contraseña proporcionada es incorrecta.',
        'code' => 'Código',
        'setup_key' => 'Clave de configuración: :setup_key.',
        'current_password' => 'Contraseña actual',
        'regenerate_recovery_codes' => 'Generar nuevos códigos de recuperación',
    ],
];
