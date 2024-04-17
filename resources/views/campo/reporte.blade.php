<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Integridad de Campos</title>
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
    
        table, th, td {
          border: 1px solid #ddd;
        }
    
        th, td {
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
    <div class="container">
        <h1>INFORME DE INTEGRIDAD DE CAMPOS</h1>
        <table>
          <thead>
            <tr>
              <th colspan="4" style="text-align: center; background-color: #dcdcdc; color: #333; font-size: 18px;">INFORMACIÓN GENERAL DE ANÁLISIS</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th scope="col" width="25%">Gestor de Base de Datos:</th>
              <td width="25%">{{$TareaCampo->bdManager}}</td>
              <th scope="col">Base de Datos:</th>
              <td>{{ $TareaCampo->baseDatos}}</td>
            </tr>
            <tr>
              <th scope="col" width="25%">Tabla:</th>
              <td width="25%">{{ $TareaCampo->tabla}}</td>
              <th scope="col">Campo:</th>
              <td>{{ $TareaCampo->campo}}</td>
            </tr>
            <tr>
              <th scope="col">Tipo:</th>
              <td>{{ $TareaCampo->tipoValidar }}</td>
              <th scope="col">Regla de validación:</th>
              <td>{{ $TareaCampo->condicion }}.{{$TareaCampo->condicion_text }}</td>
            </tr>
            <tr>
              <th scope="col">Usuario:</th>
              <td>{{ $TareaCampo->user}}</td>
              <th scope="col">Fecha de Análisis:</th>
              <td>{{ date('Y-m-d', strtotime( $TareaCampo->fecha)) }}</td>
            </tr>
          </tbody>
        </table>
        <br>




    <h1>Reporte de Integridad de Campos</h1>

    <h2>Resumen Ejecutivo</h2>

    <ul>
        <li>Se encontraron <strong>{{ count($tableData) }}</strong> errores en el campo <strong>{{ $TareaCampo->campo }} en la tabla {{ $TareaCampo->tabla }} </strong></li>


        {{-- <li>Los tipos de errores más comunes son:</li>
            <ul>
                @foreach ($errores_por_tipo as $tipo => $cantidad)
                    <li>{{ $tipo }} ({{ $cantidad }})</li>
                @endforeach
            </ul>
        <li>Los campos con mayor cantidad de errores son:</li>
            <ul>
                @foreach ($campos_con_errores as $campo => $cantidad)
                    <li>{{ $campo }} ({{ $cantidad }})</li>
                @endforeach
            </ul> --}}
    </ul>



    <table>
        <thead>
          <tr>
            <th colspan="2" style="text-align: center; background-color: #dcdcdc; color: #333; font-size: 18px;">CUADRO RESUMEN</th>
          </tr>
        </thead>
        <tbody>
          <tr>
              <th scope="col">Condicion</th>
              <td>
                Hay {{ count($tableData) }} exepciones  el campo <strong>{{ $TareaCampo->campo }} en la tabla {{ $TareaCampo->tabla }} 
                 
            
              </td>
          </tr>
          <tr>
              <th scope="col">Criterio</th>
              <td>
                ISO 27001 A.7.1.6 - Gestión de la configuración de la base de datos,Este control exige la implementación de un proceso formal para controlar los cambios en la configuración de la base de datos, incluyendo la definición de valores segun las necesidades. 
              </td>
          </tr>
         <tr>
              <th scope="col">Efecto</th>
              <td>
                Mayor riesgo de errores en la manipulación de datos, especialmente en sistemas donde el tipo de dato sea importante como los documentos de identidad,etc.
              </td>
          </tr>
          <tr>
              <th scope="col">Causa</th>
              <td>
                  Mala definicion de los datos en la base de datos , y falta de validez de inserción de datos en el campo {{ $TareaCampo->campo }}
              </td>
          </tr> 
          <tr>
            <th scope="col">Recomendacion</th>
            <td>
                - Revisar y corregir la definición de los datos en el campo {{ $TareaCampo->campo }} de la tabla {{ $TareaCampo->tabla }} para garantizar que cumple con el tipo de dato esperado y las restricciones necesarias.<br>- Implementar mecanismos de validación de datos en la aplicación para evitar que se ingrese información incorrecta en el campo {{ $TareaCampo->campo }}.
                Verificar que los valores del campo {{$TareaCampo->campo }} debe de ser de tipo {{$TareaCampo->tipoValidar}} además {{ $TareaCampo->condicion }}.{{$TareaCampo->condicion_text }} 
                @if ($TareaCampo->null==0)
                y no debe ser nulo
                    
                @endif
            </td>
        </tr> 


      </tbody>
      
      </table>


    <div class="container mt-5">
        <h1 class="mb-4">Exepciones en la tabla: {{ $tableName }}</h1>
    
        @if(count($tableData) > 0)
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        @foreach($columns as $column)
                        @if (isset($column->Field))
                            <th>{{ $column->Field }}</th>
                        @else
                            <th>{{ $column["name"]}}</th>
                        @endif
                     
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($tableData as $row)
                    <tr>
                        @foreach($columns as $column)
                        @if (isset($column->Field))
                        <td>{{ $row->{$column->Field} }}</td>
                        @else
                        <td>{{ $row->{$column["name"]} }}</td>
                        @endif
                   
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
