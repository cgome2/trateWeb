<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/init.php';

use \SmartUI\UI;
use \SmartUI\Util as SmartUtil;
use \SmartUI\Components\SmartForm;
use \Common\HTMLIndent;

//initilize the page

if ($_SERVER['REQUEST_METHOD'] =="POST")
{

// print_r($_POST);


	$leyenda = "<strong>Datos guardados</strong> Espere un momento ...";

#    $_ui->print_alert("<strong>Excepcion de Mysql :</strong> $r", 'danger');
$fecha = fechaest($_POST['fecha']);
$fecha = date('d/m/y',strtotime($fecha));
$hora = substr($_POST['hora'],0,5);
$serie = $_POST['serie'];
$sello = $_POST['sello'];
$referencia = $_POST['referencia'];
$cmd = "/usr/local/orpak/perl/Trate/bin/valida_factura_trate.pl $fecha  $referencia $serie";
$response = shell_exec($cmd);
$_ui->print_alert($cmd .':<br>' . $response, 'success');

// Y-m-d H:m
// FECHA referencia serie
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

                        'descargas' => array(
						'type' => 'hidden', // or FormField::FORM_FIELD_INPUT
						'col' => 3,
						'properties' => array(
							'id' => 'descargas',
							'label' => 'descargas',
                            'value' => $descargas
						)
					),
					'fecha' => array(
						'type' => 'input', // or FormField::FORM_FIELD_INPUT
						'col' => 3,
						'properties' => array(
							'id' => 'fecha',
							'label' => 'Fecha Documento',
							'icon' => 'fa-calendar',
							'icon_append' => true,
                            'value' => date('d/m/Y'),
                            'readonly' => true,
							'attr' => array("data-dateformat='dd/mm/yy' readonly='true'")

						)
					),
					'hora' => array(
						'type' => 'input', // or FormField::FORM_FIELD_INPUT
						'col' => 3,
						'properties' => array(
							'id' => 'hora',
							'label' => 'Hora Documento',
							'icon' => 'fa-clock-o',
							'icon_append' => true,
                            'value' => date('H:i:s'),
                            'type'=>'time'
						)
					),

					'costo_esp' => array(
						'type' => 'input',
						'col' => 2 ,
						'properties' => array(
						'label' => 'Costo Esp. $',
						'id' => 'costo_esp',
						'attr' => array("required maxlength='10'")
                        )
					),
						'litros_esp' => array(
						'type' => 'input',
						'col' => 2 ,
						'properties' => array(
							'id' => 'litros_esp',
							'label' => 'Litros Esperados' ,
                            'attr' => array("required maxlength='10'")
)
					),
						'sello' => array(
						'type' => 'input',
						'col' => 3 ,
						'properties' => array(
							'label' => 'Sello ',
							'id' => 'sello',
							'attr' => array("required maxlength='10'")
                        )
					),
                    'referencia' => array(
						'type' => 'input',
						'col' => 3 ,
						'properties' => array(
							'label' => 'Referencia ',
							'id' => 'Referencia',
							'attr' => array("required maxlength='10'")
                        )
					),
                    'estacion' => array(
						'type' => 'input',
						'col' => 2 ,
						'properties' => array(
							'label' => 'Estaci&oacute;n ',
							'id' => 'estacion',
							'attr' => array("required maxlength='10'")
                        )
					),
                    'serie' => array(
						'type' => 'input',
						'col' => 2 ,
						'properties' => array(
							'label' => 'Serie ',
							'id' => 'serie',
							'attr' => array("required maxlength='10'")
                        )
					)
						
						);



				$form = $_ui->create_smartform($fields);
				$form->fieldset(0, array('descargas','fecha','hora','estacion','serie'));
				$form->fieldset(1, array('referencia','sello','litros_esp','costo_esp'));




				$form->footer(function() use ($_ui) {
                    $btn1 = $_ui->create_button('Guardar', 'primary')->attr(array('type' => 'button','id' => 'enviar'))->print_html(true);
                    $btn2 = $_ui->create_button('Regresar', 'primary')->attr(array('type' => 'button','id' => 'regresar'))->print_html(true);
                    $btn3 = $_ui->create_button('TLS', 'primary')->attr(array('type' => 'button','id' => 'selectTLS','data-toggle'=>"modal", 'data-target'=>"#remoteModal"))->print_html(true);

					$tabla =  "<table id='remisionesTable'  width='100%'>
				        
</table>
	<table id='tlsTable' width='100%'>
<thead>
	
        <th>RUI</th>
        <th>Fecha</th>
        <th>Vol. Ini.</th>
        <th>Vol. Fin</th>
        <th>Volumen Recepcion</th>
        <th></th>
</thead>
	</table>
						
							

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

        // $('#hora').timepicker({
        //     format: 'hh:mm'
        // });
        
		$('#fecha').datepicker({
	dateFormat: 'dd/mm/yy',
	prevText: '<i class="fa fa-chevron-left"></i>',
	nextText: '<i class="fa fa-chevron-right"></i>'

});
});


// selector de tls.
$('#remoteModal').on('show.bs.modal', function (e) {
	 desc = btoa(JSON.stringify(tlsObj));

    var loadurl = 'ajax/tls.php?descargas=' + desc;
    $(this).find('.modal-body').load(loadurl);
});






function alertaValidacion(mensaje)
{alert(mensaje)
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
        if($('#estacion').val() =="")
		{
			alertaValidacion('Especifica la estacion');
            $('#estacion').select();
			return false;
		}
        if($('#sello').val() =="")
		{
			alertaValidacion('Especifica el sello');
            $('#sello').select();
			return false;
		}
        if($('#serie').val() =="")
		{
			alertaValidacion('Especifica la serie');
            $('#serie').select();
			return false;
		}
        if($('#referencia').val() =="")
		{
			alertaValidacion('Especifica la referencia');
            $('#referencia').select();
			return false;
		}
        if($('#litros_esp').val() =="" ||  $('#litros_esp').val() ==0 || isNaN($('#litros_esp').val()) )
		{
			alertaValidacion('Valor incorrecto para litros esperados');
            $('#litros_esp').select();
			return false;
		}
        if($('#costo_esp').val() =="" ||  $('#costo_esp').val() ==0 || isNaN($('#costo_esp').val()) )
		{
			alertaValidacion('Valor incorrecto para costo esperado');
            $('#costo_esp').select();
			return false;
		}
    $('#descargas').val(btoa(JSON.stringify(tlsObj)));
    
		if($('#descargas').val().length < 10)
	{
		alertaValidacion('Selecciona por lo menus un TLS');
		return false;
	}

if(confirm("Esta usted seguro de guardar la recepcion ?"))
{
						$.post("ajax/recepcionesadd.php",
							$('.smart-form').serialize(),
							function(data,status){
								$('#resultado').html(data);
							}
						);					}
}

// seleccionar tls
// if(hr == 'undefined')	{
// 	hideRemisiones();
// alert(hr);
// }
var tlsObj = [];
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
            { data: "fecha" },
            { data: "volini"},
            { data: "volfin"},
            { data: "volumen" },
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
                
                setTimeout("showTls();",1000);


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
