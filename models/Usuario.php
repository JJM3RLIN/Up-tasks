<?php
namespace Model;
class Usuario extends ActiveRecord{
    protected static $tabla = 'usuarios';
    protected static $columnasDB =[ 'id', 'nombre', 'email', 'password', 'token', 'confirmado'];
    
    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->passwordR = $args['passwordR'] ?? '';
        $this->token = $args['token'] ?? '';
        $this->confirmado = $args['confirmado'] ?? '';

        //atributos para cambiar contraseña
        $this->passwordActual = $args['passwordActual'] ?? '';
        $this->passwordNuevo = $args['passwordNuevo'] ?? '';

        
    }

    //Validación para cuentas nuevas
    public function validarNuevaCuenta() : array{
        if(!$this->nombre){
            self::$alertas['error'][] = 'El nombre de usuario es obligatorio';
        }
        if(!$this->email){
            self::$alertas['error'][] = 'El email es obligatorio';
        }
        if(!$this->password){
            self::$alertas['error'][] = 'El password no puede ir vació';
        }
        if(strlen($this->password) < 6){
            self::$alertas['error'][] = 'El password debe contener al menos 6 carácteres';
        }
        if($this->password !== $this->passwordR){
            self::$alertas['error'][] = 'El password no puede ir vació';
        }
        return self::$alertas;
    }
    public function hashPassword() : void{
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }
    public function generarToken(): void{
        $this->token = uniqid();
    }
    public function validarEmail()  : array
    {
     if(!$this->email) {
        self::$alertas['error'][] = 'El email es obligatorio';
     }

     if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
        self::$alertas['error'][] = 'Email no válido';
     }

     return self::$alertas;
    }
    public function validarPassword() : array{
        if(!$this->password){
            self::$alertas['error'][] = 'El password no puede ir vació';
        }
        if(strlen($this->password) < 6){
            self::$alertas['error'][] = 'El password debe contener al menos 6 carácteres';
        }
        return self::$alertas;
    }
    public function validarLogin() : array{
        if(!$this->password){
            self::$alertas['error'][] = 'El password no puede ir vació';
        }
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'Email no válido';
         }
         if(!$this->email) {
            self::$alertas['error'][] = 'El email es obligatorio';
         }
         return self::$alertas;
    }
    public function validarPerfil() : array{
        if(!$this->nombre){
            self::$alertas['error'][] = 'El nuevo nombre no puede ir vació';
        }
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'Email no válido';
         }
         if(!$this->email) {
            self::$alertas['error'][] = 'El email es obligatorio';
         }
         return self::$alertas;
    }
    public function nuevo_password() : array{
        if(!$this->passwordActual){
            self::$alertas['error'][] = 'El password actual no puede ir vacio';
        }
        if(!$this->passwordNuevo){
            self::$alertas['error'][] = 'El password nuevo  no puede ir vacio';
        }
        if(strlen($this->passwordNuevo) < 6){
            self::$alertas['error'][] = 'El password nuevo  debe contener al menos 6 caracteres';
        }
        return self::$alertas;
    }
    public function comprobar_password() : bool {
         return password_verify($this->passwordActual, $this->password);
    }
}