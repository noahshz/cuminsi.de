# some infos for mailing

to enable the mailing service for user registration, u need to install sendmail on your linux server
with the following commands:

## Install sendmail

    $ sudo apt-get install sendmail

## Configure sendmail

    $ sudo sendmailconfig

## restart apache

    $ sudo service apache2 restart