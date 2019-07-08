<?php
/**
 *
 *
 * @project ecm
 * @author developerck <os.developerck@gmail.com>
 * @copyright @devckworks
 * @version <1.1.1>
 * @since 2014
 */
?>
<?php
$data =$cntrlobj->form['data'];

?>


<div class="panel  panel-warning">

	<div class="panel-body">
<table class="table table-striped">

						<tbody>
								<tr><th>Filelogs</th></tr>
							<tr>
								<td>
								<textarea id="filelogs" style="display:none"><?php echo $data['filelog']?></textarea>
								<well id="filelogs_label">


								</well>
								</td>
							</tr>
								<tr><th>Scripts</th></tr>
							<tr>
								<td>
								<well>
								<?php echo nl2br(  $data['scriptlog']);?>

								</well>
								</td>
							</tr>
								<tr><th>Settings</th></tr>
							<tr>
								<td>
								<well>

								<?php echo nl2br( $data['settings']);?>

								</well>
								</td>
							</tr>
								<tr><th>Developer Comment</th></tr>
							<tr>
								<td>
								<well>

								<?php echo nl2br(  $data['comment']);?>
								</well>
								</td>
							</tr>

						</tbody>
					</table>
	</div>
</div>

<script>
$(document).ready(function(){
	displayFilelogsLabels();
});

function displayFilelogsLabels(){
	var paths = $('#filelogs').val();
	if( paths !=''){

		var patharr = paths.replace( /\n/g, " " ).split( " " )
		var str = '';
		$.each(patharr, function (i, data){
			// TODO: validation for input string
			var labelclass ='label-success';
			if ( data.charAt( 0 ) != '/' ) {
				labelclass ='label-danger';

				}
				str += '<span class="label ' +labelclass +'"  >'+data+'</span> ';
			});
			$('#filelogs_label').html(str);
		}
}

</script>