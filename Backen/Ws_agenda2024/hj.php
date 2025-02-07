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
    $sentencia = sprintf(
        "INSERT INTO persona (ci_persona, nom_persona, ape_persona,
    correo_persona, clave_persona,pregunta_perro,pregunta_mes_nacimiento,
      pregunta_nombre_mama) VALUES ('%s', '%s', '%s', '%s', '%s', '%s','%s','%s')",
        $post['cedula'],
        $post['nombre'],
        $post['apellido'],
        $post['correo'],
        $post['clave'],
        $post['pregunta_perro'],
        $post['pregunta_mes_nacimiento'],
        $post['pregunta_nombre_mama'],
    );

    $rs = mysqli_query($mysqli, $sentencia);

    if ($rs) {
        $respuesta = json_encode(array('estado' => true, "mensaje" => "Cuenta creada correctamente"));
    } else {
        $respuesta = json_encode(array('estado' => false, "mensaje" => "Error al crear la cuenta"));
    }

    echo $respuesta;
}

//--------Obtener datos de Perfil----------------------------------------------
if ($post['accion'] == 'datosPerfil') {

    $sentencia = sprintf("SELECT * FROM persona WHERE cod_persona = '%s'", $post['id']);

    $rs = mysqli_query($mysqli, $sentencia);
    if (mysqli_num_rows($rs) > 0) {
        while ($row = mysqli_fetch_array($rs)) {
            $datos[] = array(
              'nombre' => $row['nom_persona'],
              'apellido' => $row['ape_persona'],
              'correo' => $row['correo_persona'],
            );
        }
        $respuesta = json_encode(array('estado' => true, 'persona' => $datos));
    } else {
        $respuesta = json_encode(array('estado' => false));
    }
    echo $respuesta;
}

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

if ($post['accion'] == 'verificarPreguntas') {
    $sentencia = sprintf(
        "SELECT * FROM persona WHERE ci_persona = '%s'",
        $post['cedula']
    );
    $rs = mysqli_query($mysqli, $sentencia);
    if (mysqli_num_rows($rs) > 0) {
        while ($row = mysqli_fetch_array($rs)) {
            $datos[] = array(
              'codigo' => $row['cod_persona'],
              'pregunta_perro' => $row['pregunta_perro'],
              'pregunta_mes_nacimiento' => $row['pregunta_mes_nacimiento'],
              'pregunta_nombre_mama' => $row['pregunta_nombre_mama'],
            );
        }
        $respuesta = json_encode(array('estado' => true,'data' => $datos));
    } else {

        $respuesta = json_encode(array('estado' => false,'mensaje' => "No existe la persona"));
    }
    echo $respuesta;
}

if ($post['accion'] == 'actualizarPerfil') {
    $sentencia = sprintf(
        "UPDATE persona SET nom_persona = '%s', ape_persona = '%s', correo_persona = '%s' WHERE cod_persona = '%s'",
        $post['nombre'],
        $post['apellido'],
        $post['correo'],
        $post['cod_persona']
    );

    $rs = mysqli_query($mysqli, $sentencia);

    if ($rs) {
        $respuesta = json_encode(array('estado' => true, "mensaje" => "Perfil actualizado correctamente"));
    } else {
        $respuesta = json_encode(array('estado' => false, "mensaje" => "Error al actualizar el perfil"));
    }

    echo $respuesta;
}
if ($post['accion'] == 'nuevaClave') {
    $sentencia = sprintf(
        "UPDATE persona SET clave_persona = '%s' WHERE ci_persona = '%s'",
        $post ['claveRenovada'],
        $post['cedula']
    );
    $rs = mysqli_query($mysqli, $sentencia);

    if ($rs) {
        $respuesta = json_encode(array('estado' => true, "mensaje" => "Clave actualizada"));
    } else {
        $respuesta = json_encode(array('estado' => false, "mensaje" => "No se puede actualizar"));
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

//if ($post["accion"] = "actualizarContacto") {
//  $sentencia = sprintf(
//    "UPDATE contacto SET nom_contacto = '%s', ape_contacto= '%s', telefono_contacto= '%s', email_contacto= '%s', persona_cod_persona= '%s'
//   WHERE cod_contacto = '%s'",
//     $post["nombre"],
//   $post["apellido"],
// $post["telefono"],
//       $post["correo"],
//     $post["cod_contacto"],
//   );
//   $rs = mysqli_query($mysqli, $sentencia);
// if ($rs) {
//       $respuesta = json_encode(array("estado " => true, "mensaje" => "Datos actualizados "));
//   } else {

//     $respuesta = json_encode(array("estado " => false, "mensaje" => "Error al actualizar"));
//   }
//   echo $respuesta;
//}

if ($post ["accion"] == "datosContacto") {
    $sentencia = sprintf("SELECT * FROM contacto where cod_contacto = '%s'", $post["cod_contacto"]);
    $rs = mysqli_query($mysqli, $sentencia);
    if (mysqli_num_rows($rs) > 0) {
        while ($row = mysqli_fetch_array($rs)) {
            $datos = array(
              "nombre" => $row ["nom_contacto"],
              "apellido" => $row ["ape_contacto"],
              "telefono" => $row ["telefono_contacto"],
              "correo" => $row ["email_contacto"],
            );
        }
        $respuesta = json_encode(array("estado" => true, "datos" => $datos));
    } else {

        $respuesta = json_encode(array("estado" => false, "mensaje" => "Error al cargar datos"));
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