<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/init.php';
?>

<div class="modal-header"></div>  
<div class="modal-body" style="font-family:'Lucida Console', Monaco, monospace">  
              
<?php
$id = $_GET['id'];
if($_SERVER['REQUEST_METHOD']=="POST")
{

    $_POST['idrecepciones'] = $id;
    i_post("bascula","","replace");
    $litros = $_POST['litros'];
echo "update recepciones set densidad = '$litros' where idrecepciones ='$id'";
    iquery("update recepciones set densidad = '$litros' where idrecepciones ='$id'");
    echo "<script>alert('datos guardados');
        parent.location.reload();
        </script>";
    exit;
}

  $db = mycon();
 $consulta = $db->query("select * from ci_pases 
 where id ='$id'");
 $dato = array();
  $rs = $consulta->fetch_assoc();
     ?>
     <iframe name="marco" style="display:none"></iframe>
     <h1>
     Datos del Pase
     </h1>
     <form method="post" action="ajax/pasesView.php?id=<?php echo $id?>" target="marco">
                    <table id="datatable_fixed_column" class="table table-striped table-bordered" width="100%">

	
					        <thead>
        <tr>
        <th>Pase</th>
        <th style='font-weight:normal;' class='derecha'>
        <input type="text" value="<?php echo $rs['id']?>" >
        </th>
    </tr>

    <tr>
        <th >Fecha de Solicitud :</th>
        <th style='font-weight:normal;' class='derecha'>
        <input type="text" value="<?php echo $rs['fecha_solicitud']?>">
        </th>
    </tr> 
       <tr>
        <th >Viaje :</th>
        <th style='font-weight:normal;' class='derecha'>
        <input type="text" value="<?php echo $rs['viaje']?>">
        </th>
    </tr>
</thead>
</table>
<div class="modal-footer">  
<button>Guardar
</button>

    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>  
</div> 

</form>
<script>    
		$(document).ready(function() {

			$('.datepicker').datetimepicker();
});
</script>


     <?php
 