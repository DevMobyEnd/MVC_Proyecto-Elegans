<?php
class PasswordValidator
{
    public function validate($password)
    {
        if (strlen($password) < 8) {
            return 'La contraseña debe tener al menos 8 caracteres';
        }

        if (!preg_match('/[A-Z]/', $password)) {
            return 'La contraseña debe contener al menos una letra mayúscula';
        }

        if (!preg_match('/[a-z]/', $password)) {
            return 'La contraseña debe contener al menos una letra minúscula';
        }

        if (!preg_match('/[0-9]/', $password)) {
            return 'La contraseña debe contener al menos un número';
        }

        if (!preg_match('/[^A-Za-z0-9]/', $password)) {
            return 'La contraseña debe contener al menos un carácter especial';
        }

        return true;
    }
}