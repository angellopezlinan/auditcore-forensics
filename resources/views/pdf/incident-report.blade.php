@extends('pdf.layouts.master')

@section('title', 'Informe Oficial de Incidente #' . $incident->id)
@section('document_title', 'INFORME DE INCIDENTE')
@section('document_subtitle', 'SGS / Trazabilidad de Seguridad Operacional')

@section('content')

@php
    $categoryTypes = [
        'accident' => 'Accidente',
        'incident' => 'Incidente de Seguridad',
        'near_miss' => 'Near-Miss / Susto',
        'hazard' => 'Peligro Detectado'
    ];

    $severityTypes = [
        'low' => 'Baja',
        'medium' => 'Media',
        'high' => 'Alta',
        'critical' => 'Crítica'
    ];

    $statusBadges = [
        'open' => '<span class="badge badge-danger">ABIERTO</span>',
        'investigating' => '<span class="badge badge-warning">EN INVESTIGACIÓN</span>',
        'closed' => '<span class="badge badge-success">CERRADO</span>',
    ];

    $translatedCategory = $categoryTypes[$incident->category] ?? 'Desconocida';
    $translatedSeverity = $severityTypes[$incident->severity] ?? 'Desconocida';
    $incidentStatusBadge = $statusBadges[$incident->status] ?? '<span class="badge">DESCONOCIDO</span>';
@endphp

<!-- Marca de Agua Dinámica (Aviso SMS) -->
<div style="position: absolute; top: 15%; left: 0; width: 100%; text-align: center; opacity: 0.04; z-index: -1;">
    <h1 style="font-size: 150px; transform: rotate(-45deg);">SMS - AESA</h1>
</div>

<!-- Bloque 1: Clasificación del Evento -->
<h3>Clasificación del Evento ({{ $translatedCategory }})</h3>
<table>
    <tr>
        <th style="width: 25%;">ID de Expediente:</th>
        <td style="width: 25%;"><strong>#{{ sprintf('%06d', $incident->id) }}</strong></td>
        <th style="width: 25%;">Estado:</th>
        <td style="width: 25%;">{!! $incidentStatusBadge !!}</td>
    </tr>
    <tr>
        <th>Fecha del Evento:</th>
        <td>{{ \Carbon\Carbon::parse($incident->date)->format('d/m/Y') }}</td>
        <th>Gravedad:</th>
        <td><strong>{{ strtoupper($translatedSeverity) }}</strong></td>
    </tr>
    <tr>
        <th>Título / Resumen:</th>
        <td colspan="3">{{ $incident->title }}</td>
    </tr>
</table>

<!-- Bloque 2: Actores y Sistemas Involucrados -->
<h3>Actores y Trazabilidad</h3>
<table>
    <tr>
        <th style="width: 30%;">Personal Reportante:</th>
        <td style="width: 70%;">{{ $incident->user ? $incident->user->name : 'N/D' }} (ID: {{ $incident->user_id }})</td>
    </tr>
    @if($incident->drone)
    <tr>
        <th>Aeronave Afectada (UAS):</th>
        <td>{{ $incident->drone->brand }} {{ $incident->drone->model }} (S/N: {{ $incident->drone->serial_number }}) - Matrícula: {{ $incident->drone->registration_mark ?? 'N/A' }}</td>
    </tr>
    @else
    <tr>
        <th>Aeronave Afectada (UAS):</th>
        <td style="color: #6b7280; font-style: italic;">Sin aeronave vinculada al suceso.</td>
    </tr>
    @endif
    @if($incident->flight)
    <tr>
        <th>Operación Vinculada:</th>
        <td>Vuelo #{{ $incident->flight->id }} (Misión Ejecutada el {{ \Carbon\Carbon::parse($incident->flight->start_time)->format('d/m/Y') }})</td>
    </tr>
    @endif
</table>

<!-- Bloque 3: Narrativa y Análisis -->
<h3>Descripción Exhaustiva de los Hechos</h3>
<div style="border: 1px solid #d1d5db; padding: 15px; background: #f9fafb; font-size: 11px; margin-bottom: 20px;">
    {!! nl2br(e($incident->description)) !!}
</div>

@if($incident->root_cause)
<h3>Análisis de Causa Raíz</h3>
<div style="border: 1px solid #fca5a5; padding: 15px; background: #fef2f2; font-size: 11px; margin-bottom: 20px; color: #7f1d1d;">
    {!! nl2br(e($incident->root_cause)) !!}
</div>
@endif

@if($incident->mitigation_actions)
<h3>Acciones Mitigadoras y Correctoras</h3>
<div style="border: 1px solid #86efac; padding: 15px; background: #f0fdf4; font-size: 11px; margin-bottom: 20px; color: #14532d;">
    {!! nl2br(e($incident->mitigation_actions)) !!}
</div>
@endif

<!-- Zona de Firmado Oficial Institucional -->
<div style="margin-top: 50px; width: 100%; page-break-inside: avoid;">
    <table style="width: 100%; border: none;">
        <tr>
            <td style="width: 50%; border: none; text-align: center; vertical-align: bottom; height: 100px;">
                <div style="border-top: 1px solid #333; width: 80%; margin: 0 auto; padding-top: 5px;">
                    <strong>Departamento de Seguridad (SMS)</strong><br>
                    <span style="font-size: 9px; color: #666;">Fdo: ________________________________</span>
                </div>
            </td>
            <td style="width: 50%; border: none; text-align: center; vertical-align: bottom; height: 100px;">
                <div style="width: 80%; margin: 0 auto; color: #cbd5e1; border: 2px dashed #e2e8f0; height: 80px; line-height: 80px; text-transform: uppercase;">
                    <strong>Sello Oficial Entidad</strong>
                </div>
            </td>
        </tr>
    </table>
</div>

@endsection
