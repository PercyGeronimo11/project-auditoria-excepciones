<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Informe de Secuencialidad</title>
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
    <h1>INFORME DE SECUENCIALIDAD</h1>
    <table>
      <thead>
        <tr>
          <th colspan="4" style="text-align: center; background-color: #dcdcdc; color: #333; font-size: 18px;">INFORMACIÓN GENERAL DE ANÁLISIS</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th scope="col" width="25%">Gestor de Base de Datos:</th>
          <td width="25%">{{ $dataGeneral['bdManager'] }}</td>
          <th scope="col">Base de Datos:</th>
          <td>{{ $dataGeneral['dbName'] }}</td>
        </tr>
        <tr>
          <th scope="col" width="25%">Tabla:</th>
          <td width="25%">{{ $dataGeneral['tableName'] }}</td>
          <th scope="col">Campo:</th>
          <td>{{ $dataGeneral['field'] }}</td>
        </tr>
        <tr>
          <th scope="col">Tipo de Secuencia:</th>
          <td>{{ $dataGeneral['sequenceType'] }}</td>
          <th scope="col">Orden de Secuencia:</th>
          <td>{{ $dataGeneral['sequenceOrder'] }}</td>
        </tr>
        <tr>
          <th scope="col">Usuario:</th>
          <td>{{ $dataGeneral['user'] }}</td>
          <th scope="col">Fecha de Análisis:</th>
          <td>{{ date('Y-m-d', strtotime($dataGeneral['created_at'])) }}</td>
        </tr>
      </tbody>
    </table>
    <br>
    @if (is_string($results))
            <div class="alert alert-success" role="alert">
              {{ $results }}, <b>NO SE ENCONTRARON EXCEPCIONES</b>
            </div>
    @else
      @if (isset($results[0]['error']))
        <h5 class="alert alert-danger text-center">{{ $results[0]['error'] }}</h5>
      @else
        <table>
          <thead>
            <tr>
              <th colspan="4" style="text-align: center; background-color: #dcdcdc; color: #333; font-size: 18px;">EXCEPCIONES ENCONTRADAS</th>
            </tr>
            <tr>
              <th scope="col">ID</th>{{-- 
              <th scope="col">Tabla</th>
              <th scope="col">Campo</th> --}}
              <th scope="col">Valor Anterior</th>
              <th scope="col">Valor Actual</th>
              <th scope="col">Excepción</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($results as $item)
            <tr>
              <td>{{ $item['id'] }}</td>{{-- 
              <td>{{ $item['tabla'] }}</td>
              <td>{{ $item['campo'] }}</td> --}}
              <td>{{ $item['anterior'] }}</td>
              <td>{{ $item['actual'] }}</td>
              <td>{{ $item['mensaje'] }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      @endif
    @endif
    
  </div>
</body>
</html>