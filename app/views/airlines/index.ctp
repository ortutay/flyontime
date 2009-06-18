<?php $this->pageTitle = 'FlyOnTime.us: Airlines'; ?>

<div class="header">
	Airlines
</div>

<br />

<div>

<?php
foreach($Airlines as $airline)
{
?>

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="/airlines/<?php echo $airline['Enum']['code']; ?>"><?php echo $airline['Enum']['description']; ?></a>
<br /><br />

<?php
}
?>

</div>

