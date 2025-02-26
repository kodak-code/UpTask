<?php

namespace Model;

class Usuario extends ActiveRecord
{
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'email', 'password', 'token', 'confirmado'];

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->password2 = $args['password2'] ?? '';
        $this->password_actual = $args['password_actual'] ?? '';
        $this->password_nuevo = $args['password_nuevo'] ?? '';
        $this->token = $args['token'] ?? '';
        $this->confirmado = $args['confirmado'] ?? 0;
    }
    // Validar el login de usuarios
    public function validarLogin(): array
    {
        if (!$this->email) {
            self::$alertas['error'][] = 'El Email es Obligatorio';
        }
        if (!$this->password) {
            self::$alertas['error'][] = 'El Password es Obligatorio';
        }
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'El Email no es valido';
        }
        return self::$alertas;
    }
    // Validacion para cuentas nuevas
    public function validarNuevaCuenta(): array
    {
        if (!$this->nombre) {
            self::$alertas['error'][] = 'El Nombre es Obligatorio';
        }
        if (!$this->email) {
            self::$alertas['error'][] = 'El Email es Obligatorio';
        }
        if (!$this->password) {
            self::$alertas['error'][] = 'El Password es Obligatorio';
        }
        if (strlen($this->password) < 6) {
            self::$alertas['error'][] = 'El Password debe tener al menos 6 caracteres';
        }
        if ($this->password !== $this->password2) {
            self::$alertas['error'][] = 'No coinciden los Passwords';
        }
        return self::$alertas;
    }

    // Valida un email
    public function validarEmail(): array
    {
        if (!$this->email) {
            self::$alertas['error'][] = 'El Email es Obligatorio';
        }

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'El Email no es valido';
        }
        return self::$alertas;
    }
    public function validarPassword(): array
    {
        if (!$this->password) {
            self::$alertas['error'][] = 'El Password es Obligatorio';
        }
        if (strlen($this->password) < 6) {
            self::$alertas['error'][] = 'El Password debe tener al menos 6 caracteres';
        }
        return self::$alertas;
    }
    public function validar_Perfil(): array
    {
        if (!$this->nombre) {
            self::$alertas['error'][] = 'El Nombre es Obligatorio';
        }
        if (!$this->email) {
            self::$alertas['error'][] = 'El Email es Obligatorio';
        }
        return self::$alertas;
    }
    public function nuevo_password(): array
    {
        if (!$this->password_actual) {
            self::$alertas['error'][] = 'El Password Actual No puede ir vacio';
        }
        if (!$this->password_nuevo) {
            self::$alertas['error'][] = 'El Password Nuevo No puede ir vacio';
        }
        if (strlen($this->password_nuevo) < 6) {
            self::$alertas['error'][] = 'El Password debe tener al menos 6 caracteres';
        }

        return self::$alertas;
    }
    // Comprobar el password
    public function comprobar_password(): bool
    {
        return password_verify($this->password_actual, $this->password);
    }
    public function hashPassword(): void
    {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }
    public function crearToken(): void
    {
        $this->token = md5(uniqid());
    }
}
