<?php

namespace App\Http\Controllers;

use App\Models\Database;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

/* use App\Http\Controllers\DatabaseController; */

class SequenceController extends Controller
{

    public function index(Request $request){
        $database = Database::latest()->first();
        $contenido=session()->get('tablesName');
        $tableNames = array_keys($contenido);
        $columnas = [];
        $tipos = [];
        foreach($contenido as $tableName => $tableData) {
            $fields = [];
            $fields1 = [];
            $types=[];
            foreach($tableData["columns"] as $column) {
                if($database->tipo == "mysql"){
                    $fields[] = $column->Field;
                }else{
                    $fields[] = $column['name'];
                }

                if (isset($column->Type)) {
                    $types[]= $column->Type;
                }
                else{
                    $types[]= $column["type"];
                } 
            }
            foreach($tableData["data"] as $column2) {
                if (is_object($column2) && isset($column2->idActa)) {
                    $fields1[] = $column2->idActa;
                  }
            }
            $columnas[$tableName] = $fields;
            $tipos[$tableName] = $types;
        }
        //return $tipos;
        return view('secuencialidad.index',compact('tableNames','columnas','tipos'));
    }

    public function store(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'tabla' => 'required',
            'campo' => 'required',
            'tipo_secuencia' => 'required',
            'orden_secuencia' => 'required',
            'incremento' => 'required|numeric'
        ], [
            'tabla.required' => 'Seleccionar la tabla es obligatorio.',
            'campo.required' => 'Seleccionar el campo es obligatorio.',
        ]);

        // Capturar los datos del formulario
        $tabla = $request->input('tabla');
        $campo = $request->input('campo');
        $tipo_secuencia = $request->input('tipo_secuencia');
        $orden_secuencia = $request->input('orden_secuencia');
        $incremento = $request->input('incremento');
        $valor_inicial_esperado = $request->input('valor_inicial_esperado');
        $rango_valores = $request->input('rango_valores');
        if($tipo_secuencia == "alfanumerica"){
            $secuencia_alfabetica = $request->input('secuencia_alfabetica');
        }else{
            $secuencia_alfabetica =null;
        }
        //return Database::latest()->first();
        //return session()->get('tablesName');
        // Realizar el análisis de secuencialidad
        $resultado_analisis = $this->analizarSecuencialidad($tabla, $campo, $tipo_secuencia, $orden_secuencia, $incremento, $valor_inicial_esperado, $rango_valores, $secuencia_alfabetica);
        
        // Guardar el resultado del análisis o mostrarlo en la interfaz de usuario
        return view('secuencialidad.resultado_analisis')->with('resultado', $resultado_analisis);
    }

    private function analizarSecuencialidad($tabla, $campo, $tipo_secuencia, $orden_secuencia, $incremento, $valor_inicial_esperado, $rango_valores, $secuencia_alfabetica)
    {
        // Inicializar variables para el seguimiento del análisis
        $secuencia_correcta = true;
        $excepciones = [];

        // Obtener los datos de la tabla seleccionada desde la sesión
        $datos = session()->get('tablesName.' . $tabla . '.data')->pluck($campo);
        //return $datos;
        /* return session()->get('tablesName'); */
        // Verificar si se detectan excepciones en la secuencia
        $valor_anterior = null;
        for ($i = 0; $i < count($datos); $i++) {
            // Verificar el tipo de secuencia
            switch ($tipo_secuencia) {
                case 'Numérica':
                    // Verificar la secuencia numérica
                    if ($valor_anterior !== null) {
                        if($datos[$i] == $valor_anterior){
                            $secuencia_correcta = false;
                                $excepciones[] = [
                                    'id' => $i+1,
                                    'tabla' => $tabla,
                                    'campo' => $campo,
                                    'actual' => $datos[$i],
                                    'anterior' => $valor_anterior,
                                    'mensaje' => "El valor se mantuvo sin cambios"
                                ];
                        }
                        elseif ($orden_secuencia === 'ascendente') {
                            if ($datos[$i] - $valor_anterior != $incremento) {
                                $secuencia_correcta = false;
                                $excepciones[] = [
                                    'id' => $i+1,
                                    'tabla' => $tabla,
                                    'campo' => $campo,
                                    'actual' => $datos[$i],
                                    'anterior' => $valor_anterior,
                                    'mensaje' => "Esta creciendo con respecto al anterior"
                                ];
                            }
                        } else {
                            if ($valor_anterior - $datos[$i] != $incremento) {
                                $secuencia_correcta = false;
                                $excepciones[] = [
                                    'id' => $i+1,
                                    'tabla' => $tabla,
                                    'campo' => $campo,
                                    'actual' => $datos[$i],
                                    'anterior' => $valor_anterior,
                                    'mensaje' => "Esta decreciendo con respecto al anterior"
                                ];
                            }
                        }
                    }
                    elseif($datos[$i] != null){
                        if($datos[$i]>1 && $orden_secuencia === 'ascendente'){
                            $secuencia_correcta = false;
                            $excepciones[]=[
                                'id' => $i+1,
                                    'tabla' => $tabla,
                                    'campo' => $campo,
                                    'actual' => $datos[$i],
                                    'anterior' => '-',
                                    'mensaje' => "No existen los valores desde el 1 al '{$datos[$i]}'",
                            ];
                        }
                    }
                    break;
                case 'Alfanumérica':

                    if (preg_match('/[0-9]+/', $datos[$i])) {
                        if ($valor_anterior !== null) {
                            // Extraer componentes numéricos y alfabéticos de las cadenas
                            $valor_numerico = intval(preg_replace('/[^0-9]/', '', $datos[$i]));
                            $valor_anterior_numerico = intval(preg_replace('/[^0-9]/', '', $valor_anterior));
    
                            if($secuencia_alfabetica === 'si'){
                                //Valores alfabeticos 
                                $valor_alfabetico = preg_replace('/[0-9]+/', '', $datos[$i]);
                                $valor_anterior_alfabetico = preg_replace('/[0-9]+/', '', $valor_anterior);
    
                                // Verificar si la parte alfabética del valor actual sigue el orden alfabético
                                if ($orden_secuencia === 'ascendente') {
                                    // Si el orden es ascendenteendente, verificar que la letra actual sea mayor o igual a la letra anterior
                                    if (strcmp($valor_alfabetico, $valor_anterior_alfabetico) < 0) {
                                        // Si no sigue el orden alfabético, agregar una excepción
                                        $secuencia_correcta = false;
                                        $excepciones[] = [
                                            'id' => $i+1,
                                            'tabla' => $tabla,
                                            'campo' => $campo,
                                            'actual' => $datos[$i],
                                            'anterior' => $valor_anterior,
                                            'mensaje' => "Esta decreciendo",
                                        ];
                                    }
                                } elseif ($orden_secuencia === 'descendente') {
                                    // Si el orden es descendenteendente, verificar que la letra actual sea menor o igual a la letra anterior
                                    if (strcmp($valor_alfabetico, $valor_anterior_alfabetico) > 0) {
                                        // Si no sigue el orden alfabético, agregar una excepción
                                        $secuencia_correcta = false;
                                        $excepciones[] = [
                                            'id' => $i+1,
                                            'tabla' => $tabla,
                                            'campo' => $campo,
                                            'actual' => $datos[$i],
                                            'anterior' => $valor_anterior,
                                            'mensaje' => "Esta creciendo",
                                        ];
                                    }
                                }
                            }
    
                            // Comparar los componentes numéricos
                            $num_cmp = $valor_numerico - $valor_anterior_numerico;
    
                            // Verificar si los componentes numéricos siguen el orden esperado
                            if (($orden_secuencia === 'ascendente' && $num_cmp != 1)) {
                                // Si los componentes numéricos no siguen el orden esperado, agregar una excepción
                                $secuencia_correcta = false;
                                $excepciones[] = [
                                    'id' => $i+1,
                                    'tabla' => $tabla,
                                    'campo' => $campo,
                                    'actual' => $datos[$i],
                                    'anterior' => $valor_anterior,
                                    'mensaje' => "Esta decreciendo",
                                ];
                            }
                            elseif(($orden_secuencia === 'descendente' && $num_cmp != -1)){
                                $secuencia_correcta = false;
                                $excepciones[] = [
                                    'id' => $i+1,
                                    'tabla' => $tabla,
                                    'campo' => $campo,
                                    'actual' => $datos[$i],
                                    'anterior' => $valor_anterior,
                                    'mensaje' => "Esta creciendo",
                                ];
                            }
                        }
                        elseif($datos[$i] != null){
                            //return $datos[$i];
                            $valor_numerico = intval(preg_replace('/[^0-9]/', '', $datos[$i]));
                            if($valor_numerico>1 && $orden_secuencia === 'ascendente'){
                                $secuencia_correcta = false;
                                $excepciones[]=[
                                    'id' => $i+1,
                                        'tabla' => $tabla,
                                        'campo' => $campo,
                                        'actual' => $datos[$i],
                                        'anterior' => "-",
                                        'mensaje' => "No existen los valores desde el 1 al '{$datos[$i]}'",
                                ];
                            }
                        }
                    } else {
                        $excepciones[] = [
                            'error' => "Los valores no contienen una parte numérica, por lo tanto, no se realiza el análisis de secuencialidad alfanumérica o numérica.",
                        ];
                        return $excepciones;
                    }
                    
                break;
                   
                case 'Fecha':
                    // Verificar la secuencia de fechas
                    // Suponiendo que $datos[$i] es una cadena de fecha en formato 'Y-m-d'
                    if ($valor_anterior !== null) {
                        if (($orden_secuencia === 'ascendente' && strtotime($datos[$i]) < strtotime($valor_anterior))) {
                            $secuencia_correcta = false;
                            $excepciones[] = [
                                'id' => $i+1,
                                'tabla' => $tabla,
                                'campo' => $campo,
                                'actual' => $datos[$i],
                                'anterior' => $valor_anterior,
                                'mensaje' => "Es una hora menor que la anterior",
                            ];
                        }
                        elseif(($orden_secuencia === 'descendente' && strtotime($datos[$i]) > strtotime($valor_anterior))){
                            $secuencia_correcta = false;
                            $excepciones[] = [
                                'id' => $i+1,
                                'tabla' => $tabla,
                                'campo' => $campo,
                                'actual' => $datos[$i],
                                'anterior' => $valor_anterior,
                                'mensaje' => "Es una fecha mayor que la anterior",
                            ];
                        }
                    }
                    
                    break;
                case 'Hora':
                    // Verificar la secuencia de horas
                    // Suponiendo que $datos[$i] es una cadena de hora en formato 'H:i:s'
                    if ($valor_anterior !== null) {
                        if (($orden_secuencia === 'ascendente' && strtotime($datos[$i]) < strtotime($valor_anterior))) {
                            $secuencia_correcta = false;
                            $excepciones[] = [
                                'id' => $i+1,
                                'tabla' => $tabla,
                                'campo' => $campo,
                                'actual' => $datos[$i],
                                'anterior' => $valor_anterior,
                                'mensaje' => "Es una hora menor que la anterior",
                            ];
                        }
                        elseif(($orden_secuencia === 'descendente' && strtotime($datos[$i]) > strtotime($valor_anterior))){
                            $secuencia_correcta = false;
                            $excepciones[] = [
                                'id' => $i+1,
                                'tabla' => $tabla,
                                'campo' => $campo,
                                'actual' => $datos[$i],
                                'anterior' => $valor_anterior,
                                'mensaje' => "Es una hora mayor que la anterior",
                            ];
                        }
                    }
                    break;
                default:
                    // Tipo de secuencia no reconocido
                    $secuencia_correcta = false;
                    $excepciones[] = "Tipo de secuencia '{$tipo_secuencia}' no reconocido.";
                    break;
            }

            // Verificar el rango de valores esperado
            if ($rango_valores !== null) {
                // descendenteomponer el rango de valores
                list($minimo, $maximo) = explode('-', $rango_valores);
                // Verificar si el valor está dentro del rango esperado
                if ($datos[$i] < $minimo || $datos[$i] > $maximo) {
                    $secuencia_correcta = false;
                    $excepciones[] = [
                        'id' => $i+1,
                        'tabla' => $tabla,
                        'campo' => $campo,
                        'actual' => $datos[$i],
                        'anterior' => $valor_anterior,
                        'mensaje' => "Valor '{$datos[$i]}' está fuera del rango de valores esperado '{$rango_valores}'.",
                    ];
                }
            }

            // Actualizar el valor anterior para la próxima iteración
            $valor_anterior = $datos[$i];
        }

        // Si la secuencia es correcta, retornar un mensaje de éxito
        if ($secuencia_correcta) {
            return "La secuencia en el campo '{$campo}' de la tabla '{$tabla}' es correcta.";
        } else {
            // Si se detectan excepciones, retornar un arreglo de excepciones
            return $excepciones;
        }
    }



}
