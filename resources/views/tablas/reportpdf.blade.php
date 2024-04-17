<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informe de Integridad de Tablas</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
        }

        thead {
            background-color: #f2f2f2;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tbody tr:hover {
            background-color: #f2f2f2;
        }

        .highlight {
            background-color: #ffe6e6;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4"> <b> INFORME DE AUDITORÍA: INTEGRIDAD DE TABLAS </b></h2>
        <br>
        <table>
            <thead>
                <tr>
                    <th colspan="4"
                        style="text-align: center; background-color: #dcdcdc; color: #333; font-size: 18px;">INFORMACIÓN
                        GENERAL DEL ANÁLISIS</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope="col" width="25%">Gestor de Base de Datos:</th>
                    <td width="25%">{{ $integridad['type_db'] }}</td>
                    <th scope="col">Base de Datos:</th>
                    <td>{{ $integridad['name_bd'] }}</td>
                </tr>
                <tr>
                    <th scope="col" width="25%">Tabla:</th>
                    <td width="25%">{{ $integridad['table'] }}</td>
                    <th scope="col">Campo:</th>
                    <td>{{ $integridad['column_foreignkey'] }}</td>
                </tr>
                <tr>
                    <th scope="col">Tabla Referencial:</th>
                    <td>{{ $integridad['table_refer'] }}</td>
                    <th scope="col">Clave Primaria:</th>
                    <td>{{ $integridad['column_primarykey'] }}</td>
                </tr>
                <tr>
                    <th scope="col">Usuario:</th>
                    <td>{{ $integridad['user'] }}</td>
                    <th scope="col">Fecha de Análisis:</th>
                    <td>{{ date('d-m-Y', strtotime($integridad['created_at'])) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="card">
            <div class="card-header">
                <h2>RESULTADOS: EXCEPCIONES ENCONTRADAS</h2>
            </div>

            @if (count($listExceptions) > 0)
                <table class="table table-striped table-danger">
                    <thead style="background-color: red; color: white;">
                        <tr>
                            <th colspan="col"
                                style="text-align: center; background-color: red; color: white; font-size: 18px;">
                                Excepcion</th>
                            <th scope="col">Control de Claves Foraneas Nulas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($resultadosExceptionNotFound > 0)
                            @foreach ($resultadosExceptionNotFound as $key => $valor)
                                <tr>
                                    <td>{{ $key }}</td>
                                    <td>{{ $valor }}</td>
                                </tr>
                            @endforeach
                        @endif
                        @if ($resultadosExceptionNull > 0)
                            @foreach ($resultadosExceptionNull as $key => $valor)
                                <tr>
                                    <td>{{ $key }}</td>
                                    <td>{{ $valor }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            @else
                <tr>
                    <td colspan="7"> NO SE ENCONTRARIÓN EXCEPCIONES</td>
                </tr>
            @endif
        </div>
    </div>
</body>

</html>
