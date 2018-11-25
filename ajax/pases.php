<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/init.php';
if(isset($_GET['json']))
{# paginador json

$fechai = fechaest($_GET['fechai']);
$fechaf = fechaest($_GET['fechaf']);
$_SESSION['fechaiPases'] = $_GET['fechai'];
$_SESSION['fechafPases'] = $_GET['fechaf'];
$estatus = $_GET['estatus'];
if($_GET['vehiculo'] !="")
{
    $v = $_GET['vehiculo'];
    $filtro ="  camion = '$v' order by fecha_solicitud desc limit 1";
}
else if($_GET['pase'] !="")
{
    $v = $_GET['pase'];
    $filtro =" pase = '$v' ";
}
else
{
    $filtro =" date(fecha_solicitud) between '$fechai' and '$fechaf' and status = 'D' order by fecha_solicitud desc limit 100";
}
    $query = "select 
    date_format(fecha_solicitud,'%d/%m/%Y %H:%i:%s') as fecha,
     pase,
    viaje,
    camion,
    chofer,
    litros,
    status

     from ci_pases
 where 

$filtro
 ";

 $db = mycon();
 $consulta = $db->query($query);
 $dato = array();
 while( $rs = $consulta->fetch_assoc())
 {  

        $dta = array();
     $id = $rs['folio'];
     foreach($rs as $dt)
     {
     array_push($dta,$dt);
     }
     $btn = "<a href='#/ajax/pasesView.php?id=$id' class='btn btn-info' ><i class='glyphicon glyphicon-list-alt' title='Ver' data-toggle='modal' data-target='#remoteModal'></i></a>";
     $estatus = $rs['status'];
     $btn2 = "<a href='#/ajax/contingencias.php?id=$id' class='btn btn-info' ><i class='fa fa-wrench' title='Contingencia' data-toggle='modal' data-target='#remoteModal'></i></a>";

     $btn2="<a href=\"ajax/pasesView.php?id=$id&contingencia=1\" data-toggle=\"modal\" data-target=\"#remoteModal\" class=\"btn btn-primary\">Reasignar</a> ";
     array_push($dta,$btn.$btn2);
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

if(!isset($_SESSION['fechaiPases'])) $_SESSION['fechaiPases'] = date('01/m/Y');
if(!isset($_SESSION['fechafPases'])) $_SESSION['fechafPases'] = date('d/m/Y');
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

<form class="smart-form">
<fieldset>	

<section class="col col-2">
<label class="input"><i class="icon-prepend fa fa-calendar"></i>
<input type="text" id="fechai" readonly="readonly" value="<?php echo $_SESSION['fechaiPases'];?>" class="form-control calendarios" >
</label>
</section>
<section class="col col-2">

<label class="input"><i class="icon-prepend fa fa-calendar"></i>
<input type="text" id="fechaf" readonly="readonly" value="<?php echo $_SESSION['fechafPases'];?>" class="form-control calendarios" >
</label>
</section>
<section class="col col-2">


<label class="input"><i class="icon-prepend fa fa-ticket"></i>
<input type="text" id="pase" title="No. de Pase" placeholder="No. de Pase" value="" class="form-control busquedas" >
</label>
</section>
<section class="col col-2">

<label class="input">
<i class="icon-prepend fa fa-car"></i>
<input type="text" id="vehiculo" title="Placa de vehiculo"  placeholder="Placa de vehiculo"  value="" class="form-control busquedas" >
</label>
</section>

    <!-- <section class='smart-form'>
        <div class="inline-group">
            <label class="radio">
                <input type="radio" value='A' name="estatus" checked="true">
                <i></i>Activos</label>
            <label class="radio">
                <input type="radio" value='D' name="estatus">
                <i></i>Despachados</label>
        </div>
    </section> -->


																				
</fielset>
</form>


													
												
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
				                    <th>Fecha Solicitud</th>
				                    <th>Pase</th>
				                    <th>Viaje</th>
				                    <th>Vehiculo</th>
				                    <th data-hide="phone,tablet">Chofer</th>
				                    <th data-hide="phone,tablet">Litros</th>
				                    <th data-hide="phone,tablet">Estatus</th>
				                    <th></th>

					            </tr>
					        </thead>

<tbody> 


</tbody>

					        <tfoot>

					            <tr>
				                    <th>Fecha Solicitud</th>
				                    <th>Pase</th>
				                    <th>Viaje</th>
				                    <th>Vehiculo</th>
				                    <th data-hide="phone,tablet">Chofer</th>
				                    <th data-hide="phone,tablet">Litros</th>
				                    <th data-hide="phone,tablet">Estatus</th>
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
<div class="row">

<div class="modal fade" id="remoteModal" tabindex="-1" role="dialog" aria-labelledby="remoteModalLabel" aria-hidden="true">  
    <div class="modal-dialog">  
        <div class="modal-content"></div>  
    </div>  
</div>  
<
</div>
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
		ajax: 'ajax/pases.php?json=1&fechai=' + $('#fechai').val()
		+ '&fechaf=' + $('#fechaf').val() + '&vehiculo=' + $('#vehiculo').val() + '&pase=' + $('#pase').val() ,
		  "lengthChange": false,
    } );
	}
    //+'&estatus=' + $('input[name=estatus]:checked').val()

	$('.calendarios').datepicker({
	dateFormat: 'dd/mm/yy',
	prevText: '<i class="fa fa-chevron-left"></i>',
	nextText: '<i class="fa fa-chevron-right"></i>'
});


$(document).ready(function(){

    $('.calendarios').change(function(){
        $('#pases').val('');
        $('#vehiculos').val('');
        cargaDatos();
    })
    $('.busquedas').focus(function(){
        $('.busquedas').val('');
    });

    $('.busquedas').change(function(){
        cargaDatos();
    });

    //     $('input[name=estatus]').click(function(){
    //     cargaDatos();
    // })
})


</script>


    <?php
}