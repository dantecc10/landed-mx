<?php
function contains_string($main_string, $substring)
{
    // strpos devuelve la posición donde se encuentra la subcadena
    // Si no se encuentra, devuelve false
    return strpos($main_string, $substring) !== false;
}
function split_urls($urls)
{
    if (contains_string($urls, ", ")) {
        $img_urls = array();
        $img_urls = explode(", ", $urls);
    } else {
        $img_urls = $urls;
    }
    return $img_urls; // Usar como $data = split_urls($cadena_con_urls); (Validar con length)
}
function splitter($urls, $divisor)
{
    $strings_array = array();
    $strings_array = explode($divisor, $urls);
    return $strings_array; // Usar como $data = split_urls($cadena_con_urls); (Validar con length)
}
/* Validación de array
if (is_array(split_urls($urls))) {
    $img = (split_urls($urls))[0];
} else {
    $img = split_urls($urls);
}
*/
function build_table_dom($table, $data, $fields)
{
    switch ($table) {
        case 'juguetes':
            break;

        default:
            # code...
            break;
    }
}
function build_detail_carousel($imgs)
{
    $carousel = '';
    $n = sizeof($imgs);

    $carousel_capusule1 = '<div id="carousel-1" class="carousel slide" data-bs-ride="carousel">';
    $carousel_capusule2 = '</div>';

    $carousel_elements = '<div class="carousel-inner">';
    $carousel_indexes = '<ol class="carousel-indicators">';

    $carousel_buttons = '<div>
    <a class="carousel-control-prev" href="#carousel-1" role="button" data-bs-slide="prev">
      <span class="carousel-control-prev-icon"></span>
      <span class="visually-hidden">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carousel-1" role="button" data-bs-slide="next">
      <span class="carousel-control-next-icon"></span>
      <span class="visually-hidden">Next</span>
    </a>
  </div>';

    for ($i = 0; $i < $n; $i++) {
        $element = '<div class="carousel-item">';
        $index = '<li data-bs-target="#carousel-1" data-bs-slide-to="';

        if ($i == 0) {
            // Añadir clases active al primer elemento e índice
            $element = str_replace('">', ' active">', $element);
            $index = str_replace('<li ', '<li class="active" ', $index);
        }

        $element .= ('<img class="w-100 d-block" src="' . ($imgs[$i]) . '" alt="Imagen ' . ($i + 1) . '" />');
        $index .= $i;

        $element .= '</div>';
        $index .= '"></li>';

        $carousel_elements .= $element;
        $carousel_indexes .= $index;
    }

    $carousel_elements .= '</div>';
    $carousel_indexes .= '</ol>';
    $carousel .= ($carousel_capusule1 . $carousel_elements . $carousel_buttons . $carousel_indexes . $carousel_capusule2);
    return $carousel;
    /*
        Invocación:
        build_detail_carousel($split_urls($data[$10])) // $data[10] es imgs de SQL
    */
}
function avatar_img($src)
{
    if ($src == "" || $src == null) {
        if (isset($_SESSION['id'])) {
            $final_src = $_SESSION['img'];
        } else {
            $final_src = "assets/img/avatars/avatar5.jpeg";
        }
    } else {
        $final_src = $src;
    }

    $avatar_img = ('<img class="border rounded-circle img-profile" src="' . $final_src . '">');
    return $avatar_img;
}

function fecha()
{
    $day = date('l');
    $month = date('m');
    $year = date('Y');
    switch ($day) {
        case "Monday":
            $str_day = "Lunes";
            break;
        case "Tuesday":
            $str_day = "Martes";
            break;
        case "Wednesday":
            $str_day = "Miercoles";
            break;
        case "Thursday":
            $str_day = " Jueves";
            break;
        case "Friday":
            $str_day = "Viernes";
            break;
        case "Saturday":
            $str_day = "Sábado";
            break;
        case "Sunday":
            $str_day = "Domingo";
            break;
    }

    $day = date('j');

    switch ($month) {
        case '01':
            $month = 'Enero';
            break;
        case '02':
            $month = 'Febrero';
            break;
        case '03':
            $month = 'Marzo';
            break;
        case '04':
            $month = 'Abril';
            break;
        case '05':
            $month = 'Mayo';
            break;
        case '06':
            $month = 'Junio';
            break;
        case '07':
            $month = 'Julio';
            break;
        case '08':
            $month = 'Agosto';
            break;
        case '09':
            $month = 'Septiembre';
            break;
        case '10':
            $month = 'Octubre';
            break;
        case '11':
            $month = 'Noviembre';
            break;
        case '12':
            $month = 'Diciembre';
            break;
    }

    $fecha = ($str_day . " " . $day . " de " . lcfirst($month) . " del " . $year);
    $hora = date('H:i:s');
    return ($fecha . " " . $hora);
}

function sql_insertion_get_id($data, $table)
{
    include "connection.php";
    if (($_SESSION['email'] == "demo_user@system.com") OR ($_SESSION['user'] == "demo_user")) {
        $connection = new mysqli("localhost", "comercial_demo", $data[1], "comercial_demo");
    }
    // Realizar la inserción en la base de datos
    // INSERT INTO `transacciones` VALUES('', ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP);
    $sql = ("INSERT INTO `" . $table . "` VALUES ('', ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)");
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("sisdsi", $data[0], $data[1], $data[2], $data[3], $data[4], $data[5]);
    $stmt->execute();

    // Obtener el ID de la última inserción con conexión de tipo PDO mysqli
    $id_transaction = $connection->insert_id;
    return $id_transaction;
}

function sql_transaction_insert($params, $table)
{
    include_once "credentials.php";
    $data = generatePasskey('sql'); // No es un bug, es una feature, je, je
    if (($_SESSION['email'] == "demo_user@system.com") OR ($_SESSION['user'] == "demo_user")) {
        $connection = new mysqli("localhost", "comercial_demo", $data[1], "comercial_demo");
    }else{
        $connection = new mysqli("localhost", $data[0], $data[1], $data[2]);
    }
    if ($connection->connect_error) {
        die("La conexión a la base de datos falló: " . $connection->connect_error);
    } else {
        #echo ("Conexión establecida"); # Confirmación de conexión
    }
    // Consulta para la inserción
    // INSERT INTO `transacciones` VALUES('', 'Physical', 1, 'juguetes', 671.00, 'sale', 1, CURRENT_TIMESTAMP);
    $channel = strval($params[0]);
    $quantity = intval($params[1]);
    $categories = strval($params[2]);
    $amount = floatval($params[3]);
    $type = strval($params[4]);
    $user = intval($params[5]);
    $sql = ("INSERT INTO `" . $table . "` VALUES ('', '" . $channel . "', " . $quantity . ", '" . $categories . "', " . $amount . ", '" . $type . "', " . $user . ", CURRENT_TIMESTAMP)");

    // Ejecutar la consulta
    if ($connection->query($sql) === TRUE) {
        $last_id = $connection->insert_id;
    } else {
        //echo "Error en la inserción: " . $conexion->error;
    }

    // Cerrar la conexión
    $connection->close();
    return $last_id;
}

function get_last_insert_id($connection)
{
    return $connection->insert_id;
}

function import_env_configs()
{
    require_once 'vendor/autoload.php';
    $dotenv = Dotenv\Dotenv::createImmutable('/var/www/vhosts/castelancarpinteyro.com/comercial.castelancarpinteyro.com');
    return $dotenv;
    //$dotenv->load();
}