# public-diary

**1. Server Setup**

Set the document root on your server to **/public**

**2. Database Setup**

The SQL data file is the **/src/app/db/db_temp.sql** file. Simply execute it on the SQL shell.

The database connection with PHP is done in the **/src/app/db/db_conn.php** file. Change the variable values according to your database settings.
```
$db_servername  = "localhost";
$db_username    = "username";
$db_password    = "password";
```
**3. Email Setup With SSMTP (for Linux)**

Install ssmtp

```
sudo apt install ssmtp (for Debian Based Distributions)
```
Open the **/etc/ssmtp/ssmtp.conf** file and make the following changes with your email information.
``` 
root=user@email.com
mailhub=smtp.email.com:587
hostname=localhost
AuthUser=user@email.com
AuthPass=password
UseSTARTTLS=yes
UseTLS=yes
FromLineOverride=yes
```
Open the **/etc/ssmtp/revaliases** file and make the following changes with your email information.
```
root:user@email.com:smtp.email.com:587
```
Open the **/etc/php/8.0/fpm/php.ini** file and make the following change.
```
;sendmail_path
sendmail_path = ssmtp -t -i
```
