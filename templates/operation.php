<p><b>Trigger:</b> <?php echo $triggername; ?><br>
<b>Trigger status:</b> <b><span style="color:red"><?php echo $triggerstatus; ?></span></b><br>
<b>Trigger severity:</b> <?php echo $triggerseverity; ?><br></p>
<p><b>Host: </b><?php echo $eventhostname; ?> (<?php echo $eventhostdns; ?>, <?php echo $eventhostip; ?>)<br>
<b>Host description: </b><?php echo $eventhostdescription; ?></p>
<p><b>Trigger Description: </b><?php echo $triggerdescription; ?></p>
<p><b>Item Description: </b><?php echo $itemdescription; ?></p>
<p><b>Item values:</b><br>
Name: <i><?php echo $itemname; ?></i><br>
Key: <i><?php echo $itemkey; ?></i><br>
Value: <i><?php echo $itemvalue; ?></i><br></p>
<p>Some info about this server:</p>
Item graph: <a href="https://monitor.denvest.com/zabbix/history.php?action=showgraph&itemids[]=<?php echo $itemid; ?>">Graph Link</a><br>
Item history: <a href="https://monitor.denvest.com/zabbix/history.php?action=showvalues&itemids[]=<?php echo $itemid; ?>">History Link</a><br>
Graph direct image: <img src="https://monitor.denvest.com/zabbix/chart.php?itemids[]=<?php echo $itemid; ?>" />
<br>
<p>Original event ID: <i><?php echo $eventid; ?></i></p>
<br>
<p><i>Best Regards,</i></p><p>
<i><span style="font-size:8.0pt;color:#e7e6e6">May the Force be with you</span></i></p>
<p><b>*** This is an automatically generated email by Zabbix, please do not reply, but take attention ***</b></p>

