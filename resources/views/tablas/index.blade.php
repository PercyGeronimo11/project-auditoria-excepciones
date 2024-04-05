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
                    <input type="text" placeholder="Buscar por tabla" class="form-control" value="" name="buscarpor">
                </div>
            </form>
        </div>
        <br>
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
            <label for="inputPassword" class="col-sm-2 col-form-label">Campo</label>
            <select class="form-select" aria-label="Default select example" id="secondSelect" name="campo">
                <option selected disabled>Open this select menu</option>
                @foreach ($colForeignKeys[$tableNames[0]] as $item)
                    <option value={{$item}}>{{$item}}</option>
                @endforeach
            </select>
        </div>
    </div>

    <script>

        document.getElementById("fiveselect").addEventListener("change", function(){
          const valor = this.value;
          console.log("gola");
          const boton = document.getElementById("boton-ad");
          if(valor=="between"){
            boton.click();
          }
          else if(valor=="in"){
            boton.style.display="block";
          }
          else{
            var elements = document.getElementsByName("condicion_text[]");
            for (var i = elements.length - 1; i >= 0; i--) {
              elements[i].parentNode.removeChild(elements[i]);
            }
            boton.style.display="none";
          }
        });
        

          document.getElementById("fourthSelect").addEventListener("change", function(){
          const valor = this.value;
          console.log("ddd");
          longitud = document.getElementById("longitud")
          // const boton = document.getElementById("boton-ad");
         if(valor=="date"|| valor=="time"){
          longitud.style.display="none";
          }
          else{
            // var elements = document.getElementsByName("condicion_text[]");
            // for (var i = elements.length - 1; i >= 0; i--) {
            //   elements[i].parentNode.removeChild(elements[i]);
            // }
            longitud.style.display="block";
          }
          });
        
        
        
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
          // secondSelect.options[0].selected = true;
          console.log("caimos")
          });
        
          document.getElementById("secondSelect").addEventListener("change", function() {
            // Obtener el valor seleccionado del primer select
            const selectedIndex = this.selectedIndex;
            const selectedValue = document.getElementById("firstSelect").value;
            // Obtener una referencia al segundo select
            const secondSelect = document.getElementById("thirdSelect");
        
            // Limpiar cualquier opción existente en el segundo select
            secondSelect.innerHTML = "";
        
            // Crear nuevas opciones basadas en el valor seleccionado
            const option = document.createElement("option");
          
            option.selected = true;
            secondSelect.add(option);
            console.log("caimossssssss");
            secondSelect.dispatchEvent(new Event('change'));
            // Seleccionar la primera opción en el segundo select
            // secondSelect.options[0].selected = true;
        });
        
        // document.getElementById("thirdSelect").addEventListener("change", function(){
        //     const valor = this.value;
        
        //     var resultado = valor.split('(')[0];
        //     if(resultado=="float"||resultado=="double"){
        //       resultado="decimal";
        //     }
        //     if (resultado.includes("int")) {
        //       resultado="int";
        //     }
        //     if (resultado.includes("char")) {
        //       resultado="varchar";
        //     }
        
        //     selectElement = document.getElementById("fourthSelect");
        
        //     if(resultado=="varchar"){
        //     var options = selectElement.getElementsByTagName("option");
        //     for(var i = 0; i < options.length; i++) {
        //       options[i].disabled = false;
        //       option[i].selected = true;
        //     }
        //     }
        //     else{
        //       for (let i = 0; i < selectElement.options.length; i++) {
        //       const option = selectElement.options[i];
        //         console.log()
        //       // Verificar si el valor de la opción coincide con el valor deseado
        //         if (option.value !== resultado) {
        //           // Deshabilitar la opción
        //           option.disabled = true;
        //           // option.style.display = "none";
        //         }
        //         else{
        //           option.disabled = false;
        //           option.selected = true;
        //           // option.style.display = "block";
        //         }
        //       }
        //     }
        //     selectElement.dispatchEvent(new Event('change'));
        //   });
        
          const addConditionButton = document.querySelector('.add-condition-btn');
        
        addConditionButton.addEventListener('click', function() {
          const newConditionText = document.createElement('input'); // Create a new input element
          newConditionText.setAttribute('type', 'text'); // Set the input type to "text"
          newConditionText.setAttribute('class', 'form-control'); // Set the input class
          newConditionText.setAttribute('aria-label', 'Text input with dropdown button'); // Set the aria-label
          newConditionText.setAttribute('name', 'condicion_text[]'); // Set the input name
        
          newConditionText.setAttribute('style', 'margin-left:50%; width:50%;'); // Set the input name
          const conditionGroup = document.querySelector('.condiciones'); // Get the parent div
        conditionGroup.appendChild(newConditionText); // Append the clone to the parent div
        });
        
        </script>
@endsection
