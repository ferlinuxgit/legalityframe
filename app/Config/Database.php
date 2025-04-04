<?php

namespace App\Config;

/**
 * Clase Database - Gestiona la conexión a la base de datos
 * 
 * Implementa el patrón Singleton para asegurar una única conexión
 * a la base de datos durante toda la ejecución de la aplicación.
 * 
 * @package App\Config
 */
class Database
{
    /**
     * Instancia única de la clase Database
     * 
     * @var Database
     */
    private static $instance;
    
    /**
     * Conexión a la base de datos
     * 
     * @var \mysqli
     */
    private $connection;
    
    /**
     * Constructor privado para prevenir la creación de múltiples instancias
     */
    private function __construct()
    {
        $this->connect();
    }
    
    /**
     * Obtener la instancia única de Database
     * 
     * @return Database
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        
        return self::$instance;
    }
    
    /**
     * Establecer conexión con la base de datos
     */
    private function connect()
    {
        $dbConfig = $GLOBALS['config']['database'];
        
        $host = $dbConfig['host'];
        $username = $dbConfig['username'];
        $password = $dbConfig['password'];
        $database = $dbConfig['database'];
        $port = $dbConfig['port'];
        
        // Crear la conexión
        $this->connection = new \mysqli($host, $username, $password, $database, $port);
        
        // Verificar la conexión
        if ($this->connection->connect_error) {
            die("Error de conexión a la base de datos: " . $this->connection->connect_error);
        }
        
        // Establecer charset
        $this->connection->set_charset("utf8mb4");
    }
    
    /**
     * Obtener la conexión a la base de datos
     * 
     * @return \mysqli
     */
    public function getConnection()
    {
        // Comprobar si la conexión está activa
        if ($this->connection->ping() === false) {
            $this->connect();
        }
        
        return $this->connection;
    }
    
    /**
     * Cerrar la conexión a la base de datos
     */
    public function closeConnection()
    {
        if ($this->connection) {
            $this->connection->close();
        }
    }
    
    /**
     * Prevenir la clonación de la instancia
     */
    private function __clone()
    {
        // No permitir clonar la instancia
    }
    
    /**
     * Destructor - Cierra la conexión cuando finaliza el script
     */
    public function __destruct()
    {
        $this->closeConnection();
    }
} 