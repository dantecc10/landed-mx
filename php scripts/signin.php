<?php
include_once "connection.php";

if ($connection->connect_error) {
    die("La conexión a la base de datos falló: " . $connection->connect_error);
} # else { echo ("Conexión establecida");}

// Cerrar la conexión a la base de datos
if (isset($_POST['email'])) {
    session_start();
    $_SESSION['email'] = $_POST['email'];
    // Crear consulta bind_param
    $sql = ("SELECT * FROM `students` WHERE (`email_student` = ?);");
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("s", $_POST['email']);

    // Buscar un estudiante que coincida
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows == 1) {
            // El registro existe, obtener el valor del estado
            #echo "Estudiante encontrado";
            header("Location: ../signin.php?error=existent");
        } else {
            //echo "No hay un estudiante con esos datos";
            $sql = ("SELECT * FROM `educators` WHERE (`email_educator` = ?);");
            $stmt = $connection->prepare($sql);
            $stmt->bind_param("s", $_POST['email']);

            if ($result->num_rows == 1) {
                // El registro existe, obtener el valor del estado

                #echo "Educador encontrado";
                header("Location: ../signin.php?error=existent");
            } else {
                #echo "No hay educador que coincida";
                $sql = "INSERT INTO `students` VALUES('', ?, ?, ?, ?, ?, 0, ?, ?, ?, '');";
                $stmt = $connection->prepare($sql);

                $birth = new DateTime($_POST['date']);
                $curr_date = new DateTime();
                $difference = $curr_date->diff($birth);
                $age = $difference->y;
                switch ($_POST['genre']) {
                    case '1':
                        $genre = 'H';
                        break;
                    case '2':
                        $genre = 'M';
                        break;
                    default:
                        $genre = 'O';
                        break;
                }

                $stmt->bind_param("sssssiss", $_POST['name'], $_POST['last_names'], $_POST['email'], $_POST['password'], $_post['phone_number'], $age, $_POST['date'], $genre);
                if ($stmt->execute()) {
                    $_SESSION['verifier_id'] = $connection->insert_id;
                    header("Location: auth_key.php");
                } else {
                    header("Location: ../signin.php?error=true");
                }
            }
        }
    } else {
        echo "Error en la consulta";
    }

    // Cerrar la conexión
    $stmt->close();
} else {
    header("Location: ../signin.php?error=true");
}
