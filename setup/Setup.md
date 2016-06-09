# SQL Database Setup
Exported June 8, 2016

This folder contains two sql files for setting up the database.  They were created by MySQL Workbench's data export feature.  One contains the database schema, and the other contains some basic data (user levels, categories) that you would want to start off with.

### Setup
To set up the database, run the sql statements in the `SetupDatabaseStructure.sql` file.  This should create the database structure.
To set up the base data, run the sql statements in the `InsertBaseData.sql` file.  This should insert the user levels and basic categories.

### Notes
You should be able to just run these sql statements to set up the database, or you might be able to use the import wizard in MySQL Workbench, if you're using that.  These sql statements should set up the database correctly, but I haven't tested them, so you may need to tinker with them a bit.