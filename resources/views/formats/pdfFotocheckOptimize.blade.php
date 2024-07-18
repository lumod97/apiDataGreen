<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Códigos de Barras</title>
    <style>
        @font-face {
            font-family: 'Montserrat';
            src: url('{{ public_path('fonts/Montserrat.ttf') }}') format('truetype');
        }

        @font-face {
            font-family: 'MontserratBold';
            src: url('{{ public_path('fonts/Montserrat-Bold.ttf') }}') format('truetype');
        }

        @font-face {
            font-family: 'MontserratLight';
            src: url('{{ public_path('fonts/Montserrat-Light.ttf') }}') format('truetype');
        }

        @page {
            size: 53.975mm 85.725mm !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        body {
            margin: 0 !important;
            padding: 0 !important;
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            justify-content: space-between !important;
            height: 100% !important;
        }

        /* Estilo del contenedor */
        .container {
            display: grid !important;
            /* grid-template-columns: repeat(2, 1fr) !important; */
            /* Configura 2 columnas */
            /* gap: 0.5cm !important; */
            /* Espacio entre imágenes */
        }

        /* Estilo de imagen */
        .img-logo {
            margin-top: 2.8mm !important;
            margin-left: 2.7mm !important;
            width: 23mm !important;
            height: 7.475mm !important;
        }

        .img-palta {
            position: absolute;
            bottom: 0;
            left: 0;
            margin: 0;
            width: 1.2cm !important;
            height: 2.1cm !important;
        }

        .img-uva {
            position: absolute;
            bottom: 0;
            right: 0;
            margin: 0;
            width: 1.5cm !important;
            height: 2.6cm !important;
        }

        .img-barra {
            position: absolute;
            /* top: 100px; */
            top: 43mm;
            /* Prueba con un valor en píxeles u otra unidad relativa */
            left: 50%;
            transform: translateX(-50%);
            /* width: 4.9cm !important; */
            width: 42mm !important;
            height: 16.5mm !important;
            /* height: 8.5cm !important; */
            object-fit: cover !important;
        }

        .number {
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            margin: 0;
            font-family: Code128;
        }

        .barra {
            font-family: 'Arial Narrow';
        }

        span {
            font-family: 'Montss';
        }

        .text-name {
            position: absolute;
            font-family: Montserrat !important;
            justify-content: center !important;
            align-items: center !important;
            text-align: center !important;
            font-size: 3.2mm !important;
            top: 14mm;
            line-height: 1.3 !important;
            width: 100% !important;
            left: 0 !important;
        }

        .text-last-name {
            position: absolute;
            top: 21mm;
            left: 50%;
            transform: translateX(-50%);
            font-family: Montserrat !important;
            text-overflow: no-wrap !important;
            white-space: nowrap !important;
        }

        .text-id-codigo-general {
            position: absolute;
            top: 38mm;
            left: 50%;
            transform: translateX(-50%);
            font-family: Montserrat !important;
            text-overflow: no-wrap !important;
            white-space: nowrap !important;
            font-size: 3mm !important;
        }

        .text-dni {
            /* position: absolute;
            top: 59.5mm;
            left: 50%;
            transform: translateX(-50%);*/
            font-family: Montserrat !important;
            text-overflow: no-wrap !important;
            white-space: nowrap !important;
            margin: 2mm !important;
        }

        .text-mensaje {
            position: absolute;
            left: 50%;
            transform: translateX(-54%);
            /* left: 1.2mm !important; */
            /* right: 1.2mm !important; */
            bottom: 8mm;
            font-family: Montserrat !important;
            text-overflow: no-wrap !important;
            font-size: 1.8mm !important;
            text-align: center !important;
            /* white-space: nowrap !important; */
            /* margin: 2mm !important; */
        }

        .text-number {
            position: absolute !important;
            left: 50%;
            transform: translateX(-50%);
            bottom: 1.5mm !important;
            font-family: Montserrat !important;
            font-size: 1.8mm !important;
        }

        .container-dni {
            background: white !important;
            position: absolute;
            top: 57mm;
            left: 50%;
            transform: translateX(-50%);
            font-family: Montserrat !important;
            padding: 0px !important;
            vertical-align: top !important;
            /* text-overflow: no-wrap !important; */
            /* white-space: nowrap !important; */
        }

        .text-cargo {
            left: 0 !important;
            right: 0 !important;
            /* font-size: 3.0mm !important; */
            font-family: Montserrat !important;
            margin-left: 2.5mm !important;
            margin-right: 2.5mm !important;
            white-space: nowrap !important;
        }

        .container-cargo {
            position: absolute;
            font-family: Montserrat !important;
            justify-content: center !important;
            align-items: center !important;
            text-align: center !important;
            top: 30.5mm !important;
            font-size: 3mm !important;
            line-height: 0.7 !important;
            width: 100% !important;
            left: 0 !important;
        }
    </style>
</head>

<body>
    <img src="{{ $encriptado }}" class="img-barra" alt="">
    <img src="pdf_formats/logosj.png" class="img-logo" alt="">
    <img src="pdf_formats/palta.png" class="img-palta" alt="">
    <img src="pdf_formats/uva.png" class="img-uva" alt="">
    <div class="text-name">
        {{ $nombres }}
        <br>
        {{ $apellidos }}
    </div>
    {{-- <span class="text-last-name">{{ $apellidos }}</span> --}}
    {{-- <div class="container-cargo"> --}}
    {{-- <p class="text-cargo">ANALISTA PROGRAMADOR DE SIST.</p> --}}
    <div class="container-cargo">
        {{-- <p class="text-cargo"> --}}
        {{-- JEFE DE LOGÍSTICA --}}
        {{ $cargo }}
        {{-- ANALISTA PROGRAMADOR DE SISTEMAS INFORMÁTICOS --}}
        {{-- </p> --}}
    </div>
    {{-- </div> --}}
    <span class="text-id-codigo-general">{{ $codigo_general }}</span>
    <span class="text-mensaje">{{ $mensaje }}</span>
    <span class="text-number"> {{ $numero }} </span>
    <div class="container-dni">
        <span class="text-dni" style="max-width: 200px !important;">{{ $dni }}</span>
    </div>
    {{-- <span> HOLA SOY FUENTE ALTERNA </span> --}}
    {{-- {{$barra}} --}}
    {{-- {{url('pdf_formats/logosj.png')}} --}}
    <!-- Contenedor de imágenes -->
    {{-- <div class="container">
        {{-- <span class="barra"> {{ url('pdf_formats/fotocheck.jpg') }} </span> --}}
    {{-- @for ($i = 0; $i < 1000; $i++)
            <img src="{{ $images[0]['ruta'] }}" width="472.44px" height="722.83px" style=" margin-top:3px !important;margin-bottom:3px !important;border: 2px solid black;" alt="Descripción de la imagen"> 
        @endfor --}}
    {{-- @foreach ($images as $image)
        <!-- Cada imagen dentro de su propio contenedor -->
        @endforeach --}}
    {{-- </div> --}}
</body>

</html>
