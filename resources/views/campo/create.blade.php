
@extends('layout.layout')

@section('title', 'Formulario de Conexión')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


<div class="container mt-5">
  <div id="mensaje">
       
    @if (session("datos"))
    <div class="alert alert-warning alert-dismissible fade show mt-3 emergente" role="alert" style="color: white; background-color: rgb(183, 178, 31) ">
            {{session('datos')}}
    </div>
   
     @endif 
</div>
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
                                
                                <select class="form-select  @error('tabla') is-invalid @enderror select2" aria-label="Default select example" id="firstSelect" name="tabla"> 
                                    <option selected disabled>Open this select menu</option>
                           
                                    @foreach (array_keys(session()->get('tablesName')) as $item)
                                   
                                    <option value="{{$item}}">{{$item}}</option>
                                    @endforeach
                                </select>
                                @error('tabla')
                            <span class="invalid feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                            </div>
                            <div class="mb-3 row">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Campo</label>
                                <select class="form-select @error('campo') is-invalid @enderror  select2" aria-label="Default select example" id="secondSelect" name="campo">
                                    <option selected disabled>Open this select menu</option>
                                    @foreach ($columnas[$tableNames[0]] as $item)
                                        <option value={{$item}}>{{$item}}</option>
                                    @endforeach
                                </select>
                                @error('campo')
                                <span class="invalid feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            </div>
                            <div class="mb-3 row" style="display: none">
                              <label for="inputPassword" class="col-sm-2 col-form-label">Tipo</label>
                              <select class="form-select @error('tipo') is-invalid @enderror" aria-label="Default select example" id="thirdSelect" name="tipo">
                                  <option selected disabled>Open this select menu</option>
                                  @foreach ($tipos[$tableNames[0]] as $item)
                                      <option value={{$item}}>{{$item}}</option>
                                  @endforeach
                              </select>
                              @error('tipo')
                              <span class="invalid feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                          @enderror
                            </div>
                            <div class="mb-3 row">
                              <label for="inputPassword" class="col-sm-2 col-form-label">Tipo</label>
                              
                              <div class="input-group mb-3 " >
                                <select class="form-select @error('tipoValidar') is-invalid @enderror" aria-label="Default select example" id="fourthSelect" name="tipoValidar">
                                  <option value="">Selecciona un tipo de dato...</option>
                                
                                  <optgroup label="Numéricos">
                                    <option value="int">Entero</option>
                                    {{-- <option value="bigint">BIGINT (Entero grande)</option>
                                    <option value="smallint">SMALLINT (Entero pequeño)</option>
                                    <option value="tinyint">TINYINT (Entero muy pequeño)</option> --}}
                                    <option value="decimal">Decimal</option>
                                    {{-- <option value="numeric">NUMERIC (Numérico)</option> --}}
                                  </optgroup>
                                
                                  <optgroup label="Cadenas de caracteres">
                                    {{-- <option value="char">CHAR (Cadena fija)</option> --}}
                                    <option value="varchar">Cadenas de caracteres</option>
                                    {{-- <option value="nchar">NCHAR (Cadena Unicode fija)</option>
                                    <option value="nvarchar">NVARCHAR (Cadena Unicode variable)</option> --}}
                                  </optgroup>
                                
                                  {{-- <optgroup label="Cadenas binarias">
                                    <option value="varbinary">VARBINARY (Binario variable)</option>
                                    <option value="image">IMAGE (Imagen)</option>
                                  </optgroup> --}}
                                
                                  <optgroup label="Fecha y hora">
                                    <option value="date">Fecha</option>
                                    <option value="time">Hora</option>
                                    {{-- <option value="datetime">DATETIME (Fecha y hora)</option>
                                    <option value="datetime2">DATETIME2 (Fecha y hora con mayor precisión)</option> --}}
                                  </optgroup>

                                  <optgroup label="Comunes">
                                    <option value="DNI">DNI</option>
                                    <option value="email">Correo</option>
                                    {{-- <option value="datetime">DATETIME (Fecha y hora)</option>
                                    <option value="datetime2">DATETIME2 (Fecha y hora con mayor precisión)</option> --}}
                                  </optgroup>
                                
                                </select>
                                @error('tipoValidar')
                                <span class="invalid feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                                  {{-- <input type="text" class="form-control" aria-label="Text input with dropdown button"  name="condicion_text"> --}}
                                  <input type="text" class="form-control @error('longitud') is-invalid @enderror" aria-label="Text input with dropdown button"  name="longitud" id ="longitud" placeholder="Longitud">
                                  @error('longitud')
                                  <span class="invalid feedback" role="alert">
                                      <strong>{{ $message }}</strong>
                                  </span>
                              @enderror
      
                                  {{-- <input type="text" class="form-control" aria-label="Text input with dropdown button" name="condicion_text"> --}}
                                  {{-- <button type="button" class="btn btn-primary add-condition-btn" id="boton-ad" style="display: none">+</button> <br> --}}
                                 
                              </div>
                            
                     
                            </div>
                 
                            
                            <div class=" row mb-3 condiciones d-flex">
                              
                              {{-- <label for="inputPassword" class="col-sm-2 col-form-label " id="condition-group">Condición</label> --}}
                              <label for="inputPassword" class="col-sm-2 col-form-label">Condición</label>
                              <div class="input-group mb-3 " >
                         
                                <select class="form-select @error('condicion') is-invalid @enderror" aria-label="Default select example" id="fiveselect" name="condicion">
                                  <option value="1">Selecciona una condicion..</option>
                                
                                  <optgroup label="Numéricos,Fecha y hora">
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
  



                                  {{-- <optgroup label="Otro">
                                    <option value="other">Avanzado(SQL)</option>
                                  </optgroup> --}}
  
                                </select>
                                @error('condicion')
                            <span class="invalid feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                               
                                {{-- <input type="text" class="form-control" aria-label="Text input with dropdown button"  name="condicion_text"> --}}
                                <input type="text" class="form-control @error('condicion_text') is-invalid @enderror" aria-label="Text input with dropdown button"  name="condicion_text[0]">
                                @error('condicion_text')
                                <span class="invalid feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
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
                        <a href="{{ route('campo.cancelar')}}" class="btn btn-danger">Cancelar</a>
                    </form>
                 
                   
                   
                     
                 
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.3.min.js"></script> 
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

{{-- <script src="path/to/select2.min.js"></script> --}}

{{-- <script>
  $(document).ready(function() {
    $("#mySelect").select2();
  });
</script> --}}
{{-- <script>
  function mensaje() {
 var $disabledResults = $(".select2");
  $disabledResults.select2();
  // $('#idLibro').select2();
  // $('#idFolio').select2();
  }
  setTimeout(mensaje,10000);
</script> --}}

<script>
    
  function mensajeQuit(){
    let mensaje=document.getElementById("mensaje");
    mensaje.style.display="none";
  }
       setTimeout(mensajeQuit,2000);
</script>

<script>



document.getElementById("fiveselect").addEventListener("change", function(){
  const valor = this.value;
  const index = this.selectedIndex;
  console.log("gola");
  const boton = document.getElementById("boton-ad");
  const fourth = document.getElementById("fourthSelect");
  var elements = document.getElementsByName("condicion_text[0]");
  
  elements[0].type = fourth.value;
if(fourth.selectedIndex <= 2) {
  elements[0].type = "number";
}
  

  var elements = document.getElementsByName("condicion_text[]");
    for (var i = elements.length - 1; i >= 0; i--) {
      elements[i].parentNode.removeChild(elements[i]);
    }

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
  longitud = document.getElementById("longitud")
  five = document.getElementById("fiveselect")
  // const boton = document.getElementById("boton-ad");
 if(valor=="date"|| valor=="time" || valor=="DNI"|| valor=="correo" || valor=="DateTime"){

  longitud.style.display="none";
  longitud.value="";
  // if( valor=="DNI"|| valor=="correo"){
  //   condicion.value="";
  // }
  }
  else{
    // var elements = document.getElementsByName("condicion_text[]");
    // for (var i = elements.length - 1; i >= 0; i--) {
    //   elements[i].parentNode.removeChild(elements[i]);
    // }
    longitud.style.display="block";
    
  }
  five.dispatchEvent(new Event('change'));
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
    if (resultado.includes("int")|| resultado.includes("bo")||resultado.includes("it")) {
      resultado="int";
    }
    if (resultado.includes("char")) {
      resultado="varchar";
    }
    if (resultado.includes("ate")) {
      resultado="date";
    }


    selectElement = document.getElementById("fourthSelect");

    if(resultado=="varchar"){
    var options = selectElement.getElementsByTagName("option");
    for(var i = 0; i < options.length; i++) {
      options[i].disabled = false;
      options[1].selected=true;
    }
    }
    else{
      for (let i = 0; i < selectElement.options.length; i++) {
      const option = selectElement.options[i];
        console.log(option.value)
      // Verificar si el valor de la opción coincide con el valor deseado
       if(option.value ==="DNI" && (resultado=="date" || resultado=="decimal" )){
     
          option.disabled = true;
          // option.selected = true;
        }
        else if (option.value ==="DNI"&& resultado=="int") {
          // Deshabilitar la opción
          option.disabled = false;
          // option.style.display = "none";
        }
        else if (option.value !== resultado) {
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
  const fourth = document.getElementById("fourthSelect");
  newConditionText.setAttribute('type', fourth.value); // Set the input type to "text"
  if(fourth.selectedIndex <= 2) {
    newConditionText.setAttribute('type', "number");
} // Set
  newConditionText.setAttribute('class', 'form-control'); // Set the input class
  newConditionText.setAttribute('aria-label', 'Text input with dropdown button'); // Set the aria-label
  newConditionText.setAttribute('name', 'condicion_text[]'); // Set the input name

  newConditionText.setAttribute('style', 'margin-left:50%; width:50%;'); // Set the input name
  const conditionGroup = document.querySelector('.condiciones'); // Get the parent div
conditionGroup.appendChild(newConditionText); // Append the clone to the parent div
});




</script>

@endsection




