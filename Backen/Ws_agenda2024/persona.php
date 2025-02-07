<?php
include('config.php');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, Accept');
header('Content-Type: application/json; charset=utf-8');

// Decodificar el JSON recibido
$post = json_decode(file_get_contents("php://input"), true);

// Insertar nueva persona
if ($post['accion'] == 'insertar') {
    $cedula = mysqli_real_escape_string($mysqli, $post['cedula']);
    $nombre = mysqli_real_escape_string($mysqli, $post['nombre']);
    $apellido = mysqli_real_escape_string($mysqli, $post['apellido']);
    $clave = mysqli_real_escape_string($mysqli, $post['clave']);
    $correo = mysqli_real_escape_string($mysqli, $post['correo']);

    $sentencia = sprintf("INSERT INTO persona (ci_persona, nom_persona, ape_persona, clave_persona, correo_persona) 
                        VALUES ('%s', '%s', '%s', '%s', '%s')", $cedula, $nombre, $apellido, $clave, $correo);
    
    $rs = mysqli_query($mysqli, $sentencia);
    if ($rs) {
        $respuesta = json_encode(array('estado' => true, 'mensaje' => 'Datos guardados correctamente'));
    } else {
        $respuesta = json_encode(array('estado' => false, 'mensaje' => 'Error al guardar'));
    }
    echo $respuesta;
}

// Actualizar persona
if ($post['accion'] == 'actualizar') {
    $codigo = mysqli_real_escape_string($mysqli, $post['codigo']);
    $cedula = mysqli_real_escape_string($mysqli, $post['cedula']);
    $nombre = mysqli_real_escape_string($mysqli, $post['nombre']);
    $apellido = mysqli_real_escape_string($mysqli, $post['apellido']);
    $clave = mysqli_real_escape_string($mysqli, $post['clave']);
    $correo = mysqli_real_escape_string($mysqli, $post['correo']);

    $sentencia = sprintf("UPDATE persona SET ci_persona='%s', nom_persona='%s', ape_persona='%s', clave_persona='%s', correo_persona='%s' 
                        WHERE cod_persona='%s'", $cedula, $nombre, $apellido, $clave, $correo, $codigo);
    
    $rs = mysqli_query($mysqli, $sentencia);
    if ($rs) {
        $respuesta = json_encode(array('estado' => true, 'mensaje' => 'Datos actualizados correctamente'));
    } else {
        $respuesta = json_encode(array('estado' => false, 'mensaje' => 'Error al actualizar'));
    }
    echo $respuesta;
}

// Eliminar persona
if ($post['accion'] == 'eliminar') {
    $codigo = mysqli_real_escape_string($mysqli, $post['codigo']);

    $sentencia = sprintf("DELETE FROM persona WHERE cod_persona='%s'", $codigo);

    $rs = mysqli_query($mysqli, $sentencia);
    if ($rs) {
        $respuesta = json_encode(array('estado' => true, 'mensaje' => 'Datos eliminados correctamente'));
    } else {
        $respuesta = json_encode(array('estado' => false, 'mensaje' => 'Error al eliminar'));
    }
    echo $respuesta;
}

// Consultar personas (como ya lo tenías)
if ($post['accion'] == 'consultar') {
    $sentencia = "SELECT * FROM persona";
    $rs = mysqli_query($mysqli, $sentencia);
    if (mysqli_num_rows($rs) > 0) {
        while ($row = mysqli_fetch_array($rs)) {
            $datos[] = array(
                'codigo' => $row['cod_persona'],
                'nombre' => $row['nom_persona'],
                'apellido' => $row['ape_persona'],
                'cedula' => $row['ci_persona'],
            );
        }
        $respuesta = json_encode(array('estado' => true, 'personas' => $datos));
    } else {
        $respuesta = json_encode(array('estado' => false, 'mensaje' => 'No existen registros'));
    }
    echo($respuesta);
}
if ($post['accion'] == 'dato') {
    $codigo = mysqli_real_escape_string($mysqli, $post['codigo']);
    $sentencia = "SELECT * FROM persona WHERE cod_persona = '$codigo'";
    $rs = mysqli_query($mysqli, $sentencia);

    if (mysqli_num_rows($rs) > 0) {
        // En lugar de un array, solo devolver un objeto para la persona
        $row = mysqli_fetch_array($rs);
        $datos = array(
            'codigo' => $row['cod_persona'],
            'nombre' => $row['nom_persona'],
            'apellido' => $row['ape_persona'],
            'cedula' => $row['ci_persona'],
            'clave' => $row['clave_persona'], // Añadido campo 'clave' si es necesario
            'correo' => $row['correo_persona'] // Añadido campo 'correo' si es necesario
        );
        // Respuesta con el objeto 'persona'
        $respuesta = json_encode(array('estado' => true, 'persona' => $datos));
    } else {
        $respuesta = json_encode(array('estado' => false, 'mensaje' => 'No existen registros'));
    }
    echo($respuesta);
}

?>
