<?php

$db = new mysqli();
$db->connect('localhost', 'root', '', 'dbsabe');


if ($db->connect_errno) {
    echo "Error al conectar a la base de datos: " . $db->connect_error;
    exit();
}

?>