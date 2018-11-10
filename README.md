# TestVPN-web
The website for my test version of my vpn service.

# Setup

Follow the setup in the scripts repo https://github.com/TestVPN/TestVPN-scripts

and then:

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
