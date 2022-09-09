<?php
namespace Controllers;

use Model\Proyecto;
use Model\Usuario;
use MVC\Router;

class DashBoardController{
    public static function index(Router $router){

        session_start();
        isAuth();
        $proyectos = Proyecto::whereAll('propietarioId',  $_SESSION['id']);
        $router->render('dashboard/index', [
            'titulo' => 'Proyectos',
            'proyectos' => $proyectos
        ]);
    }
    public static function crear_proyecto(Router $router){

        session_start();
        isAuth();
   
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $proyecto = new Proyecto($_POST);   

            //validación
            $errores = $proyecto->validarProyecto();
            if(empty($errores)){

                //generar una url única
                $proyecto->url = md5(uniqid());

                //Almacenar al creador del proyecto
                $proyecto->propietarioId = $_SESSION['id'];
                //guardar el proyecto
                $proyecto->guardar();
                header('Location:/proyecto?url=' . $proyecto->url);

            }
        }
        $errores = [];
        $router->render('dashboard/crear-proyecto', [
            'titulo' => 'Crear proyecto',
            'errores' => $errores
        ]);
    }

    public static function proyecto(Router $router){
        
        session_start();
        isAuth();

        //Revisar que la persona que visita es el proyecto es quien la creo
        $token = $_GET['id'];
        if(!$token){
            header('Location: /dashboard');
        }
        $proyecto = Proyecto::where('url', $token);

        if($proyecto->propietarioId !== $_SESSION['id']){
            //no es el propietario del proyecto
           header('Location: /dashboard');
        }
        $router->render('dashboard/proyecto', [
            'titulo' => $proyecto->proyecto
        ]);

     }
     public static function eliminarProyecto(){
        $id = $_POST['id'];
        $proyecto = Proyecto::where('id', $id);
        if (!$proyecto){
            echo json_encode(['respuesta'=>'Ocurrio un error al eliminar el proyecto', 
            'tipo'=>'error']);
            return;
        }
            $resultado = $proyecto->eliminar();
            if($resultado)
            echo json_encode(['respuesta'=>'Eliminado correctamente',
                             'tipo'=>'success']);
            else
            echo json_encode(['respuesta'=>'Ocurrio un error al eliminar el proyecto', 
                             'tipo'=>'error']);
        

        
     }
    public static function perfil(Router $router){

        session_start();
        isAuth();
          $errores = [];
          $usuario = Usuario::find($_SESSION['id']);
          if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario->sincronizar($_POST);
            $errores = $usuario->validarPerfil();
            if(empty($errores)){

                //Verificar que el nuevo email no lo tenga alguien mas
                $existeUsuarioEmail = Usuario::where('email', $usuario->email);
                if($existeUsuarioEmail && $existeUsuarioEmail->id !== $usuario->id){
                    //mensaje de error
                    Usuario::setAlerta('error', 'El email ya esta en uso');
                }else{
                //actualizar datos
                $usuario->guardar();

                //Actualizar la sesión
                $_SESSION['nombre'] = $usuario->nombre;

                //Mostrar una alerta de que se actualizo correctamente
                Usuario::setAlerta('exito', 'Cambios guardado correctamente');
                }
                $errores = Usuario::getAlertas();
            }
          }
        $router->render('dashboard/perfil', [
            'titulo' => 'Perfil',
            'errores' => $errores,
            'usuario' => $usuario
        ]);
    }
    public static function cambiar_password(Router $router){

        $errores = [];
        session_start();
        isAuth();
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario = Usuario::find($_SESSION['id']);

            //sincronizar con los datos del usuario
            $usuario->sincronizar($_POST);
            $errores = $usuario->nuevo_password();

            if(empty($errores)){
                $resultado = $usuario->comprobar_password();
                if($resultado){
                    //Asignar nueva password
                    $usuario->password = $usuario -> passwordNuevo;

                    //Eliminar propiedades no necesarias
                    unset($usuario->passwordNuevo);
                    unset($usuario->passwordActual);
                    //Hashear password
                    $usuario->hashPassword();
                    $resultado = $usuario->guardar();
                    if($resultado)  Usuario::setAlerta('exito', 'Password guardado correctamente');
                }else{
                    Usuario::setAlerta('error', 'Password incorrecto');
                }
                $errores = Usuario::getAlertas();
            }
        }

        $router->render('dashboard/cambiar-password', [
            'titulo' => 'cambiar-password',
            'errores' => $errores
        ]);

    }
}