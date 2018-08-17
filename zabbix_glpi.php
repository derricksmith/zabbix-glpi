<?php
// load ZabbixApi
ini_set('register_argc_argv', TRUE);
require_once 'lib/simple_template_class.php';
require_once 'lib/glpi_api.php';
require_once 'lib/ZabbixApi.class.php';
use ZabbixApi\ZabbixApi;

//require_once 'lib/ZabbixApi.class.php';
//use ZabbixApi\ZabbixApi;

class zabbix_glpi {
	private $glpi_user;
	private $glpi_password;
	private $glpi_apikey;
	private $glpi_host;
	private $zabbix_user;
	private $zabbix_password;
	private $zabbix_host;
	private $verifypeer;
	private $logging_level;
	private $critical_priority;
	private $warning_priority;
	private $glpi_requester_user_id;
	private $glpi_requester_group_id;
	private $glpi_watcher_user_id;
	private $glpi_watcher_group_id;
	private $glpi_assign_user_id;
	private $glpi_assign_group_id;
	
	function __construct($config, $params){
		
		extract($config);
		
		$this->glpi_user = $glpi_user;
		$this->glpi_password = $glpi_password;
		$this->glpi_apikey = $glpi_apikey;
		$this->glpi_host = $glpi_host;
		$this->zabbix_user = $zabbix_user;
		$this->zabbix_password = $zabbix_password;
		$this->zabbix_host = $zabbix_host;
		$this->verifypeer = $verifypeer;
		$this->logging_level = $logging_level;
		$this->critical_priority = $critical_priority;
		$this->warning_priority = $warning_priority;
		$this->glpi_requester_user_id = $glpi_requester_user_id;
		$this->glpi_requester_group_id = $glpi_requester_group_id;
		$this->glpi_watcher_user_id = $glpi_watcher_user_id;
		$this->glpi_watcher_group_id = $glpi_watcher_group_id;
		$this->glpi_assign_user_id = $glpi_assign_user_id;
		$this->glpi_assign_group_id = $glpi_assign_group_id;
		
		$this->logging("Zabbix Tickets: Calling Script", "INFO");
		
		$this->logging("Zabbix Tickets: glpi_user = ".$this->glpi_user, "INFO");
		$this->logging("Zabbix Tickets: glpi_password = ".$this->glpi_password, "INFO");
		$this->logging("Zabbix Tickets: glpi_apikey = ".$this->glpi_apikey, "INFO");
		$this->logging("Zabbix Tickets: glpi_host = ".$this->glpi_host, "INFO");
		$this->logging("Zabbix Tickets: zabbix_user = ".$this->zabbix_user, "INFO");
		$this->logging("Zabbix Tickets: zabbix_password = ".$this->zabbix_password, "INFO");
		$this->logging("Zabbix Tickets: zabbix_host = ".$this->zabbix_host, "INFO");
		$this->logging("Zabbix Tickets: verifypeer = ".var_export($this->verifypeer, true), "INFO");
		$this->logging("Zabbix Tickets: logging_level = ".$this->logging_level, "INFO");
		$this->logging("Zabbix Tickets: critical_priority = ".$this->critical_priority, "INFO");
		$this->logging("Zabbix Tickets: warning_priority = ".$this->warning_priority, "INFO");
		$this->logging("Zabbix Tickets: glpi_requester_user_id = ".$this->glpi_requester_user_id, "INFO");
		$this->logging("Zabbix Tickets: glpi_requester_group_id = ".$this->glpi_requester_group_id, "INFO");
		$this->logging("Zabbix Tickets: glpi_watcher_user_id = ".$this->glpi_watcher_user_id, "INFO");
		$this->logging("Zabbix Tickets: glpi_watcher_group_id = ".$this->glpi_watcher_group_id, "INFO");
		$this->logging("Zabbix Tickets: glpi_assign_user_id = ".$this->glpi_assign_user_id, "INFO");
		$this->logging("Zabbix Tickets: glpi_assign_group_id = ".$this->glpi_assign_group_id, "INFO");
		
		
		if (!extension_loaded("curl")) {
			$this->logging("Zabbix Tickets: Extension curl not loaded", "ERROR");
			die("Extension curl not loaded");
		}
		
		if (empty($params)){
			$this->logging("Zabbix Tickets: Parameters are empty", "ERROR");
			die("Parameters are empty");
		}
		
		
		$this->logging("Zabbix Tickets: Calling GLPI API", "INFO");
		$glpi = new GLPI_API(array('username' => $this->glpi_user, 'password' => $this->glpi_password, 'apikey' => $this->glpi_apikey, 'host' => $this->glpi_host, 'verifypeer' => $this->verifypeer));
			
		if (isset($glpi->last_result->session_token)){
			$this->logging("Zabbix Tickets: GLPI Session Token = ".$glpi->last_result->session_token, "INFO");
		} else if(isset($glpi->last_result[0]) && isset($glpi->last_result[1]))  {
			$this->logging("Zabbix Tickets: GLPI API Error = ".$glpi->last_result[0]. " (".$glpi->last_result[0].")", "ERROR");
		} else {
			$this->logging("Zabbix Tickets: GLPI API Unknown Error", "ERROR");
		}
		
		
		
		//array_shift($params);
		//foreach ($params as $param){
			//parse_str($params, $parameters);
			$variables = array();
			foreach ($params as $key => $val){
				switch($key){
					case "action":
						$action=trim(urldecode($val));
						$variables[$key]=$action;
						break;
					case "eventid":
						$eventid=trim(urldecode($val));
						$variables[$key]=$eventid;
						break;
					case "eventvalue":
						$eventvalue=trim(urldecode($val));
						$variables[$key]=$eventvalue;
						break;
					case "eventage":
						$eventage=trim(urldecode($val));
						$variables[$key]=$eventage;
						break;
					case "eventdate":
						$eventdate=trim(urldecode($val));
						$variables[$key]=$eventdate;
						break;
					case "eventtime":
						$eventtime=trim(urldecode($val));
						$variables[$key]=$eventtime;
						break;
					case "eventrecoverydate":
						$eventrecoverydate=trim(urldecode($val));
						$variables[$key]=$eventrecoverydate;
						break;
					case "eventrecoverytime":
						$eventrecoverytime=trim(urldecode($val));
						$variables[$key]=$eventrecoverytime;
						break;
					case "eventhost":
						$eventhost=trim(urldecode($val));
						$variables[$key]=$eventhost;
						break;
					case "eventhostname":
						$eventhostname=trim(urldecode($val));
						$variables[$key]=$eventhostname;
						break;
					case "eventhostip":
						$eventhostip=trim(urldecode($val));
						$variables[$key]=$eventhostip;
						break;
					case "eventhostdns":
						$eventhostdns=trim(urldecode($val));
						$variables[$key]=$eventhostdns;
						break;
					case "eventhostdescription":
						$eventhostdescription=trim(urldecode($val));
						$variables[$key]=$eventhostdescription;
						break;
					case "triggerdescription":
						$triggerdescription=trim(urldecode($val));
						$variables[$key]=$triggerdescription;
						break;
					case "triggername":
						$triggername=trim(urldecode($val));
						$variables[$key]=$triggername;
						break;
					case "triggerstatus":
						$triggerstatus=trim(urldecode($val));
						$variables[$key]=$triggerstatus;
						break;
					case "triggerseverity":
						$triggerseverity=trim(urldecode($val));
						$variables[$key]=$triggerseverity;
						break;
					case "itemid":
						$itemid=trim(urldecode($val));
						$variables[$key]=$itemid;
						break;
					case "itemdescription":
						$itemdescription=trim(urldecode($val));
						$variables[$key]=$itemdescription;
						break;
					case "itemname":
						$itemname=trim(urldecode($val));
						$variables[$key]=$itemname;
						break;
					case "itemkey":
						$itemkey=trim(urldecode($val));
						$variables[$key]=$itemkey;
						break;
					case "itemvalue":
						$itemvalue=trim(urldecode($val));
						$variables[$key]=$itemvalue;
						break;
				}
			}
		//}
		if (isset($action) && !empty($action)){
			$this->logging("Zabbix Tickets: Action = ".$action, "INFO");
		} else {
			$this->logging("Zabbix Tickets: Missing Action", "ERROR");
			die("Zabbix Tickets: Missing Action");
		}
		
		switch($action){
			case "trigger_action":
				$this->logging("Zabbix Tickets: Calling Action 'trigger_action'", "INFO");
				if (isset($eventid) && !empty($eventid)){
					$this->logging("Zabbix Tickets: Event ID = ".$eventid, "INFO");
				} else {
					$this->logging("Zabbix Tickets: Missing Event ID {EVENT.ID}", "ERROR");
					die("Zabbix Tickets: Missing Event ID {EVENT.ID}");
				}
				
				if (isset($eventvalue)){
					$this->logging("Zabbix Tickets: Event Value = ".$eventvalue, "INFO");
				} else {
					$this->logging("Zabbix Tickets: Missing Event Value {EVENT.VALUE} ", "ERROR");
					die("Zabbix Tickets: Missing Event Value {EVENT.VALUE} ");
				}
				
				if (empty($eventhostdns)){
					if (isset($eventhostip) && !empty($eventhostip)){
						$this->logging("Zabbix Tickets: Event Host IP = ".$eventhostip, "INFO");
					} else {
						$this->logging("Zabbix Tickets: Missing Event Host IP {HOST.IP1}", "ERROR");
						die("Zabbix Tickets: Missing Event ID {HOST.IP1}");
					}
				} else {
					$this->logging("Zabbix Tickets: EventHost DNS = ".$eventhostdns, "INFO");
				}
				
				if (isset($triggername) && !empty($triggername)){
					$this->logging("Zabbix Tickets: Trigger Name = ".$triggername, "INFO");
				} else {
					$this->logging("Zabbix Tickets: Missing Trigger Name {TRIGGER.NAME} ", "ERROR");
					die("Zabbix Tickets: Missing Trigger Name {TRIGGER.NAME} ");
				}
				
				$tpl = new Template( __DIR__ .'/templates' );
				
				switch($eventvalue){
					case "1": //problem
						ob_start();
						echo $tpl->render( 'operation', $variables);
						$message = ob_get_clean();
						$this->logging("Zabbix Tickets: message = ".$message, "INFO");
					
						$this->logging("Zabbix Tickets: Event is problem, creating new ticket in GLPI", "INFO");
						$ticket = array(
							'input' => array(
								'name' => "Zabbix#$eventid Problem on $eventhost: $triggername",
								'content' => $message,
								'priority' => $this->critical_priority,
								'_users_id_requester' => $this->glpi_requester_user_id,
								'_groups_id_requester' => $this->glpi_requester_group_id,
								'_users_id_observer' => $this->glpi_watcher_user_id,
								'_groups_id_observer' => $this->glpi_watcher_group_id,
								'_users_id_assign' => $this->glpi_assign_user_id,
								'_groups_id_assign' => $this->glpi_assign_user_id
							)
						);
						$glpi->addItem('Ticket', $ticket);
						break;
					case "0": //recovering
						ob_start();
						echo $tpl->render( 'recovery', $variables);
						$message = ob_get_clean();
						$this->logging("Zabbix Tickets: message = ".$message, "INFO");
						
						$this->logging("Zabbix Tickets: Event is recovering, updating GLPI tickets", "INFO");
						$search = array(
							'criteria' => array(
								array(
									'field' => '12', //Status field
									'searchtype' => 'equals',
									'value' => 1 //Search on Open Tickets
								),

								array(
									'link' => 'AND',
									'field' => '1', //Title field
									'searchtype' => 'contains',
									'value' => "Zabbix#$eventid" //Search Title
								)
							)
						);
						$tickets = $glpi->search('Ticket', $search);
						
						if (!empty($tickets['data']->data)){
							
							$this->logging("Zabbix Tickets: Found open tickets, closing each ticket", "INFO");
							$post = array('input' => array());
							foreach ($tickets['data']->data as $ticket) {
								$this->logging("Zabbix Tickets: Closing GLPI ticket id ".$ticket->{2}, "INFO");
								$post['input'][] = array('id' => $ticket->{2} , 'status' => 6);
								$glpi->updateItem('Ticket', $post);
							}
						} else {
							$this->logging("Zabbix Tickets: Could not find open tickets in GLPI.  They may have been closed manually.", "WARNING");
						}
						
						$search = array(
							'criteria' => array(
								array(
									'field' => '12', //Status field
									'searchtype' => 'equals',
									'value' => 2 //Search on Open Tickets
								),

								array(
									'link' => 'AND',
									'field' => '1', //Title field
									'searchtype' => 'contains',
									'value' => "Zabbix#$eventid" //Search Title
								)
							)
						);
						$tickets = $glpi->search('Ticket', $search);
						
						if (!empty($tickets['data']->data)){
							
							$this->logging("Zabbix Tickets: Found pending(assigned) tickets, closing each ticket", "INFO");
							$post = array('input' => array());
							foreach ($tickets['data']->data as $ticket) {
								$this->logging("Zabbix Tickets: Closing GLPI ticket id ".$ticket->{2}, "INFO");
								$post['input'][] = array('id' => $ticket->{2} , 'status' => 6);
								$glpi->updateItem('Ticket', $post);
							}
						} else {
							$this->logging("Zabbix Tickets: Could not find pending(assigned) tickets in GLPI.  They may have been closed manually.", "WARNING");
						}
						
						$search = array(
							'criteria' => array(
								array(
									'field' => '12', //Status field
									'searchtype' => 'equals',
									'value' => 3 //Search on Open Tickets
								),

								array(
									'link' => 'AND',
									'field' => '1', //Title field
									'searchtype' => 'contains',
									'value' => "Zabbix#$eventid" //Search Title
								)
							)
						);
						$tickets = $glpi->search('Ticket', $search);
						
						if (!empty($tickets['data']->data)){
							
							$this->logging("Zabbix Tickets: Found pending(planned) tickets, closing each ticket", "INFO");
							$post = array('input' => array());
							foreach ($tickets['data']->data as $ticket) {
								$this->logging("Zabbix Tickets: Closing GLPI ticket id ".$ticket->{2}, "INFO");
								$post['input'][] = array('id' => $ticket->{2} , 'status' => 6);
								$glpi->updateItem('Ticket', $post);
							}
						} else {
							$this->logging("Zabbix Tickets: Could not find pending(planned) tickets in GLPI.  They may have been closed manually.", "WARNING");
						}
						
						break;
					default:
						$this->logging("Zabbix Tickets: Event value is not 0 or 1, exiting", "WARNING");
						break;
				}
				
				break;
			case "update_zabbix_ack":
				$this->logging("Zabbix Tickets: Calling Action 'update_zabbix_ack'", "INFO");
				try
				{
					$api = new ZabbixApi($this->zabbix_host, $this->zabbix_user, $this->zabbix_password);
					$problems = $api->problemGet(array("output" => "extend", "selectAcknowledges" => "extend"));
					if (count($problems) != 0){
						foreach ($problems as $problem){
							$this->logging("Zabbix Tickets: Found Problem ".$problem["eventid"], "INFO");
							if (empty($problem['acknowledges'])){
								$search = array(
									'criteria' => array(
										array(
											'field' => '12', //Status field
											'searchtype' => 'equals',
											'value' => 3 //Search on Pending(Assigned)
										),

										array(
											'link' => 'AND',
											'field' => '1', //Title field
											'searchtype' => 'contains',
											'value' => "Zabbix#".$problem["eventid"] //Search Title
										)
									)
								);
								$tickets = $glpi->search('Ticket', $search);
										
								if (!empty($tickets['data']->data)){		
									$this->logging("Zabbix Tickets: Found pending(assigned) tickets, acknowledging problem in Zabbix", "INFO");
									$post = array('input' => array());
									foreach ($tickets['data']->data as $ticket) {
										$this->logging("Zabbix Tickets: Acknowledging Zabbix problem ".$problem["eventid"], "INFO");
										
										$p = array(
											"eventids" 	=> $problem["eventid"],
											"message"	=> "Ticket ".$ticket->{2}." assigned in GLPI.  Visit ".$this->glpi_host."/front/ticket.form.php?id=".$ticket->{2}." for more info."
										);
										$acknowledged = $api->eventAcknowledge($p);
									}
								} else {
									$this->logging("Zabbix Tickets: Could not find pending(assigned) tickets in GLPI.  Nothing to update in Zabbix", "INFO");
								}
							} else {
								$this->logging("Zabbix Tickets: Problem ".$problem["eventid"]." has already been acknowledged", "INFO");
							}
						}
					} else {
						$this->logging("Zabbix Tickets: No Zabbix problems found, exiting", "INFO");
					}
				}
				catch(Exception $e)
				{
					$this->logging("Zabbix Tickets: Error connecting to Zabbix API. ".$e->getMessage() , "ERROR");
				}
				break;
			default:
				$this->logging("Zabbix Tickets: Action is not correct", "INFO");
				break;
		}
	}	
	
	function logging($msg, $log){
		if ($log == "ERROR" && ($this->logging_level == 1 || $this->logging_level == 2 || $this->logging_level == 3)){
			syslog(LOG_INFO, "ERROR -- $msg");
		}
		if ($log == "WARNING" && ($this->logging_level == 2 || $this->logging_level == 3)){
			syslog(LOG_INFO, "WARNING -- $msg");
		}
		if ($log == "INFO" && $this->logging_level == 3){
			syslog(LOG_INFO, "INFO -- $msg");
		}
	}
}

if(file_exists(__DIR__.'/config.php')){
	require_once __DIR__.'/config.php';
}

$params = array();

if (isset($_GET) && !empty($_GET)){
	$params = $_GET;
} elseif(isset($argv[1])) {
	parse_str($argv[1], $params);
}

$zabbix_glpi = new zabbix_glpi($config, $params);