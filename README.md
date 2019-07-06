# TestVPN-web
The website for my test version of my vpn service.

# Setup

## Scripts

Follow the setup in the scripts repo first https://github.com/TestVPN/TestVPN-scripts

## Secrets

create a ``secrets.php`` file in the root of the repository.

```
<?php
// secrets.php
/*******************
*                  *
*     include      *
*                  *
********************/
//require_once(__DIR__ . "/secret_includes_not_added_yet.php");

/*******************
*                  *
* Global constants *
*  S E C R E T     *
*                  *
********************/
const SECRET_BETA_KEY = "UPDATE_ME";
const SECRET_MAIL_PASS = "UPDATE_ME";
const SECRET_MAIL_PASS_ELASTIC = "UPDATE_ME";
const SECRET_CAPTCHA_KEY = 'UPDATE_ME';
?>
```

## Database

give apache permission to read and write the database file into the scripts dir

```
cd /var/www/TestVPN-scripts
chown -R www-data:www-data .
```

execute users_table.php from the web or from commandline to create the table.

http://localhost/TestVPN-web/users_table.php

```
php users_table.php
```

