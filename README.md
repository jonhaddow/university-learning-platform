# Module Dependency Learning Platform

The Module Dependency Learning Platform is a web application designed for students to view Module topics and their dependency relationships. Students can then provide topic specific feedback for lecturers to review.

## Set-up

This project was set-up locally using [Xampp](https://www.apachefriends.org/index.html). When Xampp is installed, find the live folder where public HTML documents belong (In linux, this is ```/opt/lampp/htdocs```).

Copy this project's directory into this "htdocs" folder. Go to ```/opt/lampp/etc``` and edit this file where it says: 
```
DocumentRoot "/opt/lampp/htdocs"
<Directory "/opt/lampp/htdocs">
``` 
and change it to:
```
DocumentRoot "/opt/lampp/htdocs/public"
<Directory "/opt/lampp/htdocs/public">
```

Start the Xampp server and check that it runs in a browser.

### Database

To set-up the database, go to ```localhost/phpmyadmin``` in a browser. Within this interface create a database called "test". Then select "Import" and import the ```test.sql``` file in this project's ```databaseBackup/``` directory. This should populate your database with the correct tables and some test data.