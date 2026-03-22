<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Documento Oficial - AICOR UAS')</title>
    <style>
        @page {
            margin: 100px 50px 80px 50px;
        }
        
        body {
            font-family: 'Inter', 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #111827; /* Gris muy oscuro, casi negro */
            margin: 0;
            padding: 0;
            line-height: 1.5;
        }

        /* Cabecera Fija (Repetida en cada página) */
        header {
            position: fixed;
            top: -70px;
            left: 0px;
            right: 0px;
            height: 60px;
            border-bottom: 2px solid #00458a; /* Azul Corporativo AICOR */
            padding-bottom: 10px;
        }

        header .logo-container {
            float: left;
            width: 30%;
        }

        header .logo-container h1 {
            color: #1f2937;
            margin: 0;
            font-size: 22px;
            font-weight: 800;
        }

        header .title-container {
            float: right;
            width: 60%;
            text-align: right;
        }

        header .title-container h1 {
            color: #00458a;
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        header h2 {
            margin: 2px 0 0 0;
            font-size: 11px;
            color: #4b5563; /* Gris medio */
            font-weight: normal;
        }

        /* Pie de Página Fijo */
        footer {
            position: fixed;
            bottom: -50px;
            left: 0px;
            right: 0px;
            height: 30px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 9px;
            text-align: center;
            padding-top: 5px;
        }

        .page-number:after {
            content: "Página " counter(page);
        }

        /* Contenido Principal */
        main {
            margin-top: 10px;
        }
        
        /* Secciones y Títulos Internos */
        h3 {
            color: #00458a;
            border-bottom: 1px solid #d1d5db;
            padding-bottom: 4px;
            margin-top: 20px;
            margin-bottom: 10px;
            font-size: 14px;
            text-transform: uppercase;
        }

        /* Tablas Premium */
        table {
            width: 100% !important;
            border-collapse: collapse;
            margin-bottom: 15px;
            page-break-inside: avoid;
        }

        th, td {
            border: 1px solid #d1d5db; /* Borde gris sutil */
            padding: 10px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #f9fafb; /* Fondo cabecera gris ultra-suave */
            color: #374151; /* Texto oscuro */
            font-weight: bold;
            font-size: 11px;
            width: 25%;
        }

        td {
            font-size: 11px;
            color: #1f2937;
        }

        /* Tablas de Listado (Estilos alternativos para iteraciones largas) */
        table.list-table th {
            background-color: #00458a;
            color: #ffffff;
            text-align: center;
            border-color: #00458a;
        }

        table.list-table td {
            text-align: center;
            border-color: #d1d5db;
            padding: 6px;
        }

        table.list-table tr:nth-child(even) {
            background-color: #f9fafb; /* Filas zebradas */
        }

        /* Helpers Utilitarios */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .mt-2 { margin-top: 20px; }
        .mb-2 { margin-bottom: 20px; }
        .w-100 { width: 100%; }
        
        /* Etiquetas visuales (Badges en crudo) */
        .badge {
            display: inline-block;
            padding: 3px 6px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            color: white;
            background-color: #6b7280;
        }
        .badge-success { background-color: #10b981; }
        .badge-warning { background-color: #f59e0b; color: #fff; }
        .badge-danger { background-color: #ef4444; }

    </style>
</head>
<body>

    @php
        $logoPath = public_path('images/logo-aicor.webp');
        $logoBase64 = file_exists($logoPath) ? base64_encode(file_get_contents($logoPath)) : '';
        $logoSrc = 'data:image/webp;base64,' . $logoBase64;
    @endphp

    <!-- Cabecera Institucional Estándar -->
    <header>
        <div class="logo-container">
            @if($logoBase64)
                <img src="{{ $logoSrc }}" alt="AICOR UAS Logo" style="max-height: 45px;">
            @else
                <h1>AICOR UAS</h1>
            @endif
        </div>
        <div class="title-container">
            <h1>@yield('document_title', 'DOCUMENTO DE OPERACIONES')</h1>
            <h2>@yield('document_subtitle', 'Registro Oficial AESA')</h2>
        </div>
        <div style="clear: both;"></div>
    </header>

    <!-- Pie de Página Institucional -->
    <footer>
        Generado por AICOR UAS | <span class="page-number"></span>
    </footer>

    <!-- Contenedor Principal (Donde las vistas inyectarán su código) -->
    <main>
        @yield('content')
    </main>

</body>
</html>
