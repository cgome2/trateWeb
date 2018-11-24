<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/init.php';
$idRecord = $_GET['id'];
$descargas = array();
$d = json_decode(base64_decode($_GET['descargas']));
foreach($d as $dt)
{
    array_push($descargas,$dt->id);
}

$query ="select * from tank_delivery_readings_t where  ci_movimientos is null";
$datos = dataQuery($query);
if(count($datos) == 0)
{
    die("No se encontraron recepciones pendientes de relacionar");
}
else
{
echo "<table class='table table-bordered'>
								<thead>
									<tr>
										<th></th>
										<th>Estacion</th>
										<th>Tanque</th>
										<th>Fecha Hora</th>
										<th>Producto</th>
										<th>Volumen Recepcion</th>
									</tr>
								</thead>
                                <tbody>";
    foreach($datos as $rs)
    {
        $checked ="";
        $disabled ="";
        $clase = "tls ck";
        $id = $rs->iddescargas;
        $documento = $rs->iddocumentos;
        if(in_array($id,$descargas)) $checked=" checked='checked'";
        if(($documento !="") && $documento != $idRecord) 
            {
                $checked=" disabled='disabled'  checked='checked'";
                $clase="notls ck";
            }
        $tanque = $rs->tanque;
        $fecha = fechahoramex($rs->fecha);
        $producto = $rs->producto;
        $estacion = $rs->estacion;
        $volumen = decimales2($rs->tls);
        echo "
            <tr>
                <td><input type='checkbox' $checked class='$clase' name='iddescargas[]' id='d$id' value='$id' data-estacion='$estacion' data-tanque='$tanque' data-fecha='$fecha' data-producto='$producto' data-tls='$volumen'></td>
                <td>$estacion</td>
                <td>$tanque</td>
                <td>$fecha</td>
                <td>$producto</td>
                <td>$volumen</td>
            </tr>
        ";

    }
    echo "</tbody></table>
    
    ";
    ?>
<input type="button" class="btn-primary" value="Seleccionar" onclick="selecciona()">
<script>

function selecciona()
{
e = 0;
tlsObj = [];
    var checkedVals = $('.tls:checkbox:checked').map(function() {
    idcheck = this.id;

        dato  ={"id":$('#'+idcheck).val(),"estacion":$('#'+idcheck).attr('data-estacion'),"tanque": $('#'+idcheck).attr('data-tanque'),"fecha":$('#'+idcheck).attr('data-fecha'),"producto":$('#'+idcheck).attr('data-producto'),"tls":$('#'+idcheck).attr('data-tls')};
    tlsObj.push(dato);
    e++;


}).get();

if(e>0)
{
	hideTls();
	showTls();
}   

$('#remoteModal').modal('hide');
}
</script>

    <?php
}
?>
<script>
$(document).ready(function() {
  $('.table tr').click(function(event) {
    if (event.target.type !== 'checkbox') {
      $(':checkbox', this).trigger('click');
    }
  });
});

</script>