<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Solicitud de Pago (Transferencia)</title>
</head>
<style type="text/css">
    @page {
        margin: .8cm .8cm .8cm .8cm;
        background-color: #fff;
    }

    body {
        font-family: 'AvenirNextLTPro', 'Muli', Arial, Helvetica, sans-serif;
        font-size: 7pt;
    }

    p {
        color: #21274E;
    }

    .h1 {
        font-size: 20pt;
        padding: 0px;
        margin: 0px;
        margin-bottom: -7px;
        color: #0C3456;
        text-align: right
    }

    .subtitle {
        padding: 0px;
        margin: 0px;
        font-size: 15pt;
        color: #0C3456;
        font-style: bold;
        text-align: right
    }

    .estatus {
        padding: 0px;
        margin: 0px;
        font-size: 11pt;
        color: #0C3456;
        font-style: bold;
        text-align: right
    }

    .backblue {
        text-align: center;
        vertical-align: middle;
        background-color: #0C3456;
        color: white;
        width: 100%;
        position: fixed;
    }

    .header {
        position: fixed;
        left: 0px;
        right: 0px;
        height: 200px;
        text-align: center;
        top: 0px;
    }

    .contenido {
        margin-top: 30px;
        top: 75px;
        position: relative;
        color: #001e2c;
        width: 100%;
        font-size: 7pt;
    }

    .contenido p {
        margin: 0px;
    }

    .det-title {
        top: 9% !important;
        text-align: CENTER;
        font-size: 10pt;
    }

    .detalle {
        position: relative;
        color: #001e2c;
        width: 100%;
        font-size: 7pt;
        top: 10% !important;
        text-align: CENTER;
        font-size: 9pt;
    }

    .info-cabecera {
        background-color: #0c335624;
        margin-left: 0px;
        margin-right: 10px;
    }

    .info-cabecera p {
        font-size: 7.3pt;
        margin-left: 5px;
    }

    .title-cabecera {
        background-color: #0C345610;
        margin-left: 0px;
        margin-right: 10px;
    }

    .cell-detalle {
        background-color: #0c33560b;
        margin: 0px;
        border-right: 1px solid #0c33563d;
        border-bottom: 1px solid #0c33563d;
        text-align: justify;
    }


    .title-cabecera p {
        margin-top: 3px;
        margin-left: 5px;
        margin-bottom: 2px;
    }

    .title-cell {
        color: #fff;
        font-style: bold;
        padding: 4.5px;
        text-align: justify;
        font-size: 8pt;
    }

    .cell-detalle p {
        margin: 0px;
        padding: 0px;
        font-size: 7pt;
        margin-top: 0px;
        margin-right: 5px;
    }

    .table {
        display: table;
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .table-row {
        display: table-row;
    }

    .table-cell1 {
        display: table-cell;
        vertical-align: middle;
        padding-left: 0.3cm;
    }

    .cell-title {
        display: table-cell;
        vertical-align: middle;
        padding-left: 0.3cm;
    }
</style>

<body>
    <div class="header">
        <div class="table">
            <div class="table-row">
                <div class="table-cell1" style="padding-left: 40px;">
                    <img src="img/logo.png" height="90" alt="">
                </div>
                <div colspan="1" class="table-cell1">
                </div>
                <div colspan="3" class="table-cell1">
                    <p class="h1">SOLICITUD DE <strong>PAGO</strong></p>
                    <p class="subtitle">
                        @if($purchaseRequest->paymentMethod === 'cash')
                        Efectivo
                        @else
                        @if($purchaseRequest->paymentMethod === 'transference')
                        TRANSFERENCIA
                        @else
                        CHEQUE
                        @endif
                        @endif
                    </p>
                </div>
            </div>

            <div class="table-row">
                <div colspan="2" class="table-cell1">
                </div>
                <div colspan="3" class="table-cell1">
                    <p class="estatus">
                        {{$Helpers->statusPurchaseRequest($purchaseRequest->status)}}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <br>

    <div class="contenido">
        <div class="table">
            <div class="table-row" style="background-color: #21274E;">
                <div colspan="4" class="table-cell1" style="background-color: #21274E; height:18px;"></div>
            </div>
            <div class="table-row">
                <div colspan="1" class="table-cell1 title-cabecera">
                    <p>Estación solicitante</p>
                </div>
                <div colspan="1" class="table-cell1 title-cabecera">
                    <p>Negocio</p>
                </div>
                <div colspan="2" class="table-cell1 title-cabecera">
                    <p>¿Solicitud Extraordinaria?</p>
                </div>

            </div>
            <div class="table-row">
                <div colspan="1" class="table-cell1 title-cabecera">
                    <div class="info-cabecera">
                        <p>{{$purchaseRequest->station}}</p>
                    </div>
                </div>
                <div colspan="1" class="table-cell1 title-cabecera">
                    <div class="info-cabecera">
                        <p>{{$Helpers->business($purchaseRequest->business)}}</p>
                    </div>
                </div>
                <div colspan="2" class="table-cell1 title-cabecera">
                    <div class="info-cabecera">
                        <p>
                            @if($purchaseRequest->extraordinary == 0)
                            No
                            @else
                            Si
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            <div class="table-row">
                <div colspan="2" class="table-cell1 title-cabecera">
                    <div class="info-cabecera" style="background: #21274E;">
                        <p style="color:#fff; font-style:bold; padding: 4px;">Proveedor</p>
                    </div>
                </div>
                <div colspan="2" class="table-cell1 title-cabecera">
                    <p>Importe de la solicitud</p>
                </div>
            </div>
            <div class="table-row">
                <div colspan="2" class="table-cell1 title-cabecera">
                    <div class="info-cabecera" style="background-color: #fff;">
                        <p style="font-size:9.5pt; margin-top:-15px;">
                            <strong>{{$purchaseRequest->provider->name}}</strong>
                        </p>
                    </div>
                </div>
                <div colspan="1" class="table-cell1 title-cabecera">
                    <div class="info-cabecera" style="background-color: #fff; border: 1pt solid #21274E;">
                        <p style="font-size: 11.5pt; margin-top:-1px;">
                            $ {{number_format((float)$purchaseRequest->import, 2, '.', ',')}}</p>
                    </div>
                </div>
                <div colspan="1" class="table-cell1 title-cabecera">
                </div>
            </div>
            <div class="table-row">
                <div colspan="2" class="table-cell1 title-cabecera">
                    <p>Cuenta Destino</p>
                </div>
                <div colspan="1" class="table-cell1 title-cabecera"></div>
                <div colspan="1" class="table-cell1 title-cabecera"></div>
            </div>
            <div class="table-row">
                <div colspan="2" class="table-cell1 title-cabecera">
                    @if($purchaseRequest->paymentMethod === 'transference')
                    <div class="info-cabecera">
                        <p>{{$purchaseRequest->account->bank->name}} - {{$purchaseRequest->account->bankAccount}}</p>
                    </div>
                    @else
                    <div class="info-cabecera">
                        <p>N/A</p>
                    </div>
                    @endif
                </div>
                <div colspan="1" class="table-cell1 title-cabecera">
                </div>
                <div colspan="1" class="table-cell1 title-cabecera">
                </div>
            </div>
            <div class="table-row">
                <div colspan="1" class="table-cell1 title-cabecera">
                    <p>Clabe interbancaria</p>
                </div>

                <div colspan="1" class="table-cell1 title-cabecera">
                    Fecha de Solicitud
                </div>
            </div>
            <div class="table-row">
                <div colspan="1" class="table-cell1 title-cabecera">
                    @if($purchaseRequest->paymentMethod === 'transference')
                    <div class="info-cabecera">
                        <p>{{$purchaseRequest->account->clabe}}&nbsp;</p>
                    </div>
                    @else
                    <div class="info-cabecera">
                        <p>N/A</p>
                    </div>
                    @endif
                </div>
                <div colspan="1" class="table-cell1 title-cabecera">
                    <p style="font-size:8pt; margin-top:-8px;">
                        {{$Helpers->formatTimezoneToDate($purchaseRequest->created_at)}}
                    </p>
                </div>
            </div>

            <div class="table-row">
                <div colspan="4" class="table-cell1 title-cabecera">
                    <br>
                </div>
            </div>
        </div>
    </div>

    <div class="contenido det-title">
        <p>DETALLE DE LA SOLICITUD</p>
    </div>

    <div class="detalle">
        <div class="table">
            <div class="table-row" style="background-color: #21274E;">
                <div colspan="1" class="table-cell1" style="background-color: #21274E;">
                    <p class="title-cell">Cargo</p>
                </div>
                <div colspan="1" class="table-cell1" style="background-color: #21274E;">
                    <p class="title-cell">Concepto</p>
                </div>
                <div colspan="1" class="table-cell1" style="background-color: #21274E;">
                    <p class="title-cell">Tipo Mov.</p>
                </div>
                <div colspan="1" class="table-cell1" style="background-color: #21274E;">
                    <p class="title-cell">Obs.</p>
                </div>
                <div colspan="1" class="table-cell1" style="background-color: #21274E;">
                    <p class="title-cell">Importe Total</p>
                </div>
                <div colspan="1" class="table-cell1" style="background-color: #21274E;">
                    <p class="title-cell">Pago</p>
                </div>

            </div>
            @foreach($purchaseRequest->details as $det)
            <div class="table-row">
                <div colspan="1" class="table-cell1 cell-detalle">
                    <p>{{$det->charge}}</p>
                </div>
                <div colspan="1" class="table-cell1 cell-detalle">
                    <p>{{$det->concept}}</p>
                </div>
                <div colspan="1" class="table-cell1 cell-detalle">
                    <p>{{$Helpers->movementTypes($det->movementType)}}</p>
                </div>
                <div colspan="1" class="table-cell1 cell-detalle">
                    <p>{{$det->observation}}</p>
                </div>
                <div colspan="1" class="table-cell1 cell-detalle">
                    <p>${{number_format((float)$det->totalAmount, 2, '.', ',')}}</p>
                </div>
                <div colspan="1" class="table-cell1 cell-detalle">
                    <p>${{number_format((float)$det->paymentAmount, 2, '.', ',')}}</p>
                </div>
            </div>
            @endforeach
            <div class="table-row" style="background-color: #21274E;">
                <div colspan="5" class="table-cell1" style="background-color: #21274E;">
                    <p class="title-cell" style="text-align: right;">Total del pago</p>
                </div>
                <div colspan="1" class="table-cell1" style="background-color: #fff; border: 1pt solid #21274E;">
                    <p>${{number_format((float)$purchaseRequest->import, 2, '.', ',')}}</p>
                </div>

            </div>
        </div>
    </div>
</body>

</html>