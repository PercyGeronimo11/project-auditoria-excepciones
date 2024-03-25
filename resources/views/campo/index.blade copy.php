
@extends('layout.layout')

@section('title', 'Formulario de Conexión')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<div class="container mt-5">
    <div class="row">
       
        
                    {{-- <h5 class="card-title">{{ $tableName }}</h5> --}}
                    <form action="
                            {{-- {{ route('')}}" method="GET"> --}}
                            " method="GET">
                            {{-- <div class="mb-3 row">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Email</label>
                                <div class="col-sm-10">
                                  <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="email@example.com">
                                </div>
                            </div> --}}
                            <div class="mb-3 row">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Tabla</label>
                                <select class="form-select" aria-label="Default select example" id="firstSelect"> 
                                    <option selected disabled>Open this select menu</option>
                                    @foreach ($tableNames as $item)
                                        <option value="{{$item}}">{{$item}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 row">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Campo</label>
                                <select class="form-select" aria-label="Default select example" id="secondSelect">
                                    <option selected disabled>Open this select menu</option>
                                    @foreach ($columnas[$tableNames[0]] as $item)
                                        <option value="1">{{$item}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 row">
                              <label for="inputPassword" class="col-sm-2 col-form-label">Tipo</label>
                              <select class="form-select" aria-label="Default select example" id="thirdSelect">
                                  <option selected disabled>Open this select menu</option>
                                  @foreach ($tipos[$tableNames[0]] as $item)
                                      <option value="1">{{$item}}</option>
                                  @endforeach
                              </select>
                            </div>
                            <div class="mb-3 row">
                              <label for="inputPassword" class="col-sm-2 col-form-label">Tipo(Validar)</label>
                              <select class="form-select" aria-label="Default select example" id="thirdSelect">
                                <option value="">Selecciona un tipo de dato...</option>
                              
                                <optgroup label="Numéricos">
                                  <option value="int">INT (Entero)</option>
                                  <option value="bigint">BIGINT (Entero grande)</option>
                                  <option value="smallint">SMALLINT (Entero pequeño)</option>
                                  <option value="tinyint">TINYINT (Entero muy pequeño)</option>
                                  <option value="decimal">DECIMAL (Decimal)</option>
                                  <option value="numeric">NUMERIC (Numérico)</option>
                                </optgroup>
                              
                                <optgroup label="Cadenas de caracteres">
                                  <option value="char">CHAR (Cadena fija)</option>
                                  <option value="varchar">VARCHAR (Cadena variable)</option>
                                  <option value="nchar">NCHAR (Cadena Unicode fija)</option>
                                  <option value="nvarchar">NVARCHAR (Cadena Unicode variable)</option>
                                </optgroup>
                              
                                <optgroup label="Cadenas binarias">
                                  <option value="varbinary">VARBINARY (Binario variable)</option>
                                  <option value="image">IMAGE (Imagen)</option>
                                </optgroup>
                              
                                <optgroup label="Fecha y hora">
                                  <option value="date">DATE (Fecha)</option>
                                  <option value="time">TIME (Hora)</option>
                                  <option value="datetime">DATETIME (Fecha y hora)</option>
                                  <option value="datetime2">DATETIME2 (Fecha y hora con mayor precisión)</option>
                                </optgroup>
                              
                                <optgroup label="Otros tipos de datos">
                                  <option value="bit">BIT (Booleano)</option>
                                  <option value="uniqueidentifier">UNIQUEIDENTIFIER (Identificador único global)</option>
                                  <option value="money">MONEY (Moneda)</option>
                                  <option value="xml">XML (Datos XML)</option>
                                  <option value="table">TABLE (Tabla temporal)</option>
                                </optgroup>
                              </select>
                            </div>
                 
                            <label for="inputPassword" class="col-sm-2 col-form-label">Condición</label>
                            <div class="mb-3 row">
                              
                              <div class="col-2">
                         
                                <select class="form-select" aria-label="Default select example" id="thirdSelect">
                                  <option value="">Selecciona una condicion..</option>
                                
                                  <optgroup label="Numéricos">
                                    <option value=">">></option>
                                    {{-- <option value=">=">>=</option>
                                    <option value="<="><= </option> --}}
                                    <option value="<"><</option>
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
                                  <optgroup label="Otro">
                                    <option value="other">Avanzado(SQL)</option>
                                  </optgroup>
  
                                </select>
                              </div>
                              <div class="col">
                            
                                <input type="text" id="check" class="form-control" aria-describedby="passwordHelpBlock">
                              </div>
                            
                            </div>

                            <div class="input-group mb-3">
                              <select class="form-select" aria-label="Default select example" id="thirdSelect">
                                <option value="">Selecciona una condicion..</option>
                              
                                <optgroup label="Numéricos">
                                  <option value=">">></option>
                                  {{-- <option value=">=">>=</option>
                                  <option value="<="><= </option> --}}
                                  <option value="<"><</option>
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
                                <optgroup label="Otro">
                                  <option value="other">Avanzado(SQL)</option>
                                </optgroup>

                              </select>
                              <input type="text" class="form-control" aria-label="Text input with dropdown button">
                            </div>
        
                            {{-- <div class="mb-3 row">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Password</label>
                                <div class="col-sm-10">
                                  <input type="password" class="form-control" id="inputPassword">
                                </div>
                            </div> --}}
                           
                        <button type="submit" class="btn btn-primary">Ver</button>
                    </form>
    </div>
</div>

<script>
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
  console.log("caimos")
});

document.getElementById("secondSelect").addEventListener("change", function() {
  // Get the selected value from the first select
  const selectedIndex = this.selectedIndex;
  const selectedValue = document.getElementById("firstSelect").value;
  // Get a reference to the second select
  const secondSelect = document.getElementById("thirdSelect");

  // Clear any existing options in the second select
  secondSelect.innerHTML = "";

  // Create new options based on the selected value
  
  const option = document.createElement("option");
  option.value = tipos[selectedValue][selectedIndex]; // Assuming you want the item as the value
  option.text = tipos[selectedValue][selectedIndex];
  secondSelect.add(option);

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
  console.log("caimos")
});
</script>

@endsection




