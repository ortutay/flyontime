<?php $this->pageTitle = 'FlyOnTime.us'; ?>

<script type="text/javascript">
	var _date = new Date();
	var in_js = _date.getTime();
	
	var url = '/m/lines/security/wait/' + '<?php echo $Airport; ?>' + '?in_js=' + in_js;
	window.location = url;
</script>
