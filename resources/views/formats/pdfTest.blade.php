<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Códigos de Barras</title>
    <style>
        /* Estilo de página A4 */
        @page {
            margin: 0px !important;
            padding: 0px !important;
        }

        @font-face {
            font-family: 'Code128';
            src: 'fonts\\code128.ttf';
        }

        @font-face {
            font-family: 'Montserrat';
            src: url('{{ public_path('fonts/Montserrat.ttf') }}') format('truetype');
        }


        body {
            position: relative;
            size: 100% 100%;
            margin: 0px !important;
            padding: 0px !important;
            /* Ajusta el margen si es necesario */
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
            position: absolute;
            top: 0.35cm !important;
            left: 0.25cm !important;
            width: 2.5cm !important;
            /* Ancho de la imagen */
            height: 0.9cm !important;
            /* Altura de la imagen */
        }

        .img-palta {
            position: absolute !important;
            /* bottom: 0;
            left: 0; */
            /* margin: 0; */
            width: 1.2cm !important;
            height: 2.1cm !important;
        }

        .img-uva {
            position: absolute !important;
            /* bottom: 0 !important;
            right: 0 !important;
            margin: 0 ; */
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

        .divImagenes {
            width: 100% !important;
        }

        /* .spanDNI {
            position: absolute;
            font-family: 'Montserrat';
            font-size: 0.5cm;
            border: 2px solid black;
            z-index: 2;
            /* Asegúrate de que el z-index sea mayor que el de la imagen */
            bottom: cm;
            /* Ajusta la altura desde la parte inferior del contenedor */
        } */
    </style>
</head>

<body style="position: relative !important;">
    {{-- LOGO --}}
    <div class="img-logo">
        <img src="pdf_formats/logosj.png" alt="">
    </div>
    {{-- CODIGO DE BARRAS --}}
    <div style="position: relative; top: 3cm;">
        <img src="{{ 'data:image/png;base64,' . $encriptado }}" alt="Descripción de la imagen" style="z-index: 1;">
        <div style="position: absolute !important; top: 0 !important; left: 80px !important; bottom: 80px !important; z-index: 2 !important; background: rgba(255, 255, 255, 0.5);">
            <span>Tu texto aquí</span>
        </div>
    </div>
    

    {{-- GRAFICO IZQUIERDA --}}
    <div>
    </div>
    <div style="position: absolute; left: 0 !important; bottom: 0 !important;">
        <img src="pdf_formats/palta.png" style="position: absolute; " class="img-palta" alt="">
    </div>
    {{-- GRAFICO DERECHA --}}
    <div style="position: absolute; right: 0 !important; bottom: 0 !important;">
        <img src="pdf_formats/uva.png" style="position: absolute;" class="img-uva" alt="">
    </div>
    <div>
</body>

</html>
