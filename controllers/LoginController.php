<?php
namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController{

    public static function login(Router $router){

        $usuario = new Usuario;
        $errores = [];
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
         
            $usuario->sincronizar($_POST);
           $errores = $usuario->validarLogin();
            if(empty($errores)){
                $usuario = Usuario::where('email', $usuario->email);
                if($usuario && $usuario->confirmado === '1'){
                    //el usuario existe y verificamos la contraseña
                    if(password_verify($_POST['password'], $usuario->password)){
                       //Iniciamos sesión
                       session_start();
                       $_SESSION['login'] = true;
                       $_SESSION['email'] = $usuario->email;
                       $_SESSION['nombre'] = $usuario->nombre;
                       $_SESSION['id'] = $usuario->id;

                       //Redireccionamos
                        header('Location: /dashboard');
                    }else{
                        Usuario::setAlerta('error', 'Contraseña incorrecta');
                    }
    
                }else{
                    Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado');
                }
                $errores = Usuario::getAlertas();
            }

        }

        $router->render('auth/login', [
            'titulo'=>'Iniciar Sesión',
            'usuario'=> $usuario,
            'errores'=> $errores
        ]);
    }
    public static function logout(){
        session_start();
        $_SESSION = [];
        header('Location: /');
        
    }
    public static function crear(Router $router){
      
        $usuario = new Usuario;
        $errores = [];
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario->sincronizar($_POST);
            $errores = $usuario->validarNuevaCuenta();
            if(empty($errores)){ 
                $existeUsuario = Usuario::where('email', $_POST['email']);
            if($existeUsuario){ 
                Usuario::setAlerta('error', 'El usuario ya esta registrado');
                $errores = Usuario::getAlertas();
            }else{

                //hashear password
                $usuario->hashPassword();

                //Eliminar password 2

                //unset nos permite eliminar un elemento
                unset($usuario->passwordR);

                //generar toke
                $usuario->generarToken();

                $usuario->confirmado = 0;

                //Crear usuario
                $resultado = $usuario->guardar();

                 //enviar email
                $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                $email->enviarConfirmacion();
                if($resultado){
                    header('Location: /mensaje');
                }

            }
            }
            
          
        }

        $router->render('auth/crear', [
            'titulo'=>'Crea una cuenta',
            'usuario'=> $usuario,
            'errores'=> $errores
        ]);
    }
    public static function olvide(Router $router){
        $errores = [];
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario = new Usuario($_POST);
             $errores = $usuario->validarEmail();
           if(empty($errores)){
              //Buscar al usuario
              $usuario = Usuario::where('email', $usuario->email);
                if($usuario && $usuario->confirmado === '1'){
                    //El usuario fue encontrado

                    //generamos un nuevo token
                    $usuario->generarToken();

                    //actualizamos el usuario
                    $usuario->guardar();

                    //enviar el email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();

                    //Imprimir la alerta
                     Usuario::setAlerta('exito', 'Hemos enviado las instrucciones a tu email');

                }else{
                    Usuario::setAlerta('error', 'EL usuario no existe o no esta confirmado');
                }
                $errores=Usuario::getAlertas();
            }
        }
        $router->render('auth/olvide', [
            'titulo'=>'Olvide Password',
            'errores'=>$errores
        ]);
    }
    public static function reestablecer (Router $router){
        $token = s($_GET['token']);

        $mostrar = true;

        if(!$token) header('Location: /');

        //Identificar al usuario
        $usuario = Usuario::where('token', $token);

        if(empty($usuario)){
            Usuario::setAlerta('error', 'Token no válido');
            $mostrar = false;
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            //Añadir nuevo password
            $usuario->sincronizar($_POST);

            //Validar password
            $errores = $usuario->validarPassword();

            if(empty($errores)){
                //Hashear password
                $usuario->hashPassword();
                //eliminar token
                $usuario->token = null;
                //guardar al usuario
               $resultado = $usuario->guardar();
                //redireccionar
              if($resultado) header('Location /');
            }
        }

        $errores = Usuario::getAlertas();
        $router->render('auth/reestablecer', [
            'titulo'=>'Reestablecer Password',
            'errores'=>$errores,
            'mostrar'=>$mostrar
        ]);
    }
    public static function mensaje(Router $router){
  
        $router->render('auth/mensaje', [
            'titulo'=>'Instruccciones'
        ]);
    }
    public static function confirmar(Router $router){
        $token = s($_GET['token']);

        if(!$token){
            //evitar que entren  sin un token
            header('Location: /');
        }
        //encontrar usuario

        $usuario = Usuario::where('token', $token);

        if(empty($usuario)){
            //no hay usuario con el token
            Usuario::setAlerta('error', 'Token no váido');
        }else{

           //confirmar cuenta
            $usuario->confirmado = '1';
            $usuario->token = null;
            unset($usuario->passwordR);

            $usuario->guardar();
            Usuario::setAlerta('exito', 'Cuenta confirmada correctamente');
        }
        $alertas = Usuario::getAlertas();

        $router->render('auth/confirmar', [
            'titulo'=>'Cuenta creada exitosamente',
            'errores' => $alertas
        ]);
    }
}