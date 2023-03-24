<?php
/*sincronizacion de la base de datos con carpeta de imagenes */
$conexion = mysqli_connect('NOMBRE_DEL_HOST','NOMBRE_DE_USUARIO','CONTRASEÑA','BASEDEDATOS');
/** Abrimos el directorio*/
$dir = scandir('./TUDIRECTORIO');
# Eliminamos los datos por defecto del directorio 
unset($dir[0], $dir[1]);
$dir = array_values($dir);
#obtenemos la base de datos
$consulta = mysqli_query($conexion, "select * from imagenes ");

$imagen = array();
while ($fila = mysqli_fetch_array($consulta)) {
  $id = $fila['id'];
  $nombre = $fila['nombre'];
  if (!empty($nombre)) {
    array_push($imagen, $nombre);

    if (!in_array($nombre, $dir)) {
      #Eliminamos las filas sin imagenes
      mysqli_query($conexion, "DELETE  FROM imagenes WHERE  id = '$id' ");
    }
  }
};
$aux = 0;
for ($i = 0; $i < count($imagen); $i++) {
  if ($aux === count($dir)) {
    break;
  }
  if ($dir[$aux] === $imagen[$i]) {
    $aux = $aux + 1;
    $i = 0;
  }
  # si es la ultima posición del ciclo y ninguna fue igual entonces la eliminamos    
  if ($i === count($imagen) - 1) {
    var_dump('las imagenes que solo estan en el directorio son:<br>', $dir[$aux], '<br>');
    unlink("./imagenes/$dir[$aux]");
    $aux = $aux + 1;
    $i = 0;
  }
}
