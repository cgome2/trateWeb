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
										<th>RUI</th>
										<th>Fecha</th>
										<th>Vol. Ini.</th>
										<th>Vol Fin</th>
										<th>Volumen Recepcion</th>
									</tr>
								</thead>
                                <tbody>";
    foreach($datos as $rs)
    {

        $checked ="";
        $disabled ="";
        $clase = "tls ck";
        $id = $rs->reception_unique_id;
        if(in_array($id,$descargas)) $checked=" checked='checked'";
        $fecha = fechahoramex($rs->end_delivery_timestamp);
        $volini = $rs->start_volume;
        $volfin = $rs->end_volume;
        $volumen = $volfin-$volini;
        echo "
            <tr>
                <td><input type='checkbox' $checked class='$clase' name='iddescargas[]' id='d$id' value='$id'  data-fecha='$fecha' data-volini='$volini' data-volfin='$volfin' data-volumen='$volumen'></td>
                <td>$id</td>
                <td>$fecha</td>
                <td>$volini</td>
                <td>$volfin</td>
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

        dato  ={"id":$('#'+idcheck).val(),"volini": $('#'+idcheck).attr('data-volini'),"fecha":$('#'+idcheck).attr('data-fecha'),"volfin":$('#'+idcheck).attr('data-volfin'),"volumen":$('#'+idcheck).attr('data-volumen')};
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