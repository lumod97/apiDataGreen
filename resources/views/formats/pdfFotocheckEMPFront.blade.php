<!DOCTYPE html>
<html lang="en">

{{----------------------------------------------------------------------------------------------------}}
{{----------------------------------------------------------------------------------------------------}}
{{----------------------------------------------------------------------------------------------------}}
{{---------------------------------VISTA FRONTAL DE FOTOCHECK-----------------------------------------}}
{{----------------------------------------------------------------------------------------------------}}
{{----------------------------------------------------------------------------------------------------}}
{{----------------------------------------------------------------------------------------------------}}
{{----------------------------------------------------------------------------------------------------}}


{{-- TABAJAMOS CON DOMPDF PARA LA GENERACIÓN DE ARCHIVOS PDF DESDE UNA VISTA EN BLADE (LARAVEL) --}}

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vista con imágenes</title>
    <style>
        /* DEFINIMOS EL TAMAÑO DE LA HOJA QUE QUEREMOS PASAR A PDF */
        @page {
            size: 53.975mm 85.725mm !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        /* PERSONALIZAMOS LAS FUENTES QUE QUEREMOS USAR */
        @font-face {
            font-family: 'Montserrat';
            src: url('{{ public_path('fonts/Montserrat.ttf') }}') format('truetype');
        }

        @font-face {
            font-family: 'MontserratBold';
            src: url('{{ public_path('fonts/Montserrat-Bold.ttf') }}') format('truetype');
        }


        /* ESTILOS DE PLANTILLA */

        body {
            margin: 0 !important;
            padding: 0 !important;
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            justify-content: space-between !important;
            height: 100% !important;
        }

        .image-full {
            position: absolute !important;
            bottom: 0 !important;
            left: 0 !important;
            width: 53.975mm !important;
            height: 53mm !important;
            object-fit: cover !important;
        }

        .image-logo {
            position: absolute !important;
            top: 2mm !important;
            left: 0 !important;
            width: 20mm !important;
            height: 7mm !important;
            object-fit: cover !important;
        }

        .image-center {
            position: absolute !important;
            top: 8.2mm !important;
            /* top: 18 mm !important; */
            width: 36mm;
            left: 50% !important;
            transform: translateX(-50%) !important;
        }

        .image-bottom-right {
            position: absolute !important;
            bottom: 1cm !important;
            right: 2cm !important;
        }

        .text-saludo {
            position: absolute;
            font-family: Montserrat !important;
            color: #ffffff;
            top: 55mm;
            left: 4mm;
        }

        .text-nombre {
            position: absolute;
            font-family: MontserratBold !important;
            font-family: Montserrat !important;
            color: #ffffff;
            top: 63mm;
            left: 4mm;
            right: 2mm;
            line-height: 12px;
        }

        .text-nombre > span {
            font-family: MontserratBold !important;
            line-height: 12px;
        }
        
        
        .text-cargo {
            position: absolute;
            font-family: Montserrat !important;
            color: #ffffff;
            bottom: 3.7mm;
            left: 0;
            right: 0;
            font-size: 12px;
            text-align: center;
            line-height: 9px;
            /* Alinea el texto al centro */
        }

        .hr {
            position: absolute;
            /* font-family: Montserrat !important; */
            height:1px;
            width: 80%;
            background-color: #ffffff;
            bottom: 11mm;
            left: 4mm;
        }
    </style>
</head>

<body>
    {{-- INICIAMOS DEFINICIÓN DE IMÁGENES, TEXTO U OTROS CAMPOS --}}
    <!-- Imagen centrada a 4cm desde arriba -->
    {{-- EN ESTE CASO SE ESTÁN PONIENDO VALORES EN CRUDO AUN PARA LAS RUTAS DE LAS IMÁGENES --}}
    {{-- <img src="data:image/png;base64,{{base64_encode(file_get_contents($foto))}}" alt="Imagen Centrada" class="image-center"> --}}
    <img src="data:image/png;base64,{{base64_encode(file_get_contents('pdf_formats\\images\\fotos_personal\\'.str_replace(' ', '', $dni).'.png'))}}" alt="Imagen Centrada" class="image-center">
    {{-- logosj --}}
    <img src="pdf_formats/images/logosj.png" alt="Imagen Completa" class="image-logo">
    <!-- Imagen que cubre toda la página -->
    <img src="pdf_formats/images/imagen_bottom.png" alt="Imagen Completa" class="image-full">
    <span class="text-saludo"> ¡Hola!</span>
    {{-- INVOCAMOS EL NOMBRE DESDE EL OBJETO, CUANDO SE ENVIA UN OBJETO, SE INVOCAN LOS CAMPOS COMO VARIABLES --}}
    <span class="text-nombre">Soy <span>{{$nombre}}</span> </span>
    <div class="hr"></div>
    {{-- <span class="text-cargo"> Técnico de Soporte </span> --}}
    <span class="text-cargo">{{$cargo}}</span>
</body>

</html>