@extends('layout.layout')

@section('title', 'Formulario de Conexión')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://kit.fontawesome.com/7920972db5.js" crossorigin="anonymous"></script>

    <style>
        .bold-label {
            font-weight: bold;
            font-family: 'Arial', sans-serif; /* Cambiar a la fuente deseada */
        }
    </style>

    <div class="container mt-5">

        <br>
        @if (session('message'))
            <div class="alert alert-danger">
                {{ session('message') }}
            </div>
        @endif
        <div class="container-fluid py-4">
            <div class="row justify-content-center">
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h4 class="text-muted">Integridad de tablas</h4>
                        </div>
                        <div class="body">
                            <form action="{{ route('integridadtablas.store') }}" method="POST">
                                @csrf
                                <div class="card-body">
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="tabla1select" class="form-label bold-label">Tabla a Evaluar</label>
                                                <select class="form-select" id="tabla1select" name="nameTabla">
                                                    <option selected disabled>Seleccionar</option>
                                                    @foreach ($tableNames as $item)
                                                        <option value="{{ $item }}">{{ $item }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="tabla2Select" class="form-label bold-label">Tabla Referenciada</label>
                                                <select class="form-select" id="tabla2Select" name="nameTablaRef">
                                                    <option selected disabled>Seleccionar</option>
                                                    @foreach ($tableNames as $item)
                                                        <option value="{{ $item }}">{{ $item }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="column1select" class="form-label bold-label">Clave Foranea</label>
                                                <select class="form-select" id="column1select" name="nameClaveForanea">
                                                    <option selected disabled>Seleccionar</option>
                                                    @foreach ($colForeignKeys[$tableNames[0]] as $item)
                                                        <option value="{{ $item }}">{{ $item }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="column2Select" class="form-label bold-label">Clave Primaria</label>
                                                <select class="form-select" id="column2Select" name="nameClavePrimary">
                                                    <option selected disabled>Seleccionar</option>
                                                    @foreach ($colPrimaryKeys[$tableNames[0]] as $item)
                                                        <option value="{{ $item }}">{{ $item }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-primary d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary btn-lg">Guardar <i class="fas fa-save ms-2"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const colForeignKeys = <?php echo json_encode($colForeignKeys); ?>;

        document.getElementById("tabla1select").addEventListener("change", function() {
            // Get the selected value from the first select
            const selectedValue = this.value;

            // Get a reference to the second select
            const column1select = document.getElementById("column1select");

            // Clear any existing options in the second select
            column1select.innerHTML = "";

            // Create new options based on the selected value
            if (selectedValue in colForeignKeys) {
                for (const item of colForeignKeys[selectedValue]) {
                    const option = document.createElement("option");
                    option.value = item; // Assuming you want the item as the value
                    option.text = item;
                    column1select.add(option);
                }
            } else {
                // Handle the case where there are no options for the selected value
                const option = document.createElement("option");
                option.disabled = true;
                option.text = "No options available";
                column1select.add(option);
            }
            column1select.dispatchEvent(new Event('change'));
        });

        const colPrimaryKeys = <?php echo json_encode($colPrimaryKeys); ?>;

        document.getElementById("tabla2Select").addEventListener("change", function() {
            // Get the selected value from the first select
            const selectedValue = this.value;

            // Get a reference to the second select
            const column2Select = document.getElementById("column2Select");

            // Clear any existing options in the second select
            column2Select.innerHTML = "";

            // Create new options based on the selected value
            if (selectedValue in colPrimaryKeys) {
                for (const item of colPrimaryKeys[selectedValue]) {
                    const option = document.createElement("option");
                    option.value = item; // Assuming you want the item as the value
                    option.text = item;
                    column2Select.add(option);
                }
            } else {
                // Handle the case where there are no options for the selected value
                const option = document.createElement("option");
                option.disabled = true;
                option.text = "No options available";
                column2Select.add(option);
            }
            column2Select.dispatchEvent(new Event('change'));
        });
    </script>
@endsection
