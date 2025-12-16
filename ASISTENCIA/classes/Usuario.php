<?php
// Incluir la clase base
include_once 'BaseModel.php';

class Usuario extends BaseModel {
    private $id_usuario;
    private $username;
    private $password; // ¡Solo para hashing/verificación!
    private $rol;

    public $table_name = "USUARIO";

    // ... getters y setters si son necesarios ...

    // Función para el login
    public function login($username, $password_ingresada) {
        // 1. Consultar el usuario por username
        $query = "SELECT ID_USUARIO, USERNAME, PASSWORD, ROL, ID_DOCENTE
                  FROM " . $this->table_name . "
                  WHERE USERNAME = :username
                  LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row){
            // 2. Verificar la contraseña hasheada
            if(password_verify($password_ingresada, $row['PASSWORD'])){
                // Login exitoso
                $this->id_usuario = $row['ID_USUARIO'];
                $this->username = $row['USERNAME'];
                $this->rol = $row['ROL'];
                return $row; // Devuelve los datos del usuario
            }
        }
        return false; // Login fallido
    }
    
    // Ejemplo de método para registrar un nuevo usuario (ADMIN)
    public function create($username, $password, $rol, $id_docente = null) {
        $query = "INSERT INTO " . $this->table_name . "
                  SET USERNAME=:username, PASSWORD=:password, ROL=:rol, ID_DOCENTE=:id_docente";
        
        $stmt = $this->conn->prepare($query);

        // Limpieza de datos y hashing de la contraseña
        $username = htmlspecialchars(strip_tags($username));
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $rol = htmlspecialchars(strip_tags($rol));

        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':rol', $rol);
        $stmt->bindParam(':id_docente', $id_docente);

        if($stmt->execute()){
            return true;
        }
        return false;
    }
}
?>