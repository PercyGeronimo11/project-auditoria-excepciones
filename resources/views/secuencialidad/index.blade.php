
@extends('layout.layout')

@section('title', 'Secuencialidad')

@section('content')
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  <div class="container mt-5">
    <div class="row">
      <form action="{{ route('secuencialidad.store')}}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="mb-3 row">
              <label for="inputPassword" class="col-sm-2 col-form-label">Tabla</label>
              <select class="form-select" aria-label="Default select example" id="firstSelect" name="tabla"> 
                  <option selected disabled>Selecciona una tabla</option>
                  @foreach ($tableNames as $item)
                      <option value="{{$item}}">{{$item}}</option>
                  @endforeach
              </select>
          </div>
          <div class="mb-3 row">
              <label for="inputPassword" class="col-sm-2 col-form-label">Campo</label>
              <select class="form-select" aria-label="Default select example" id="secondSelect" name="campo">
                  <option selected disabled>Selecciona un campo</option>
                  @foreach ($columnas[$tableNames[0]] as $item)
                      <option value="{{$item}}">{{$item}}</option>
                  @endforeach
              </select>
          </div>
          <div class="mb-3 row">
            <label for="tipo_secuencia" class="col-sm-2 col-form-label">Tipo de secuencia:</label>
            <select class="form-select" aria-label="Default select example" id="tipo_secuencia" name="tipo_secuencia" id="tipo_secuencia">
                <option value="" selected disabled>Selecciona un tipo de secuencia...</option>
                <option value="numerica">Numérica</option>
                <option value="alfanumerica">Alfanumérica</option>
                <option value="fecha">Fecha</option>
                <option value="hora">Hora</option>
            </select>
          </div>
          <div id="alfanumericaInput" class="mb-3 row" style="display: none;">
            <label for="secuencia_alfabetica" class="col-form-label">¿Seguir un orden tambien en la parte alfabetica?</label>
            <select class="form-select" aria-label="Default select example" id="secuencia_alfabetica" name="secuencia_alfabetica">
                <option value="si">Sí</option>
                <option value="no">No</option>
            </select>
          </div>
          <div class="mb-3 row">
            <label for="orden_secuencia" class="col-sm-2 col-form-label">Orden de clasificación:</label>
            <select class="form-select" aria-label="Default select example" id="thirdSelect2" name="orden_secuencia" id="orden_secuencia">
              <option value="" selected disabled>Selecciona un orden de secuencia...</option>
              <option value="ascendente">Ascendente</option>
              <option value="descendente">Descendente</option>
            </select>
          </div>
          <div class="mb-3 row">
            <label for="incremento" class="form-label">Incremento</label>
            <input type="number" class="form-control" id="incremento" name="incremento" aria-describedby="emailHelp" value="1">
          </div>{{-- 
          <div class="mb-3 row">
            <label for="valor_inicial_esperado" class="form-label">Valor inicial esperado:</label>
            <input type="number" class="form-control" id="valor_inicial_esperado" name="valor_inicial_esperado" aria-describedby="emailHelp">
          </div>
          <div class="mb-3 row">
            <label for="rango_valores" class="form-label">Rango de valores esperado:</label>
            <input type="text" class="form-control" id="rango_valores" name="rango_valores" aria-describedby="emailHelp">
          </div> --}}
          {{-- <div class=" row mb-3 condiciones d-flex">
              <label for="inputPassword" class="col-sm-2 col-form-label">Condición</label>
              <div class="input-group mb-3">
                  <select class="form-select" aria-label="Default select example" id="thirdSelect" name="condicion">
                      <option value="">Selecciona una condicion..</option>
                      <optgroup label="Numéricos">
                          <option value=">">></option>
                          <option value="<"><</option>
                      </optgroup>
                      <optgroup label="Cadenas de caracteres">
                          <option value="like">like</option>
                          <option value="in">in(Solo esos valores)</option>
                      </optgroup>
                      <optgroup label="Otro">
                          <option value="other">Avanzado(SQL)</option>
                      </optgroup>
                  </select>
                  <input type="text" class="form-control" aria-label="Text input with dropdown button" name="condicion_text">
                  <input type="text" class="form-control" aria-label="Text input with dropdown button" name="condicion_text[0]">
                  <button type="button" class="btn btn-primary add-condition-btn">+</button><br>
              </div>
          </div> --}}
          <button type="submit" class="btn btn-primary">analizar</button>
      </form>   
    </div>
  </div>

  <script>
    const columnas = <?php echo json_encode($columnas); ?>;
    document.getElementById("firstSelect").addEventListener("change", function() {
        // Obtener el valor seleccionado del primer select
        const selectedValue = this.value;

        // Get a reference to the second select
        const secondSelect = document.getElementById("secondSelect");

        // Clear any existing options in the second select
        secondSelect.innerHTML = "";

        // Create new options based on the selected value
        if (selectedValue in columnas) {
            for (const item of columnas[selectedValue]) {
                const option = document.createElement("option");
                option.value = item; // Assuming you want the item as the value
                option.text = item;
                secondSelect.add(option);
            }
        } else {
            // Handle the case where there are no options for the selected value
            const option = document.createElement("option");
            option.disabled = true;
            option.text = "No options available";
            secondSelect.add(option);
        }
    });

  </script>
@endsection