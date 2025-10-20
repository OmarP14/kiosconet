<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ticket - {{ $venta->numero_venta }}</title>
    <style>
        @page {
            margin: 0;
            size: 80mm auto;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            width: 80mm;
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            line-height: 1.4;
            padding: 5mm;
        }
        
        .ticket-header {
            text-align: center;
            border-bottom: 2px dashed #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        
        .ticket-header h1 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .ticket-header p {
            font-size: 11px;
            margin: 2px 0;
        }
        
        .ticket-info {
            margin-bottom: 10px;
            font-size: 11px;
        }
        
        .ticket-info .row {
            display: flex;
            justify-content: space-between;
            margin: 3px 0;
        }
        
        .ticket-items {
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
            padding: 8px 0;
            margin: 10px 0;
        }
        
        .item {
            margin: 5px 0;
        }
        
        .item-name {
            font-weight: bold;
            font-size: 12px;
        }
        
        .item-details {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            margin-top: 2px;
        }
        
        .ticket-totals {
            margin: 10px 0;
        }
        
        .ticket-totals .row {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
            font-size: 12px;
        }
        
        .ticket-totals .total-final {
            font-size: 16px;
            font-weight: bold;
            border-top: 2px solid #000;
            padding-top: 8px;
            margin-top: 8px;
        }
        
        .ticket-payment {
            margin: 10px 0;
            padding: 8px 0;
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
            font-size: 11px;
        }
        
        .ticket-payment .row {
            display: flex;
            justify-content: space-between;
            margin: 3px 0;
        }
        
        .ticket-footer {
            text-align: center;
            margin-top: 15px;
            font-size: 10px;
            line-height: 1.6;
        }
        
        .barcode {
            text-align: center;
            margin: 10px 0;
            font-size: 14px;
            letter-spacing: 2px;
        }
        
        @media print {
            body {
                width: 80mm;
            }
            
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="ticket-header">
        <h1>{{ config('app.name', 'MI KIOSCO') }}</h1>
        <p>Dirección del Negocio</p>
        <p>Tel: (123) 456-7890</p>
        <p>CUIT: 20-12345678-9</p>
    </div>

    <div class="ticket-info">
        <div class="row">
            <span><strong>TICKET:</strong></span>
            <span>{{ $venta->numero_venta }}</span>
        </div>
        <div class="row">
            <span><strong>Fecha:</strong></span>
            <span>{{ $venta->fecha_venta->format('d/m/Y H:i') }}</span>
        </div>
        <div class="row">
            <span><strong>Cliente:</strong></span>
            <span>{{ $venta->cliente->nombre }} {{ $venta->cliente->apellido }}</span>
        </div>
        <div class="row">
            <span><strong>Atendido por:</strong></span>
            <span>{{ $venta->usuario->name ?? 'Sistema' }}</span>
        </div>
    </div>

    <div class="ticket-items">
        @foreach($venta->detalles as $detalle)
        <div class="item">
            <div class="item-name">{{ $detalle->producto->nombre }}</div>
            <div class="item-details">
                <span>{{ $detalle->cantidad }} x ${{ number_format($detalle->precio_unitario, 2) }}</span>
                <span>${{ number_format($detalle->subtotal, 2) }}</span>
            </div>
            @if($detalle->descuento_porcentaje > 0)
            <div class="item-details">
                <span>Descuento ({{ $detalle->descuento_porcentaje }}%)</span>
                <span>-${{ number_format($detalle->descuento_monto, 2) }}</span>
            </div>
            @endif
        </div>
        @endforeach
    </div>

    <div class="ticket-totals">
        <div class="row">
            <span>Subtotal:</span>
            <span>${{ number_format($venta->subtotal, 2) }}</span>
        </div>
        
        @if($venta->descuento_porcentaje > 0)
        <div class="row">
            <span>Descuento Global ({{ $venta->descuento_porcentaje }}%):</span>
            <span>-${{ number_format($venta->descuento_monto, 2) }}</span>
        </div>
        @endif
        
        @if($venta->comision > 0)
        <div class="row">
            <span>Comisión tarjeta:</span>
            <span>+${{ number_format($venta->comision, 2) }}</span>
        </div>
        @endif
        
        <div class="row total-final">
            <span>TOTAL:</span>
            <span>${{ number_format($venta->total, 2) }}</span>
        </div>
    </div>

    <div class="ticket-payment">
        <div class="row">
            <span><strong>Método de Pago:</strong></span>
            <span>{{ strtoupper($venta->metodo_pago) }}</span>
        </div>
        
        @if($venta->metodo_pago === 'efectivo')
            <div class="row">
                <span>Efectivo recibido:</span>
                <span>${{ number_format($venta->monto_recibido, 2) }}</span>
            </div>
            <div class="row">
                <span>Vuelto:</span>
                <span>${{ number_format($venta->vuelto, 2) }}</span>
            </div>
        @elseif($venta->metodo_pago === 'tarjeta')
            <div class="row">
                <span>Tipo:</span>
                <span>{{ strtoupper($venta->tipo_tarjeta) }}</span>
            </div>
            <div class="row">
                <span>Últimos 4 dígitos:</span>
                <span>****{{ $venta->ultimos_digitos }}</span>
            </div>
            <div class="row">
                <span>Autorización:</span>
                <span>{{ $venta->codigo_autorizacion }}</span>
            </div>
        @elseif($venta->metodo_pago === 'transferencia')
            <div class="row">
                <span>N° Transferencia:</span>
                <span>{{ $venta->numero_transferencia }}</span>
            </div>
            <div class="row">
                <span>Banco:</span>
                <span>{{ strtoupper($venta->banco) }}</span>
            </div>
        @elseif($venta->metodo_pago === 'cuenta_corriente')
            <div class="row">
                <span>Saldo anterior:</span>
                <span>${{ number_format($venta->saldo_anterior, 2) }}</span>
            </div>
            <div class="row">
                <span>Nuevo saldo:</span>
                <span>${{ number_format($venta->nuevo_saldo, 2) }}</span>
            </div>
        @endif
    </div>

    @if($venta->observaciones)
    <div style="margin: 10px 0; font-size: 11px;">
        <strong>Observaciones:</strong><br>
        {{ $venta->observaciones }}
    </div>
    @endif

    <div class="barcode">
        *{{ $venta->numero_venta }}*
    </div>

    <div class="ticket-footer">
        <p>¡Gracias por su compra!</p>
        <p>Conserve este ticket</p>
        <p style="margin-top: 8px; font-size: 9px;">
            Impreso el {{ now()->format('d/m/Y H:i:s') }}
        </p>
    </div>

    <script>
        // Auto-imprimir al cargar
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>