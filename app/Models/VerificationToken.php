<?php

namespace App\Models;

use App\Config\Database;

/**
 * Modelo VerificationToken - Gestiona los tokens de verificación de email
 * 
 * @package App\Models
 */
class VerificationToken
{
    /**
     * ID del token
     * 
     * @var int
     */
    private $id;
    
    /**
     * ID del usuario asociado
     * 
     * @var int
     */
    private $user_id;
    
    /**
     * Token de verificación
     * 
     * @var string
     */
    private $token;
    
    /**
     * Fecha de creación
     * 
     * @var string
     */
    private $created_at;
    
    /**
     * Fecha de expiración
     * 
     * @var string
     */
    private $expires_at;
    
    /**
     * Constructor
     * 
     * @param array $attributes Atributos iniciales
     */
    public function __construct(array $attributes = [])
    {
        $this->created_at = date('Y-m-d H:i:s');
        // Por defecto, los tokens expiran en 24 horas
        $this->expires_at = date('Y-m-d H:i:s', strtotime('+24 hours'));
        
        foreach ($attributes as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }
    
    /**
     * Crear un nuevo token de verificación
     * 
     * @param int $userId ID del usuario
     * @param int $expirationHours Horas hasta la expiración (por defecto 24)
     * @return VerificationToken|false
     */
    public static function create($userId, $expirationHours = 24)
    {
        $db = Database::getInstance()->getConnection();
        
        try {
            // Generar un token seguro
            $token = bin2hex(random_bytes(32));
            
            // Calcular fecha de expiración
            $expiresAt = date('Y-m-d H:i:s', strtotime("+{$expirationHours} hours"));
            
            // Eliminar tokens anteriores para este usuario
            self::deleteAllForUser($userId);
            
            // Crear el nuevo token
            $verificationToken = new self([
                'user_id' => $userId,
                'token' => $token,
                'expires_at' => $expiresAt
            ]);
            
            // Preparar la consulta SQL
            $sql = "INSERT INTO verification_tokens (user_id, token, created_at, expires_at) VALUES (?, ?, ?, ?)";
            
            $stmt = $db->prepare($sql);
            $stmt->bind_param(
                "isss",
                $verificationToken->user_id,
                $verificationToken->token,
                $verificationToken->created_at,
                $verificationToken->expires_at
            );
            
            // Ejecutar la consulta
            if ($stmt->execute()) {
                $verificationToken->id = $db->insert_id;
                return $verificationToken;
            }
            
            return false;
        } catch (\Exception $e) {
            error_log("Error al crear token de verificación: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Buscar un token por su valor
     * 
     * @param string $token Valor del token
     * @return VerificationToken|null
     */
    public static function findByToken($token)
    {
        $db = Database::getInstance()->getConnection();
        
        $stmt = $db->prepare("SELECT * FROM verification_tokens WHERE token = ? AND expires_at > NOW()");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return null;
        }
        
        return new self($result->fetch_assoc());
    }
    
    /**
     * Verificar si un token es válido
     * 
     * @return bool
     */
    public function isValid()
    {
        // Verificar que no ha expirado
        return strtotime($this->expires_at) > time();
    }
    
    /**
     * Eliminar el token actual
     * 
     * @return bool
     */
    public function delete()
    {
        $db = Database::getInstance()->getConnection();
        
        $stmt = $db->prepare("DELETE FROM verification_tokens WHERE id = ?");
        $stmt->bind_param("i", $this->id);
        
        return $stmt->execute();
    }
    
    /**
     * Eliminar todos los tokens de un usuario
     * 
     * @param int $userId ID del usuario
     * @return bool
     */
    public static function deleteAllForUser($userId)
    {
        $db = Database::getInstance()->getConnection();
        
        $stmt = $db->prepare("DELETE FROM verification_tokens WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        
        return $stmt->execute();
    }
    
    /**
     * Obtener el ID del token
     * 
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Obtener el ID del usuario
     * 
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
    }
    
    /**
     * Obtener el valor del token
     * 
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }
    
    /**
     * Obtener la fecha de creación
     * 
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }
    
    /**
     * Obtener la fecha de expiración
     * 
     * @return string
     */
    public function getExpiresAt()
    {
        return $this->expires_at;
    }
} 