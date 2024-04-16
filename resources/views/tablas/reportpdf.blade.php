<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://kit.fontawesome.com/7920972db5.js" crossorigin="anonymous"></script>


<div class="container mt-5">
    <h2 class="mb-4"> <b> INTEGRIDAD DE TABLAS: EXCEPCIONES ENCONTRADAS </b></h2>
    <br>
    <table>
        <thead>
          <tr>
            <th colspan="4" style="text-align: center; background-color: #dcdcdc; color: #333; font-size: 18px;">INFORMACIÓN GENERAL DE ANÁLISIS</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th scope="col" width="25%">Gestor de Base de Datos:</th>
            <td width="25%">{{ $integridad['type_bd'] }}</td>
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
            <h2>Se encontro {{ $numExcepciones }} excepciones</h2>
        </div>

        <table class="table table-striped table-danger">
            <thead style="background-color: red; color: white;">
                <tr>
                    <th scope="col">N°</th>
                    <th scope="col">Tabla</th>
                    <th scope="col">Clave Foranea</th>
                    <th scope="col">Tabla Referenciada</th>
                    <th scope="col">Excepcion</th>
                </tr>
            </thead>
            <tbody>
                @if (count($listExceptions) > 0)
                    @php $index = 1; @endphp
                    @foreach ($listExceptions as $exceptionKey => $exceptionValue)
                        <tr>
                            <th scope="row">{{ $index }}</th>
                            <td>{{ $tableNameSelect }}</td>
                            <td>{{ $exceptionKey }}</td>
                            <td>{{ $tableRefNameSelect }}</td>
                            <td>{{ $exceptionValue }}</td>
                        </tr>
                        @php $index++; @endphp
                    @endforeach
                @else
                    <tr>
                        <td colspan="7"> No Se encontraron excepciones</td>
                    </tr>

                @endif
            </tbody>
        </table>

    </div>

</div>
