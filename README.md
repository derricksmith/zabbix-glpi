# Create GLPI tickets from Zabbix trigger actions

This tool facilitates opening and closing GLPI tickets when a trigger alert is generated in Zabbix.  The tool relies on the GLPI and Zabbix API.

# Configuration

1. Configure settings in the config.php file.
2. Copy zabbix-glpi folder to the alerts scripts location (e.g /etc/zabbix/alert.d/)
3. Create a shell script called glpi_ticekts.sh and save to alerts scripts location 
    ```bash
    #!/bin/bash

    while read -r line; do
        key=$(echo $line | cut -d= -f1)
        value=$(echo $line | cut -d= -f2)
        value=$(echo $value | /usr/bin/php -r 'echo urlencode(fgets(STDIN));')
        parameters="${parameters}$key=$value&" 
    done <<< "$1"

    /usr/bin/php /etc/zabbix/alert.d/zabbix-glpi/zabbix_glpi.php "$parameters" 
    ```
4. Configure a Media Type for GLPI Tickets
    * Script Name: glpi_tickets.sh
    * Script Parameters {ALERT.MESSAGE}
    
5. Configure Media Type for users, optionally add those users to a user group
6. Configure Action
   * Set Default message for Operations, Recovery operations, Acknowledgement operations
      ```
      Operations Default Message
      action=trigger_action
      eventid={EVENT.ID}
      eventvalue={EVENT.VALUE}
      eventage={EVENT.AGE}
      eventdate={EVENT.DATE}
      eventtime={EVENT.TIME}
      eventrecoverydate={EVENT.RECOVERY.DATE}
      eventrecoverytime={EVENT.RECOVERY.TIME}
      eventhost={HOST.HOST}
      eventhostname={HOST.NAME1}
      eventhostdns={HOST.DNS1}
      eventhostip={HOST.IP1}
      eventhostdescription={HOST.DESCRIPTION}
      triggerdescription={TRIGGER.DESCRIPTION}
      triggername={TRIGGER.NAME}
      triggerstatus={TRIGGER.STATUS}
      triggerseverity={TRIGGER.SEVERITY}
      itemid={ITEM.ID1}
      itemdescription={ITEM.DESCRIPTION1}
      itemname={ITEM.NAME1}
      itemkey={ITEM.KEY1}
      itemvalue={ITEM.VALUE1}
   * Configure operations to send to users or groups, set "Send only to" as the media type you created earlier
    
