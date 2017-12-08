<!-- Form generated from formsWiz -->
<?php

$fake_register_globals=false;
$sanitize_all_escapes=true;

include_once("../../globals.php");
include_once("$srcdir/api.inc");
formHeader("Form: dictation");
$returnurl = $GLOBALS['concurrent_layout'] ? 'encounter_top.php' : 'patient_encounter.php';
?>
<html><head>
<?php html_header_show();?>
<style type="text/css">@import url(<?php echo $GLOBALS['webroot'] ?>/library/dynarch_calendar.css);</style>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="<?php echo $GLOBALS['webroot']; ?>/library/breadcrumbs/css/reset.css"> <!-- CSS reset -->
	<link rel="stylesheet" href="<?php echo $GLOBALS['webroot']; ?>/library/breadcrumbs/css/style.css"> <!-- Resource style -->
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<script src="<?php echo $GLOBALS['webroot']; ?>/library/breadcrumbs/js/modernizr.js"></script> <!-- Modernizr -->
		<!--[if IE 8]><script src="js/es5.js"></script><![endif]-->
		<script src="js/jquery.min.js"></script>
		<script src="js/selectize.js"></script>
		<script src="js/index.js"></script>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/dynarch_calendar.js"></script>
<?php include_once("{$GLOBALS['srcdir']}/dynarch_calendar_en.inc.php"); ?>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/dynarch_calendar_setup.js"></script>
<style>
.cd-breadcrumb.triangle li {
	font-size: 1.6rem;
}
dl {
	display:none;
}
body {
    font-size: 1.6rem !important;
    font-family: "Source Sans Pro", sans-serif !important;
	margin-left: 15px !important;
}
</style>
</head>
<body class="body_top" style="background: white">
<?php $newcrop_user_role=sqlQuery("select newcrop_user_role from users where username='".$_SESSION['authUser']."'");
 ?>
  <?php if($newcrop_user_role['newcrop_user_role']=='erxdoctor') { ?>
<?php 
$rid=sqlStatement("SELECT form_id from forms where encounter='".$_SESSION['encounter']."' and formdir='ros' order by form_id desc limit 1 ");
		$rid1=sqlFetchArray($rid);
		$rid2=$rid1['form_id'];
$vid=sqlStatement("SELECT form_id from forms where encounter='".$_SESSION['encounter']."' and formdir='vitals' order by form_id desc limit 1 ");
		$vid1=sqlFetchArray($vid);
		$vid2=$vid1['form_id'];
		$plid=sqlStatement("SELECT form_id from forms where encounter='".$_SESSION['encounter']."' and formdir='dictation' order by form_id desc limit 1 ");
		$plid1=sqlFetchArray($plid);
		$plid2=$plid1['form_id'];
		$nvid=sqlStatement("SELECT id from form_encounter where encounter='".$_SESSION['encounter']."'");

  $nvid1=sqlFetchArray($nvid);

 $nvid2=$nvid1['id'];
		?>
<section>
	<nav>
		<ol class="cd-breadcrumb triangle custom-icons">
		<li><a href="../summary/stats_full.php"><i class="fa fa-note" style="margin-right: 8px;"></i>Medical Issues</a></li>
			<?php if($vid2 == null) { ?>
			<li><a href="../encounter/load_form.php?formname=vitals"><i class="fa fa-note" style="margin-right: 8px;"></i>Vitals</a></li>
			<?php } else { ?>
			<li><a href="../encounter/view_form.php?formname=vitals&id=<?php echo $vid2; ?>"><i class="fa fa-note" style="margin-right: 8px;"></i>Vitals</a></li>
			<?php } if($rid2 == null) { ?>
			<li><a href="../../patient_file/encounter/load_form.php?formname=ros"><i class="fa fa-note" style="margin-right: 8px;"></i>Review of systems</a></li>
						<?php } else { ?>
			<li><a href="../../patient_file/encounter/view_form.php?formname=ros&id=<?php echo $rid2 ?>"><i class="fa fa-note" style="margin-right: 8px;"></i>Review of systems</a></li>
			<?php } ?>
			<li><a href="../encounter/view_form.php?formname=newpatient&id=<?php echo $nvid2; ?>"><i class="fa fa-note" style="margin-right: 8px;"></i>Visit Notes</a></li>
			<li><a href="../encounter/load_form.php?formname=procedure_order"><i class="fa fa-note" style="margin-right: 8px;"></i>Lab Tests</a></li>
			<li><a href="../../../controller.php?prescription&edit&id=&pid=<?php echo $pid ?>"><i class="fa fa-note" style="margin-right: 8px;"></i>Prescription</a></li>
									<li class="current"></i><em>Plan</em></li>
			<li><a href="../../patient_file/transaction/add_transaction.php"><i class="fa fa-note" style="margin-right: 8px;"></i>Referral</a></li>
			<li><a href="../../patient_file/encounter/admit_doctor_form.php"><i class="fa fa-note" style="margin-right: 8px;"></i>Admission</a></li>
			<li><a href="../../patient_file/summary/summary_print.php">Summary</a></li>
		</ol>
	</nav>
</section>
  <?php }?>
<form method=post action="<?php echo $rootdir;?>/forms/dictation/save.php?mode=new" name="my_form">
<span class="title"><?php echo xlt('Plan Details'); ?></span>
<!-- <div style="float:right;">
<input action="action" onclick="history.go(-1);" class="css_button_small" style='height: 24px;border:none' type="button" value="Back" />

 <a href="<?php echo "$rootdir/patient_file/transaction/add_transaction.php";?>" style='height: 24px;border:none' class='css_button_small'
 onclick="top.restoreSession()"><?php echo xlt('Next'); ?></a>
 </div> -->
<br><br>
<span class=text><?php echo xlt('Plan: '); ?></span><br><textarea class="form-control" wrap=virtual name="dictation" ></textarea><br>
<span class=text><?php echo xlt('Additional Notes:'); ?> </span><br><textarea class="form-control"  wrap=virtual name="additional_notes" ></textarea><br><br><br>
 Review After: <!--<input type="text" name="reviewafter"><br><br>-->
 <?php
    echo "<input type='text' size='16' name='form_date_collected' id='form_date_collected'" .
      " value='" . substr($row['date_collected'], 0, 16) . "'" .
      " title='" . xl('Date and time that the sample was collected') . "'" .
      // " onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)'" .
      " />" .
      "<img src='$rootdir/pic/show_calendar.gif' align='absbottom' width='24' height='22'" .
      " id='img_date_collected' border='0' alt='[?]' style='cursor:pointer'" .
      " title='" . xl('Click here to choose a date and time') . "' />";
?><br><br>
<!--<input type='text' size='16' name='form_date_collected' id='form_date_collected'
   title='Date and time that the sample was collected'
      />
      <img src='$rootdir/pic/show_calendar.gif' align='absbottom' width='24' height='22'
      id='img_date_collected' border='0' alt='[?]' style='cursor:pointer'"
       title='Click here to choose a date and time' />-->
<a href="javascript:top.restoreSession();document.my_form.submit();" class="btn btn-primary link_submit"><?php echo xlt('Save'); ?></a>
<a href="<?php echo "$rootdir/patient_file/encounter/$returnurl";?>" class="btn btn-warning"
 onclick="top.restoreSession()"><?php echo xlt('Cancel'); ?></a>
 <script language='JavaScript'>
Calendar.setup({inputField:'form_date_collected', ifFormat:'%Y-%m-%d %H:%M',
 button:'img_date_collected', showsTime:true});
</script>
</form>
<?php
formFooter();
?>
