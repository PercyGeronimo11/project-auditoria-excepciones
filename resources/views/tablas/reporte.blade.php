<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte </title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 5px;
        }

        th {
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Reporte de Integridad de Tablas</h1>
    <p>Fecha: {{ date('Y-m-d') }}</p>

    <h2>Resumen Ejecutivo</h2>

    <ul>
        <li>Se encontraron <strong>{{ count($tableData) }}</strong> errores en el campo <strong>{{ $TareaCampo->campo }} en la tabla {{ $TareaCampo->tabla }} </strong></li>

    </ul>

    <div class="container mt-5">
        <h1 class="mb-4">Tabla: {{ $tableName }}</h1>
    
        @if(count($tableData) > 0)
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        @foreach($columns as $column)
                        <th>{{ $column->Field }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($tableData as $row)
                    <tr>
                        @foreach($columns as $column)
                        <td>{{ $row->{$column->Field} }}</td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="alert alert-info" role="alert">
            No hay datos en la tabla {{ $tableName }}
        </div>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>Campo</th>
                <th>Tipo de Dato</th>
                <th>Reglas de Validación</th>
                {{-- <th>Errores Encontrados</th> --}}
                {{-- <th>Causas Raíz</th> --}}
                <th>Recomendaciones</th>
            </tr>
        </thead>
        <tbody>
            {{-- @foreach ($errores_por_campo as $campo => $errores) --}}
                <tr>
                    <td>{{ $TareaCampo->campo }}</td>
                    <td>{{ $TareaCampo->tipoValidar}}</td>
                    <td>{{ $TareaCampo->condicion }}.{{$TareaCampo->condicion_text }}</td>
                    {{-- <td>
                        <ul>
                            @foreach ($errores as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </td> --}}
                    {{-- <td>
                        <ul>
                            @foreach ($causas_raiz[$campo] as $causa)
                                <li>{{ $causa }}</li>
                            @endforeach
                        </ul>
                    </td> --}}
                    <td>
                        <ul>
                            {{-- @foreach ($recomendaciones[$campo] as $recomendacion)
                                <li>{{ $recomendacion }}</li>
                            @endforeach --}}
                            El campo {{$TareaCampo->campo }} debe de ser {{$TareaCampo->tipoValidar}} además {{ $TareaCampo->condicion }}.{{$TareaCampo->condicion_text }} 
                            @if ($TareaCampo->null==0)
                            y no debe ser nulo
                                
                            @endif
                        </ul>
                    </td>
                </tr>
            {{-- @endforeach --}}
        </tbody>
    </table>

    {{-- <h2>Análisis Detallado</h2>

    <p>A continuación se presenta un análisis detallado de la integridad de cada campo:</p>

    

    <h2>Visualizaciones</h2>

    <ul>
        <li><a href="{{ url('reporte-integridad/grafico-barras') }}">Gráfico de Barras: Distribución de errores por tipo de error</a></li>
        <li><a href="{{ url('reporte-integridad/grafico-pastel') }}">Gráfico de Pastel: Distribución de errores por campo</a></li>
        <li><a href="{{ url('reporte-integridad/mapa-calor') }}">Mapa de Calor: Visualización de la cantidad de errores por campo en una tabla</a></li>
    </ul>

    <h2>Conclusiones</h2>

    <p>En base al análisis realizado, se concluye que:</p>

    <ul>
        <li>Se encontraron {{ $total_errores }} errores en {{ $total_campos }} campos.</li>
        <li>Los tipos de errores más comunes son {{ $tipo_error_1 }}, {{ $tipo_error_2 }} y {{ $tipo_error_3 }}</li>
        <li>Los campos con mayor cantidad de errores son {{ $campo_1 }}, {{ $campo_2 }} y {{ $campo_3 }}</li>
    </ul>

    <p>Se recomienda implementar medidas para corregir los errores encontrados y realizar análisis de integridad de datos de forma regular.</p>

    <h2>Apéndices</h2>

    <ul>
        <li><a href="{{ url('reporte-integridad/diccionario-errores') }}">Diccionario de Errores</a></li>
        <li><a href="{{ url('reporte-integridad/detalles-tecnicos') }}">Detalles --}}
