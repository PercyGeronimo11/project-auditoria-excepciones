@extends('layout.layout')

@section('title', 'Ejecutar Consulta')

@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <strong class="card-title">Ejecutar consulta SQL</strong>
                    </div>
                    <div class="card-body">
                        <div id="pay-invoice">
                            <div class="card-body">
                                <div class="card-title">
                                    <h3 class="text-center">Script</h3>
                                </div>
        <form action="{{ route('execute.query') }}" method="POST">
            @csrf
            <div class="form-group">

                <div class="mb-3">
                    <label class="form-control-label" style="font-size: 12px; font-weight: bold;">Nombre:</label>
                    <div class="input-group">
                        <div class="input-group-addon" style="font-size: 12px;"><i class="fa fa-font"></i></div>
                        <input type="text" name="nombre" id="nombre" style="font-size: 12px; width: 150px;"> <!-- Modificar el tamaño del input -->
                    </div>
                </div>

                <div class="input-group line-numbered-textarea">
                    <textarea class="form-control resizable-textarea" id="query" name="query" rows="5" style="font-size: 12px;" required>{{ old('query') }}</textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-play"></i></button>
            <button type="button" class="btn btn-primary btn-sm" id="guardarConsulta"><i class="fa fa-save"></i></button>

        </form>
    </div>
</div>
      </div>
    </div>
   </div>
  </div>


        @isset($errorMessage)
            <div class="sufee-alert alert with-close alert-danger alert-dismissible fade show">
                <span class="badge badge-pill badge-danger">Error</span>
                {{ $errorMessage }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endisset




        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card">
                    @isset($results)
                        <div class="card-header">
                            <strong class="card-title">Resultados de la Consulta:</strong>
                        </div>
                        @if ($results)
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" style="background-color: #f8f9fa; font-size: 12px;"> <!-- Agregué el tamaño de la fuente y algunos estilos adicionales -->
                                    <thead style="background-color: #e9ecef;">
                                        <tr>
                                            @foreach ($results[0] as $key => $value)
                                                <th>{{ $key }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($results as $result)
                                            <tr>
                                                @foreach ($result as $value)
                                                    <td>{{ $value }}</td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-warning" role="alert">
                                No se encontraron resultados para la consulta.
                            </div>
                        @endif
                    @endisset
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var queryTextArea = document.getElementById("query");
            var lineNumberContainer = document.createElement("div");
            lineNumberContainer.className = "line-number-container";
            queryTextArea.parentNode.insertBefore(lineNumberContainer, queryTextArea);
    
            queryTextArea.addEventListener("input", function(event) {
                var lines = queryTextArea.value.split("\n");
                lineNumberContainer.innerHTML = "";
                lines.forEach(function(line, index) {
                    var lineNumber = document.createElement("div");
                    lineNumber.textContent = index + 1;
                    lineNumberContainer.appendChild(lineNumber);
                });
            });
    
            queryTextArea.addEventListener("keydown", function(event) {
                if (event.key === "Enter") {
                    var currentScroll = queryTextArea.scrollTop;
                    queryTextArea.scrollTop = currentScroll + queryTextArea.clientHeight;
                }
            });
    
            // Obtener el formulario y el input del query
            var queryForm = document.querySelector('form[action="{{ route('execute.query') }}"]');
            var queryInput = document.getElementById("query");
    
            // Escuchar el evento de envío del formulario
            queryForm.addEventListener("submit", function(event) {
                // Guardar el valor del input en el almacenamiento local
                localStorage.setItem("savedQuery", queryInput.value);
                if (!queryInput.value.trim()) {
                event.preventDefault();
                alert("El campo de consulta está vacío. Por favor, ingresa una consulta antes de ejecutar.");
              }
                

            });
    
            var savedQuery = localStorage.getItem("savedQuery");
            if (savedQuery) {
                queryInput.value = savedQuery;
            }
        });
    
       document.getElementById("guardarConsulta").addEventListener("click", function(event) {
        event.preventDefault(); 
    var query = document.getElementById("query").value;
    var nombre = document.getElementById("nombre").value.replace(/^\s+/, '');; 
    
      if (nombre=="") {
        alert("Por favor, ingresa un nombre.");
            return;
         }

    fetch("{{ route('execute.query') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ 
            query: query, 
            guardarConsulta: true,
            nombre: nombre // Incluir el nombre de la consulta en el cuerpo de la solicitud
        })
    })
.then(response => {
    if (!response.ok) {
        throw new Error("Error al guardar la consulta");
    }
    return response.json();
})
.then(data => {
    if (data.message) {
        alert(data.message); // Mostrar mensaje de éxito o información
    } else {
        alert("Error al guardar la consulta");
    }
})
.catch(error => {
    alert("Ingresa la consulta ");
});


});

    </script>



@endsection
