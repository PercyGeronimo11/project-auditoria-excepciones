
@extends('layout.layout')

@section('title', 'Formulario de Conexión')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://kit.fontawesome.com/7920972db5.js" crossorigin="anonymous"></script>



<div class="container mt-5">
  <h1 class="mb-4">Tareas de integridad de campo</h1>
  
  <div id="mensaje">
    
    @if (isset($datos))
    <div class="alert alert-warning alert-dismissible fade show mt-3 emergente" role="alert" style="color: white; background-color: rgb(183, 178, 31) ">
      {{$datos}}
    </div>
   
     @endif 
</div>

  <br>
  <div  id="search-nav">
    <form class="navbar-left navbar-form nav-search mr-md-3" method="GET" role="search">
        <div class="input-group">
            <div class="input-group-prepend">
                <button type="submit" class="btn btn-search pr-1">
                    <i class="fa fa-search search-icon"></i>
                </button>
            </div>
            <input type="text" placeholder="Buscar por tabla" class="form-control" value="{{$busqueda}}" name="buscarpor" >
        </div>
    </form>

</div>
<br>
  
  {{-- <div class="collapse" id="search-nav">
    

</div> --}}
  <a href="{{route('tareacampo.create')}}" class="btn btn-primary float-right" style="float: right"><i class="fas fa-plus"></i>Nuevo Registro</a>
  <br><br>
  @if(count($TareaCampos) > 0)
  <div class="table-responsive">
      <table class="table table-striped">
          <thead>
              <tr>
                <td>Tabla</td>
                <td>Campo</td>
                <td>Fecha</td>
                <td>Condición</td>
                <td>Action</td>
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
                    {{-- <a href="{{ route('tareacampo.confirmar',$row->idCargo) }}" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i>Eliminar</a>  --}}
                    <button   class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#staticBackdrop{{$row->id}}">
                        <i class="fas fa-trash"></i>
                      </button>  
                      
                  
                        
                    @endif     
                    <a href="{{ route('analizar.campo', ['state' => 1, 'id' => $row->id]) }}" class="btn btn-info btn-sm">
                      <i class="fa-solid fa-magnifying-glass-chart"></i>
                  </a>
                  
                  
                    {{-- <a href="{{ route('analizar.campo',$row->id, 1) }}" class="btn btn-info btn-sm"><i class="fa-solid fa-magnifying-glass-chart"></i></a> --}}
                    {{-- <a href="{{ route('analizar.campo',$row->id,1) }}" class="btn btn-info btn-sm"><i class="fa-solid fa-magnifying-glass-chart"></i></a> --}}
                    <a href="{{ route('campo.pdf',$row->id) }}" class="btn btn-outline-secondary btn-sm"><i class="fa-regular fa-file-pdf"></i></a>
                    
                      
                            
                            
                              <!-- Modal -->
                              <div class="modal fade " id="staticBackdrop{{$row->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <h1 class="modal-title fs-5" id="staticBackdropLabel">Eliminar Tarea</h1>
                                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <span>
                                            Codigo : {{$row->id}}
                                      {{-- <br> Cargo: {{$row->descripcion}} --}}
                                    
                                        </span>
                                      
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
  @else
  <div class="alert alert-info" role="alert">
      No hay datos en la tabla {{ $TareaCampos }}
  </div>
  @endif
</div>


<script>
    
    function mensajeQuit(){
      let mensaje=document.getElementById("mensaje");
      mensaje.style.display="none";
    }
         setTimeout(mensajeQuit,2000);
</script>
  

@endsection




