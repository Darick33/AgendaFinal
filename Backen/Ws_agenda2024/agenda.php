<?php

require'config.php';

$post = json_decode(file_get_contents('php://input'), true);
// ------------------------------LOGIN-----------------------------------------
if ($post['accion'] == 'login') {
    $sentencia = sprintf("SELECT * FROM persona WHERE ci_persona = '%s' AND clave_persona = '%s'", $post['usuario'], $post['clave']);

    $rs = mysqli_query($mysqli, $sentencia);

    if (mysqli_num_rows($rs) > 0) {
        while ($row = mysqli_fetch_assoc($rs)) {
            $data = array(
                'codigo' => $row ['cod_persona'],
                'nombre' => $row ['nom_persona'] . " " . $row['ape_persona'],
            );
        }
        $respuesta = json_encode(array('estado' => true, 'persona' => $data));
    } else {
        $respuesta = json_encode(array('estado' => false, 'mensaje' => 'Usuario o clave incorrectos'));
    }

    echo $respuesta;
}

// ------------------Verificacion Cedula-----------------------------------------
if ($post['accion'] == 'vcedula') {
    $sentencia = sprintf("SELECT * FROM persona WHERE ci_persona = '%s'", $post['ci']);

    $rs = mysqli_query($mysqli, $sentencia);

    if (mysqli_num_rows($rs) > 0) {
        $respuesta = json_encode(array('estado' => true));
    } else {
        $respuesta = json_encode(array('estado' => false));
    }

    echo $respuesta;
}

//-----------CREAR CUENTA------------------------------------------------------
if ($post['accion'] == 'cuenta') {
    $sentencia = sprintf("INSERT INTO persona (ci_persona, nom_persona, ape_persona, correo_persona, clave_persona) VALUES ('%s', '%s', '%s', '%s', '%s')", $post['cedula'], $post['nombre'], $post['apellido'], $post['correo'], $post['clave']);

    $rs = mysqli_query($mysqli, $sentencia);

    if ($rs) {
        $respuesta = json_encode(array('estado' => true, "mensaje" => "Cuenta creada correctamente"));
    } else {
        $respuesta = json_encode(array('estado' => false, "mensaje" => "Error al crear la cuenta"));
    }

    echo $respuesta;
}

if ($post['accion'] == 'datosPerfil') {

    // Seleccionamos toda la información de la persona por su ID
    $sentencia = sprintf("SELECT * FROM persona WHERE cod_persona = '%s'", $post['id']);

    $rs = mysqli_query($mysqli, $sentencia);
    if (mysqli_num_rows($rs) > 0) {
        while ($row = mysqli_fetch_assoc($rs)) {
            $data = array(
                'codigo' => $row['cod_persona'],
                'ci' => $row['ci_persona'],
                'nombre' => $row['nom_persona'],
                'apellido' => $row['ape_persona'],
                'correo' => $row['correo_persona'],
                'clave' => $row['clave_persona'],
            );
        }
        // Respuesta con los datos de la persona
        $respuesta = json_encode(array('estado' => true, 'persona' => $data));
    } else {
        // Si no se encuentra la persona
        $respuesta = json_encode(array('estado' => false, 'mensaje' => 'Persona no encontrada'));
    }
    echo $respuesta;
}

if ($post['accion'] == 'actualizarPerfil') {
    $sentencia = sprintf(
        "UPDATE persona SET nom_persona = '%s', ape_persona = '%s', correo_persona = '%s' WHERE cod_persona = '%s'",
        $post['nombre'],
        $post['apellido'],
        $post['correo'],
        $post['id']
    );

    $rs = mysqli_query($mysqli, $sentencia);

    if ($rs) {
        $respuesta = json_encode(array('estado' => true, "mensaje" => "Perfil actualizado correctamente"));
    } else {
        $respuesta = json_encode(array('estado' => false, "mensaje" => "Error al actualizar el perfil"));
    }

    echo $respuesta;
}

//--------Obtener datos de Perfil----------------------------------------------


if ($post['accion'] == 'lcontactos') {
    $sentencia = sprintf("SELECT * FROM contacto WHERE persona_cod_persona = '%s'", $post['codigo']);
    $rs = mysqli_query($mysqli, $sentencia);
    if (mysqli_num_rows($rs) > 0) {
        while ($row = mysqli_fetch_array($rs)) {
            $datos[] = array(
              'codigo' => $row['cod_contacto'],
              'nombre' => $row['nom_contacto'],
              'telefono' => $row['telefono_contacto'],
            );
        }
        $respuesta = json_encode(array('estado' => true,'data' => $datos));
    } else {

        $respuesta = json_encode(array('estado' => false,'mensaje' => "No hay contactos"));
    }
    echo $respuesta;
}
if ($post['accion'] == 'nuevoContacto') {
    $sentencia = sprintf(
        "INSERT INTO contacto(nom_contacto, ape_contacto, telefono_contacto, email_contacto, persona_cod_persona) 
    VALUES ('%s', '%s', '%s', '%s', '%s')",
        $post['nombre'],
        $post['apellido'],
        $post['telefono'],
        $post['correo'],
        $post['cod_persona']
    );

    $rs = mysqli_query($mysqli, $sentencia);

    if ($rs) {
        $respuesta = json_encode(array('estado' => true, "mensaje" => "Contacto creado correctamente"));
    } else {
        $respuesta = json_encode(array('estado' => false, "mensaje" => "Error al guardar el contacto"));
    }

    echo $respuesta;
}

if ($post['accion'] == 'preguntaSeguridad') {
    // Consultamos la pregunta de seguridad de la persona
    $sentencia = sprintf("SELECT pregunta FROM preguntas_seguridad WHERE cod_persona = (SELECT cod_persona FROM persona WHERE ci_persona = '%s')", $post['ci']);

    $rs = mysqli_query($mysqli, $sentencia);

    if (mysqli_num_rows($rs) > 0) {
        // Si se encuentra la pregunta, la retornamos
        $row = mysqli_fetch_assoc($rs);
        $respuesta = json_encode(array('estado' => true, 'pregunta' => $row['pregunta']));
    } else {
        // Si no hay pregunta de seguridad, retornamos un error
        $respuesta = json_encode(array('estado' => false, 'mensaje' => 'Pregunta no encontrada'));
    }

    echo $respuesta;
}

if ($post['accion'] == 'todasLasPreguntasSeguridad') {
    $sentencia = sprintf("SELECT pregunta, respuesta FROM preguntas_seguridad WHERE cod_persona = (SELECT cod_persona FROM persona WHERE ci_persona = '%s')", $post['ci']);

    $rs = mysqli_query($mysqli, $sentencia);

    if (mysqli_num_rows($rs) > 0) {
        $preguntasRespuestas = array();
        while ($row = mysqli_fetch_assoc($rs)) {
            $preguntasRespuestas[] = array(
                'pregunta' => $row['pregunta'],
                'respuesta' => $row['respuesta']
            );  
        }
        $respuesta = json_encode(array('estado' => true, 'preguntas_respuestas' => $preguntasRespuestas));
    } else {
        $respuesta = json_encode(array('estado' => false, 'mensaje' => 'No se encontraron preguntas de seguridad'));
    }

    echo $respuesta;
}


if ($post['accion'] == 'verificarRespuestaSeguridad') {
    // Consultamos la respuesta de seguridad asociada con la persona
    $sentencia = sprintf("SELECT respuesta FROM preguntas_seguridad WHERE cod_persona = (SELECT cod_persona FROM persona WHERE ci_persona = '%s')", $post['ci']);

    $rs = mysqli_query($mysqli, $sentencia);

    if (mysqli_num_rows($rs) > 0) {
        // Si encontramos la respuesta almacenada, comparamos con la proporcionada
        $row = mysqli_fetch_assoc($rs);
        
        // Comparamos la respuesta
        if ($row['respuesta'] == $post['respuesta']) {
            $respuesta = json_encode(array('estado' => true, 'mensaje' => 'Respuesta correcta'));
        } else {
            $respuesta = json_encode(array('estado' => false, 'mensaje' => 'Respuesta incorrecta'));
        }
    } else {
        $respuesta = json_encode(array('estado' => false, 'mensaje' => 'Pregunta no encontrada'));
    }

    echo $respuesta;
}


if ($post['accion'] == 'cambiarClave') {
    $sentencia = sprintf(
        "UPDATE persona SET clave_persona = '%s' WHERE ci_persona = '%s'",
        $post['clave'],  
        $post['id']
    );

    $rs = mysqli_query($mysqli, $sentencia);

    if ($rs) {
        $respuesta = json_encode(array('estado' => true, "mensaje" => "Contraseña actualizada correctamente"));
    } else {
        $respuesta = json_encode(array('estado' => false, "mensaje" => "Error al actualizar la contraseña"));
    }

    echo $respuesta;
}



if ($post['accion'] == 'verificarTelefono') {
    $sentencia = sprintf(
        "SELECT cod_contacto from contacto WHERE persona_cod_persona ='%s'
    and telefono_contacto = '%s'",
        $post['cod_persona'],
        $post['telefono'],
    );
    if (mysqli_num_rows(mysqli_query($mysqli, $sentencia)) > 0) {
        $respuesta = json_encode(array('estado' => true, 'mensaje' => 'telefono ya existe'));

    } else {
        $respuesta = json_encode(array('estado' => false,'mensaje' => 'Se ha creado'));
    }
    echo $respuesta;
}



if ($post ["accion"] == "datosContacto") {
    $sentencia = sprintf("SELECT * FROM contacto where cod_contacto = '%s'", $post["cod_contacto"]);
    $rs = mysqli_query($mysqli, $sentencia);
    if (mysqli_num_rows($rs) > 0) {
        while ($row = mysqli_fetch_assoc($rs)) {
            $datos = array(
              "nombre" => $row ["nom_contacto"],
              "apellido" => $row ["ape_contacto"],
              "telefono" => $row ["telefono_contacto"],
              "correo" => $row ["email_contacto"],
            );
        }
        $respuesta = json_encode(array("estado" => true, "data" => $datos));
    } else {

        $respuesta = json_encode(array("estado" => false, "mensaje" => "Error al cargar datos"));
    }
    echo $respuesta;
}



   
   if ($post['accion'] == 'actualizarContacto') {
    // Verificar que las variables no estén vacías
    if (empty($post['cod_contacto']) || empty($post['nombre']) || empty($post['apellido']) || empty($post['telefono']) || empty($post['correo'])) {
        echo json_encode(array('estado' => false, 'mensaje' => 'Faltan datos para actualizar el contacto'));
        exit;
    }

    // Construcción de la consulta SQL
    $sentencia = sprintf(
        "UPDATE contacto SET nom_contacto = '%s', ape_contacto = '%s', telefono_contacto = '%s', email_contacto = '%s' WHERE cod_contacto = '%s'",
        $post['nombre'],
        $post['apellido'],
        $post['telefono'],
        $post['correo'],
        $post['cod_contacto']
    );

    $rs = mysqli_query($mysqli, $sentencia);

    if ($rs) {
        echo json_encode(array('estado' => true, "mensaje" => "Contacto actualizado correctamente"));
    } else {
        echo json_encode(array('estado' => false, "mensaje" => "Error al actualizar el contacto", "error" => mysqli_error($mysqli)));
    }
}




if ($post ["accion"] == "eliminarContacto") {
    $sentencia = sprintf(
        "DELETE FROM contacto WHERE cod_contacto = '%s'",
        $post ["cod_contacto"]
    );
    $rs = mysqli_query($mysqli, $sentencia);
    if ($rs) {

        $respuesta = json_encode(array("estado" => true, "mensaje" => "Contacto eliminado"));
    } else {

        $respuesta = json_encode(array("estado" => true, "mensaje" => "Error al eliminar"));
    }
    echo $respuesta;
}