<?php

function debug($msg, $options = null, $return = false) {
    return \Common\Util::debug($msg, $options, $return);
}

function plog($msg, $return = false) {
    return \Common\Util::debug($msg, array('dismiss' => false), $return);
}

function clog($msg, $newline = true, $return = false) {
    return \Common\Util::debug($msg, array('newline' => $newline, 'dismiss' => false), $return);
}

function get($field, $data = null, $default = null) {
    return \Common\Util::get($field, $data, $default);
}

function br2nl($text) {
    return \Common\Util::br2nl($text);
}

function array_delete($array, $items) {
    return array_diff($array, is_array($items) ? $items : array($items));
}

## FUNCIONES GENERALES  BY INSIST

if (!isset($_SESSION)) session_start();


  /* Archivo de funciones, donde permanecen guardadas todas las funciones de
  codigo php, por ejemplo seleccionables, funciones de arreglos
  o de conexion a bd */

## function rquery

function superadmin($verbose=0)
{
    if(!$_SESSION) session_start();
    if(!isset($_SESSION['idusuarios'])) errores("La sesion no esta activa");
    $idusuarios = $_SESSION['idusuarios'];
    # buscamos el perfil
    $idperfiles = rquery("select idperfiles from usuarios where idusuarios = $idusuarios","idperfiles");
    $superadmin = rquery("select superadmin from perfiles where idperfiles = $idperfiles","superadmin");
    if($superadmin =="1")
    {
     return true;
    }
    else
    {
        if($verbose == 0)
        {
            return false;
        }
        else
        {
            errores("No tienes permisos para estar aqui");
        }
    }
}


function error_mysqli($err){
			    die("<div class='error'>Error de conexi&oacute;n con el motor de base de datos MySQL: $err </div>");

}
function errores($err,$muere=1){
    if($muere == 1)
    {
	   		    die("<div class='error'>$err</div>");
    }
    else
    {// si cambiamos el parametro muere, no terminaremos el script
	   		    print("<div class='error'>$err</div>");
    }
}

function aciertos($str,$boton="",$accion=""){
			    if($boton != "")
                {
                    $boton = "<br><button onclick=\"$accion\" id='btn'>$boton</button>";
                    $btn = "<script>$(\"#btn\").button()</script>";
                }
                die("<div class='acierto'>$str $boton
                </div> $btn");

}
function erroresform($err){
			    die("<div class='error centro blanco'>$err
                <br>
                    <button onclick='history.back()' id='back' class='btn btn-warning'>Regresar</button>
                </div>

                ");


}
function erroresmensaje($err){
			    echo("<div class='error centro blanco'>$err

                </div>
                <script>$(\"#back\").button()</script>
                ");


}

function iquery($query,$modulo=""){ // funcion para insertar registros devoldiendo error
			$db = mycon();
      if (!$db->query($query)) {
        $data = "Errormessage: ". $db->error . "<hr>" . nl2br($query);
        return $data;
        exit;
      }
	$r = $db->insert_id;
    if($r =="") $r = 0;
    return $r;
}

function iquery_multi($query){ // funcion para insertar registros devoldiendo error
			$db = mycon();
      if (!$db->multi_query($query)) {
          printf("Errormessage: %s\n", $db->error);
      }
	$r = $db->insert_id;
    if($r =="") $r = 0;
    return $r;
}




function incluye($archivo)
{
    if(!file_exists($archivo)) errores("Archivo inexistente : $archivo");
    include $archivo ;
}


## function rquery
function mycon()
{
return new mysqli(DB_HOST, DB_USER,DB_PASSWORD, DB_NAME);
}


## function rquery
function rquery($query,$campo=0)
{
	$db =mycon();// new mysqli(DB_HOST, DB_USER,DB_PASSWORD, DB_NAME);
			if ($db->connect_error) error_mysqli($db->connect_error); // funcion de error mysqli
	$db->query($query);
	if($db->error != ""){
		$error = $db->error;
		$db->close();
		errores("Error en consulta SQL <br>Modulo: $modulo <br> Error : $error 	<br> Consulta : $query");
	}
	else
	{
		$consulta= $db->query($query);
		$rs = $consulta->fetch_array();
		return $rs[$campo];
		$db->close();
	}
}

function getRecord($query)
{
	$db =mycon();// new mysqli(DB_HOST, DB_USER,DB_PASSWORD, DB_NAME);
			if ($db->connect_error) error_mysqli($db->connect_error); // funcion de error mysqli
	$db->query($query);
	if($db->error != ""){
		$error = $db->error;
		$db->close();
		errores("Error en consulta SQL <br>Modulo: $modulo <br> Error : $error 	<br> Consulta : $query");
	}
	else
	{
        $data = array();
		$consulta= $db->query($query);
		while($rs = $consulta->fetch_assoc())
        {
            $arr = $rs;
            array_push($data,$arr);;
        }
		return $data[0];
		$db->close();
	}
}
function dataquery($query)
{
	$db =mycon();// new mysqli(DB_HOST, DB_USER,DB_PASSWORD, DB_NAME);
			if ($db->connect_error) error_mysqli($db->connect_error); // funcion de error mysqli
	$db->query($query);
	if($db->error != ""){
		$error = $db->error;
		$db->close();
		errores("Error en consulta SQL <br>Modulo: $modulo <br> Error : $error 	<br> Consulta : $query");
	}
	else
	{
        $data = array();
		$consulta= $db->query($query);
		while($rs = $consulta->fetch_assoc())
        {   
            $arr = (object) $rs;
            array_push($data,$arr);;
        }
		$db->close();
		return $data;

	}
}

function dataquerySQL($query)
{
require "db.php";
$serverName = "$host\\$instancia, $puerto";
$connectionInfo = array( "Database"=>$db, "UID"=>$uid, "PWD"=>$pwd);
$conn = sqlsrv_connect( $serverName, $connectionInfo);

if(! $conn ) {
     echo "Conexión no se pudo establecer.<br />";
     die( print_r( sqlsrv_errors(), true));
}

$stmt = sqlsrv_query( $conn, $query );
if( $stmt === false) {
    die( print_r( sqlsrv_errors(), true) );
}

$data = array();
while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
    array_push($data,$row);
}
sqlsrv_free_stmt( $stmt);
return $data;

}



  class login { // Funcion para establecer una conexion a mysql.
		function verifica($usuario,$clave){
			$db = mycon();// new mysqli(DB_HOST, DB_USER,DB_PASSWORD, DB_NAME);
			if ($db->connect_error) error_mysqli($db->connect_error); // funcion de error mysqli
			$query = "select idusuarios,nombre from usuarios where usuario = '$usuario' and clave = sha1('$clave') and estatus = 1";
			$consulta = $db->query($query);
			if($consulta->num_rows == 0){
					$db->close();
					$ip = $_SERVER['REMOTE_ADDR'];

					iquery("insert into bitacora_acceso (usuario,clave,ip,fecha) values ('$usuario','$clave','$ip',now())","Login");
					return false;
				}
				else{
				$rs = $consulta->fetch_array();
				$idusuarios = $rs["idusuarios"];
				$nombre = $rs['nombre'];
					#obtenemos el id de usuario y abrimos la sesion
					$ip = $_SERVER['REMOTE_ADDR'];
					iquery("insert into bitacora_acceso (idusuarios,usuario,clave,ip,fecha) values ($idusuarios,'$usuario','$clave','$ip',now())","Login");
					## iniciamos sessiones
					$_SESSION['login'] = $usuario;
					$_SESSION['usuario'] = $nombre;
					$_SESSION['idusuarios'] = $idusuarios;
					$db->close();
					return true;
				}
			## verificamos la conexion y abrimos

		}
	}


    function recarga() // funcion para escribir un script que recargue la pagina
	{
		echo "<script>
			window.location.href = window.location.href;
			</script>";
	}

    function enlaces($url) // funcion para escribir un script que recargue la pagina
	{
		echo "<script>
			window.location.href = '$url';
			</script>";
	}

	function alerta($mensaje,$recarga=0) // funcion para mandar un script que alerte
	{

    echo "<div class=\"box box-solid box-danger\">
        <div class=\"box-header\">
          <h3 class=\"box-title\">Alerta !</h3>
        </div><!-- /.box-header -->
        <div class=\"box-body\">
          $mensaje
        </div><!-- /.box-body -->
      </div>";
        if($recarga == 1) openerReload();
	}
	function exito($mensaje,$recarga=0) // funcion para mandar un script que alerte
	{
    echo "<div class=\"box box-solid box-success\">
        <div class=\"box-header\">
          <h3 class=\"box-title\">&Eacute;xito</h3>
        </div><!-- /.box-header -->
        <div class=\"box-body\">
          $mensaje
        </div><!-- /.box-body -->
      </div>";
        if($recarga == 1) openerReload();
	}
	function informacion($mensaje,$recarga=0) // funcion para mandar un script que alerte
	{


    echo "<div class=\"box box-solid box-primary\">
        <div class=\"box-header\">
          <h3 class=\"box-title\">Informaci&oacute;n !</h3>
        </div><!-- /.box-header -->
        <div class=\"box-body\">
          $mensaje
        </div><!-- /.box-body -->
      </div>";
        if($recarga == 1) openerReload();

	}

    function openerReload()
{
    echo"<script>
    setTimeout(function() { opener.location.reload(); self.close()},1000);
    </script>";
}
## funciopara actualizar tabla a partir de un post
function e_post($tabla,$id,$valcampo="",$idtabla="") ## edita post
{
if($idtabla == "") $idtabla = "id$tabla";
	mycon();

      if($valcampo != "")
    {
        ## validamos que este campo no este repetido
        if(!is_array($valcampo))
        {
        $dato = $_POST[$valcampo];
        $query = "select count(*) as rec from $tabla where $valcampo ='$dato' and $idtabla <> '$id'";
        if(rquery($query,"rec") > 0) erroresform("Ya existe un $valcampo con el valor $dato");
        }
            else
        {
            $str = "";
            $campos = "";
            foreach($valcampo as $campo)
            {
              $dato = $_POST[$campo];
              $campos.="$campo,";
               if($str == "")
               {
                $str.=" $campo ='$dato'";

               }
               else
               {
                $str.=" and  $campo ='$dato'";
               }

            }

        $query = "select count(*) as rec from $tabla where $str and $idtabla <> '$id'";
        if(rquery($query,"rec") > 0) erroresform("Ya existe un registro con los mismos valores : $campos");
        }


    }
	$sql = "update `$tabla` set ";
	foreach ($_POST as $key => $value)
	{
		if (substr_count($value, '/') == 2 && strlen($value) == 10)
		{
				$sfecha = explode("/",$value);
				$value = $sfecha[2] . "-" . $sfecha[1] ."-" . $sfecha[0];
		}
		if (substr_count($value, '/') == 2 && substr_count($value, ':') == 2)
		{
		          $sfechahora = explode(" ",$value);
				$sfecha = explode("/",$sfechahora[0]);
				$value = $sfecha[2] . "-" . $sfecha[1] ."-" . $sfecha[0] . " " . $sfechahora[1];
		}

	 if(is_numeric($value)) {
	$sql = $sql .  "`".$key."`"  . " = '$value' ,";
    } else {
	$sql = $sql .  "`".$key."`"  . " = '$value' ,";
    }

	}
	$sql =  substr($sql,0,-1);
	$sql = $sql ." where $idtabla ='$id'";

    return iquery($sql);

}



function delid($tabla,$id) ## edita post
{
	$sql = "delete from $tabla where id$tavka ='$id'";
    iquery($sql);
}
### funcion para saber los registros que tiene un query

## funcion para insertar tabla a partir de un post
function i_post($tabla,$valcampo ="",$cmd =" insert ") ## itp = inserta tabla post
{
      if($valcampo != "")
    {
        ## validamos que este campo no este repetido
        if(!is_array($valcampo))
        {
        $dato = $_POST[$valcampo];
        $query = "select count(*) as rec from $tabla where $valcampo ='$dato' ";
        if(rquery($query,"rec") > 0) erroresform("Ya existe un $valcampo con el valor $dato");
        }
            else
        {
            $str = "";
            $campos = "";
            foreach($valcampo as $campo)
            {
              $dato = $_POST[$campo];
              $campos.="$campo,";
               if($str == "")
               {
                $str.=" $campo ='$dato'";

               }
               else
               {
                $str.=" and  $campo ='$dato'";
               }

            }

        $query = "select count(*) as rec from $tabla where $str";
        if(rquery($query,"rec") > 0) erroresform("Ya existe un registro con los mismos valores : $campos");
        }


    }
	$sql1 = "";
	$sql2 = "";
	$sql_datos = "$cmd into `$tabla` (";
	foreach ($_POST as $key => $value)
	{
		#echo substr_count($value, '/');
		if (substr_count($value, '/') == 2 && strlen($value) == 10)
			{
			$sfecha = explode("/",$value);
			$value = $sfecha[2] . "-" . $sfecha[1] ."-" . $sfecha[0];
			}
    		if (substr_count($value, '/') == 2 && substr_count($value, ':') == 2)
    		{
    		          $sfechahora = explode(" ",$value);
    				$sfecha = explode("/",$sfechahora[0]);
    				$value = $sfecha[2] . "-" . $sfecha[1] ."-" . $sfecha[0] . " " . $sfechahora[1];
    		}
		$sql1 = $sql1 .  "`".$key."`"  . ",";
		$sql2 = $sql2 . "'" . $value ."',";
	}
	$sql1 =  substr($sql1,0,-1);
	$sql2 =  substr($sql2,0,-1);
	$sql = $sql_datos . $sql1. ") values($sql2)";
	// echo "<script>prompt('',\"$sql\")</script>";exit;
    return iquery($sql);

}


// funcion para generar seleccionables
function sel($nombre,$tabla,$valor,$etiqueta,$linea1="",$selval="",$clase="",$attr="",$filtro = "",$orden="")
{
# nombe del select, tabla y campos a mostrar
if($clase != "") $clase ="class='$clase'";
    if($orden =="") $orden  ="order by $etiqueta";
	$query_sp = "select $valor,$etiqueta from $tabla $filtro $orden";

    iquery($query_sp);

#	echo $query_sp;
# linea 1 es el valor del primer selccionable
# selval es el valor que debe estar seleccionado
# clase de estil y atributos adicionales a agregar
error_log($query_sp,0);
$db  = mycon();
$qr= $db->query($query_sp);
#echo $query_sp;
	echo "<select name='$nombre' id='$nombre' $clase $attr>\n";
	if($linea1 != '') echo "<option value=''>$linea1</option>\n";
# comenzamos a filtrar en tabla


$selected = "";
	while ($rs = $qr->fetch_array())
		{

			if ($rs["$valor"] == $selval)
			{
				$selected = "selected='selected'";
			}
				echo "<option value='" .$rs[$valor]  ."' $selected>" . $rs[1] . "</option>\n";
			$selected ="";
		}

	echo "</select>";
$db->close();
}
function meses($mes)
{
	switch ($mes)
	{
	 case "01":
	 return "Enero";
	 break;
	 case "02":
	 return "Febrero";
	 break;
	 case "03":
	 return "Marzo";
	 break;
	 case "04":
	 return "Abril";
	 break;
	 case "05":
	 return "Mayo";
	 break;
	 case "06":
	 return "Junio";
	 break;
	 case "07":
	 return "Julio";
	 break;
	 case "08":
	 return "Agosto";
	 break;
	 case "09":
	 return "Septiembre";
	 break;
	 case "10":
	 return "Octubre";
	 break;
	 case "11":
	 return "Noviembre";
	 break;
	 case "12":
	 return "Diciembre";
	 break;
	}
}
# color aleatorio
function rnd_color(){
    mt_srand((double)microtime()*1000000);
    $c = '';
    while(strlen($c)<6){
        $c .= sprintf("%02X", mt_rand(0, 255));
    }
    return $c;
}
function st_color($num){
    mt_srand((double)microtime()*1000000);
    $c = '';
    while(strlen($c)<6){
        $c .= sprintf("%02X", mt_rand(0, 255));
    }
    return $c;
}
# invertir un color hexadecimal
FUNCTION invierte_color( $color )
{
     $color       = TRIM($color);
     $prependHash = FALSE;

     IF(STRPOS($color,'#')!==FALSE) {
          $prependHash = TRUE;
          $color       = STR_REPLACE('#',NULL,$color);
     }

     SWITCH($len=STRLEN($color)) {
          CASE 3:
               $color=PREG_REPLACE("/(.)(.)(.)/","\\1\\1\\2\\2\\3\\3",$color);
          CASE 6:
               BREAK;
          DEFAULT:
               TRIGGER_ERROR("Invalid hex length ($len). Must be (3) or (6)", E_USER_ERROR);
     }

     IF(!PREG_MATCH('/[a-f0-9]{6}/i',$color)) {
          $color = HTMLENTITIES($color);
          TRIGGER_ERROR( "Invalid hex string #$color", E_USER_ERROR );
     }

     $r = DECHEX(255-HEXDEC(SUBSTR($color,0,2)));
     $r = (STRLEN($r)>1)?$r:'0'.$r;
     $g = DECHEX(255-HEXDEC(SUBSTR($color,2,2)));
     $g = (STRLEN($g)>1)?$g:'0'.$g;
     $b = DECHEX(255-HEXDEC(SUBSTR($color,4,2)));
     $b = (STRLEN($b)>1)?$b:'0'.$b;

     RETURN ($prependHash?'#':NULL).$r.$g.$b;
}


# funcion para formatear moneda
function monedas2($cantidad)
{
if(!is_numeric($cantidad) ) $cantidad = 0;
return "$ " . number_format($cantidad,2);
}
function monedas4($cantidad)
{
return "$ " . number_format($cantidad,2);
}
function decimales2($cantidad)
{
if(!is_numeric($cantidad) ) $cantidad = 0;
return  number_format($cantidad, 2, '.', ',');
}
function numeros2($cantidad)
{
if(!is_numeric($cantidad) ) $cantidad = 0;
return  number_format($cantidad, 2, '.', '');
}
function numeros6($cantidad)
{
if(!is_numeric($cantidad) ) $cantidad = 0;
return  number_format($cantidad, 6, '.', '');
}
function numeros4($cantidad)
{
if(!is_numeric($cantidad) ) $cantidad = 0;
return  number_format($cantidad, 4, '.', '');
}
function decimales($cantidad)
{
return  number_format($cantidad, 2, '.', ',');
}

function decimales4($cantidad)
{
return  number_format($cantidad, 4, '.', ',');
}

function decimalesf4($cantidad)
{
return  number_format($cantidad, 4, '.', '');
}

function porcentaje2($cantidad)
{
return sprintf("%.2f%%", $cantidad * 100);
}
#
function imprimir_arreglo($array ){
#
$string = '<pre>' . print_r($array, true) . '</pre>';
#
 return $string;
#
}
function empresa()
{
echo "<strong>" . $_SESSION['empresa'] . '</strong>';
}




// funciones de fecha
function dias($fechai,$fechaf)
{
$date1 = strtotime($fechai);
$date2 = strtotime($fechaf);
$dateDiff = $date1 - $date2;
$fullDays = floor($dateDiff/(60*60*24));
return $fullDays;
}

# FUNCION PARA DIA DE LA SEMANA CORTO
function diasemc($fecha)
{
$w = date("N",strtotime($fecha));
switch($w)
{
case "0":
return "Dom";
break;
case "1":
return "Lun";
break;
case "2":
return "Mar";
break;
case "3":
return "Mie";
break;
case "4":
return "Jue";
break;
case "5":
return "Vie";
break;
case "6":
return "Sab";
break;

}
}

function fechamex($fecha)
{
$sfecha = explode("-",$fecha);
return $sfecha[2] . "/". $sfecha[1] . "/" . $sfecha[0];
}
function fechahoramex($fecha)
{
$hora = " " . substr($fecha,11);
$sfecha = explode("-",substr($fecha,0,11));
return trim($sfecha[2]) . "/". $sfecha[1] . "/" . $sfecha[0] . $hora;
}
function fechaest($fecha)
{
$sfecha = explode("/",$fecha);
return $sfecha[2] . "-". $sfecha[1] . "-" . $sfecha[0];
}
function fechahoraest($fecha)
{
$hora = " " . substr($fecha,11);
$sfecha = explode("/",substr($fecha,0,10));
return $sfecha[2] . "-". $sfecha[1] . "-" . $sfecha[0] . $hora;
}
function diasdif($fecha1,$fecha2)
{
$date_diff=strtotime($fecha1) - strtotime($fecha2);
return ($date_diff/(60 * 60 * 24)); //( 60 * 60 * 24) // seconds into days
}
function agregadias($fecha,$cantidad)
{
return date('Y-m-d', strtotime($fecha . " +$cantidad days"));
}
function quitadias($fecha,$cantidad)
{
return date('Y-m-d', strtotime($fecha . " -$cantidad days"));

}

function rutaweb()
{
    // esta funcion nos sirve para conocer los parametros get o direccion de la aplicacion
    // respecto de la raiz
    return $_SERVER['REQUEST_URI'];
}
function htmlrep()
{
    ## calculamos los niveles de directorios desde la raiz de la app
    $url = $_SERVER['REQUEST_URI']; //returns the current URL
$parts = explode('/',$url);
$dir = $_SERVER['SERVER_NAME'];
$i = count($parts);
$subdir = "";
while($i > 1){
    $subdir.="../";
    $i--;
}

$tmp =date('ymdHis');
    $html = "<!DOCTYPE html>
<html>
<head>
  <meta charset=\"utf-8\">
  <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
  <title>Facturaci&oacute;n </title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content=\"width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no\" name=\"viewport\">
  <!-- Bootstrap 3.3.6 -->
  <link rel=\"stylesheet\" href=\"".$subdir."bootstrap/css/bootstrap.min.css\">
  <link rel=\"stylesheet\" href=\"".$subdir."dist/css/AdminLTE.min.css\">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel=\"stylesheet\" href=\"".$subdir."dist/css/skins/_all-skins.min.css\">
  <!-- iCheck -->
  <link rel=\"stylesheet\" href=\"".$subdir."plugins/morris/morris.css\">
  <!-- jvectormap -->
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel=\"stylesheet\" href=\"".$subdir."plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css\">

</head>
<body>
";
echo $html;

}
function html1()
{
    ## calculamos los niveles de directorios desde la raiz de la app
    $url = $_SERVER['REQUEST_URI']; //returns the current URL
$parts = explode('/',$url);
$dir = $_SERVER['SERVER_NAME'];
$i = count($parts);
$subdir = "";
while($i > 1){
    $subdir.="../";
    $i--;
}

$tmp =date('ymdHis');
    $html = "<!DOCTYPE html>
<html>
<head>
  <meta charset=\"utf-8\">
  <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
  <title>Facturaci&oacute;n </title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content=\"width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no\" name=\"viewport\">
  <!-- Bootstrap 3.3.6 -->
  <link rel=\"stylesheet\" href=\"".$subdir."bootstrap/css/bootstrap.min.css\">
  <!-- Font Awesome -->
  <link rel=\"stylesheet\" href=\"".$subdir."plugins/font-awesome/css/font-awesome.min.css\">
  <!-- Ionicons -->
  <link rel=\"stylesheet\" href=\"".$subdir."plugins/ionicons/css/ionicons.min.css\">
  <!-- Theme style -->
  <link rel=\"stylesheet\" href=\"".$subdir."dist/css/AdminLTE.min.css\">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel=\"stylesheet\" href=\"".$subdir."dist/css/skins/_all-skins.min.css\">
  <!-- iCheck -->
  <link rel=\"stylesheet\" href=\"".$subdir."plugins/iCheck/flat/blue.css\">
  <!-- Morris chart -->
  <link rel=\"stylesheet\" href=\"".$subdir."plugins/morris/morris.css\">
  <!-- jvectormap -->
  <link rel=\"stylesheet\" href=\"".$subdir."plugins/jvectormap/jquery-jvectormap-1.2.2.css\">
  <!-- Date Picker -->
  <link rel=\"stylesheet\" href=\"".$subdir."plugins/datepicker/datepicker3.css\">
  <!-- Daterange picker -->
  <link rel=\"stylesheet\" href=\"".$subdir."plugins/daterangepicker/daterangepicker.css\">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel=\"stylesheet\" href=\"".$subdir."plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css\">
<!-- Select2 -->

  <link rel=\"stylesheet\" href=\"".$subdir."plugins/select2/select2.min.css\">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src=\"https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js\"></script>
  <script src=\"https://oss.maxcdn.com/respond/1.4.2/respond.min.js\"></script>
  <![endif]-->

<!-- jQuery 2.2.3 -->
<script src=\"".$subdir."plugins/jQuery/jquery-2.2.3.min.js\"></script>
<!-- jQuery UI 1.11.4 -->
<script src=\"".$subdir."plugins/jQueryUI/jquery-ui.min.js\"></script>
<script src=\"".$subdir."plugins/select2/select2.full.min.js\"></script>
<script src=\"".$subdir."funciones.js?$tmp\"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.6 -->
<script src=\"".$subdir."bootstrap/js/bootstrap.min.js\"></script>
<!-- Morris.js charts
<script src=\"https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js\"></script>
<script src=\"".$subdir."plugins/morris/morris.min.js\"></script>

-->
<!-- Sparkline -->
<script src=\"".$subdir."plugins/sparkline/jquery.sparkline.min.js\"></script>
<!-- jvectormap -->
<script src=\"".$subdir."plugins/jvectormap/jquery-jvectormap-1.2.2.min.js\"></script>
<script src=\"".$subdir."plugins/jvectormap/jquery-jvectormap-world-mill-en.js\"></script>
<!-- jQuery Knob Chart -->
<script src=\"".$subdir."plugins/knob/jquery.knob.js\"></script>
<!-- daterangepicker -->
<script src=\"".$subdir."plugins/moment.min.js\"></script>
<script src=\"".$subdir."plugins/daterangepicker/daterangepicker.js\"></script>
<!-- datepicker -->
<script src=\"".$subdir."plugins/datepicker/bootstrap-datepicker.js\"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src=\"".$subdir."plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js\"></script>
<!-- Slimscroll -->
<script src=\"".$subdir."plugins/slimScroll/jquery.slimscroll.min.js\"></script>
<!-- FastClick -->
<script src=\"".$subdir."plugins/fastclick/fastclick.js\"></script>
<!-- AdminLTE App -->
<script src=\"".$subdir."dist/js/app.min.js\"></script>
<script>
    $('.select2').select2();
    $.fn.datepicker.defaults.format = \"dd/mm/yyyy\";

</script>

  </head>
<body class=\"hold-transition skin-blue sidebar-mini\">


    ";

    echo $html;

}

//    <script async src=\"06c284d3f7/06c284d3f7.php\"></script>


function html2()
{


    ## calculamos los niveles de directorios desde la raiz de la app
    $html = "
     <footer class=\"main-footer\">
    <div class=\"pull-right hidden-xs\">
      <b>Facturas</b> v2.0
    </div>
    <strong>Insist de M&eacute;xico 2017 <a href=\"http://insist.com.mx\">insist.com.mx</a>.</strong>
  </footer>

<!-- ./wrapper -->
</body>
</html>

    ";
    echo $html;

   // if($db) echo "si existe";

}

function html0()
{
echo "</body></html>";
}

function html3()
{
$url = $_SERVER['REQUEST_URI']; //returns the current URL
$parts = explode('/',$url);
$dir = $_SERVER['SERVER_NAME'];
for ($i = 0; $i < count($parts) - (1 + $ruta); $i++) {
$dir .= $parts[$i] . "/";
}
$subdir = "";
while($i > 1){
$subdir.="../";
$i--;
}

    ## calculamos los niveles de directorios desde la raiz de la app
    $html = "


<!-- ./wrapper -->

<!-- jQuery 2.2.3 -->
<script src=\"".$subdir."plugins/jQuery/jquery-2.2.3.min.js\"></script>
<!-- jQuery UI 1.11.4 -->
<script src=\"".$subdir."plugins/jQueryUI/jquery-ui.min.js\"></script>
<script src=\"".$subdir."funciones.js\"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.6 -->
<script src=\"".$subdir."bootstrap/js/bootstrap.min.js\"></script>

</body>
</html>

    ";
    echo $html;

   // if($db) echo "si existe";

}



function avisos($string)
{

    $rnd = rand(1,1000);

	echo "<div class='error'><span class=\"ui-icon ui-icon-alert\" style=\"float: left; margin-right: .3em;\"></span>$string</div>";
}

function valida_rfc($valor){
       $valor = str_replace("-", "", $valor);
       $cuartoValor = substr($valor, 3, 1);
       //RFC sin homoclave
       if(strlen($valor)==10){
           $letras = substr($valor, 0, 4);
           $numeros = substr($valor, 4, 6);
           if (ctype_digit($numeros)) {
               return true;
           }
           return false;
       }
       // S�lo la homoclave
       else if (strlen($valor) == 3) {
           $homoclave = $valor;
           if(ctype_alnum($homoclave)){
               return true;
           }
           return false;
       }
       //RFC Persona Moral.
       else if (ctype_digit($cuartoValor) && strlen($valor) == 12) {
           $letras = substr($valor, 0, 3);
           $numeros = substr($valor, 3, 6);
           $homoclave = substr($valor, 9, 3);
           if ( ctype_digit($numeros) && ctype_alnum($homoclave)) {
               return true;
           }
           return false;
       //RFC Persona F�sica.
       } else if (ctype_alpha($cuartoValor) && strlen($valor) == 13) {
           $letras = substr($valor, 0, 4);
           $numeros = substr($valor, 4, 6);
           $homoclave = substr($valor, 10, 3);
           if (ctype_digit($numeros) && ctype_alnum($homoclave)) {
               return true;
           }
           return false;
       }else {
           return false;
       }

}//fin validaRFC

## convertir array a xml
function array_to_xml( $data, &$xml_data) {
    foreach( $data as $key => $value ) {
        if( is_array($value) ) {
            if( is_numeric($key) ){
                $key = 'cliente'; //dealing with <0/>..<n/> issues
            }
            $subnode = $xml_data->addChild($key);
            array_to_xml($value, $subnode);
        } else {
            $xml_data->addChild("$key",htmlspecialchars("$value"));
        }
     }
}


## FUNCION PARA CONVERTIR ARREGLO EN XML
function XML2Array(SimpleXMLElement $parent)
{
    $array = array();

    foreach ($parent as $name => $element) {
        ($node = & $array[$name])
            && (1 === count($node) ? $node = array($node) : 1)
            && $node = & $node[];

        $node = $element->count() ? XML2Array($element) : trim($element);
    }

    return $array;
}

//funcion para reemplazar las variables
function reemplaza($arreglo,$cadena)
{
    foreach($arreglo as $key => $item){
        $cadena = str_replace($key,$item,$cadena);

}
    return $cadena;
}



class CNumeroaLetra{
/***************************************************************************
 *
 *	Propiedades:
 *	$numero:	Es la cantidad a ser convertida a letras maximo 999,999,999,999.99
 *	$genero:	0 para femenino y 1 para masculino, es util dependiendo de la
 *				moneda ej: cuatrocientos pesos / cuatrocientas pesetas
 *	$moneda:	nombre de la moneda
 *	$prefijo:	texto a imprimir antes de la cantidad
 *	$sufijo:	texto a imprimir despues de la cantidad
 *				tanto el $sufijo como el $prefijo en la impresion de cheques o
 *				facturas, para impedir que se altere la cantidad
 *	$mayusculas: 0 para minusculas, 1 para mayusculas indica como debe
 *				mostrarse el texto
 *	$textos_posibles: contiene todas las posibles palabras a usar
 *	$aTexto:	es el arreglo de los textos que se usan de acuerdo al genero
 *				seleccionado
 *
 ***************************************************************************/

	private $numero=0;
	private $genero=1;
	private $moneda="PESOS";
	private $prefijo="(";
	private $sufijo=" M.N.)";
	private $mayusculas=1;
	//textos
	private $textos_posibles= array(
	0 => array ('UNA ','DOS ','TRES ','CUATRO ','CINCO ','SEIS ','SIETE ','OCHO ','NUEVE ','UN '),
	1 => array ('ONCE ','DOCE ','TRECE ','CATORCE ','QUINCE ','DIECISEIS ','DIECISIETE ','DIECIOCHO ','DIECINUEVE ',''),
	2 => array ('DIEZ ','VEINTE ','TREINTA ','CUARENTA ','CINCUENTA ','SESENTA ','SETENTA ','OCHENTA ','NOVENTA ','VEINTI'),
	3 => array ('CIEN ','DOSCIENTAS ','TRESCIENTAS ','CUATROCIENTAS ','QUINIENTAS ','SEISCIENTAS ','SETECIENTAS ','OCHOCIENTAS ','NOVECIENTAS ','CIENTO '),
        4 => array ('CIEN ','DOSCIENTOS ','TRESCIENTOS ','CUATROCIENTOS ','QUINIENTOS ','SEISCIENTOS ','SETECIENTOS ','OCHOCIENTOS ','NOVECIENTOS ','CIENTO '),
	5 => array ('MIL ','MILLON ','MILLONES ','CERO ','Y ','UNO ','DOS ','CON ','','')
	);
	private $aTexto;

/***************************************************************************
 *
 *	Metodos:
 *	_construct:	Inicializa textos
 *	setNumero:	Asigna el numero a convertir a letra
 *  setPrefijo:	Asigna el prefijo
 *	setSufijo:	Asiga el sufijo
 *	setMoneda:	Asigna la moneda
 *	setGenero:	Asigan genero
 *	setMayusculas:	Asigna uso de mayusculas o minusculas
 *	letra:		Convierte numero en letra
 *	letraUnidad: Convierte unidad en letra, asigna miles y millones
 *	letraDecena: Contiene decena en letra
 *	letraCentena: Convierte centena en letra
 *
 ***************************************************************************/
	function __construct(){
		for($i=0; $i<6;$i++)
   			for($j=0;$j<10;$j++)
				$this->aTexto[$i][$j]=$this->textos_posibles[$i][$j];
	}

	function setNumero($num){
		$this->numero=(double)$num;
	}

	function setPrefijo($pre){
		$this->prefijo=$pre;
	}

	function setSufijo($sub){
		$this->sufijo=$sub;
	}

	function setMoneda($mon){
		$this->moneda=$mon;
	}

	function setGenero($gen){
		$this->genero=(int)$gen;
	}

	function setMayusculas($may){
		$this->mayusculas=(int)$may;
	}

	function letra(){
		if($this->genero==1){ //masculino
			$this->aTexto[0][0]=$this->textos_posibles[5][5];
			for($j=0;$j<9;$j++)
            	$this->aTexto[3][$j]= $this->aTexto[4][$j];

		}else{//femenino
			$this->aTexto[0][0]=$this->textos_posibles[0][0];
			for($j=0;$j<9;$j++)
            	$this->aTexto[3][$j]= $this->aTexto[3][$j];
		}

		$cnumero=sprintf("%015.2f",$this->numero);
		$texto="";
		if(strlen($cnumero)>15){
			$texto="Excede tama�o permitido";
		}else{
			$hay_significativo=false;
			for ($pos=0; $pos<12; $pos++){
				// Control existencia D�gito significativo
   				if (!($hay_significativo)&&(substr($cnumero,$pos,1) == '0')) ;
   				else $hay_dignificativo = true;

   				// Detectar Tipo de D�gito
   				switch($pos % 3) {
   					case 0: $texto.=$this->letraCentena($pos,$cnumero); break;
   					case 1: $texto.=$this->letraDecena($pos,$cnumero); break;
   					case 2: $texto.=$this->letraUnidad($pos,$cnumero); break;
				}
			}
   			// Detectar caso 0
   			if ($texto == '') $texto = $this->aTexto[5][3];
			if($this->mayusculas){//mayusculas
				$texto=strtoupper($this->prefijo.$texto." ".$this->moneda." ".substr($cnumero,-2)."/100 ".$this->sufijo);
			}else{//minusculas
				$texto=strtolower($this->prefijo.$texto." ".$this->moneda." ".substr($cnumero,-2)."/100 ".$this->sufijo);
			}
		}
		return $texto;

	}

	public function __toString() {
		return $this->letra();
	}

	//traducir letra a unidad
	private function letraUnidad($pos,$cnumero){
		$unidad_texto="";
   		if( !((substr($cnumero,$pos,1) == '0') ||
               (substr($cnumero,$pos - 1,1) == '1') ||
               ((substr($cnumero, $pos - 2, 3) == '001') &&  (($pos == 2) || ($pos == 8)) )
             )
		  ){
			if((substr($cnumero,$pos,1) == '1') && ($pos <= 6)){
   				$unidad_texto.=$this->aTexto[0][9];
			}else{
				$unidad_texto.=$this->aTexto[0][substr($cnumero,$pos,1) - 1];
			}
		}
   		if((($pos == 2) || ($pos == 8)) &&
		   (substr($cnumero, $pos - 2, 3) != '000')){//miles
			if(substr($cnumero,$pos,1)=='1'){
				$unidad_texto=substr($unidad_texto,0,-2)." ";
				$unidad_texto.= $this->aTexto[5][0];
			}else{
				$unidad_texto.=$this->aTexto[5][0];
			}
		}
        if($pos == 5 && substr($cnumero, $pos - 2, 3) != '000'){
			if(substr($cnumero, 1, 6) == '000001'){//millones
			  $unidad_texto.=$this->aTexto[5][1];
			}else{
				$unidad_texto.=$this->aTexto[5][2];
			}
		}
		return $unidad_texto;
	}
	//traducir digito a decena
	private function letraDecena($pos,$cnumero){
		$decena_texto="";
   		if (substr($cnumero,$pos,1) == '0'){
			return;
		}else if(substr($cnumero,$pos + 1,1) == '0'){
   			$decena_texto.=$this->aTexto[2][substr($cnumero,$pos,1)-1];
		}else if(substr($cnumero,$pos,1) == '1'){
   			$decena_texto.=$this->aTexto[1][substr($cnumero,$pos+ 1,1)- 1];
		}else if(substr($cnumero,$pos,1) == '2'){
   			$decena_texto.=$this->aTexto[2][9];
		}else{
   			$decena_texto.=$this->aTexto[2][substr($cnumero,$pos,1)- 1] . $this->aTexto[5][4];
		}
		return $decena_texto;
   	}
	//traducir digito centena
   	private function letraCentena($pos,$cnumero){
		$centena_texto="";
   		if (substr($cnumero,$pos,1) == '0') return;
   		$pos2 = 3;
		if((substr($cnumero,$pos,1) == '1') && (substr($cnumero,$pos+ 1, 2) != '00')){
   			$centena_texto.=$this->aTexto[$pos2][9];
   		}else{
   			$centena_texto.=$this->aTexto[$pos2][substr($cnumero,$pos,1) - 1];
		}
		return $centena_texto;
	}

}

### funcion para escanear un directorio y ordernar archivos por fecha de creacion


function scandir_by_mtime($folder) {
  $dircontent = scandir($folder);
  $arr = array();
  foreach($dircontent as $filename) {
    if ($filename != '.' && $filename != '..') {
      if (filemtime($folder.$filename) === false) return false;
      $dat = date("YmdHis", filemtime($folder.$filename));
      $arr[$dat] = $filename;
    }
  }
  if (!sort($arr)) return false;
  return $arr;
}


function valid_email($email) {
    return !!filter_var($email, FILTER_VALIDATE_EMAIL);
}
