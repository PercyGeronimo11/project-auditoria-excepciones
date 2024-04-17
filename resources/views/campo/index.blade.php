@extends('layout.layout')

@section('title', 'Formulario de Conexión')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://kit.fontawesome.com/7920972db5.js" crossorigin="anonymous"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">


<div class="container mt-5">
  <h1 class="mb-4">Tareas de integridad de campo</h1>
  
  <div id="mensaje">
    
    @if (isset($datos))
    <div class="alert alert-warning alert-dismissible fade show mt-3 emergente" role="alert" style="color: white; background-color: rgb(183, 178, 31)">
      {{$datos}}
    </div>
   
     @endif 
</div>

  <br>

  <div class="row">

      <div class="col-md-6 text-right">
  <div  id="search-nav">
    <form class="navbar-left navbar-form nav-search mr-md-3" method="GET" role="search">
        <div class="input-group">
            <div class="input-group-prepend">
                <button type="submit" class="btn btn-search pr-1">
                    <i class="fa fa-search search-icon"></i>
                </button>
            </div>
            <input type="text" placeholder="Buscar por tabla" class="form-control" style="width: 150px;" value="{{$busqueda}}" name="buscarpor" >
          </div>
    </form>
</div>
</div>

<br>
<div class="col-md-6 mb-4">

  <a href="{{route('tareacampo.create')}}" class="btn btn-secondary mb-3"><i class="fas  fa-plus-circle"></i></a>
</div> 
</div> 
  @if(count($TareaCampos) > 0)
  <div class="card">
    <div class="card-header" style="background-color: #444; color: #fff;">
      <strong class="card-title">Lista de bases de datos</strong>

    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead style="background-color: #444; color: #fff;">
                <tr>
                  <th>Tabla</th>
                  <th>Campo</th>
                  <th>Fecha</th>
                  <th>Condición</th>
                  <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($TareaCampos as $row)
                <tr>
                    <td>{{ $row->tabla}}</td>
                    <td>{{ $row->campo}}</td>
                    <td>{{ $row->fecha}}</td>
                    <td>{{ $row->condicion." ".$row->condicion_text}}</td>
                    <td>
                      @if ($row->estado == 1)
                      <a href="{{ route('tareacampo.edit',$row->id) }}" class="btn btn-info btn-sm"><i class="fas fa-edit"></i></a>
                      <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#staticBackdrop{{$row->id}}"><i class="fas fa-trash"></i></button>  
                      @endif  
                      <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#staticBackdropp{{$row->id}}"><i class="fa-solid fa-file-circle-check"></i></button>
                      <a href="{{ route('tareacampo.show',$row->id) }}" class="btn btn-info btn-sm"><i class="fa fa-clone"></i></a> 
                      <a href="{{ route('analizar.campo', ['state' => 1, 'id' => $row->id]) }}" class="btn btn-info btn-sm"><i class="fa-solid fa-magnifying-glass-chart"></i></a>
                      <a href="{{ route('campo.pdf',$row->id) }}" class="btn btn-outline-secondary btn-sm"><i class="fa-regular fa-file-pdf"></i></a>
                              
                      <!-- Modal -->
                      <div class="modal fade" id="staticBackdropp{{$row->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Reporte</h1>
                            </div>
                            <div class="modal-body">
                                <iframe src="{{asset($row->url_doc)}})" class="object-cover mt-2" height="500vh" width="450vh" frameborder="0" scrolling=""> </iframe>
                            </div>
                            <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                        </div>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="staticBackdrop{{$row->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h1 class="modal-title fs-5" id="staticBackdropLabel">Eliminar Tarea</h1>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <span>Codigo : {{$row->id}}</span>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                              <form method="POST" action="{{route('tareacampo.destroy',$row->id)}}">
                                @method('delete')
                                @csrf
                                <button class="btn btn-danger"><i class="fas fa-check-square"></i> SI</button>
                              </form>
                            </div>
                          </div>
                        </div>
                    </div>
                  </td>
                </tr>
                @endforeach
            </tbody>
        </table>
      </div>
    </div>
  </div>

  <nav aria-label="...">
    <ul class="pagination pagination-lg">
      {{$TareaCampos->links()}}
    </ul>
  </nav>

  @else
  <div class="alert alert-info" role="alert">
      No hay datos en la tabla {{ $TareaCampos }}
  </div>
  @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>

<script>
    
    function mensajeQuit(){
      let mensaje=document.getElementById("mensaje");
      mensaje.style.display="none";
    }
         setTimeout(mensajeQuit,2000);
</script>
  

@endsection
