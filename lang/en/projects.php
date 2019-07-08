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
 $text = array();
 $text['help_rv'] = '<h4>Release Version:-</h4><p>Release Version Name, as 1.1.1.1 or any sort of unique thing to identify release changelog</p><h4>RC:-</h4><p>If Release Version same then RC should be different. as RC1, RC2 etc.</p>';
 $text['help_scmdetail'] = 'This is SCM Detail. currently we are supporting only SVN';
 $text['help_dbdetail'] = 'This is DB Detail. currently we are supporting only MYSQL';
 $text['help_issueid']= 'Please Enter Issue ID.';
 $text['help_filelog']= '<h4>File Path Rule</h4><ul>
 							<li> Every File Path should be start wtih a /</li>
 							<li> There should not be any space in  single filepath</li>
 							<li> Multiple file path should be separated by ,</li>
 							<li> If a file path has / at last it means all the files for that directory.</li>
 							<li> Valid File Path Example
 								<br/>
 								/var/www/html/pro/a.php
 								<br/>/var/www/html/pro/a.php, /var/www/html/pro/b.php
 								<br/>/var/www/html/pro/

 							</li>
 						</ul>';

 $text['help_scriptlog'] ='Database related script.';
 $text['help_settings'] ='Any customization other than file changes and script.';
 $text['help_changelogcomment'] ='Developer Comments.';
 $text['help_labelname'] ='it will be useful for searching, and can be treated as component.';
 $text['help_assign_project'] ='Only Active Projects!';
 $text['help_assign_user'] ='Only Active Users!';
 $text['help_projectlist'] = 'Order By Based on Active!';
 $text['help_rvlist'] = 'Order By Based Creation Time!';
 $text['help_activeproject'] = 'Only Active Projects!';
 $text['help_notlockedrv'] = 'Only Unlocked Release Version!';