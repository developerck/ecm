/*
@devckworks
ECM [Ease Changelog Manager]
MySQL - 5.5.27 : Database - ecm
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

/*Table structure for table `ecm_changelog_import` */

DROP TABLE IF EXISTS `ecm_changelog_import`;

CREATE TABLE `ecm_changelog_import` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `project_id` bigint(20) DEFAULT NULL,
  `releserversion_id` bigint(20) DEFAULT NULL,
  `issuename` varchar(100) DEFAULT NULL,
  `filelog` text,
  `scriptlog` text,
  `settings` text,
  `comments` text,
  `islocked` tinyint(1) DEFAULT '0' COMMENT '(0|1)',
  `lockedtime` bigint(20) DEFAULT NULL,
  `isimported` tinyint(1) DEFAULT '0' COMMENT '(0|1)',
  `importtime` bigint(20) DEFAULT NULL,
  `creationtime` bigint(20) DEFAULT NULL,
  `createdby` bigint(20) DEFAULT NULL,
  `updationtime` bigint(20) DEFAULT NULL,
  `updatedby` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_changelog_projectid` (`project_id`),
  KEY `fk_changelog_releaseversionid` (`releserversion_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `ecm_changelog_import` */

/*Table structure for table `ecm_changelogs` */

DROP TABLE IF EXISTS `ecm_changelogs`;

CREATE TABLE `ecm_changelogs` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `project_id` bigint(20) DEFAULT NULL,
  `releaseversion_id` bigint(20) DEFAULT NULL,
  `issueid` varchar(100) DEFAULT NULL,
  `filelog` longtext,
  `scriptlog` longtext,
  `settings` longtext,
  `comment` longtext,
  `islocked` tinyint(1) DEFAULT '0' COMMENT '(0|1)',
  `lockedtime` bigint(20) DEFAULT NULL,
  `isimported` tinyint(1) DEFAULT '0' COMMENT '(0|1)',
  `importtime` bigint(20) DEFAULT NULL,
  `creationtime` bigint(20) DEFAULT NULL,
  `createdby` bigint(20) DEFAULT NULL,
  `updationtime` bigint(20) DEFAULT NULL,
  `updatedby` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_changelog_projectid` (`project_id`),
  KEY `fk_changelog_releaseversionid` (`releaseversion_id`),
  CONSTRAINT `fk_changelog_projectid` FOREIGN KEY (`project_id`) REFERENCES `ecm_projects` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `ecm_changelogs` */

/*Table structure for table `ecm_config` */

DROP TABLE IF EXISTS `ecm_config`;

CREATE TABLE `ecm_config` (
  `propertyname` varchar(50) NOT NULL,
  `value` text,
  PRIMARY KEY (`propertyname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `ecm_config` */

insert  into `ecm_config`(`propertyname`,`value`) values ('version','10101');

/*Table structure for table `ecm_deployed_changelog` */

DROP TABLE IF EXISTS `ecm_deployed_changelog`;

CREATE TABLE `ecm_deployed_changelog` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `changelog_id` bigint(20) DEFAULT NULL,
  `server_id` bigint(20) DEFAULT NULL,
  `deployment_id` bigint(20) DEFAULT NULL,
  `deployed` tinyint(1) DEFAULT '0' COMMENT '(0|1)',
  `deployedtime` bigint(20) DEFAULT NULL,
  `deployedby` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_changelogdeployment_serverid` (`server_id`),
  KEY `fk_changelogdeployment_cahngelogid` (`changelog_id`),
  KEY `fk_changelogdeployment_deploymentid` (`deployment_id`),
  CONSTRAINT `fk_changelogdeployment_cahngelogid` FOREIGN KEY (`changelog_id`) REFERENCES `ecm_changelogs` (`id`),
  CONSTRAINT `fk_changelogdeployment_deploymentid` FOREIGN KEY (`deployment_id`) REFERENCES `ecm_deployment` (`id`),
  CONSTRAINT `fk_changelogdeployment_serverid` FOREIGN KEY (`server_id`) REFERENCES `ecm_servers` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `ecm_deployed_changelog` */

/*Table structure for table `ecm_deployedsteps` */

DROP TABLE IF EXISTS `ecm_deployedsteps`;

CREATE TABLE `ecm_deployedsteps` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `server_id` bigint(20) DEFAULT NULL,
  `deployment_id` bigint(20) DEFAULT NULL,
  `stepname` varchar(255) DEFAULT NULL,
  `stepiinputtype` varchar(255) DEFAULT NULL,
  `stepinputname` varchar(255) DEFAULT NULL,
  `stepinputcomment` text,
  `steprequired` tinyint(1) DEFAULT NULL,
  `stepsequence` int(11) DEFAULT NULL,
  `creationtime` bigint(20) DEFAULT NULL,
  `createdby` bigint(20) DEFAULT NULL,
  `updationtime` bigint(20) DEFAULT NULL,
  `updatedby` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_deployedsteps_serverid` (`server_id`),
  KEY `fk_deployedsteps_deploymentid` (`deployment_id`),
  CONSTRAINT `fk_deployedsteps_deploymentid` FOREIGN KEY (`deployment_id`) REFERENCES `ecm_deployment` (`id`),
  CONSTRAINT `fk_deployedsteps_serverid` FOREIGN KEY (`server_id`) REFERENCES `ecm_servers` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `ecm_deployedsteps` */

/*Table structure for table `ecm_deployment` */

DROP TABLE IF EXISTS `ecm_deployment`;

CREATE TABLE `ecm_deployment` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `server_id` bigint(20) DEFAULT NULL,
  `deploymenttitle` varchar(255) DEFAULT NULL,
  `comment` text,
  `deploymenttime` bigint(20) DEFAULT NULL,
  `deploymentby` bigint(20) DEFAULT NULL,
  `changelogid` text,
  PRIMARY KEY (`id`),
  KEY `fk_deployment_serverid` (`server_id`),
  CONSTRAINT `fk_deployment_serverid` FOREIGN KEY (`server_id`) REFERENCES `ecm_servers` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `ecm_deployment` */

/*Table structure for table `ecm_permissions` */

DROP TABLE IF EXISTS `ecm_permissions`;

CREATE TABLE `ecm_permissions` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `module` varchar(50) NOT NULL,
  `controller` varchar(50) NOT NULL,
  `action` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

/*Data for the table `ecm_permissions` */

insert  into `ecm_permissions`(`id`,`module`,`controller`,`action`,`description`) values (1,'users','user','*','User Related Permission'),(2,'projects','project','*','Project Related Permission'),(3,'projects','releaseversion','*','Release Version Related Permission'),(4,'projects','changelog','*','Changelog Related Permission'),(5,'servers','server','*','Server Related Permision'),(6,'servers','deployment','do','Can Do Deployment'),(7,'servers','deploymentsteps','customizesteps','Can Customize Deployment Steps'),(8,'apps','setting','*','App Wide Settings Permission');

/*Table structure for table `ecm_project_dbdetail` */

DROP TABLE IF EXISTS `ecm_project_dbdetail`;

CREATE TABLE `ecm_project_dbdetail` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `project_id` bigint(20) DEFAULT NULL,
  `dbtype` varchar(255) DEFAULT 'MYSQL' COMMENT 'MYSQL|MSSQL|POSTGRE|ORACLE',
  `dbservername` varchar(255) DEFAULT NULL,
  `dbserverurl` varchar(255) DEFAULT NULL,
  `dbusername` varchar(255) DEFAULT NULL,
  `dbpassword` varchar(255) DEFAULT NULL,
  `dbport` int(5) DEFAULT '3306',
  `dbotherdetail` text,
  `isactive` tinyint(1) DEFAULT '1' COMMENT '(0|1)',
  `creationtime` bigint(20) DEFAULT NULL,
  `createdby` bigint(20) DEFAULT NULL,
  `updationtime` bigint(20) DEFAULT NULL,
  `updatedby` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_ftpdetail_server_id` (`project_id`),
  CONSTRAINT `fk_prejectdbdetail_projectid` FOREIGN KEY (`project_id`) REFERENCES `ecm_projects` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `ecm_project_dbdetail` */

/*Table structure for table `ecm_project_releaseversion` */

DROP TABLE IF EXISTS `ecm_project_releaseversion`;

CREATE TABLE `ecm_project_releaseversion` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `project_id` bigint(20) DEFAULT NULL,
  `rvname` varchar(50) DEFAULT NULL,
  `rcname` varchar(50) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0' COMMENT '(0|1)',
  `islocked` tinyint(1) DEFAULT '0' COMMENT '(0|1)',
  `description` text,
  `creationtime` bigint(20) DEFAULT NULL,
  `createdby` bigint(20) DEFAULT NULL,
  `updationtime` bigint(20) DEFAULT NULL,
  `updatedby` bigint(20) DEFAULT NULL,
  `lockedtime` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `fk_verion_unique` (`project_id`,`rvname`,`rcname`),
  CONSTRAINT `fk_version_project` FOREIGN KEY (`project_id`) REFERENCES `ecm_projects` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `ecm_project_releaseversion` */

/*Table structure for table `ecm_project_scmdetail` */

DROP TABLE IF EXISTS `ecm_project_scmdetail`;

CREATE TABLE `ecm_project_scmdetail` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `project_id` bigint(20) DEFAULT NULL,
  `scmtype` varchar(255) DEFAULT 'SVN' COMMENT 'SVN|CVS|GIT',
  `secmervername` varchar(255) DEFAULT NULL,
  `secmerverurl` varchar(255) DEFAULT NULL,
  `scmusername` varchar(255) DEFAULT NULL,
  `scmpassword` varchar(255) DEFAULT NULL,
  `scmport` int(5) DEFAULT NULL,
  `scmotherdetail` text,
  `isactive` tinyint(1) DEFAULT '1' COMMENT '(0|1)',
  `creationtime` bigint(20) DEFAULT NULL,
  `createdby` bigint(20) DEFAULT NULL,
  `updationtime` bigint(20) DEFAULT NULL,
  `updatedby` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_ftpdetail_server_id` (`project_id`),
  CONSTRAINT `fk_projectscmdetail_projectid` FOREIGN KEY (`project_id`) REFERENCES `ecm_projects` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `ecm_project_scmdetail` */

/*Table structure for table `ecm_project_user` */

DROP TABLE IF EXISTS `ecm_project_user`;

CREATE TABLE `ecm_project_user` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `project_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `assignedtime` bigint(20) DEFAULT NULL,
  `assignedby` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_projectuser_project` (`project_id`),
  KEY `fk_projectuser_user` (`user_id`),
  CONSTRAINT `fk_projectuser_project` FOREIGN KEY (`project_id`) REFERENCES `ecm_projects` (`id`),
  CONSTRAINT `fk_projectuser_user` FOREIGN KEY (`user_id`) REFERENCES `ecm_users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `ecm_project_user` */

/*Table structure for table `ecm_projects` */

DROP TABLE IF EXISTS `ecm_projects`;

CREATE TABLE `ecm_projects` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  `isactive` tinyint(4) DEFAULT '1',
  `creationtime` bigint(20) DEFAULT NULL,
  `createdby` bigint(20) DEFAULT NULL,
  `updationtime` bigint(20) DEFAULT NULL,
  `updatedby` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `ecm_projects` */

/*Table structure for table `ecm_role_perm` */

DROP TABLE IF EXISTS `ecm_role_perm`;

CREATE TABLE `ecm_role_perm` (
  `roles_id` bigint(20) NOT NULL,
  `permissions_id` bigint(20) NOT NULL,
  KEY `fk_role_id` (`roles_id`),
  KEY `fk_permissions_id` (`permissions_id`),
  CONSTRAINT `fk_permissions_id` FOREIGN KEY (`permissions_id`) REFERENCES `ecm_permissions` (`id`),
  CONSTRAINT `fk_role_id` FOREIGN KEY (`roles_id`) REFERENCES `ecm_roles` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `ecm_role_perm` */

insert  into `ecm_role_perm`(`roles_id`,`permissions_id`) values (1,1),(1,2),(1,3),(1,4),(1,5),(1,6),(1,7),(1,8),(2,2),(2,3),(2,4),(2,5),(2,6),(2,7),(3,4),(3,6);

/*Table structure for table `ecm_roles` */

DROP TABLE IF EXISTS `ecm_roles`;

CREATE TABLE `ecm_roles` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `rolename` varchar(50) NOT NULL,
  `shortname` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `role_shortname` (`shortname`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `ecm_roles` */

insert  into `ecm_roles`(`id`,`rolename`,`shortname`) values (1,'ADMIN','ADMIN'),(2,'PROJECT MANAGER','PM'),(3,'DEVELOPER','DEV');

/*Table structure for table `ecm_server_dbdetail` */

DROP TABLE IF EXISTS `ecm_server_dbdetail`;

CREATE TABLE `ecm_server_dbdetail` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `server_id` bigint(20) DEFAULT NULL,
  `dbtype` varchar(255) DEFAULT 'MYSQL' COMMENT 'MYSQL|MSSQL|POSTGRE|ORACLE',
  `dbservername` varchar(255) DEFAULT NULL,
  `dbserverurl` varchar(255) DEFAULT NULL,
  `dbusername` varchar(255) DEFAULT NULL,
  `dbpassword` varchar(255) DEFAULT NULL,
  `dbport` int(5) DEFAULT '3306',
  `dbotherdetail` text,
  `isactive` tinyint(1) DEFAULT '1' COMMENT '(0|1)',
  `creationtime` bigint(20) DEFAULT NULL,
  `createdby` bigint(20) DEFAULT NULL,
  `updationtime` bigint(20) DEFAULT NULL,
  `updatedby` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_ftpdetail_server_id` (`server_id`),
  CONSTRAINT `fk_dbdetail_serverid` FOREIGN KEY (`server_id`) REFERENCES `ecm_servers` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `ecm_server_dbdetail` */

/*Table structure for table `ecm_server_deploymentsteps` */

DROP TABLE IF EXISTS `ecm_server_deploymentsteps`;

CREATE TABLE `ecm_server_deploymentsteps` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `server_id` bigint(20) DEFAULT NULL,
  `stepid` varchar(50) DEFAULT NULL,
  `steplabel` varchar(255) DEFAULT NULL,
  `stepinputtype` varchar(50) DEFAULT 'CHECKBOX' COMMENT 'TEXT|CHECKBOX',
  `stepinputname` varchar(255) DEFAULT NULL,
  `stepcomment` text,
  `steprequired` tinyint(1) DEFAULT '0' COMMENT '(0|1) for optional|required',
  `stepsequence` int(11) DEFAULT NULL,
  `creationtime` bigint(20) DEFAULT NULL,
  `createdby` bigint(20) DEFAULT NULL,
  `updationtime` bigint(20) DEFAULT NULL,
  `updatedby` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_deploymentsteps_serverid` (`server_id`),
  CONSTRAINT `fk_deploymentsteps_serverid` FOREIGN KEY (`server_id`) REFERENCES `ecm_servers` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

/*Data for the table `ecm_server_deploymentsteps` */

/*Table structure for table `ecm_server_ftpdetail` */

DROP TABLE IF EXISTS `ecm_server_ftpdetail`;

CREATE TABLE `ecm_server_ftpdetail` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `server_id` bigint(20) DEFAULT NULL,
  `ftpservername` varchar(255) DEFAULT NULL,
  `ftpserverurl` varchar(255) DEFAULT NULL,
  `ftpusername` varchar(255) DEFAULT NULL,
  `ftppassword` varchar(255) DEFAULT NULL,
  `ftptype` varchar(255) DEFAULT 'FTP' COMMENT 'FTP|SFTP',
  `ftpport` int(5) DEFAULT '21',
  `ftpotherdetail` text,
  `isactive` tinyint(1) DEFAULT '1' COMMENT '(0|1)',
  `creationtime` bigint(20) DEFAULT NULL,
  `createdby` bigint(20) DEFAULT NULL,
  `updationtime` bigint(20) DEFAULT NULL,
  `updatedby` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_ftpdetail_server_id` (`server_id`),
  CONSTRAINT `fk_ftpdetail_server_id` FOREIGN KEY (`server_id`) REFERENCES `ecm_servers` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `ecm_server_ftpdetail` */

/*Table structure for table `ecm_server_user` */

DROP TABLE IF EXISTS `ecm_server_user`;

CREATE TABLE `ecm_server_user` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `server_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `assignedtime` bigint(20) DEFAULT NULL,
  `assignedby` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_projectuser_project` (`server_id`),
  KEY `fk_projectuser_user` (`user_id`),
  CONSTRAINT `fk_serveruser_serverid` FOREIGN KEY (`server_id`) REFERENCES `ecm_servers` (`id`),
  CONSTRAINT `fk_serveruser_userid` FOREIGN KEY (`user_id`) REFERENCES `ecm_users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Data for the table `ecm_server_user` */

/*Table structure for table `ecm_servers` */

DROP TABLE IF EXISTS `ecm_servers`;

CREATE TABLE `ecm_servers` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `description` text,
  `project_id` bigint(20) DEFAULT NULL,
  `creationtime` bigint(20) DEFAULT NULL,
  `createdby` bigint(20) DEFAULT NULL,
  `updationtime` bigint(20) DEFAULT NULL,
  `updatedby` bigint(20) DEFAULT NULL,
  `isactive` tinyint(1) DEFAULT '1' COMMENT '(0|1)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `servername` (`name`,`project_id`),
  KEY `fk_server_projectid` (`project_id`),
  CONSTRAINT `fk_server_projectid` FOREIGN KEY (`project_id`) REFERENCES `ecm_projects` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `ecm_servers` */

/*Table structure for table `ecm_user_role` */

DROP TABLE IF EXISTS `ecm_user_role`;

CREATE TABLE `ecm_user_role` (
  `user_id` bigint(20) NOT NULL,
  `role_id` bigint(20) NOT NULL,
  UNIQUE KEY `fk_user_id` (`user_id`),
  KEY `fk_userrole_id` (`role_id`),
  CONSTRAINT `fk_role_id_userrole` FOREIGN KEY (`role_id`) REFERENCES `ecm_roles` (`id`),
  CONSTRAINT `fk_user_id_userrole` FOREIGN KEY (`user_id`) REFERENCES `ecm_users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


/*Table structure for table `ecm_users` */

DROP TABLE IF EXISTS `ecm_users`;

CREATE TABLE `ecm_users` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `password` varchar(128) NOT NULL,
  `passwordsalt` varchar(5) NOT NULL,
  `emailid` varchar(100) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `displayname` varchar(30) DEFAULT NULL,
  `mobileno` varchar(20) DEFAULT NULL,
  `signature` text,
  `lastpwdreset` bigint(20) DEFAULT NULL,
  `isactive` tinyint(1) DEFAULT '1' COMMENT '(0|1)',
  `isdeleted` tinyint(1) DEFAULT '0' COMMENT '(0|1)',
  `lastlogin` bigint(20) NOT NULL,
  `timezone` varchar(20) DEFAULT NULL,
  `creationtime` bigint(20) DEFAULT NULL,
  `updationtime` bigint(20) DEFAULT NULL,
  `createdby` bigint(20) DEFAULT NULL,
  `updatedby` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_user` (`emailid`),
  KEY `self_user_fk` (`createdby`),
  CONSTRAINT `self_user_fk` FOREIGN KEY (`createdby`) REFERENCES `ecm_users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;


ALTER TABLE `ecm_changelogs` ADD COLUMN `labelname` VARCHAR(255) NULL AFTER `updatedby`; 
ALTER TABLE `ecm_changelog_import` ADD COLUMN `labelname` VARCHAR(255) NULL AFTER `updatedby`; 
ALTER TABLE `ecm_deployedsteps` DROP COLUMN `updationtime`, DROP COLUMN `updatedby`, ADD COLUMN `stepinputvalue` TEXT NULL AFTER `stepsequence`;
ALTER TABLE `ecm_deployment` ADD COLUMN `project_id` BIGINT NOT NULL AFTER `id`, CHANGE `server_id` `server_id` BIGINT(20) NOT NULL;
ALTER TABLE `ecm_deployed_changelog` CHANGE `changelog_id` `changelog_id` BIGINT(20) NOT NULL, CHANGE `server_id` `server_id` BIGINT(20) NOT NULL, ADD COLUMN `project_id` BIGINT NOT NULL AFTER `server_id`, CHANGE `deployment_id` `deployment_id` BIGINT(20) NOT NULL;
ALTER TABLE `ecm_deployedsteps` ADD COLUMN `stepid` VARCHAR(50) NULL AFTER `stepname`;
ALTER TABLE `ecm_deployedsteps` CHANGE `stepname` `steplabel` VARCHAR(255) CHARSET utf8 COLLATE utf8_general_ci NULL, CHANGE `stepinputcomment` `stepcomment` TEXT CHARSET utf8 COLLATE utf8_general_ci NULL;
ALTER TABLE `ecm_deployedsteps` CHANGE `stepiinputtype` `stepinputtype` VARCHAR(255) CHARSET utf8 COLLATE utf8_general_ci NULL; 
/*Data for the table `ecm_users` */


/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
