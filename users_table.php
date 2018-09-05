<?php
require_once(__DIR__ . "/global.php");

function CreateTableUsers()
{
   class MyDB extends SQLite3
   {
      function __construct()
      {
         $this->open(DATABASE_PATH_RAW) ;
      }
   }
   $check_db = new MyDB() ;
   if(!$check_db) {
      echo $check_db->lastErrorMsg() . "\n<br>";
   } else {
      echo "Opened database successfully\n<br>";
   }

	$sql =<<<EOF
		CREATE TABLE Accounts
		(ID				INTEGER			PRIMARY KEY		AUTOINCREMENT,
		Username			TEXT			NOT NULL,
		Password			TEXT			NOT NULL,
		OldPassword			TEXT,
		Mail		                TEXT,
		IP				TEXT,
		InvitedFriends			INTEGER			DEFAULT 0,
		NumVPNS				INTEGER			DEFAULT 0,
		LastVPN				DATE,
		Config0				TEXT			DEFAULT '',
		Config1				TEXT			DEFAULT '',
		Config2				TEXT			DEFAULT '',
		Config3				TEXT			DEFAULT '',
		Config4				TEXT			DEFAULT '',
		Config5				TEXT			DEFAULT '',
		Config6				TEXT			DEFAULT '',
		Config7				TEXT			DEFAULT '',
		Config8				TEXT			DEFAULT '',
		Config9				TEXT			DEFAULT '',
		Config10			TEXT			DEFAULT '',
		Config11			TEXT			DEFAULT '',
		Config12			TEXT			DEFAULT '',
		Config13			TEXT			DEFAULT '',
		Config14			TEXT			DEFAULT '',
		Config15			TEXT			DEFAULT '',
		RegisterDate			DATE,
		LastLogin			DATE);
EOF;

   $ret = $check_db->exec($sql) ;
   if(!$ret) {
      echo $check_db->lastErrorMsg() . "\n</br>";
   } else {
      echo "Users Table created successfully\n</br>";
   }
   $check_db->close();
}

CreateTableUsers();
?>
