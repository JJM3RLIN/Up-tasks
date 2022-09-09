<?php 
namespace Controllers;

use Model\Proyecto;
use Model\Tarea;

class TareaController{
    public static function index(){

        session_start();
        $proyectoId = $_GET['id'];

        if(!$proyectoId) header('Location:/dashboard');
        //verificar el proeycto
        $proyecto = Proyecto::where('url', $proyectoId);
        if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']) header('Location:/404');

        //Ya se valido que existe
        $tareas = Tarea::whereAll('proyectoId', $proyecto->id);

        echo json_encode($tareas);
    }
    public static function crear(){
       
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            session_start();

            //verificar que el proyecto exista
            $proyecto = Proyecto::where('url', $_POST['proyectoId']);

            if(!$proyecto ||  $proyecto->propietarioId !== $_SESSION['id']){
                $respuesta = [
                  'tipo' => 'error',
                  'mensaje' => 'Hubo un error al agregar la tarea'
                ];
                echo json_encode($respuesta);
                return;   
            }
            //todo bien
            $tarea = new Tarea($_POST);

            //Para que tenga el id del proyecto y no lo de la url
            $tarea->proyectoId = $proyecto->id;
            $resultado = $tarea->guardar();
            $respuesta = [
                'tipo' => 'success',
                'mensaje' => 'Tarea creada correctamente',
                'id' => $resultado['id'],
                'proyectoId' => $proyecto->id
              ];
              echo json_encode($respuesta);
        }
    }
    public static function actualizar(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            session_start();
            $proyecto = Proyecto::where('url', $_POST['proyectoId']);
            if(!$proyecto ||  $proyecto->propietarioId !== $_SESSION['id']){
                $respuesta = [
                  'tipo' => 'error',
                  'mensaje' => 'Hubo un error al actualizar la tarea'
                ];
                echo json_encode($respuesta);
                return;   
            }
            $tarea = new Tarea($_POST);
            $tarea->proyectoId = $proyecto->id;
            $resultado = $tarea->guardar();

            if($resultado){
                $respuesta = [
                    'tipo' => 'success',
                    'id'=> $tarea->id,
                    'proyectoId'=>$proyecto->id,
                    'mensaje' => 'Actualizado correctamente'
                  ];
                  echo json_encode($respuesta);
            }

      }
    }
    public static function eliminar(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            session_start();
            //Verificar que el proyecto exista
            $proyecto = Proyecto::where('url', $_POST['proyectoId']);

            if(!$proyecto ||  $proyecto->propietarioId !== $_SESSION['id']){
                $respuesta = [
                  'tipo' => 'error',
                  'mensaje' => 'Hubo un error al actualizar la tarea'
                ];
                echo json_encode($respuesta);
                return;   
            }
            $tarea = new Tarea($_POST);
            $resultado = $tarea->eliminar();

            $respuesta = [
                'tipo' => 'success',
                'mensaje' => 'Eliminado correctamente',
                'resultado' => $resultado
            ];
            echo json_encode($respuesta);
        }
    }

}