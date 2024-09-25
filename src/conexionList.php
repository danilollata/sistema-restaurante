<?php
header('Content-Type: application/json');
include("../conexion.php"); 

$sql = "SELECT nombre, imagen FROM platos";
$result = $conexion->query($sql);

$platos = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $platos[] = $row;
    }
}

echo json_encode(array('platos' => $platos));
?>
