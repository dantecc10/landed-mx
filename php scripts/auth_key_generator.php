<?php
session_start();
if (isset($_GET['verifier'])) {
    $verifier = $_GET['verifier'];
    $email = $_GET['email'];
} else {
    $email = $_SESSION['email'];
    $verifier = $_SESSION['verifier_id'];
}
function generateKey($verifier)
{ // Operative ✅
    # $contadorDígitos = 0;
    $min = 100000;
    $max = 999999;
    # $dígitoAleatorioGenerado = rand(1, $max);
    $auth_key = rand($min, $max);
    //$auth_key = 486753; // Debug 🐞

    // Importar la conexión
    include_once "connection.php";

    $key_compare = intval($auth_key);
    $sql = "SELECT * FROM `auth_keys` WHERE ((`content_auth_key` = $key_compare) OR (`related_account_key` = $verifier)) AND (`status_key` = 0);";
    $result = $connection->query($sql);

    if ($result->num_rows > 0) {
        $connection->close();
        return null;
    } else {
        $connection->close();
        return $auth_key;
    }
}

$auth_key = generateKey($email);
$contador = 0;
while ($contador < 5) {
    //echo "Esto no se debería ver"; // Debug 🐞
    if ($auth_key == null) {
        $auth_key = generateKey($email);
    } else {
        include_once "connection.php";
        $sql = "INSERT INTO `auth_keys` VALUES ('', ?, ?, 0, CURRENT_TIMESTAMP())";
        $stmt = $connection->prepare($sql);
        // Limpiar y vincular los parámetros
        $stmt->bind_param("si", $auth_key, $verifier);
        #$clean_email = $connection->real_escape_string($email); //$clean_password = mysqli_real_escape_string($connection, $password);
        // Ejecutar la sentencia preparada
        $stmt->execute();

        // Verificar el éxito de la inserción
        if ($stmt->affected_rows > 0) {
            //echo "Generación y almacenamiento de clave exitosos."; // Debug 🐞
            $_SESSION["key"] = $auth_key;
            #$_SESSION["email"] = $email;
            header("Location: send_key_mail.php");
        } else {
            echo "Error al almacenar y/o generar la clave."; // Debug 🐞
        }

        // Cerrar la conexión
        $connection->close();
        break;
    }
    $contador++;
}
if ($contador == 5) {
    # code...
}
//echo $auth_key; // Debug 🐞
