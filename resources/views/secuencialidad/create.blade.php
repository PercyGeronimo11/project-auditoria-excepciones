@extends('layout.layout')

@section('title', 'Excepciones de secuencialidad')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<style>
    .error-message {
        display: block;
        color: red;
        font-size: 12px;
        margin-top: 5px;
        font-weight: 500;
    }
    .card {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 20px;
    }
    .card-title {
        font-size: 1.2rem;
        font-weight: bold;
        margin-bottom: 20px;
    }
    .form-label {
        font-weight: bold;
        font-size: 14px;
        font-family: 'Arial', sans-serif;
        letter-spacing: 1px; /* Ajusta el espaciado entre letras */
    }
    .form-select {
        font-size: 12px;
        margin-bottom: 10px;
        width: 100%;
    }
</style>
<div class="container mt-5">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="card">
                <h3 class="card-title">Excepciones de secuencialidad</h3>
                <form action="{{ route('secuencialidad.store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3 row">
                        <label for="inputPassword" class="col-sm-3 col-form-label" style="font-weight: bold; font-size: 12px; font-family: 'Arial', sans-serif; letter-spacing: 1px;">Tabla</label>
                        <select class="form-select select2" aria-label="Default select example" id="firstSelect" name="tabla" required>
                            <option selected disabled>Selecciona una tabla</option>
                            @foreach ($tableNames as $item)
                            <option value="{{$item}}" @if(isset($sequence_result) && $sequence_result->tableName == $item) selected @endif>{{$item}}</option>
                            @endforeach
                        </select>
                        @error('tabla')
                        <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3 row">
                        <label for="inputPassword" class="col-sm-3 col-form-label" style="font-weight: bold; font-size: 12px; font-family: 'Arial', sans-serif; letter-spacing: 1px;">Campo</label>
                        <select class="form-select" aria-label="Default select example" id="secondSelect" name="campo" required>
                            <option selected disabled>Selecciona un campo</option>
                            @if(isset($sequence_result))
                            @foreach ($columnas[$sequence_result->tableName] as $item)
                            <option value="{{$item}}" @if($sequence_result->field == $item) selected @endif>{{$item}}</option>
                            @endforeach
                            @endif
                        </select>
                        @error('campo')
                        <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3 row">
                        <label for="tipo_secuencia" class="col-sm-3 col-form-label" style="font-weight: bold; font-size: 12px; font-family: 'Arial', sans-serif; letter-spacing: 1px;">Secuencia:</label>
                        <select class="form-select" aria-label="Default select example" id="tipo_secuencia" name="tipo_secuencia" id="tipo_secuencia">
                            @if(isset($sequence_result))
                            <option value="" disabled>Selecciona un tipo de secuencia...</option>
                            <option selected value={{$sequence_result->sequenceType}}>{{$sequence_result->sequenceType}}</option>
                            @else
                                <option value="" selected disabled>Selecciona un tipo de secuencia...</option>
                            @endif
                            
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
                        <label for="orden_secuencia" class="col-sm-3 col-form-label" style="font-weight: bold; font-size: 12px; font-family: 'Arial', sans-serif; letter-spacing: 1px;">Orden:</label>
                        <select class="form-select" aria-label="Default select example" id="thirdSelect2" name="orden_secuencia" id="orden_secuencia">
                            <option value="ascendente" selected>Ascendente</option>
                            <option value="descendente">Descendente</option>
                        </select>
                    </div>
                    <div class="mb-3 row" id="div-incremento">
                        <label for="incremento" class="form-label">Incremento</label>
                        <input type="number" class="form-control" id="incremento" name="incremento" aria-describedby="emailHelp" value="1">
                    </div>
                    <button type="submit" class="btn btn-success">ANALIZAR</button>
                </form>
            </div>
        </div>
    </div>
</div>


<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.3.min.js"></script> 
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script>
    window.onload = function() {

// $(document).ready(function() {
//   $("#mySelect").select2();
// });

new TomSelect("#firstSelect",{
  create: true,
  sortField: {
      field: "text",

  }
});
/* new TomSelect("#secondSelect",{
  create: true,
  sortField: {
  field: "text"
  }
}); */
   
};
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
            if (text == "Alfanumérica") {
                option.text = "Numérica o " + text;
            } else {
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
