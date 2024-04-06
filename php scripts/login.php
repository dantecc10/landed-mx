<?php
session_start();
session_destroy();
session_start();
include_once "connection.php";

if ($connection->connect_error) {
    die("La conexión a la base de datos falló: " . $connection->connect_error);
} else {
    echo ("Conexión establecida");
}

// Cerrar la conexión a la base de datos
if (isset($_POST['email']) && isset($_POST['password'])) {
    // Crear consulta bind_param
    $sql = ("SELECT * FROM `students` WHERE (`email_student` = ? AND `password_student` = ?);");
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ss", $_POST['email'], $_POST['password']);

    // Buscar un estudiante que coincida
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows == 1) {
            // El registro existe, obtener el valor del estado
            $row = $result->fetch_assoc();
            $_SESSION["logged"] = true;
            $_SESSION['id_student'] = $row['id_student'];
            $_SESSION['name_student'] = $row['name_student'];
            $_SESSION['last_names_student'] = $row['last_names_student'];
            $_SESSION['email_student'] = $row['email_student'];
            //$_SESSION['password_student'] = $row['password_student'];
            $_SESSION['phone_number_student'] = $row['phone_number_student'];
            $_SESSION['subscription_plan_id_student'] = $row['subscription_plan_id_student'];
            $_SESSION['age_student'] = $row['age_student'];
            $_SESSION['birth_student'] = $row['birth_student'];
            $_SESSION['about_me_student'] = $row['about_me_student'];
            $_SESSION['icon_img_student'] = $row['icon_img_student'];
        } else {
            echo "Más de un usuario o ninguno";
        }
    } else {
        echo "Error en la consulta";
    }

    // Cerrar la conexión
    $stmt->close();
} else {
    header("Location: ../login.php?error=true");
}