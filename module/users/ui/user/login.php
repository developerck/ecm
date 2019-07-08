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
$cokkie_email = isset($_COOKIE['ecm'])?generalDecrypt($_COOKIE['ecm']):'';

?>
   <div class="container">
        <div class="row">

            <div class="col-md-4 col-md-offset-4" id='loginform'>
            <?php if($error_msg){ ?>
            <div class="alert alert-danger"><?php echo $error_msg;?></div>
            <?php } ?>
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Sign In Into ECM</h3>
                    </div>
                    <div class="panel-body">
                        <form role="form" action="" method="post" onsubmit="return checkForm();">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" id="emailid" placeholder="Email-id" name="emailid" type="text" maxlength="50" autofocus " value="<?php
echo $cokkie_email; ?>">
                                </div>
                                <div class="form-group">
                                    <input class="form-control" id="password" placeholder="Password" name="password" type="password" value="" maxlength="20">
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input name="remember" type="checkbox" value="1" <?php if(isset($_COOKIE['ecm'])) {
		echo 'checked="checked"';
	}
	else {
		echo '';
	}
	?> >Remember Me
                                    </label>
                                </div>
                                <!-- Change this to a button or input when using this as a form -->
                                <input type="submit" class="btn btn-lg btn-success btn-block" style="background: #2A6496; " name="submit" value="Sign In"/>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    function checkForm(){
		var emailid= $('#emailid').val();
		var pwd = $('#password').val();
		if($.trim(emailid) =='' || password == ''){
			if(!$('#jserror').length){
			$('#loginform').prepend('<div id="jserror" class="alert alert-danger">Emailid and Password is required!</div>');
			}else{
				$('#jserror').html('Emailid and Password is required!');	
			}
			if($.trim(emailid) ==''){
				 $('#emailid').focus();
				}
			else if(pwd ==''){
				 $('#password').focus();
				}
			return false;
		}else{
			return true;
		}
    }

    </script>