
@extends('layout.layout')

@section('title', 'Formulario de Conexión')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<div class="container mt-5">
    <div class="row">
       
        
                    {{-- <h5 class="card-title">{{ $tableName }}</h5> --}}
                    <form action="
                            {{ route('tareacampo.store')}}" method="POST" enctype="multipart/form-data">
                            
                              @csrf
                            {{-- " method="GET"> --}}
                            {{-- <div class="mb-3 row">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Email</label>
                                <div class="col-sm-10">
                                  <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="email@example.com">
                                </div>
                            </div> --}}
                            <div class="mb-3 row">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Tabla</label>
                                <select class="form-select" aria-label="Default select example" id="firstSelect" name="tabla"> 
                                    <option selected disabled>Open this select menu</option>
                           
                                    @foreach ($tableNames as $item)
                                   
                                    <option value="{{$item}}">{{$item}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 row">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Campo</label>
                                <select class="form-select" aria-label="Default select example" id="secondSelect" name="campo">
                                    <option selected disabled>Open this select menu</option>
                                    @foreach ($columnas[$tableNames[0]] as $item)
                                        <option value={{$item}}>{{$item}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 row">
                              <label for="inputPassword" class="col-sm-2 col-form-label">Tipo</label>
                              <select class="form-select" aria-label="Default select example" id="thirdSelect" name="tipo">
                                  <option selected disabled>Open this select menu</option>
                                  @foreach ($tipos[$tableNames[0]] as $item)
                                      <option value={{$item}}>{{$item}}</option>
                                  @endforeach
                              </select>
                            </div>
                            <div class="mb-3 row">
                              <label for="inputPassword" class="col-sm-2 col-form-label">Tipo(Validar)-Longitud</label>
                              
                              <div class="input-group mb-3 " >
                                <select class="form-select" aria-label="Default select example" id="fourthSelect" name="tipoValidar">
                                  <option value="">Selecciona un tipo de dato...</option>
                                
                                  <optgroup label="Numéricos">
                                    <option value="int">INT (Entero)</option>
                                    {{-- <option value="bigint">BIGINT (Entero grande)</option>
                                    <option value="smallint">SMALLINT (Entero pequeño)</option>
                                    <option value="tinyint">TINYINT (Entero muy pequeño)</option> --}}
                                    <option value="decimal">DECIMAL (Decimal)</option>
                                    {{-- <option value="numeric">NUMERIC (Numérico)</option> --}}
                                  </optgroup>
                                
                                  <optgroup label="Cadenas de caracteres">
                                    {{-- <option value="char">CHAR (Cadena fija)</option> --}}
                                    <option value="varchar">VARCHAR (Cadena variable)</option>
                                    {{-- <option value="nchar">NCHAR (Cadena Unicode fija)</option>
                                    <option value="nvarchar">NVARCHAR (Cadena Unicode variable)</option> --}}
                                  </optgroup>
                                
                                  {{-- <optgroup label="Cadenas binarias">
                                    <option value="varbinary">VARBINARY (Binario variable)</option>
                                    <option value="image">IMAGE (Imagen)</option>
                                  </optgroup> --}}
                                
                                  <optgroup label="Fecha y hora">
                                    <option value="date">DATE (Fecha)</option>
                                    <option value="time">TIME (Hora)</option>
                                    {{-- <option value="datetime">DATETIME (Fecha y hora)</option>
                                    <option value="datetime2">DATETIME2 (Fecha y hora con mayor precisión)</option> --}}
                                  </optgroup>
                                
                                  {{-- <optgroup label="Otros tipos de datos">
                                    <option value="bit">BIT (Booleano)</option>
                                    <option value="uniqueidentifier">UNIQUEIDENTIFIER (Identificador único global)</option>
                                    <option value="money">MONEY (Moneda)</option>
                                    <option value="xml">XML (Datos XML)</option>
                                    <option value="table">TABLE (Tabla temporal)</option>
                                  </optgroup> --}}
                                </select>
                                  {{-- <input type="text" class="form-control" aria-label="Text input with dropdown button"  name="condicion_text"> --}}
                                  <input type="text" class="form-control" aria-label="Text input with dropdown button"  name="longitud" id ="longitud">
                                  {{-- <input type="text" class="form-control" aria-label="Text input with dropdown button" name="condicion_text"> --}}
                                  {{-- <button type="button" class="btn btn-primary add-condition-btn" id="boton-ad" style="display: none">+</button> <br> --}}
                                 
                              </div>
                            
                     
                            </div>
                 
                            
                            <div class=" row mb-3 condiciones d-flex">
                              
                              {{-- <label for="inputPassword" class="col-sm-2 col-form-label " id="condition-group">Condición</label> --}}
                              <label for="inputPassword" class="col-sm-2 col-form-label">Condición</label>
                              <div class="input-group mb-3 " >
                         
                                <select class="form-select" aria-label="Default select example" id="fiveselect" name="condicion">
                                  <option value="">Selecciona una condicion..</option>
                                
                                  <optgroup label="Numéricos">
                                    <option value=">">></option>
                                    {{-- <option value=">=">>=</option>
                                    <option value="<="><= </option> --}}
                                    <option value="<"><</option>
                                    <option value="between">entre</option>
                                    {{-- <option value="==">=</option>
                                    <option value="<>"><>(diferente)</option> --}}
                                  </optgroup>
                                
                                  <optgroup label="Cadenas de caracteres">
                                    <option value="like">like</option>
                                    <option value="in">in(Solo esos valores)</option>
                                  </optgroup>
  
                                  {{-- <optgroup label="Fecha y hora">
                                    <option value="date">DATE (Fecha)</option>
                                    <option value="time">TIME (Hora)</option>
                                  </optgroup> --}}



                                  {{-- <optgroup label="Otro">
                                    <option value="other">Avanzado(SQL)</option>
                                  </optgroup> --}}
  
                                </select>
                               
                                {{-- <input type="text" class="form-control" aria-label="Text input with dropdown button"  name="condicion_text"> --}}
                                <input type="text" class="form-control" aria-label="Text input with dropdown button"  name="condicion_text[0]">
                                {{-- <input type="text" class="form-control" aria-label="Text input with dropdown button" name="condicion_text"> --}}
                                <button type="button" class="btn btn-primary add-condition-btn" id="boton-ad" style="display: none">+</button> <br>
                              </div>
                             
                              
                              {{-- <input type="text" class="form-control align-self-end" style="float: right;width: 526px;"> --}}
                            </div>
                            {{-- <div style="width:50%;float:right !important;">
                              One of three columns
                            </div> --}}
                            
        
                            {{-- <div class="mb-3 row">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Password</label>
                                <div class="col-sm-10">
                                  <input type="password" class="form-control" id="inputPassword">
                                </div>
                            </div> --}}
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" value="0" id="flexCheckDefault" name="null">
                              <label class="form-check-label" for="flexCheckDefault"  >
                                No nulo
                              </label>
                            </div>
                            <br>
                        <button type="submit" class="btn btn-primary">analizar</button>
                    </form>
                   
                     
                 
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



    const columnas = <?php echo json_encode($columnas); ?>;
    
    const tipos = <?php echo json_encode($tipos); ?>;
  
  document.getElementById("firstSelect").addEventListener("change", function() {
  // Get the selected value from the first select
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
    option.value = tipos[selectedValue][selectedIndex]; // Suponiendo que deseas el elemento como el valor
    option.text = tipos[selectedValue][selectedIndex];
    option.selected = true;
    secondSelect.add(option);
    console.log("caimossssssss");
    secondSelect.dispatchEvent(new Event('change'));
    // Seleccionar la primera opción en el segundo select
    // secondSelect.options[0].selected = true;
});

document.getElementById("thirdSelect").addEventListener("change", function(){
    const valor = this.value;

    var resultado = valor.split('(')[0];
    if(resultado=="float"||resultado=="double"){
      resultado="decimal";
    }
    if (resultado.includes("int")) {
      resultado="int";
    }
    if (resultado.includes("char")) {
      resultado="varchar";
    }

    selectElement = document.getElementById("fourthSelect");

    if(resultado=="varchar"){
    var options = selectElement.getElementsByTagName("option");
    for(var i = 0; i < options.length; i++) {
      options[i].disabled = false;
      option[i].selected = true;
    }
    }
    else{
      for (let i = 0; i < selectElement.options.length; i++) {
      const option = selectElement.options[i];
        console.log()
      // Verificar si el valor de la opción coincide con el valor deseado
        if (option.value !== resultado) {
          // Deshabilitar la opción
          option.disabled = true;
          // option.style.display = "none";
        }
        else{
          option.disabled = false;
          option.selected = true;
          // option.style.display = "block";
        }
      }
    }
    selectElement.dispatchEvent(new Event('change'));
  });



    // if (selectedValue in tipos) {
    //   for (const item of tipos[selectedValue]) {
    //     const option = document.createElement("option");
    //     option.value = item; // Assuming you want the item as the value
    //     option.text = item;
    //     secondSelect.add(option);
    //   }
    // } else {
    //   // Handle the case where there are no options for the selected value
    //   const option = document.createElement("option");
    //   option.disabled = true;
    //   option.text = "No options available";
    //   secondSelect.add(option);
    // }
   

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




