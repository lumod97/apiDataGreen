<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Códigos de Barras</title>
    <style>
        /* Estilo de página A4 */
        @page {
            size: A4;
            margin-top: 1.5cm;
            margin-bottom: -50px;
            padding-bottom: -50px;
            /* Ajusta el margen si es necesario */
        }

        @font-face {
            font-family: 'Code128';
            src: 'fonts\\code128.ttf';
        }


        body {
            padding-top: 0.5cm !important;
            /* Ajusta el valor según tus necesidades */
        }

        /* Estilo del contenedor */
        .container {
            display: grid !important;
            grid-template-columns: repeat(2, 1fr) !important;
            /* Configura 2 columnas */
            gap: 0.5cm !important;
            /* Espacio entre imágenes */
        }

        /* Estilo de imagen */
        img {
            width: 472.44px !important;
            /* Ancho de la imagen */
            height: 722.83px !important;
            /* Altura de la imagen */
        }
    </style>
</head>

<body>
    <!-- Contenedor de imágenes -->
    <div class="container">
        {{-- <span class="barra"> 72450801 </span> --}}
        {{-- @for ($i = 0; $i < 1000; $i++)
            @endfor --}}
        @foreach ($images as $image)
            <img src="{{ 'data:image/jpeg;base64,'.$image['ruta'] }}" width="472.44px" height="722.83px"
                style=" margin-top:3px !important;border: 2px solid black;"
                alt="Descripción de la imagen">
            <!-- Cada imagen dentro de su propio contenedor -->
        @endforeach
    </div>
</body>

</html>
