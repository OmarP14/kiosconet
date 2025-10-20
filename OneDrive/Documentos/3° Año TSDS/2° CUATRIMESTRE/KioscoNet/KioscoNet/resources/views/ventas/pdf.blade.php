<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Comprobante - {{ $venta->numero }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11pt;
            line-height: 1.6;
            color: #333;
        }
        
        /* ENCABEZADO */
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            margin-bottom: 30px;
        }
        
        .header-content {
            display: table;
            width: 100%;
        }
        
        .header-left {
            display: table-cell;
            width: 60%;
            vertical-align: middle;
        }
        
        .header-right {
            display: table-cell;
            width: 40%;
            vertical-align: middle;
            text-align: right;
        }
        
        .company-name {
            font-size: 32pt;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .company-info {
            font-size: 10pt;
            line-height: 1.8;
            opacity: 0.95;
        }
        
        .document-type {
            background-color: white;
            color: #667eea;
            padding: 15px 20px;
            font-size: 20pt;
            font-weight: bold;
            border-radius: 8px;
            margin-bottom: 10px;
            text-align: center;
        }
        
        .document-number {
            font-size: 16pt;
            font-weight: bold;
            background-color: rgba(255,255,255,0.2);
            padding: 8px 15px;
            border-radius: 5px;
            text-align: center;
        }
        
        /* SECCIONES */
        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        
        .section-title {
            background-color: #f8f9fa;
            color: #2c3e50;
            padding: 12px 15px;
            font-size: 14pt;
            font-weight: bold;
            border-left: 5px solid #667eea;
            margin-bottom: 15px;
        }
        
        /* INFORMACIÓN DE LA VENTA */
        .info-grid {
            display: table;
            width: 100%;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .info-row {
            display: table-row;
        }
        
        .info-row:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .info-label {
            display: table-cell;
            width: 30%;
            padding: 12px 15px;
            font-weight: bold;
            color: #495057;
            border-bottom: 1px solid #dee2e6;
        }
        
        .info-value {
            display: table-cell;
            width: 70%;
            padding: 12px 15px;
            color: #212529;
            border-bottom: 1px solid #dee2e6;
        }
        
        /* TABLA DE PRODUCTOS */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        th {
            padding: 15px 10px;
            text-align: left;
            font-weight: bold;
            font-size: 11pt;
        }
        
        th.text-center {
            text-align: center;
        }
        
        th.text-right {
            text-align: right;
        }
        
        td {
            padding: 12px 10px;
            border-bottom: 1px solid #dee2e6;
            font-size: 10pt;
        }
        
        tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        tbody tr:hover {
            background-color: #e9ecef;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .product-name {
            font-weight: bold;
            color: #2c3e50;
        }
        
        .product-code {
            color: #6c757d;
            font-size: 9pt;
            display: block;
            margin-top: 3px;
        }
        
        /* TOTALES */
        .totals-box {
            float: right;
            width: 50%;
            margin-top: 20px;
            border: 2px solid #667eea;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .totals-row {
            display: table;
            width: 100%;
            border-bottom: 1px solid #e9ecef;
        }
        
        .totals-row:last-child {
            border-bottom: none;
        }
        
        .totals-label {
            display: table-cell;
            width: 60%;
            padding: 12px 20px;
            text-align: right;
            font-weight: 600;
            background-color: #f8f9fa;
        }
        
        .totals-value {
            display: table-cell;
            width: 40%;
            padding: 12px 20px;
            text-align: right;
            font-weight: bold;
            font-size: 12pt;
        }
        
        .total-final {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .total-final .totals-label,
        .total-final .totals-value {
            background: transparent;
            color: white;
            font-size: 16pt;
            padding: 15px 20px;
        }
        
        /* INFORMACIÓN DE PAGO */
        .payment-box {
            clear: both;
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-top: 30px;
            margin-bottom: 20px;
        }
        
        .payment-title {
            font-weight: bold;
            font-size: 14pt;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid rgba(255,255,255,0.3);
        }
        
        .payment-info {
            display: table;
            width: 100%;
        }
        
        .payment-row {
            display: table-row;
        }
        
        .payment-label {
            display: table-cell;
            width: 40%;
            padding: 8px 0;
            font-weight: 600;
        }
        
        .payment-value {
            display: table-cell;
            width: 60%;
            padding: 8px 0;
            text-align: right;
            font-size: 12pt;
        }
        
        /* OBSERVACIONES */
        .observations {
            background-color: #fff3cd;
            border-left: 5px solid #ffc107;
            padding: 15px 20px;
            margin: 20px 0;
            border-radius: 5px;
        }
        
        .observations-title {
            font-weight: bold;
            color: #856404;
            margin-bottom: 8px;
        }
        
        .observations-text {
            color: #856404;
            line-height: 1.6;
        }
        
        /* FIRMAS */
        .signatures {
            display: table;
            width: 100%;
            margin-top: 60px;
            page-break-inside: avoid;
        }
        
        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: center;
            padding: 0 30px;
        }
        
        .signature-line {
            border-top: 2px solid #333;
            margin-top: 60px;
            padding-top: 10px;
            font-weight: bold;
            color: #495057;
        }
        
        /* PIE DE PÁGINA */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 20px;
            background-color: #f8f9fa;
            border-top: 3px solid #667eea;
            text-align: center;
            font-size: 9pt;
            color: #6c757d;
        }
        
        .footer-brand {
            font-weight: bold;
            color: #667eea;
            font-size: 11pt;
            margin-bottom: 5px;
        }
        
        /* MARCA DE AGUA SI ESTÁ ANULADA */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 100pt;
            color: rgba(220, 53, 69, 0.15);
            z-index: -1;
            font-weight: bold;
        }
        
        /* BADGE DE ESTADO */
        .status-badge {
            display: inline-block;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 10pt;
        }
        
        .status-completada {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-anulada {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    {{-- Marca de agua si está anulada --}}
    @if($venta->anulada)
    <div class="watermark">ANULADA</div>
    @endif

    {{-- ENCABEZADO --}}
    <div class="header">
        <div class="header-content">
            <div class="header-left">
                <div class="company-name">KioscoNet</div>
                <div class="company-info">
                    📍 Dirección del Negocio<br>
                    📞 Tel: (123) 456-7890<br>
                    📧 Email: info@mikiosco.com<br>
                    💼 CUIT: 20-12345678-9
                </div>
            </div>
            <div class="header-right">
                <div class="document-type">COMPROBANTE DE VENTA</div>
                <div class="document-number">{{ $venta->numero }}</div>
            </div>
        </div>
    </div>

    {{-- INFORMACIÓN DE LA VENTA --}}
    <div class="section">
        <div class="section-title">📋 Información de la Venta</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Fecha y Hora:</div>
                <div class="info-value">{{ $venta->fecha_venta ? $venta->fecha_venta->format('d/m/Y H:i:s') : $venta->created_at->format('d/m/Y H:i:s') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Cliente:</div>
                <div class="info-value">{{ $venta->cliente->nombre }} {{ $venta->cliente->apellido }}</div>
            </div>
            @if($venta->cliente->documento)
            <div class="info-row">
                <div class="info-label">DNI/CUIT:</div>
                <div class="info-value">{{ $venta->cliente->documento }}</div>
            </div>
            @endif
            @if($venta->cliente->direccion)
            <div class="info-row">
                <div class="info-label">Dirección:</div>
                <div class="info-value">{{ $venta->cliente->direccion }}</div>
            </div>
            @endif
            <div class="info-row">
                <div class="info-label">Lista de Precios:</div>
                <div class="info-value"><strong>{{ strtoupper($venta->lista_precios ?? 'MINORISTA') }}</strong></div>
            </div>
            <div class="info-row">
                <div class="info-label">Atendido por:</div>
                <div class="info-value">{{ $venta->usuario->name ?? 'Sistema' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Estado:</div>
                <div class="info-value">
                    <span class="status-badge {{ $venta->anulada ? 'status-anulada' : 'status-completada' }}">
                        {{ $venta->anulada ? '❌ ANULADA' : '✅ COMPLETADA' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- DETALLE DE PRODUCTOS --}}
    <div class="section">
        <div class="section-title">🛒 Detalle de Productos</div>
        
        <table>
            <thead>
                <tr>
                    <th class="text-center" style="width: 8%;">Cant.</th>
                    <th style="width: 47%;">Producto</th>
                    <th class="text-right" style="width: 15%;">Precio Unit.</th>
                    <th class="text-center" style="width: 10%;">Desc.</th>
                    <th class="text-right" style="width: 20%;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($venta->detalles as $detalle)
                <tr>
                    <td class="text-center">{{ $detalle->cantidad }}</td>
                    <td>
                        <span class="product-name">{{ $detalle->producto->nombre }}</span>
                        @if($detalle->producto->codigo_barra)
                        <span class="product-code">Código: {{ $detalle->producto->codigo_barra }}</span>
                        @endif
                    </td>
                    <td class="text-right">${{ number_format($detalle->precio_unitario, 2) }}</td>
                    <td class="text-center">
                        @if($detalle->descuento_porcentaje > 0)
                            {{ $detalle->descuento_porcentaje }}%
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-right"><strong>${{ number_format($detalle->subtotal, 2) }}</strong></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- TOTALES --}}
    <div class="totals-box">
        <div class="totals-row">
            <div class="totals-label">Subtotal:</div>
            <div class="totals-value">${{ number_format($venta->subtotal ?? $venta->total, 2) }}</div>
        </div>
        
        @if($venta->descuento_porcentaje > 0)
        <div class="totals-row">
            <div class="totals-label">Descuento ({{ $venta->descuento_porcentaje }}%):</div>
            <div class="totals-value" style="color: #dc3545;">-${{ number_format($venta->descuento_monto, 2) }}</div>
        </div>
        @endif
        
        @if($venta->comision > 0)
        <div class="totals-row">
            <div class="totals-label">Comisión:</div>
            <div class="totals-value" style="color: #fd7e14;">+${{ number_format($venta->comision, 2) }}</div>
        </div>
        @endif
        
        <div class="totals-row total-final">
            <div class="totals-label">TOTAL:</div>
            <div class="totals-value">${{ number_format($venta->total, 2) }}</div>
        </div>
    </div>

    {{-- INFORMACIÓN DE PAGO --}}
    <div class="payment-box" style="clear: both;">
        <div class="payment-title">💳 Información de Pago</div>
        <div class="payment-info">
            <div class="payment-row">
                <div class="payment-label">Método de Pago:</div>
                <div class="payment-value"><strong>{{ strtoupper($venta->metodo_pago_nombre ?? $venta->metodo_pago) }}</strong></div>
            </div>
            
            @if($venta->metodo_pago === 'efectivo')
                <div class="payment-row">
                    <div class="payment-label">Efectivo Recibido:</div>
                    <div class="payment-value">${{ number_format($venta->monto_recibido ?? 0, 2) }}</div>
                </div>
                <div class="payment-row">
                    <div class="payment-label">Vuelto:</div>
                    <div class="payment-value" style="font-size: 14pt; font-weight: bold;">${{ number_format($venta->vuelto ?? 0, 2) }}</div>
                </div>
            @elseif($venta->metodo_pago === 'tarjeta')
                <div class="payment-row">
                    <div class="payment-label">Tipo de Tarjeta:</div>
                    <div class="payment-value">{{ strtoupper($venta->tipo_tarjeta ?? 'N/A') }}</div>
                </div>
                <div class="payment-row">
                    <div class="payment-label">Últimos 4 dígitos:</div>
                    <div class="payment-value">****{{ $venta->ultimos_digitos ?? '0000' }}</div>
                </div>
                <div class="payment-row">
                    <div class="payment-label">Código de Autorización:</div>
                    <div class="payment-value">{{ $venta->codigo_autorizacion ?? 'N/A' }}</div>
                </div>
            @elseif($venta->metodo_pago === 'transferencia')
                <div class="payment-row">
                    <div class="payment-label">Número de Transferencia:</div>
                    <div class="payment-value">{{ $venta->numero_transferencia ?? 'N/A' }}</div>
                </div>
                <div class="payment-row">
                    <div class="payment-label">Banco:</div>
                    <div class="payment-value">{{ strtoupper($venta->banco ?? 'N/A') }}</div>
                </div>
                @if($venta->fecha_transferencia)
                <div class="payment-row">
                    <div class="payment-label">Fecha Transferencia:</div>
                    <div class="payment-value">{{ \Carbon\Carbon::parse($venta->fecha_transferencia)->format('d/m/Y H:i') }}</div>
                </div>
                @endif
            @elseif(in_array($venta->metodo_pago, ['cc', 'cuenta_corriente']))
                <div class="payment-row">
                    <div class="payment-label">Saldo Anterior:</div>
                    <div class="payment-value">${{ number_format($venta->saldo_anterior ?? 0, 2) }}</div>
                </div>
                <div class="payment-row">
                    <div class="payment-label">Monto de esta Venta:</div>
                    <div class="payment-value">${{ number_format($venta->total, 2) }}</div>
                </div>
                <div class="payment-row">
                    <div class="payment-label">Nuevo Saldo:</div>
                    <div class="payment-value" style="font-size: 14pt;"><strong>${{ number_format($venta->nuevo_saldo ?? 0, 2) }}</strong></div>
                </div>
            @endif
        </div>
    </div>

    {{-- OBSERVACIONES --}}
    @if($venta->observaciones)
    <div class="observations">
        <div class="observations-title">📝 Observaciones:</div>
        <div class="observations-text">{{ $venta->observaciones }}</div>
    </div>
    @endif

    {{-- FIRMAS --}}
    <div class="signatures">
        <div class="signature-box">
            <div class="signature-line">
                Firma del Cliente
            </div>
        </div>
        <div class="signature-box">
            <div class="signature-line">
                Firma del Vendedor
            </div>
        </div>
    </div>

    {{-- PIE DE PÁGINA --}}
    <div class="footer">
        <div class="footer-brand">KioscoNet - Sistema de Gestión</div>
        <p>Documento generado el {{ now()->format('d/m/Y H:i:s') }}</p>
        <p style="margin-top: 5px; font-size: 8pt;">
            Este comprobante tiene validez como documento no fiscal
        </p>
    </div>
</body>
</html>