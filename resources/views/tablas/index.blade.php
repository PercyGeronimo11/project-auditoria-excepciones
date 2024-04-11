@extends('layout.layout')

@section('title', 'Formulario de Conexión')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://kit.fontawesome.com/7920972db5.js" crossorigin="anonymous"></script>

    <div class="container mt-5">
        <h1 class="mb-4">INTEGRIDAD DE TABLAS</h1>
        <br>
        <div id="search-nav">
            <form class="navbar-left navbar-form nav-search mr-md-3" method="GET" role="search">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <button type="submit" class="btn btn-search pr-1">
                            <i class="fa fa-search search-icon"></i>
                        </button>
                    </div>
                    <input type="text" placeholder="Buscar por tabla" class="form-control" value=""
                        name="buscarpor">
                </div>
            </form>
        </div>

        <form action="{{route('integridadtablas.analysis')}}">
            <div class="mb-3 row">
                <label for="inputPassword" class="col-sm-2 col-form-label">Tabla</label>
                <select class="form-select" aria-label="Default select example" id="firstSelect" name="tabla">
                    <option selected disabled>Seleccionar</option>
                    @foreach ($tableNames as $item)
                        <option value="{{ $item }}">{{ $item }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3 row">
                <label for="inputPassword" class="col-sm-2 col-form-label">Clave Foranea</label>
                <select class="form-select" aria-label="Default select example" id="secondSelect" name="claveForanea">
                    <option selected disabled>Seleccionar</option>
                    @foreach ($colForeignKeys[$tableNames[0]] as $item)
                        <option value={{ $item }}>{{ $item }}</option>
                    @endforeach
                </select>
            </div>
            <div>
              <button type="submit" class="button bg-warning">Analizar</button>
            </div>
        </form>
    </div>

    <script>
    
        const colForeignKeys = <?php echo json_encode($colForeignKeys); ?>;


        document.getElementById("firstSelect").addEventListener("change", function() {
            // Get the selected value from the first select
            const selectedValue = this.value;

            // Get a reference to the second select
            const secondSelect = document.getElementById("secondSelect");

            // Clear any existing options in the second select
            secondSelect.innerHTML = "";

            // Create new options based on the selected value
            if (selectedValue in colForeignKeys) {
                for (const item of colForeignKeys[selectedValue]) {
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
            secondSelect.dispatchEvent(new Event('change'));
        });

      
    </script>
@endsection
