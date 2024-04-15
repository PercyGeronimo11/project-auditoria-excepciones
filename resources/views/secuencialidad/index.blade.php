
@extends('layout.layout')

@section('title', 'Secuencialidad')

@section('content')
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <style>
    .error-message {
        display: block;
        color: red;
        font-size: 14px;
        margin-top: 5px;
        font-weight: 500;
    }   
  </style>
  <div class="container mt-5">
    <div class="row">
      <form action="{{ route('secuencialidad.store')}}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="mb-3 row">
              <label for="inputPassword" class="col-sm-2 col-form-label">Tabla</label>
              <select class="form-select select2" aria-label="Default select example" id="firstSelect" name="tabla" required> 
                  <option selected disabled>Selecciona una tabla</option>
                  @foreach ($tableNames as $item)
                      <option value="{{$item}}">{{$item}}</option>
                  @endforeach
              </select>
              @error('tabla')
                <span class="error-message">{{ $message }}</span>
              @enderror
          </div>
          <div class="mb-3 row">
              <label for="inputPassword" class="col-sm-2 col-form-label">Campo</label>
              <select class="form-select" aria-label="Default select example" id="secondSelect" name="campo" required>
                  <option selected disabled>Selecciona un campo</option>
                  {{-- @foreach ($columnas[$tableNames[0]] as $item)
                      <option value="{{$item}}">{{$item}}</option>
                  @endforeach --}}
              </select>
              @error('campo')
                <span class="error-message">{{ $message }}</span>
              @enderror
          </div>
          <div class="mb-3 row">
            <label for="tipo_secuencia" class="col-sm-2 col-form-label">Tipo de secuencia:</label>
            <select class="form-select" aria-label="Default select example" id="tipo_secuencia" name="tipo_secuencia" id="tipo_secuencia">
                <option value="" selected disabled>Selecciona un tipo de secuencia...</option>
                {{-- <option value="numerica">Numérica</option>
                <option value="alfanumerica">Alfanumérica</option>
                <option value="fecha">Fecha</option>
                <option value="hora">Hora</option> --}}
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
              <option value="ascendente" selected>Ascendente</option>
              <option value="descendente">Descendente</option>
            </select>
          </div>
          <div class="mb-3 row" id="div-incremento">
            <label for="incremento" class="form-label">Incremento</label>
            <input type="number" class="form-control" id="incremento" name="incremento" aria-describedby="emailHelp" value="1">
          </div>
          <button type="submit" class="btn btn-primary">ANALIZAR</button>
      </form>   
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
  <script>
    const columnas = <?php echo json_encode($columnas); ?>;
    const tipos = <?php echo json_encode($tipos); ?>;
    document.getElementById("firstSelect").addEventListener("change", function() {
        // Obtener el valor seleccionado del primer select
        const selectedValue = this.value;

        // Get a reference to the second select
        const secondSelect = document.getElementById("secondSelect");
        secondSelect.options.length = 1;
        // Clear any existing options in the second select
        //secondSelect.innerHTML = "";

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
document.getElementById("secondSelect").addEventListener("change", function() {
    const selectedColumn = this.value;
    const selectedTable = document.getElementById("firstSelect").value;
    const tipoSecuenciaSelect = document.getElementById("tipo_secuencia");

    tipoSecuenciaSelect.innerHTML = "";

    // Obtener los tipos de columna de la tabla seleccionada
    const tiposColumna = tipos[selectedTable];

    // Buscar el índice de la columna seleccionada en el array de columnas de la tabla
    const columnIndex = columnas[selectedTable].indexOf(selectedColumn);
    console.log(tiposColumna);
    // Verificar si la columna seleccionada existe en las columnas de la tabla
    if (columnIndex !== -1) {
        // Si la columna existe, obtener el tipo de columna correspondiente
        const tipoColumna = tiposColumna[columnIndex];
        let tipoSecuencia = "";
        // Crear opciones según el tipo de columna
        if (tipoColumna.toLowerCase().includes("char") || tipoColumna.toLowerCase().includes("varchar")) {
            tipoSecuencia = "Alfanumérica";
        } else if (tipoColumna.toLowerCase() === "date") {
            tipoSecuencia = "Fecha";
        } else if (tipoColumna.toLowerCase() === "datetime") {
            tipoSecuencia = "Fecha";
        } else if (tipoColumna.toLowerCase() === "time") {
            tipoSecuencia = "Hora";
        } else {
            tipoSecuencia = "Numérica";
        }
        addOption(tipoSecuencia);
    } else {
        // Si la columna seleccionada no existe, mostrar un mensaje de error o manejar la situación según sea necesario
        console.error("La columna seleccionada no existe en la tabla seleccionada.");
    }

    // Función para agregar una opción al select de tipo de secuencia
    function addOption(text) {
        const option = document.createElement("option");
        option.value = text;
        if(text == "Alfanumérica"){
            option.text = "Numérica o "+text;
        }else{
            option.text = text;
        }
        
        tipoSecuenciaSelect.add(option);
    }
});
document.getElementById("secondSelect").addEventListener("change", function() {
    const incrementoInput = document.getElementById("div-incremento");
    const tipoSecuencia = document.getElementById("tipo_secuencia").value;
    console.log(tipoSecuencia);
    // Habilitar o deshabilitar el campo de incremento según el tipo de secuencia seleccionado
    if (tipoSecuencia === "Alfanumérica" || tipoSecuencia === "Numérica") {
        incrementoInput.style.display = "block";
    } else {
        incrementoInput.style.display = "none";
    }
});

  </script>
@endsection