<?php

namespace App\Models;

use App\Config\Database;

/**
 * Modelo User - Representa a un usuario del sistema
 * 
 * @package App\Models
 */
class User
{
    /**
     * ID del usuario
     * 
     * @var int
     */
    private $id;

    /**
     * Nombre completo del usuario
     * 
     * @var string
     */
    private $name;

    /**
     * Email del usuario
     * 
     * @var string
     */
    private $email;

    /**
     * Contraseña encriptada
     * 
     * @var string
     */
    private $password;

    /**
     * Estado de la cuenta
     * 
     * @var int
     */
    private $status;

    /**
     * Rol del usuario
     * 
     * @var int
     */
    private $role;

    /**
     * Fecha de verificación del email
     * 
     * @var string|null
     */
    private $email_verified_at;

    /**
     * Idioma preferido del usuario
     * 
     * @var string
     */
    private $language;

    /**
     * Fecha del último inicio de sesión
     * 
     * @var string|null
     */
    private $last_login;

    /**
     * Token para recordar sesión
     * 
     * @var string|null
     */
    private $remember_token;

    /**
     * Fecha de creación
     * 
     * @var string
     */
    private $created_at;

    /**
     * Fecha de última actualización
     * 
     * @var string
     */
    private $updated_at;

    /**
     * Constructor
     * 
     * @param array $attributes Atributos iniciales
     */
    public function __construct(array $attributes = [])
    {
        // Establecer valores por defecto
        $this->status = \USER_STATUS_PENDING;
        $this->role = \USER_ROLE_USER;
        $this->language = config('app.locale', 'es');
        
        // Establecer fecha actual para created_at y updated_at
        $this->created_at = date('Y-m-d H:i:s');
        $this->updated_at = date('Y-m-d H:i:s');
        
        // Asignar atributos proporcionados
        foreach ($attributes as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    /**
     * Crear un nuevo usuario
     * 
     * @param array $data Datos del usuario
     * @return User|false
     */
    public static function create(array $data)
    {
        $db = Database::getInstance()->getConnection();
        
        try {
            // Encriptar la contraseña
            $data['password'] = self::hashPassword($data['password']);
            
            // Preparar datos para inserción
            $user = new self($data);
            
            // Preparar la consulta SQL
            $sql = "INSERT INTO users (name, email, password, status, role, language, created_at, updated_at) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $db->prepare($sql);
            $stmt->bind_param(
                "sssiisss",
                $user->name,
                $user->email,
                $user->password,
                $user->status,
                $user->role,
                $user->language,
                $user->created_at,
                $user->updated_at
            );
            
            // Ejecutar la consulta
            if ($stmt->execute()) {
                $user->id = $db->insert_id;
                return $user;
            }
            
            return false;
        } catch (\Exception $e) {
            // Registrar el error
            error_log("Error al crear usuario: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Buscar un usuario por su ID
     * 
     * @param int $id ID del usuario
     * @return User|null
     */
    public static function find($id)
    {
        $db = Database::getInstance()->getConnection();
        
        $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return null;
        }
        
        return new self($result->fetch_assoc());
    }

    /**
     * Buscar un usuario por su email
     * 
     * @param string $email Email del usuario
     * @return User|null
     */
    public static function findByEmail($email)
    {
        $db = Database::getInstance()->getConnection();
        
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return null;
        }
        
        return new self($result->fetch_assoc());
    }

    /**
     * Actualizar un usuario
     * 
     * @param array $data Datos a actualizar
     * @return bool
     */
    public function update(array $data)
    {
        $db = Database::getInstance()->getConnection();
        
        // Actualizar propiedades
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
        
        // Actualizar fecha de modificación
        $this->updated_at = date('Y-m-d H:i:s');
        
        // Construir la consulta dinámica
        $updates = [];
        $params = [];
        $types = '';
        
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $updates[] = "{$key} = ?";
                $params[] = $value;
                
                // Determinar el tipo para bind_param
                if (is_int($value)) {
                    $types .= 'i';
                } elseif (is_float($value)) {
                    $types .= 'd';
                } else {
                    $types .= 's';
                }
            }
        }
        
        // Añadir updated_at
        $updates[] = "updated_at = ?";
        $params[] = $this->updated_at;
        $types .= 's';
        
        // Añadir el ID al final para el WHERE
        $params[] = $this->id;
        $types .= 'i';
        
        $sql = "UPDATE users SET " . implode(', ', $updates) . " WHERE id = ?";
        
        $stmt = $db->prepare($sql);
        
        // Aplicar tipos y parámetros dinámicamente
        $stmt->bind_param($types, ...$params);
        
        return $stmt->execute();
    }

    /**
     * Verificar el email del usuario
     * 
     * @return bool
     */
    public function markEmailAsVerified()
    {
        $this->email_verified_at = date('Y-m-d H:i:s');
        $this->status = \USER_STATUS_ACTIVE;
        
        return $this->update([
            'email_verified_at' => $this->email_verified_at,
            'status' => $this->status
        ]);
    }

    /**
     * Comprobar si un usuario tiene el email verificado
     * 
     * @return bool
     */
    public function hasVerifiedEmail()
    {
        return $this->email_verified_at !== null;
    }

    /**
     * Verificar si una contraseña coincide con la del usuario
     * 
     * @param string $password Contraseña a verificar
     * @return bool
     */
    public function checkPassword($password)
    {
        return password_verify($password, $this->password);
    }

    /**
     * Generar hash de contraseña
     * 
     * @param string $password Contraseña en texto plano
     * @return string
     */
    public static function hashPassword($password)
    {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    }

    /**
     * Establecer el último inicio de sesión
     * 
     * @return bool
     */
    public function updateLastLogin()
    {
        $this->last_login = date('Y-m-d H:i:s');
        
        return $this->update([
            'last_login' => $this->last_login
        ]);
    }

    /**
     * Establecer idioma del usuario
     * 
     * @param string $language Código de idioma
     * @return bool
     */
    public function setLanguage($language)
    {
        if (array_key_exists($language, \AVAILABLE_LANGUAGES)) {
            $this->language = $language;
            
            return $this->update([
                'language' => $language
            ]);
        }
        
        return false;
    }

    /**
     * Generar token de recuerdo
     * 
     * @return string
     */
    public function generateRememberToken()
    {
        $token = bin2hex(random_bytes(32));
        $this->remember_token = $token;
        
        $this->update([
            'remember_token' => $token
        ]);
        
        return $token;
    }

    /**
     * Obtener el ID
     * 
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Obtener el nombre
     * 
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Obtener el email
     * 
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Obtener el estado
     * 
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Obtener el rol
     * 
     * @return int
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Comprobar si el usuario es administrador
     * 
     * @return bool
     */
    public function isAdmin()
    {
        return $this->role === \USER_ROLE_ADMIN;
    }

    /**
     * Obtener el idioma
     * 
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }
} 