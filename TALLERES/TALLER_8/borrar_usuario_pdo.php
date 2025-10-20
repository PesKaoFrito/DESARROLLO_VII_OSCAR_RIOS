<?php
    require_once "config_pdo.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = $pdo->prepare($conn, $_POST['id']);
        $sql = "DELETE FROM usuarios WHERE id = ?";
        
        if($stmt = $pdo->prepare($sql)){
          $stmt->bindParam(":id",$id,PDO::PARAM_INT);

            if ($stmt->execute()) {
                echo "Usuario eliminado con éxito.";
            } else {
                echo "ERROR: No se pudo ejecutar $sql.";
            }
        }    
    }
?>