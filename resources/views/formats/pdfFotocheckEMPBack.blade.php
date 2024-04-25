<!DOCTYPE html>
<html lang="en">

{{-- ------------------------------------------------------------------------------------------------ --}}
{{-- ------------------------------------------------------------------------------------------------ --}}
{{-- ------------------------------------------------------------------------------------------------ --}}
{{-- -------------------------------VISTA TRASERA DE FOTOCHECK--------------------------------------- --}}
{{-- ------------------------------------------------------------------------------------------------ --}}
{{-- ------------------------------------------------------------------------------------------------ --}}
{{-- ------------------------------------------------------------------------------------------------ --}}
{{-- ------------------------------------------------------------------------------------------------ --}}

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vista con imágenes</title>
    <style>
        @page {
            size: 53.975mm 85.725mm !important;
            margin: 0 !important;
            padding: 0 !important;
        }

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
            top: 0 !important;
            left: 0 !important;
            width: 53.975mm !important;
            height: 85.725mm !important;
            object-fit: cover !important;
        }

        .image-barcode {
            position: absolute !important;
            top: 26mm !important;
            height: 14mm;
            width: 41mm;
            left: 6mm;
            right: 6mm;
            /* left: 50% !important;
            transform: translateX(-50%) !important; */
        }

        .image-bottom-right {
            position: absolute !important;
            bottom: 1cm !important;
            right: 2cm !important;
        }

        .text-dni {
            position: absolute;
            font-family: sans-serif !important;
            top: 40mm;
            left: 50%;
            font-size: 20px;
            transform: translateX(-50%) !important;
        }

        .text-dni-test {
            position: absolute;
            font-family: sans-serif !important;
            top: 40mm;
            left: 13mm;
            /* right: 10mm; */
            font-size: 16px;
        }

        .text-nombre {
            position: absolute;
            font-family: Montserrat !important;
            top: 63mm;
            left: 4mm;
        }

        .text-linea-etica {
            position: absolute;
            font-family: Montserrat !important;
            color: #BF015A;
            bottom: 5mm;
            left: 9mm;
            right: 9mm;
            font-size: 9.5px;
            line-height: 7px;
            text-align: center;
        }

        .text-linea-etica > span{
            font-family: Montserrat;
        }

        .text-slogan-empresa {
            position: absolute;
            font-family: Montserrat !important;
            line-height: 10px;
            top: 51mm;
            left: 4mm;
            right: 4mm;
            font-size: 12px;
            text-align: center;
        }

        .hr {
            position: absolute;
            /* font-family: Montserrat !important; */
            height: 1px;
            width: 80%;
            background-color: #ffffff;
            bottom: 11mm;
            left: 4mm;
        }

        .text-return-message {
            position: absolute;
            font-family: Montserrat !important;
            /* CON UNA REGLA, OBTENEMOS LAS MEDIDAS EN EL FOTOCHECK REAL, EL QUE SE GENERÓ EN BASE AL DISEÑO OBTENIDO
            0.7 DESDE toP
            0.5 DESDE LEFT Y RIGHT */
            color: #00A152;
            top: 5mm;
            left: 6mm;
            right: 6mm;
            /* EL TAMAÑO DE FUENTE AUN NO LO TOMO POR PUNTOS */
            font-size: 7px;
            line-height: 7.5px;
            text-align: center;
        }
    </style>
</head>

<body>

    {{-- PONEMOS EL TEXTO DE CABECERA PARA EL MENSAJE DE DEVOLUCIÓN DEL FOTOCHECK --}}
    <span class="text-return-message"> Si encuentras esta identificación entrégala en el área de Gestión del Talento
        Humano </span>

    {{-- IMAGEN DE CODIGO DE BARRAS --}}
    <!-- Imagen centrada a 4cm desde arriba -->
    <img src="{{$codigo_barras}}" alt="Código de barras" class="image-barcode">

    {{-- TEXTO QUE CONTIENE EL NÚMERO DE DNI DEL EMPLEADO --}}
    <span class="text-dni">{{str_replace(' ', '', $dni)}}</span>
    {{-- <span class="text-dni-test">7 2 4 5 0 8 0 1</span> --}}
    {{-- TEXTO QUE CONTIENE EL SLOGAN DE LA EMPRESA --}}
    <span class="text-slogan-empresa">¡Para un mañana mejor, un presente juntos!</span>
    {{-- TEXTO QUE CONTIENE EL FOOTER, DONDE SE VISUALIZA LA INFORMACIÓN DE LA LÍNEA ÉTICA DE SAN JUAN --}}
    <span class="text-linea-etica"> Ante cualquier queja o sugerencia comunícate con la línea ética San Juan <br><br>
        <span>
            942 084 516
        </span>
    </span>
</body>

</html>
