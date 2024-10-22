<!-- resources/views/downloads.blade.php -->
<!DOCTYPE html>
<html lang="es">

<head>
    <title>Descarga de Archivos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>
    <div class="container-fluid px-3"> <!-- container-fluid para pantallas más pequeñas -->
        <center>
            <!-- Imagen agregada entre el título y la lista de botones -->
            <img src="{{ asset('pdf_formats/logosj.png') }}" alt="Descarga de Archivos" class="img-fluid my-3">
            <h2>Descarga de Archivos</h2>
        </center>
        <div class="list-group">

            <!-- Primer botón optimizado para móvil -->
            <a href="{{ route('download', ['filename' => 'Chronos 2.0.0.apk']) }}"
                class="btn btn-primary d-flex align-items-center mb-3 py-2">
                <i class="bi bi-download me-3 fs-4"></i> <!-- Ícono agrandado -->
                <div class="text-start">
                    <div class="fw-bold">Chronos 2.0.0</div> <!-- Título -->
                    <small class="text-light">Versión estable APK</small> <!-- Subtítulo -->
                </div>
            </a>

            <!-- Segundo botón optimizado para móvil -->
            <a href="{{ route('download', ['filename' => 'MiniGreen1.7.8.apk']) }}"
                class="btn btn-primary d-flex align-items-center mb-3 py-2">
                <i class="bi bi-download me-3 fs-4"></i> <!-- Ícono agrandado -->
                <div class="text-start">
                    <div class="fw-bold">MiniGreen 1.7.8</div> <!-- Título -->
                    <small class="text-light">Versión estable APK</small> <!-- Subtítulo -->
                </div>
            </a>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
