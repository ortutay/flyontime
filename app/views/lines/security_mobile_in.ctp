<?php $this->pageTitle = 'FlyOnTime.us'; ?>

<?php if(isset($Inline) && $Inline == true) { ?>

<script type="text/javascript">
	var _date = new Date();
	var in_js = _date.getTime();
	
	var url = '/m/lines/security/wait/' + '<?php echo $Airport; ?>' + '?in_js=' + in_js;
	window.location = url;
</script>

<?php } else { ?>

<div class="header">Airport Security</div>

<hr />

<div class="subheader" style="margin: 5px;">I'm sorry, you cannot create a security line entry at this time.  Please try again later.</div>

<hr />

<br />

<div>

<a href="/m/lines/security">Return to Search</a>

</div>

<?php } ?>

