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
        foreach($contenido as $tableName => $tableData) {
            $fields = [];
            $fields1 = [];
            foreach($tableData["columns"] as $column) {
                if($database->tipo == "mysql"){
                    $fields[] = $column->Field;
                }else{
                    $fields[] = $column['name'];
                }
            }
            foreach($tableData["data"] as $column2) {
                if (is_object($column2) && isset($column2->idActa)) {
                    $fields1[] = $column2->idActa;
                  }
            }
            $columnas[$tableName] = $fields;
        }
        //return $columnas;
        return view('secuencialidad.index',compact('tableNames','columnas'));
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
        /* return session()->get('tablesName'); */
        // Verificar si se detectan excepciones en la secuencia
        $valor_anterior = null;
        foreach ($datos as $valor) {
            // Verificar el tipo de secuencia
            switch ($tipo_secuencia) {
                case 'numerica':
                    // Verificar la secuencia numérica
                    if ($valor_anterior !== null) {
                        if ($orden_secuencia === 'ascendente') {
                            if ($valor - $valor_anterior != $incremento) {
                                $secuencia_correcta = false;
                                $excepciones[] = "Excepción de secuencialidad detectada: Valor '{$valor}' es inesperado, se esperaba un incremento de '{$incremento}' desde el valor anterior '{$valor_anterior}'.";
                            }
                        } else {
                            if ($valor_anterior - $valor != $incremento) {
                                $secuencia_correcta = false;
                                $excepciones[] = "Excepción de secuencialidad detectada: Valor '{$valor}' es inesperado, se esperaba un decremento de '{$incremento}' desde el valor anterior '{$valor_anterior}'.";
                            }
                        }
                    }
                    break;
                case 'alfanumerica':

                    if ($valor_anterior !== null) {
                        // Extraer componentes numéricos y alfabéticos de las cadenas
                        $valor_numerico = intval(preg_replace('/[^0-9]/', '', $valor));
                        $valor_anterior_numerico = intval(preg_replace('/[^0-9]/', '', $valor_anterior));

                        if($secuencia_alfabetica === 'si'){
                            //Valores alfabeticos 
                            $valor_alfabetico = preg_replace('/[0-9]+/', '', $valor);
                            $valor_anterior_alfabetico = preg_replace('/[0-9]+/', '', $valor_anterior);

                            // Verificar si la parte alfabética del valor actual sigue el orden alfabético
                            if ($orden_secuencia === 'ascendente') {
                                // Si el orden es ascendenteendente, verificar que la letra actual sea mayor o igual a la letra anterior
                                if (strcmp($valor_alfabetico, $valor_anterior_alfabetico) < 0) {
                                    // Si no sigue el orden alfabético, agregar una excepción
                                    $secuencia_correcta = false;
                                    $excepciones[] = "Excepción de secuencialidad detectada: Valor '{$valor}' es inesperado, no sigue el orden alfabético en la secuencia alfanumérica.";
                                }
                            } elseif ($orden_secuencia === 'descendente') {
                                // Si el orden es descendenteendente, verificar que la letra actual sea menor o igual a la letra anterior
                                if (strcmp($valor_alfabetico, $valor_anterior_alfabetico) > 0) {
                                    // Si no sigue el orden alfabético, agregar una excepción
                                    $secuencia_correcta = false;
                                    $excepciones[] = "Excepción de secuencialidad detectada: Valor '{$valor}' es inesperado, no sigue el orden alfabético en la secuencia alfanumérica.";
                                }
                            }
                        }

                        // Comparar los componentes numéricos
                        $num_cmp = $valor_numerico - $valor_anterior_numerico;

                        // Verificar si los componentes numéricos siguen el orden esperado
                        if (($orden_secuencia === 'ascendente' && $num_cmp != 1) || ($orden_secuencia === 'descendente' && $num_cmp != -1)) {
                            // Si los componentes numéricos no siguen el orden esperado, agregar una excepción
                            $secuencia_correcta = false;
                            $excepciones[] = "Excepción de secuencialidad detectada: Valor '{$valor}' es inesperado, no sigue el orden {$orden_secuencia} en la secuencia alfanumérica.";
                        }
                    }
                break;
                   
                case 'fecha':
                    // Verificar la secuencia de fechas
                    // Suponiendo que $valor es una cadena de fecha en formato 'Y-m-d'
                    if ($valor_anterior !== null) {
                        if (($orden_secuencia === 'ascendente' && strtotime($valor) < strtotime($valor_anterior)) || ($orden_secuencia === 'descendente' && strtotime($valor) > strtotime($valor_anterior))) {
                            $secuencia_correcta = false;
                            $excepciones[] = "Excepción de secuencialidad detectada: Valor '{$valor}' es inesperado, no sigue el orden {$orden_secuencia} en la secuencia de fechas.";
                        }
                    }
                    break;
                case 'hora':
                    // Verificar la secuencia de horas
                    // Suponiendo que $valor es una cadena de hora en formato 'H:i:s'
                    if ($valor_anterior !== null) {
                        if (($orden_secuencia === 'ascendente' && strtotime($valor) < strtotime($valor_anterior)) || ($orden_secuencia === 'descendente' && strtotime($valor) > strtotime($valor_anterior))) {
                            $secuencia_correcta = false;
                            $excepciones[] = "Excepción de secuencialidad detectada: Valor '{$valor}' es inesperado, no sigue el orden {$orden_secuencia} en la secuencia de horas.";
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
                if ($valor < $minimo || $valor > $maximo) {
                    $secuencia_correcta = false;
                    $excepciones[] = "Excepción de secuencialidad detectada: Valor '{$valor}' está fuera del rango de valores esperado '{$rango_valores}'.";
                }
            }

            // Actualizar el valor anterior para la próxima iteración
            $valor_anterior = $valor;
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
