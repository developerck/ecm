<?php
/**
 *
 *
 * @project 	ecm
 * @author 	    developerck <os.developerck@gmail.com>
 * @copyright 	@devckworks
 * @version 	<1.1.1>
 * @since	    2014
 */

?>
<?php
$ecm = \devlib\TextManager::getText('ECM');
$about = \devlib\TextManager::getText('DEV_SIGNATURE');
?>
<div class="footer" id="footer">
	 <a data-html="true" style="cursor: help" data-container="body"
		data-toggle="popover" data-placement="top"
		data-content="<?php echo $ecm;?>"
		href="javascript:void(0);" title="ECM [Ease Changelog Manager]" > ECM </a> | <a
		href="javascript:void(0);" style="cursor: help"><i class=""
		data-html="true" data-container="body" data-toggle="popover"
		data-placement="top"
		data-content="<?php echo $about;?>"
		title="About Us">devckworks@2014 </i></a>
		 
</div>
<script>
//cehcking if debuggin on
var JS_DEBUG ;
JS_DEBUG = <?php echo $CNF->debug?$CNF->debug:0;?>;

</script>
<!-- Bootstrap core JavaScript==================================================-->
<!-- Placed at the end of the document so the pages load faster -->

<script
	src="<?php echo $CNF->wwwroot.$CNF->uidir;?>/js/plugins/zebraform/javascript/zebra_form.js">
		</script>

<!-- Chart And Time Line -->
<script
	src="<?php echo $CNF->wwwroot.$CNF->uidir;?>/js/plugins/morris/morris.js"></script>
<script
	src="<?php echo $CNF->wwwroot.$CNF->uidir;?>/js/plugins/morris/raphael-2.1.0.min.js"></script>
<script
	src="<?php echo $CNF->wwwroot.$CNF->uidir;?>/js/general.js">

		</script>
<script
	src="<?php echo $CNF->wwwroot.$CNF->uidir;?>/js/global.js">
		</script>
</body>
<!--
     _                _                                          _
     __| _/_______  __ ____ |  | ____  _  _____________|  | __  ______
 / __ |/ __ \  \/ // ___\|  |/ /\ \/ \/ /  _ \_  __ \  |/ / /  ___/
/ /_/ \  ___/\   /\  \___|    <  \     (  <_> )  | \/    <  \___ \
\____ |\___  >\_/  \___  >__|_ \  \/\_/ \____/|__|  |__|_ \/____  >
     \/    \/          \/     \/                         \/     \/

        -->
</html>