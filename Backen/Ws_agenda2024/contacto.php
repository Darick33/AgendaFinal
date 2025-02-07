<?php
include('config.php');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, Accept');
header('Content-Type: application/json; charset=utf-8');

$post = json_decode(file_get_contents("php://input"), true);
if ($post['accion'] == 'insertar') {
    $nombre = mysqli_real_escape_string($mysqli, $post['nombre']);
    $apellido = mysqli_real_escape_string($mysqli, $post['apellido']);
    $telefono = mysqli_real_escape_string($mysqli, $post['telefono']);
    $email = mysqli_real_escape_string($mysqli, $post['email']);
    $persona_cod_persona = mysqli_real_escape_string($mysqli, $post['persona_cod_persona']); // Asumimos que se pasa el código de la persona

    $sentencia = sprintf("INSERT INTO contacto (nom_contacto, ape_contacto, telefono_contacto, email_contacto, persona_cod_persona) 
                        VALUES ('%s', '%s', '%s', '%s', '%s')", 
                        $nombre, $apellido, $telefono, $email, $persona_cod_persona);
    
    $rs = mysqli_query($mysqli, $sentencia);
    if ($rs) {
        $respuesta = json_encode(array('estado' => true, 'mensaje' => 'Datos guardados correctamente'));
    } else {
        $respuesta = json_encode(array('estado' => false, 'mensaje' => 'Error al guardar'));
    }
    echo $respuesta;
}
if ($post['accion'] == 'actualizar') {
    $codigo = mysqli_real_escape_string($mysqli, $post['codigo']);
    $nombre = mysqli_real_escape_string($mysqli, $post['nombre']);
    $apellido = mysqli_real_escape_string($mysqli, $post['apellido']);
    $telefono = mysqli_real_escape_string($mysqli, $post['telefono']);
    $email = mysqli_real_escape_string($mysqli, $post['email']);
    $persona_cod_persona = mysqli_real_escape_string($mysqli, $post['persona_cod_persona']);

    $sentencia = sprintf("UPDATE contacto SET nom_contacto='%s', ape_contacto='%s', telefono_contacto='%s', email_contacto='%s', persona_cod_persona='%s' 
                        WHERE cod_contacto='%s'", 
                        $nombre, $apellido, $telefono, $email, $persona_cod_persona, $codigo);
    
    $rs = mysqli_query($mysqli, $sentencia);
    if ($rs) {
        $respuesta = json_encode(array('estado' => true, 'mensaje' => 'Datos actualizados correctamente'));
    } else {
        $respuesta = json_encode(array('estado' => false, 'mensaje' => 'Error al actualizar'));
    }
    echo $respuesta;
}

if ($post['accion'] == 'eliminar') {
    $codigo = mysqli_real_escape_string($mysqli, $post['codigo']);

    $sentencia = sprintf("DELETE FROM contacto WHERE cod_contacto='%s'", $codigo);

    $rs = mysqli_query($mysqli, $sentencia);
    if ($rs) {
        $respuesta = json_encode(array('estado' => true, 'mensaje' => 'Datos eliminados correctamente'));
    } else {
        $respuesta = json_encode(array('estado' => false, 'mensaje' => 'Error al eliminar'));
    }
    echo $respuesta;
}

// Consultar contactos
if ($post['accion'] == 'consultar') {
    $sentencia = "SELECT * FROM contacto";
    $rs = mysqli_query($mysqli, $sentencia);
    if (mysqli_num_rows($rs) > 0) {
        while ($row = mysqli_fetch_array($rs)) {
            $datos[] = array(
                'codigo' => $row['cod_contacto'],
                'nombre' => $row['nom_contacto'],
                'apellido' => $row['ape_contacto'],
                'telefono' => $row['telefono_contacto'],
                'email' => $row['email_contacto'],
                'persona_cod_persona' => $row['persona_cod_persona']
            );
        }
        $respuesta = json_encode(array('estado' => true, 'contactos' => $datos));
    } else {
        $respuesta = json_encode(array('estado' => false, 'mensaje' => 'No existen registros'));
    }
    echo($respuesta);
}

// Consultar un solo contacto (por código)
if ($post['accion'] == 'dato') {
    $codigo = mysqli_real_escape_string($mysqli, $post['codigo']);
    $sentencia = "SELECT * FROM contacto WHERE cod_contacto = '$codigo'";
    $rs = mysqli_query($mysqli, $sentencia);

    if (mysqli_num_rows($rs) > 0) {
        // En lugar de un array, solo devolver un objeto para el contacto
        $row = mysqli_fetch_array($rs);
        $datos = array(
            'codigo' => $row['cod_contacto'],
            'nombre' => $row['nom_contacto'],
            'apellido' => $row['ape_contacto'],
            'telefono' => $row['telefono_contacto'],
            'email' => $row['email_contacto'],
            'persona_cod_persona' => $row['persona_cod_persona']
        );
        // Respuesta con el objeto 'contacto'
        $respuesta = json_encode(array('estado' => true, 'contacto' => $datos));
    } else {
        $respuesta = json_encode(array('estado' => false, 'mensaje' => 'No existen registros'));
    }
    echo($respuesta);
}
?>
