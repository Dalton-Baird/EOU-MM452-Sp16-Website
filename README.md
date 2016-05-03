# EOU Website Redesign - With Forum
MM 352 - MM 452 - Spring 2016
Eastern Oregon University

This is a redesign of the EOU website.  It was originally created for the final project in MM 352.  In MM 452, it was extended to include an EOU forum that students can use.

## Setup
- Clone this repository somewhere
- Have a web server running PHP and MySQLi (preferably Apache)
- Have a MySQL database
- Point the web server's document root to the /root folder of this repository
- Create a /root/fragments/sql-credentials.php file containing the MySQL server credentials (see /root/fragments/connect.php for the required variables)
- Make sure the database has the required tables set up (TODO: once database tables are stable, add instructions on how to do that)