<?php
$config = array(
## Required ##
'glpi_user'					      => '',
'glpi_password'			      => '',
'glpi_apikey'				      => '',
'glpi_host'					      => '',
'zabbix_user'				      => '',
'zabbix_password'			    => '',
'zabbix_host'				      => '',
'verifypeer'				      => false, // SETS curl_setopt ($curl, CURLOPT_SSL_VERIFYPEER, FALSE)
'logging_level'				    => 1,  //0=none,1=errors only,2=errors and warnings,3=debug
'critical_priority'		    => 5,
'warning_priority'		    => 3,

## Optional ##
'glpi_requester_user_id'	=> '',
'glpi_requester_group_id'	=> '',
'glpi_watcher_user_id'		=> '',
'glpi_watcher_group_id'		=> '',
'glpi_assign_user_id'		  => '',
'glpi_assign_group_id'		=> '',

## End Variables ##
);
?>
