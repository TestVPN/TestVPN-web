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
