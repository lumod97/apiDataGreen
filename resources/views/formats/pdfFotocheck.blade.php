<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Códigos de Barras</title>
    <style>
        /* Estilo de página A4 */
        @page {
            size: 6cm 9.18cm;
            margin: 0px;
            padding: 0px;
            /* Ajusta el margen si es necesario */
        }

        @font-face {
            font-family: 'Code128';
            src: 'fonts\\code128.ttf';
        }

        @font-face {
            font-family: 'Montss';
            src: url('{{ storage_path('fonts/example.ttf') }}') format('truetype');
        }


        body {
            /* padding-top: 0.5cm !important; */
            background: #ffffff;
            background-size: 100% 100%;
            /* Ajusta para que la imagen de fondo se ajuste al tamaño del body */
            background-repeat: no-repeat;
            /* Evita que la imagen de fondo se repita */
            /* Ajusta el valor según tus necesidades */
            width: 6cm !important;
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
            margin-top: 0.35cm !important;
            margin-left: 0.25cm !important;
            width: 2.5cm !important;
            /* Ancho de la imagen */
            height: 0.9cm !important;
            /* Altura de la imagen */
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
            top: 100px;
            /* Prueba con un valor en píxeles u otra unidad relativa */
            left: 50%;
            transform: translateX(-50%);
            margin: 0;
            width: 4.9cm !important;
            height: 8.5cm !important;
            object-fit: cover;
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

        span{
            font-family: 'Montss';
        }
    </style>
</head>

<body>
    <img src="{{ $encriptado }}" class="img-barra" alt="">
    <img src="pdf_formats/logosj.png" class="img-logo" alt="">
    <img src="pdf_formats/palta.png" class="img-palta" alt="">
    <img src="pdf_formats/uva.png" class="img-uva" alt="">
    {{-- <span> HOLA SOY FUENTE ALTERNA </span> --}}
    {{-- {{$barra}} --}}
    {{-- <span class="number"> 946027276 </span> --}}
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
