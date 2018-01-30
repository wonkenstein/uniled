# Notes

* Hacked together, a decent framework should handle a lot of the functions in functions.php

# Install

* Create database
* Create table in that database with `send_friend.sql`
* Copy config.default.php to config.php and update with your database credentials
* I've made the assumption that sendmail is configured and the php mail() function works on your system
* Fill in form incorrectly
    * Should flag errors
* Fill in form correctly
    * Redirected to thanks page
    * New entry in database
    * Email should be sent to your friend
