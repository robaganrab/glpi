<?php
/*
 
 ----------------------------------------------------------------------
GLPI - Gestionnaire libre de parc informatique
 Copyright (C) 2002 by the INDEPNET Development Team.
 http://indepnet.net/   http://glpi.indepnet.org
 ----------------------------------------------------------------------
 Based on:
IRMA, Information Resource-Management and Administration
Christian Bauer, turin@incubus.de 

 ----------------------------------------------------------------------
 LICENSE

This file is part of GLPI.

    GLPI is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    GLPI is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with GLPI; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 ----------------------------------------------------------------------
 Original Author of file:
 Purpose of file:
 ----------------------------------------------------------------------
*/
 

include ("_relpos.php");
// CLASSES Computers


class Computer {

	var $fields	= array();
	var $updates	= array();
	
	function getfromDB ($ID,$template) {

		if ($template) {
			$table = "templates";
		} else {
			$table = "computers";
		}
		
		// Make new database object and fill variables
		$db = new DB;
		$query = "SELECT * FROM $table WHERE (ID = '$ID')";
		if ($result = $db->query($query)) {
			if ($db->numrows($result)) {
				$data = mysql_fetch_array($result);
				for($i=0; $i < count($data); $i++) {
					list($key,$val) = each($data);
					$this->fields[$key] = $val;
				}
				return true;
			}
		} else {
			return false;
		}
	}

	function updateInDB($updates)  {

		$db = new DB;

		for ($i=0; $i < count($updates); $i++) {
			$query  = "UPDATE computers SET ";
			$query .= $updates[$i];
			$query .= "='";
			$query .= $this->fields[$updates[$i]];
			$query .= "' WHERE ID='";
			$query .= $this->fields["ID"];	
			$query .= "'";
			$result=$db->query($query);
		}
		
	}
	
	function addToDB() {
		
		$db = new DB;

		$this->comments = addslashes($this->comments);
		
		// Build query
		$query = "INSERT INTO computers (";
		for ($i=0; $i < count($this->fields); $i++) {
			list($key,$val) = each($this->fields);
			$fields[$i] = $key;
			$values[$i] = $val;
		}		
		for ($i=0; $i < count($fields); $i++) {
			$query .= $fields[$i];
			if ($i!=count($fields)-1) {
				$query .= ",";
			}
		}
		$query .= ") VALUES (";
		for ($i=0; $i < count($values); $i++) {
			$query .= "'".$values[$i]."'";
			if ($i!=count($values)-1) {
				$query .= ",";
			}
		}
		$query .= ")";

		if ($result=$db->query($query)) {
			return true;
		} else {
			return false;
		}
	}

	function deleteFromDB($ID,$template) {

		if ($template) {
			$table = "templates";
		} else {
			$table = "computers";
		}

		$db = new DB;

		$query = "DELETE from $table WHERE ID = '$ID'";
		if ($result = $db->query($query)) {
			$query = "SELECT * FROM tracking WHERE (computer = \"$ID\")";
			$result = $db->query($query);
			$number = $db->numrows($result);
			$i=0;
			while ($i < $number) {
		  		$job = $db->result($result,$i,"ID");
		    		$query = "DELETE FROM followups WHERE (tracking = \"$job\")";
		      		$db->query($query);
				$i++;
			}
			$query = "DELETE FROM tracking WHERE (computer = \"$ID\")";
			$result = $db->query($query);
			$query = "DELETE FROM inst_software WHERE (cID = \"$ID\")";
			$result = $db->query($query);
			$query = "DELETE FROM networking_ports WHERE (device_on = $ID AND device_type = 1)";
			$result = $db->query($query);

			return true;
		} else {
			return false;
		}
	}

}

?>
