<?php 
//@author Steven Radomski
//@email radomskist@yahoo.com

//PHP INJECTION SYNTAX
//String, break up values with ,
//\ skips next character so Use \, in the case of a , to be ignored

//Errorlog
//Returns false so if you want to use it like the die() command you type return errlog(...);

//Loading onfiguration file
$inifile =  parse_ini_file("config.ini", true);

/*****************
ERROR LOG FUNCTION
*****************/
function errlog($log)
	{
	//TODO Bug tracker? (add errors to another database)
	error_log("$log\n");
	return false;
	}


/***********************************
OPENING DATABSE CONNECTION FUNCTION
***********************************/
function opendatabase($dataname)
	{
	global $inifile;

	//Connection pointer to sql
	$sqlc = new mysqli($inifile["conndet"]["server"], $inifile["conndet"]["user"], $inifile["conndet"]["pass"]);

	//Checking if connection is good
	if($sqlc->connect_errno)
		return errlog("Error: " . $sqlc->connect_errno . " Failed to connect to server " . $inifile["conndet"]["server"] . ".");

	//attempting to open database
	if(!$sqlc->select_db ( $dataname ))
		{
		//If connection fails run this

		//If autocreate is false
		if(!($inifile["mysqlval"]["autocreate"]))
			return errlog("Database $dataname does not exist.");
		else
			{
			//If not create new one
			errlog("Database does not exist. Creating database $dataname");

			//Checking if new data base was created
			if (!($sqlc->query('CREATE DATABASE ' . $dataname)))
				//If not then log failed
				return errlog("Error: " . $sqlc->error ." Failed to create database: $dataname");
			else
				$sqlc->select_db ( $dataname );
				return $sqlc;
			}
		}
	else
		//Conncetion succeeded
		return $sqlc;
	}

/*****************
ADD VALUE TO TABLE 
******************/
function addrec($sqlconnection, $settable, $setrecord)
	{
	//Selecting the table
	if(!$gettable = $sqlconnection->query("SELECT * FROM $settable"))
		{
		//If table doens't exist
		if($sqlconnection->errno == 1146)
			{
			return errlog("Table " . $settable . " does not exist.");
			}
		//Other error
		else
			{
			return errlog("Insert error: " . $sqlconnection->error);
			}
		}

	/*CONVERSION PROCESS*/
	else
		{
		//amount of fields in the table
		$tabfcount = $gettable->field_count;

		//Saving location of commas
		$comqueue = array();
		//Finding amount of values in string
		for( $i = 0; $i <= strlen( $setrecord ); $i++)
			{
			if(substr($setrecord, $i, 1) == ',')
				{
				array_unshift($comqueue, $i); //Adding comma spot to list for later

				if(count($comqueue) >= $tabfcount)
					return errlog("Error. Too many parameters for $settable");
				}
			//If \ skip next character
			elseif(substr($setrecord, $i, 1) == '\\')
				$i++;
			}

		//if not enough commas
		if(count($comqueue) != $tabfcount - 1)
			return errlog("Error. Not enough parameters for $settable");

		/*Generating record*/

		/*adding fields*/
		$recconv = "INSERT INTO $settable (";
		while ($finfo = $gettable->fetch_field()) 
			{
			$recconv = $recconv . $finfo->name;
			
			//Checking if last line
			$tabfcount--;
			if($tabfcount != 0)
				$recconv = $recconv . ',';	
			}
		$recconv = $recconv . ") VALUES (";

		/*adding values*/

		//Resetting count
		$tabfcount = $gettable->field_count;

		//Substring range
		$ssstart = 0;
		$ssend = -1;

		//breaking each comma into a substr and adding it to the list
		while($tabfcount != 0)
			{
			$ssstart = $ssend + 1;

			//If last value then rest of string
			if($tabfcount != 1)
				$ssend = array_pop($comqueue);
			else
				$ssend = strlen( $setrecord );

			$recconv = $recconv . "'" . substr($setrecord, $ssstart, $ssend - $ssstart) . "'";

			//Checking if last value		
			$tabfcount--;
			if($tabfcount != 0)
				$recconv = $recconv . ',';	
			}
		$recconv = $recconv . ");";

		/*CALLING IN QUERY*/
		if(!$sqlconnection->query($recconv))
			return errlog("Error: " . $sqlconnection->error ." Failed to add record to table: $settable");
		
		}

	return true;
	}

/*****************
ADD TABLE TO DATABASE
******************/
function addtab($sqlconnection, $settable, $setfields)
	{
	//Creating table
	if(!$sqlconnection->query("CREATE TABLE $settable ($setfields);"))
		//If fails
		return errlog("Error: " . $sqlconnection->error ." Failed to create table: $settable");
	else
	//If succeeds
		return true;
	}

/***************
LOOKUP FUNCTIONS
****************/
//Look up first value that matches search
function lookupfield($sqlconnection, $settable, $searchfield, $searchf)
	{
	//Creating table
	if($returnfield = $sqlconnection->query("SELECT * FROM $settable WHERE $searchfield='$searchf' limit 1;"));
		return $returnfield->fetch_array(MYSQLI_ASSOC);
	//If fails
	return errlog("Error: " . $sqlconnection->error ." Failed to delete $searchf in: $settable");
	}


/***************
DELETE FUNCTIONS
****************/
//Delete first value that matches
function deletefield($sqlconnection, $settable, $searchfield, $searchf)
	{
	//Creating table
	if($sqlconnection->query("DELETE FROM $settable WHERE $searchfield='$searchf';"))
		return true;
	//If fails
	return errlog("Error: " . $sqlconnection->error ." Failed to delete $searchf in: $settable");
	}
	
?>
