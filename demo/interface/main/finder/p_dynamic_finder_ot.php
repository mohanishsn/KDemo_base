<?php
// Copyright (C) 2012 Rod Roark <rod@sunsetsystems.com>
// Sponsored by David Eschelbacher, MD
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// Sanitize escapes and stop fake register globals.
//
$sanitize_all_escapes = true;
$fake_register_globals = false;

require_once("../../globals.php");
require_once("$srcdir/formdata.inc.php");

$popup = empty($_REQUEST['popup']) ? 0 : 1;

// Generate some code based on the list of columns.
//
$colcount = 0;
$header0 = "";
$header  = "";
$coljson = "";
$res = sqlStatement("SELECT option_id, title FROM list_options WHERE " .
  "list_id = 'OTSpecific' ORDER BY seq, title");
while ($row = sqlFetchArray($res)) {
  $colname = $row['option_id'];
  $title = xl_list_label($row['title']);
  $header .= "   <th>";
  $header .= text($title);
  $header .= "</th>\n";
   $header0 .= "   <td align='center'><input type='text' size='10' ";
  $header0 .= "value='' class='search_init' /></td>\n"; 
  if ($coljson) $coljson .= ", ";
  $coljson .= "{\"sName\": \"" . addcslashes($colname, "\t\r\n\"\\") . "\"}";
  ++$colcount;
}
?>
<html>
<head>
<?php html_header_show(); ?>

<link rel="stylesheet" href="<?php echo $css_header; ?>" type="text/css">
<link rel="stylesheet" href="../../../library/css/bootstrap.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
<link rel="stylesheet" href="../../../library/dist/css/AdminLTE.css">
<link rel="stylesheet" href="../../../library/css/mycss.css">
<style type="text/css">
@import "../../../library/js/datatables/media/css/demo_page.css";
@import "../../../library/js/datatables/media/css/demo_table.css";
.mytopdiv { float: left; margin-right: 1em; }
.menu{
			width: 100px;
			background: #000;
			color: #fff;
			position:absolute;
			z-index: 999999;
			display: none;
			box-shadow: 0 0 10px #713C3C;
		}
		.menu ul{
			list-style: none;
			padding: 0;
			margin:0;
		}
		.menu ul a{
			text-decoration: none;
		}
		.menu ul li{
			width: 88%;
			padding: 6%;
			background-color: #C0C0C0;
			color: #fff;
		}
		.menu ul li:hover{
			background-color: #F7BA4B;
	    		color: #444343;
		}
</style>

<script type="text/javascript" src="../../../library/js/datatables/media/js/jquery.js"></script>
<script type="text/javascript" src="../../../library/js/datatables/media/js/jquery.dataTables.min.js"></script>
<!-- this is a 3rd party script -->
<script type="text/javascript" src="../../../library/js/datatables/extras/ColReorder/media/js/ColReorderWithResize.js"></script>

<script language="JavaScript">

$(document).ready(function() {

 // Initializing the DataTable.
 //
 var oTable = $('#pt_table').dataTable( {
  "bProcessing": true,
  // next 2 lines invoke server side processing
  "bServerSide": true,
  "sAjaxSource": "p_dynamic_finder_ot_ajax.php",
  // sDom invokes ColReorderWithResize and allows inclusion of a custom div
  "sDom"       : 'Rlfrt<"mytopdiv">ip',
  // These column names come over as $_GET['sColumns'], a comma-separated list of the names.
  // See: http://datatables.net/usage/columns and
  // http://datatables.net/release-datatables/extras/ColReorder/server_side.html
  "aoColumns": [ <?php echo $coljson; ?> ],
  "aLengthMenu": [ 10, 25, 50, 100 ],
  "iDisplayLength": <?php echo empty($GLOBALS['gbl_pt_list_page_size']) ? '25' : '25'; ?>,
  // language strings are included so we can translate them$GLOBALS
  "oLanguage": {
   "sSearch"      : "<?php echo xla('Search all columns'); ?>:",
   "sLengthMenu"  : "<?php echo xla('Show') . ' _MENU_ ' . xla('entries'); ?>",
   "sZeroRecords" : "<?php echo xla('No matching records found'); ?>",
   "sInfo"        : "<?php echo xla('Showing') . ' _START_ ' . xla('to{{range}}') . ' _END_ ' . xla('of') . ' _TOTAL_ ' . xla('entries'); ?>",
   "sInfoEmpty"   : "<?php echo xla('Nothing to show'); ?>",
   "sInfoFiltered": "(<?php echo xla('filtered from') . ' _MAX_ ' . xla('total entries'); ?>)",
   "oPaginate": {
    "sFirst"   : "<?php echo xla('First'); ?>",
    "sPrevious": "<?php echo xla('Previous'); ?>",
    "sNext"    : "<?php echo xla('Next'); ?>",
    "sLast"    : "<?php echo xla('Last'); ?>"
   }
  }
 } );

 // This puts our custom HTML into the table header.
 $("div.mytopdiv").html("<form name='myform'><input type='checkbox' name='form_new_window' value='1'<?php
  if (!empty($GLOBALS['gbl_pt_list_new_window'])) echo ' checked'; ?> /><?php
  echo xlt('Open in New Window'); ?></form>");

 // This is to support column-specific search fields.
 // Borrowed from the multi_filter.html example.
 $("thead input").keyup(function () {
  // Filter on the column (the index) of this element
	oTable.fnFilter( this.value, $("thead input").index(this) );
 });

 // OnClick handler for the rows
  $(document).delegate('#pt_table tbody tr', 'mousedown', function(e){
 //$('#pt_table tbody tr').live('click', function (e) {
  // ID of a row element is pid_{value}
  var newpid = this.id.substring(4);
  var enc=$(this).find('td').eq(2).html();
  var encounter=enc.substring(4);
  //alert(encounter);
  // If the pid is invalid, then don't attempt to set 
  // The row display for "No matching records found" has no valid ID, but is
  // otherwise clickable. (Matches this CSS selector).  This prevents an invalid
  // state for the PID to be set.
if( e.button == 2 ) {
	
      if (newpid.length===0)
      {
        return;
      }
      
		$("tr").on("contextmenu",function(e){
		       //prevent default context menu for right click
		       e.preventDefault();

		       var menu = $(".menu"); 

		       //hide menu if already shown
		       menu.hide(); 
		       
		       //get x and y values of the click event
		       var pageX = e.pageX;
		       var pageY = e.pageY;

		       //position menu div near mouse cliked area
		       menu.css({top: pageY , left: pageX});

		       var mwidth = menu.width();
		       var mheight = menu.height();
		       var screenWidth = $(window).width();
		       var screenHeight = $(window).height();

		       //if window is scrolled
		       var scrTop = $(window).scrollTop();

		       //if the menu is close to right edge of the window
		       if(pageX+mwidth > screenWidth){
		       	menu.css({left:pageX-mwidth});
		       }

		       //if the menu is close to bottom edge of the window
		       if(pageY+mheight > screenHeight+scrTop){
		       	menu.css({top:pageY-mheight});
		       }

		       //finally show the menu
		       menu.show();
		}); 
		
		$("html").on("click", function(){
			$(".menu").hide();
		});
	
         
       } else { 
         
        
  if (newpid.length===0)
  {
      return;
  }
  if (document.myform.form_new_window.checked) {
   openNewTopWindow(newpid);
  }
  else {
   top.restoreSession();
<?php if ($GLOBALS['concurrent_layout']) { ?>

document.location.href = "../../reports/timeSchduler.php?set_pid=" + newpid+'&encounter='+encounter;
   
<?php } else { ?>
   top.location.href = "../../patient_file/patient_file.php?set_pid=" + newpid;
<?php } ?>
  }
	   }
 $(document).ready(function() {
    $('ul.menu1 li').live("click", function() {
		var name=$(this).text();
		var formname='admit';
		var framed=1;
		if(name=="Transfer to ICU")
		{
		//alert(newpid);
		 top.restoreSession();
		 //alert(encounter);
		document.location.href="../../patient_file/encounter/transfer_ot.php?set_pid=" + newpid+'&encounter='+encounter;
		}else if(name=="Release")
		{
		top.restoreSession();
		document.location.href="../../patient_file/encounter/release_form.php?set_pid=" + newpid+'&encounter='+encounter;
		}else if(name=="Delete")
		{
		top.restoreSession();
		document.location.href="../../forms/admit/delete.php?set_pid=" + newpid+'&encounter='+encounter+'&formname='+formname;
		}
    });


});
 } );

});

function openNewTopWindow(pid) {
 document.fnew.patientID.value = pid;
 top.restoreSession();
 document.fnew.submit();
}

</script>

</head>
<body class="body_top">
<div class="menu">
	<ul class="menu1">
		<li><a href="#">Transfer to ICU</a></li>
		<li><a href="#">Release</a></li>
	</ul>
</div>
<div class="col-md-12">
<div class="col-md-4">
          <!-- Info Boxes Style 2 -->
          <div class="info-box bg-yellow">
            <span class="info-box-icon"><i class="ion ion-ios-people-outline"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">TOTAL IPD REGISTRATION</span>
              <span class="info-box-number">4</span>

              <div class="progress">
                <div class="progress-bar" style="width: 50%"></div>
              </div>
                  <span class="progress-description">
                    70% Increase in 30 Days
                  </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
		  </div>
		  <div class="col-md-4">
          <div class="info-box bg-green">
            <span class="info-box-icon"><i class="ion ion-ios-heart-outline"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">TOTAL ADMISSION</span>
              <span class="info-box-number">3</span>

              <div class="progress">
                <div class="progress-bar" style="width: 20%"></div>
              </div>
                  <span class="progress-description">
                    60% Increase in 30 Days
                  </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
		  </div>
		  <div class="col-md-4">
          <div class="info-box bg-red">
            <span class="info-box-icon"><i class="ion ion-ios-cloud-download-outline"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Total IPD DISCHARGE</span>
              <span class="info-box-number">3</span>

              <div class="progress">
                <div class="progress-bar" style="width: 70%"></div>
              </div>
                  <span class="progress-description">
                    70% Increase in 30 Days
                  </span>
            </div>
			            <!-- /.info-box-content -->
          </div>
		  </div>
          <!-- /.info-box -->
</div>
        </div>
<div id="dynamic"><!-- TBD: id seems unused, is this div required? -->

<!-- Class "display" is defined in demo_table.css -->
<table cellpadding="0" cellspacing="0" border="0" class="display" id="pt_table">
 <thead>
  <!--<tr>
<?php echo $header0; ?>
  </tr>-->
  <tr>
<?php echo $header; ?>
  </tr>
  
 </thead>
 <tbody>
  <tr>
   <!-- Class "dataTables_empty" is defined in jquery.dataTables.css -->
   <td colspan="<?php echo $colcount; ?>" class="dataTables_empty">...</td>
  </tr>
 </tbody>
</table>

</div>

<!-- form used to open a new top level window when a patient row is clicked -->
<form name='fnew' method='post' target='_blank' action='../main_screen.php?auth=login&site=<?php echo attr($_SESSION['site_id']); ?>'>
<input type='hidden' name='patientID'      value='0' />
</form>

</body>
</html>

