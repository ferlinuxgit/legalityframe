<?php

namespace App\Validators;

use App\Models\User;

/**
 * Validador para el formulario de registro
 * 
 * @package App\Validators
 */
class RegisterValidator
{
    /**
     * Errores de validación
     * 
     * @var array
     */
    private $errors = [];
    
    /**
     * Datos a validar
     * 
     * @var array
     */
    private $data;
    
    /**
     * Reglas de validación
     * 
     * @var array
     */
    private $rules = [
        'name' => ['required', 'min:3', 'max:255'],
        'email' => ['required', 'email', 'unique:users'],
        'password' => ['required', 'min:8', 'strong_password'],
        'password_confirmation' => ['required', 'same:password']
    ];
    
    /**
     * Mensajes de error personalizados
     * 
     * @var array
     */
    private $messages = [
        'required' => 'auth.required',
        'min' => 'auth.min_length',
        'max' => 'auth.max_length',
        'email' => 'auth.email_format',
        'unique' => 'auth.email_exists',
        'same' => 'auth.password_mismatch',
        'strong_password' => 'auth.password_weak'
    ];
    
    /**
     * Constructor
     * 
     * @param array $data Datos a validar
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }
    
    /**
     * Ejecutar la validación
     * 
     * @return bool
     */
    public function validate()
    {
        $this->errors = [];
        
        foreach ($this->rules as $field => $rules) {
            foreach ($rules as $rule) {
                // Comprobar si la regla tiene parámetros
                if (strpos($rule, ':') !== false) {
                    list($ruleName, $ruleParam) = explode(':', $rule, 2);
                } else {
                    $ruleName = $rule;
                    $ruleParam = null;
                }
                
                // Nombre del método de validación
                $method = 'validate' . ucfirst($ruleName);
                
                // Verificar si el método existe
                if (method_exists($this, $method)) {
                    // Llamar al método de validación
                    if (!$this->$method($field, $ruleParam)) {
                        // Si la validación falla, añadir el error y continuar con el siguiente campo
                        break;
                    }
                }
            }
        }
        
        return empty($this->errors);
    }
    
    /**
     * Obtener los errores de validación
     * 
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
    
    /**
     * Obtener los datos validados
     * 
     * @return array
     */
    public function getValidatedData()
    {
        return $this->data;
    }
    
    /**
     * Validar que un campo sea obligatorio
     * 
     * @param string $field Nombre del campo
     * @return bool
     */
    private function validateRequired($field)
    {
        $value = $this->data[$field] ?? '';
        
        if (empty($value) && $value !== '0') {
            $this->addError($field, 'required');
            return false;
        }
        
        return true;
    }
    
    /**
     * Validar longitud mínima
     * 
     * @param string $field Nombre del campo
     * @param int $min Longitud mínima
     * @return bool
     */
    private function validateMin($field, $min)
    {
        $value = $this->data[$field] ?? '';
        
        if (isset($this->data[$field]) && mb_strlen($value) < $min) {
            $this->addError($field, 'min', ['min' => $min]);
            return false;
        }
        
        return true;
    }
    
    /**
     * Validar longitud máxima
     * 
     * @param string $field Nombre del campo
     * @param int $max Longitud máxima
     * @return bool
     */
    private function validateMax($field, $max)
    {
        $value = $this->data[$field] ?? '';
        
        if (isset($this->data[$field]) && mb_strlen($value) > $max) {
            $this->addError($field, 'max', ['max' => $max]);
            return false;
        }
        
        return true;
    }
    
    /**
     * Validar formato de email
     * 
     * @param string $field Nombre del campo
     * @return bool
     */
    private function validateEmail($field)
    {
        $value = $this->data[$field] ?? '';
        
        if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->addError($field, 'email');
            return false;
        }
        
        return true;
    }
    
    /**
     * Validar unicidad en la base de datos
     * 
     * @param string $field Nombre del campo
     * @param string $table Nombre de la tabla
     * @return bool
     */
    private function validateUnique($field, $table)
    {
        $value = $this->data[$field] ?? '';
        
        if (!empty($value)) {
            // Verificar si el valor ya existe
            if ($table === 'users' && User::findByEmail($value)) {
                $this->addError($field, 'unique');
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Validar que dos campos sean iguales
     * 
     * @param string $field Nombre del campo
     * @param string $otherField Nombre del otro campo
     * @return bool
     */
    private function validateSame($field, $otherField)
    {
        $value = $this->data[$field] ?? '';
        $otherValue = $this->data[$otherField] ?? '';
        
        if ($value !== $otherValue) {
            $this->addError($field, 'same');
            return false;
        }
        
        return true;
    }
    
    /**
     * Validar que la contraseña sea segura
     * 
     * @param string $field Nombre del campo
     * @return bool
     */
    private function validateStrongPassword($field)
    {
        $value = $this->data[$field] ?? '';
        
        // Al menos una letra mayúscula, una minúscula, un número y un carácter especial
        $hasUpperCase = preg_match('/[A-Z]/', $value);
        $hasLowerCase = preg_match('/[a-z]/', $value);
        $hasNumber = preg_match('/[0-9]/', $value);
        $hasSpecialChar = preg_match('/[^A-Za-z0-9]/', $value);
        
        if (!$hasUpperCase || !$hasLowerCase || !$hasNumber || !$hasSpecialChar) {
            $this->addError($field, 'strong_password');
            return false;
        }
        
        return true;
    }
    
    /**
     * Añadir un error de validación
     * 
     * @param string $field Nombre del campo
     * @param string $rule Nombre de la regla
     * @param array $params Parámetros para el mensaje
     */
    private function addError($field, $rule, $params = [])
    {
        $message = $this->messages[$rule] ?? $rule;
        
        // Obtener la traducción si es necesario
        if (strpos($message, '.') !== false) {
            $translatedMessage = __($message, $params);
        } else {
            $translatedMessage = $message;
        }
        
        $this->errors[$field] = $translatedMessage;
    }
} 