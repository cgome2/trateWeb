<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/init.php';

use \SmartUI\UI;
use \SmartUI\Util as SmartUtil;
use \SmartUI\Components\SmartForm;
use \Common\HTMLIndent;

//initilize the page

if ($_SERVER['REQUEST_METHOD'] =="POST")
{

	if(isset($_POST['remisionesTable_length'])) unset($_POST['remisionesTable_length']);
	
	foreach($_POST as $key=>$value)
	{
	if(substr($key,0,4) =="jqg_" || substr($key,-7) =="_length")
	unset($_POST[$key]);
	}
	// filtramos campos innesesarios
	$id = $_GET['id'];

	$descargas = array();
	$dd = $_POST['descargas'];
	$d = json_decode(base64_decode($_POST['descargas']));
	foreach($d as $dt)	
	{
		array_push($descargas,$dt->id);
	}
	$descargas = implode($descargas,",");
	unset($_POST['descargas']);
	$lg = $_POST['litrosGalones'];
	$td = $_POST['tipoDocumento'];

	if($td == "1")
	{
		$litros = $_POST['volumenFactura'];
		if($lg == 2) $litros = $litros * 3.78541;
		// insertamos en tabla de facturas
		$f = $_POST['folioFactura'];
		if(rquery("select count(*) from facturas where folio = '$f'") == 0)
		{
		$idf = iquery("insert into facturas(folio) values('$f')");
		$_POST['idfacturas'] = $idf;
		}
		else
		{
 			$vf = rquery("select idfacturas from facturas where folio = '$f'");
 			$vd = rquery("select idfacturas from documentos where iddocumentos = '$id'");
			if($vd != $vf)
			{
			echo"
				<script>
					$.smallBox({
						title : \"Alerta de validacion\",
						content : \"<i class='fa fa-clock-o'></i> <i>El folio de factura $f ya esta en uso $id $idf</i>\",
						color : \"#C46A69\",
						iconSmall : \"fa fa-times fa-2x fadeInRight animated\",
						timeout : 4000
					});
			</script>
			";
			exit;
			}
		}


	}
	else
	{
		$litros = $_POST['volumenRemision'];
		if($lg ==2) $litros = $litros * 3.78541;
		// insertamos en tabla de remisiones
		$f = $_POST['folioRemision'];
		if(rquery("select count(*) from remisiones where folio = '$f'") == 0)
		{
		$idf = iquery("insert into remisiones(folio) values('$f')");
		$_POST['idremisiones'] = $idf;
		}
		

	}

	
	$leyenda = "<strong>Datos guardados</strong> Espere un momento ...";
	$nv = "";
	$_POST['litros'] = $litros;
	if($id != "")
	{
	# analizamos los permisos
	// if($permisos['docs'] == "20000")
	// {
	// $_POST['usuario'] = $_SESSION['loginHO'];
	// $_POST['fcap'] = date('Y-m-d H:i:s');
	// $_POST['ip'] =  $_SERVER['REMOTE_ADDR'];
	// $_POST['descargas'] = $dd;;
	// $_POST['iddocumentos'] = $id;
	// $_POST['remisiones'] = $remi;
    // $r = i_post("documentosAudit");
	// $leyenda  ="<strong>Solicitud de cambios almacenada correctamente</strong> Espere un momento ... ";
	// $nv ="1";
	// }
	// else //if($permisos['docs'] == "3")
	// {
    $r = e_post("documentos",$id);
	// }
    }
	else
	{
	$_POST['usuario'] = $_SESSION['loginHO'];
	$_POST['fcap'] = date('Y-m-d H:i:s');
	$_POST['ip'] =  $_SERVER['REMOTE_ADDR'];
	$r = i_post("documentos");
	$id = $r;
	}
	
	if(isset($dt) && $nv == "")
	{//? si mandaron remisiones las procesamos
	iquery("update documentos set iddocumentosPadre = null where iddocumentosPadre ='$id'");
			foreach($dt as $rs)
			{
				$idd = $rs['id'];
				$vf = $rs['vfactura'];
				$ppg = $rs['pxg'];
				$queryup ="update documentos set volumenFacturado = '$vf', ppg='$ppg', iddocumentosPadre ='$id' where iddocumentos ='$idd'";
				iquery($queryup);
				
			}
	}
    if(is_numeric($r))
    {
	if( $nv == ""){
	// guardamos descargas.
	iquery("update descargas set iddocumentos = null where iddocumentos = $id");

	iquery("update descargas set iddocumentos = '$id' where iddescargas in($descargas)");
	}
    $_ui->print_alert($leyenda, 'success');
    $_ui->href("#ajax/documentos.php");
}
else
{
    $_ui->print_alert("<strong>Excepcion de Mysql :</strong> $r", 'danger');
}
exit;

}
$_ui->start_track();

?>
<style>
.select2-selection__choice
{
	padding-right:25px;
}
/* style all input elements with a required attribute */
input:required:invalid {
  box-shadow: 2px 2px 10px rgba(200, 0, 0, 0.85);
}

/**
 * style input elements that have a required
 * attribute and a focus state
 */
input:required:invalid:focus {
  border: 1px solid red;
  outline: none;
}

/**
 * style input elements that have a required
 * attribute and a hover state
 */
input:required:invalid:hover {
  opacity: 1;
}

</style>
		<section id="widget-grid" class="">

			<?php

				$_ui->start_track();
                // SmartForm layout
				$fields = array(

					
					'costo_esp' => array(
						'type' => 'input',
						'col' => 2 ,
						'properties' => array(
						'label' => 'Costo Esperado $',
						'id' => 'costo_esp',
						'attr' => array("required maxlength='10'")
                        )
					),
						'litros_esp' => array(
						'type' => 'input',
						'col' => 3 ,
						'properties' => array(
							'id' => 'litros_esp',
							'label' => 'Litros Esperados' ,
                            'attr' => array("required maxlength='10'")
)
					),
						'sello' => array(
						'type' => 'input',
						'col' => 2 ,
						'properties' => array(
							'label' => 'Sello ',
							'id' => 'sello',
							'attr' => array("required maxlength='10'")
                        )
					),
                    'referencia' => array(
						'type' => 'input',
						'col' => 2 ,
						'properties' => array(
							'label' => 'Referencia ',
							'id' => 'referencia',
							'attr' => array("required maxlength='10'")
                        )
					),
                    'estacion' => array(
						'type' => 'input',
						'col' => 2 ,
						'properties' => array(
							'label' => 'estacion ',
							'id' => 'estacion',
							'attr' => array("required maxlength='10'")
                        )
					),
                    'serie' => array(
						'type' => 'input',
						'col' => 2 ,
						'properties' => array(
							'label' => 'serie ',
							'id' => 'serie',
							'attr' => array("required maxlength='10'")
                        )
					)
						
						);



				$form = $_ui->create_smartform($fields);
				$form->fieldset(0, array('estacion','sello','serie'));
				$form->fieldset(1, array('referencia','litros_esp','costo_esp'));




				$form->footer(function() use ($_ui) {
                    $btn1 = $_ui->create_button('Guardar', 'primary')->attr(array('type' => 'button','id' => 'enviar'))->print_html(true);
                    $btn2 = $_ui->create_button('Regresar', 'primary')->attr(array('type' => 'button','id' => 'regresar'))->print_html(true);
                    $btn3 = $_ui->create_button('TLS', 'primary')->attr(array('type' => 'button','id' => 'selectTLS','data-toggle'=>"modal", 'data-target'=>"#remoteModal"))->print_html(true);

					$tabla =  "<table id='remisionesTable'  width='100%'>
				        
</table>
	<table id='tlsTable' width='100%'>
<thead>
	
					<th>Id</th>
					<th>Estacion</th>
				<th>Tanque</th>
				<th>Fecha Hora</th>
				<th>Producto</th>
				<th>Volumen Recepcion</th>
				<th></th>
</thead>
	</table>
	<span class='btn' id='quitar'><i class='fa fa-trash'></i> Eliminar seleccionados</span>
	<span class='btn' id='rgalones'><i class='fa fa-money'></i> Completar Precio Galones</span>
							<br>
						
							

";
					return $tabla.$btn1.$btn2.$btn3;
				});


				$form->title('<h1><strong>Captura de Recepciones<strong></h1>');
				$result = $form->print_html(true);
				echo $result;

			?>


		</section>
  <div class="modal fade" id="remoteModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Selecciona un TLS</h4>
        </div>
        <div class="modal-body">
         
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
      
    </div>
  </div>





<script type="text/javascript">
			var responsiveHelper_dt_basic = undefined;
			var responsiveHelper_datatable_fixed_column = undefined;
			var responsiveHelper_datatable_col_reorder = undefined;
			var responsiveHelper_datatable_tabletools = undefined;

	loadScript("js/plugin/datatables/jquery.dataTables.min.js", function(){
		loadScript("js/plugin/datatables/dataTables.colVis.min.js", function(){
			loadScript("js/plugin/datatables/dataTables.tableTools.min.js", function(){
				loadScript("js/plugin/datatables/dataTables.bootstrap.min.js", function(){
					loadScript("js/plugin/datatable-responsive/datatables.responsive.min.js", pagefunction)
				});
			});
		});
	});

loadScript("js/plugin/jqgrid/jquery.jqGrid.min.js");
loadScript("js/plugin/jqgrid/grid.locale-en.min.js");

	pageSetUp();


	function listado()
	{
		window.location.replace("#ajax/recepciones.php");
	}
    $(document).ready(function(){
	 $('fieldset').css('border','0 none transparent');
    $('#regresar').click(function(){
        listado();
    });
    $("#enviar").click(function(){
	validar();
    });

		
});


// selector de tls.
$('#remoteModal').on('show.bs.modal', function (e) {
	 desc = btoa(JSON.stringify(tlsObj));

    var loadurl = 'ajax/tls.php?descargas=' + desc;
    $(this).find('.modal-body').load(loadurl);
});






function alertaValidacion(mensaje)
{
		$.smallBox({
			title : "Alerta de validacion",
			content : "<i class='fa fa-clock-o'></i> <i>"+ mensaje +"</i>",
			color : "#C46A69",
			iconSmall : "fa fa-times fa-2x fadeInRight animated",
			timeout : 4000
		});

}
function alertaInfo(mensaje)
{
		$.smallBox({
			title : "Alerta de validacion",
			content : "<i class='fa fa-clock-o'></i> <i>"+ mensaje +"</i>",
			color : "#669266",
			iconSmall : "fa fa-times fa-2x fadeInRight animated",
			timeout : 4000
		});

}
function validar()
{
			volumen1 = 0;
			for (var i = 0; i < tlsObj.length; i++){
  // look for the entry with a matching `code` value
			 volumen1 = parseFloat(volumen1) + parseFloat(tlsObj[i].tls.replace(/,/g, ''));
			}
if($('#temperatura').val() <= -30 || $('#temperatura').val() >= 104 )
{
alertaValidacion('La temperatura especificada es incorrecta');
return false;
}
if($('#temperaturaEntrada').val() <= -30 || $('#temperaturaEntrada').val() >= 104 )
{
alertaValidacion('La temperatura de entrada especificada es incorrecta');
return false;
}
$('#descargas').val(btoa(JSON.stringify(tlsObj)));
// importes
		


	if($('#folioRemision').val() =="")
	{
		alertaValidacion('Especifica el folio de la remision');
		return false;
	}

	if(isNaN($('#volumenRemision').val()) || $('#volumenRemision').val() <= 0 )
	{
		alertaValidacion('Especifica el volumen de la remision');
		return false;
	}

	if($('#chofer').val() =="")
	{
		alertaValidacion('Especifica el nombre del chofer');
		return false;
	}
	if($('#persona').val() =="")
	{
		alertaValidacion('Especifica el nombre quien descarga');
		return false;
	}
		if($('#descargas').val().length < 10)
	{
		alertaValidacion('Selecciona por lo menus un TLS');
		return false;
	}
		mensajeC ="Esta usted seguro de guardar los cambios realizados? <br>"
		mensajeC = mensajeC + " Proveedor : Novum <br>";
		mensajeC = mensajeC + " Folio Remision : " + $('#folioRemision').val() + " <br>";
		mensajeC = mensajeC + " Volumen Remision : " + $('#volumenRemision').val() + " <br>";
		mensajeC = mensajeC + " Volumen TLS: " + volumen1 + " <br>";

}


				$.SmartMessageBox({
					title : "Confirmaci&oacute;n",
					content : mensajeC,
					buttons : '[No][Si]'
				}, function(ButtonPressed) {
					if (ButtonPressed === "Si") {
						$.post("ajax/documentos/documento.php?id=<?php echo $id?>",
							$('.smart-form').serialize(),
							function(data,status){
								$('#resultado').html(data);
							}
						);					}
					else
					{
						return false;
					}
				});



// seleccionar tls
// if(hr == 'undefined')	{
// 	hideRemisiones();
// alert(hr);
// }
var tlsObj = {};
function showTls(){
if ( $.fn.dataTable.isDataTable( '#tlsTable' ) ) {
    tablaTls = $('#tlsTable').DataTable();
}
else {


tablaTLS = $('#tlsTable').DataTable({
	"aaData":tlsObj,
			bFilter : false,
             columns: [	
            { data: "id" },
            { data: "estacion" },
            { data: "tanque"},
            { data: "fecha"},
            { data: "producto" },
            { data: "tls", className: "text-right editar"  },
            {
                data: null,
                className: "center",
                defaultContent: ' <span onclick="quitaTls(this)" class="btn btn-primary"><i class="fa fa-trash" /></i></span>'
            }
        ],
		"aoColumnDefs": [
        {
        "bSearchable": false,
        "bVisible": false,
        "aTargets": [0]
        }
		]
});
}
}
function hideTls(){
	tablaTLS.destroy();

}
function quitaTls(obj)
{
	 idtr = $(obj).closest('tr');
	ix =  $("tr").index(idtr);
	ix = ix -1;
	tablaTLS.rows(ix).remove().draw();
	tlsObj = tablaTLS.rows().data().toArray()
}


$(document).ready(function(){
					// remove classes
				$(".ui-jqgrid").removeClass("ui-widget ui-widget-content");
				$(".ui-jqgrid-view").children().removeClass("ui-widget-header ui-state-default");
				$(".ui-jqgrid-labels, .ui-search-toolbar").children().removeClass("ui-state-default ui-th-column ui-th-ltr");
				$(".ui-jqgrid-pager").removeClass("ui-state-default");
				$(".ui-jqgrid").removeClass("ui-widget-content");

				// add classes
				$(".ui-jqgrid-htable").addClass("table table-bordered table-hover");
				$(".ui-jqgrid-btable").addClass("table table-bordered table-striped");

				$(".ui-pg-div").removeClass().addClass("btn btn-sm btn-primary");
				$(".ui-icon.ui-icon-plus").removeClass().addClass("fa fa-plus");
				$(".ui-icon.ui-icon-pencil").removeClass().addClass("fa fa-pencil");
				$(".ui-icon.ui-icon-trash").removeClass().addClass("fa fa-trash-o");
				$(".ui-icon.ui-icon-search").removeClass().addClass("fa fa-search");
				$(".ui-icon.ui-icon-refresh").removeClass().addClass("fa fa-refresh");
				$(".ui-icon.ui-icon-disk").removeClass().addClass("fa fa-save").parent(".btn-primary").removeClass("btn-primary").addClass("btn-success");
				$(".ui-icon.ui-icon-cancel").removeClass().addClass("fa fa-times").parent(".btn-primary").removeClass("btn-primary").addClass("btn-danger");

				$(".ui-icon.ui-icon-seek-prev").wrap("<div class='btn btn-sm btn-default'></div>");
				$(".ui-icon.ui-icon-seek-prev").removeClass().addClass("fa fa-backward");

				$(".ui-icon.ui-icon-seek-first").wrap("<div class='btn btn-sm btn-default'></div>");
				$(".ui-icon.ui-icon-seek-first").removeClass().addClass("fa fa-fast-backward");

				$(".ui-icon.ui-icon-seek-next").wrap("<div class='btn btn-sm btn-default'></div>");
				$(".ui-icon.ui-icon-seek-next").removeClass().addClass("fa fa-forward");

				$(".ui-icon.ui-icon-seek-end").wrap("<div class='btn btn-sm btn-default'></div>");
				$(".ui-icon.ui-icon-seek-end").removeClass().addClass("fa fa-fast-forward");

})

$(document).ready(function(){
	$("#sello").attr('autocomplete', 'off');
	$("#estacion").attr('autocomplete', 'off');
	$("#serie").attr('autocomplete', 'off');
	$("#referencia").attr('autocomplete', 'off');
	$("#litros_esp").attr('autocomplete', 'off');
	$("#costo_esp").attr('autocomplete', 'off');


});


</script>
<div id="ajaxResults">
</div>
<div id="resultado">

</div>
