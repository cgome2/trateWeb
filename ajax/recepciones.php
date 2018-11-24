<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/init.php';
if(isset($_GET['json']))
{# paginador json

$fechai = fechaest($_GET['fechai']);
$fechaf = fechaest($_GET['fechaf']);
$_SESSION['fechaiDocumentos'] = $_GET['fechai'];
$_SESSION['fechafDocumentos'] = $_GET['fechaf'];

    $query = "select 
    date_format(fecha_hora,'%d/%m/%Y %H:%i:%s') as fecha,
    estacion,
    sello,
    serie,referencia,
    litros_esp,
    costo_esp

     from ci_movimientos
 where 
date(fecha_hora) between '$fechai' and '$fechaf'
and tipo_referencia = 2
 ";

 $db = mycon();
 $consulta = $db->query($query);
 $dato = array();
 while( $rs = $consulta->fetch_assoc())
 {  

        $dta = array();
     $id = $rs['referencia'];
     foreach($rs as $dt)
     {
     array_push($dta,$dt);
     }
     $btn = "<a href='#/ajax/recepcionesver.php?id=$id' class='btn btn-primary' >Ver</a>";
     array_push($dta,$btn);
     array_push($dato,$dta);
 }

$json = json_encode(array("data"=>$dato),0);
if ($json)
    echo $json;
else
    echo json_last_error_msg();

}
else if($_SERVER['REQUEST_METHOD']=="POST")
{

}
else
{
    # mostramos el formulario

if(!isset($_SESSION['fechaiDocumentos'])) $_SESSION['fechaiDocumentos'] = date('01/m/Y');
if(!isset($_SESSION['fechafDocumentos'])) $_SESSION['fechafDocumentos'] = date('d/m/Y');
  ?>

<!-- row -->
<div class="row">
	
	<!-- col -->
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			
			<!-- PAGE HEADER -->
			<i class="fa-fw fa fa-download"></i> 
				Recepciones
		</h1>
	</div>
	<!-- end col -->
	
	<!-- right side of the page with the sparkline graphs -->
	<!-- col -->
	
</div>
<!-- end row -->

<!--
	The ID "widget-grid" will start to initialize all widgets below 
	You do not need to use widgets if you dont want to. Simply remove 
	the <section></section> and you can use wells or panels instead 
	-->

<!-- widget grid -->
<section id="widget-grid" class="">

<div class="row" style="width:100%;">


<fieldset>	
<section class="col col-2">
<label class="input">
<input type="text" id="fechai" readonly="readonly" value="<?php echo $_SESSION['fechaiDocumentos'];?>" class="form-control" >
</label>
<label class="input">
<input type="text" id="fechaf" readonly="readonly" value="<?php echo $_SESSION['fechafDocumentos'];?>" class="form-control" >
</label>

<label class="input">
<button onclick="cargaDatos()" class="btn btn-default"><i class="fa fa-search"></i></button>
</label>
<label class="input">
<a  title="Agregar Documentos" href="#ajax/recepcionesadd.php" class="btn btn-default"><i class="fa fa-plus"></i></a>
</label>

</section>
																				
</fielset>


													
												
</div>
	<!-- row -->
	<div class="row">
		
		<!-- NEW WIDGET START -->
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			
			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-1" data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false" data-widget-sortable="false">


				<!-- widget div-->
				<div>

					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->

					</div>
					<!-- end widget edit box -->

					<!-- widget content -->
					<div class="widget-body no-padding">

						<table id="datatable_fixed_column" class="table" width="100%">
	
					        <thead>

					            <tr>
				                    <th>Fecha</th>
				                    <th>Estaci&oacute;n</th>
				                    <th>Sello</th>
				                    <th>Serie</th>
				                    <th data-hide="phone,tablet">Referencia</th>
				                    <th data-hide="phone,tablet">Litros Esperados</th>
				                    <th data-hide="phone,tablet">Costo Esperado</th>
				                    <th></th>

					            </tr>
					        </thead>

<tbody> 


</tbody>

					        <tfoot>

					            <tr>
				                    <th>Fecha</th>
				                    <th>Estaci&oacute;n</th>
				                    <th>Sello</th>
				                    <th>Serie</th>
				                    <th data-hide="phone,tablet">Referencia</th>
				                    <th data-hide="phone,tablet">Litros Esperados</th>
				                    <th data-hide="phone,tablet">Costo Esperado</th>
				                    <th></th>
					            </tr>
					        </tfoot>
					
						</table>

					</div>
					<!-- end widget content -->

				</div>
				<!-- end widget div -->

			</div>
			<!-- end widget -->

		</article>
		<!-- WIDGET END -->
		
	</div>

	<!-- end row -->

	<!-- row -->


	<!-- end row -->

</section>
<!-- end widget grid -->

<script type="text/javascript">


	/* DO NOT REMOVE : GLOBAL FUNCTIONS!
	 *
	 * pageSetUp(); WILL CALL THE FOLLOWING FUNCTIONS
	 *
	 * // activate tooltips
	 * $("[rel=tooltip]").tooltip();
	 *
	 * // activate popovers
	 * $("[rel=popover]").popover();
	 *
	 * // activate popovers with hover states
	 * $("[rel=popover-hover]").popover({ trigger: "hover" });
	 *
	 * // activate inline charts
	 * runAllCharts();
	 *
	 * // setup widgets
	 * setup_widgets_desktop();
	 *
	 * // run form elements
	 * runAllForms();
	 *
	 ********************************
	 *
	 * pageSetUp() is needed whenever you load a page.
	 * It initializes and checks for all basic elements of the page
	 * and makes rendering easier.
	 *
	 */

	pageSetUp();
	
	/*
	 * ALL PAGE RELATED SCRIPTS CAN GO BELOW HERE
	 * eg alert("my home function");
	 * 
	 * var pagefunction = function() {
	 *   ...
	 * }
	 * loadScript("js/plugin/_PLUGIN_NAME_.js", pagefunction);
	 * 
	 */
	
	// PAGE RELATED SCRIPTS
	
	// pagefunction	
	var pagefunction = function() {
	
			cargaDatos();
		/* BASIC ;*/
			var responsiveHelper_dt_basic = undefined;
			var responsiveHelper_datatable_fixed_column = undefined;
			var responsiveHelper_datatable_col_reorder = undefined;
			var responsiveHelper_datatable_tabletools = undefined;
			
			var breakpointDefinition = {
				tablet : 1024,
				phone : 480
			};


	};

	// load related plugins
	
	loadScript("js/plugin/datatables/jquery.dataTables.min.js", function(){
		loadScript("js/plugin/datatables/dataTables.colVis.min.js", function(){
			loadScript("js/plugin/datatables/dataTables.tableTools.min.js", function(){
				loadScript("js/plugin/datatables/dataTables.bootstrap.min.js", function(){
					loadScript("js/plugin/datatable-responsive/datatables.responsive.min.js", pagefunction)
				});
			});
		});
	});


	function cargaDatos() 
	{ 
if ( $.fn.dataTable.isDataTable( '#datatable_fixed_column' ) ) {
    table = $('#datatable_fixed_column').DataTable();
	table.destroy();
    
}
 	$('#datatable_fixed_column').DataTable( {
		 bFilter: false,
		ajax: 'ajax/recepciones.php?json=1&fechai=' + $('#fechai').val()
		+ '&fechaf=' + $('#fechaf').val() ,
		  "lengthChange": false,
    } );
	}

	$('.form-control').datepicker({
	dateFormat: 'dd/mm/yy',
	prevText: '<i class="fa fa-chevron-left"></i>',
	nextText: '<i class="fa fa-chevron-right"></i>'
});





</script>


    <?php
}