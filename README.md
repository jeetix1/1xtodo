# 1xtodo
#### _ToDo List project as I could not find one that works like I want it to work._

This project is just started and does not do much atm. 
## Features

- ✨Little to no documentation! x3
- ✨Can Write to a txt file. (If you dont want the hassle with seting up a MySQL database... Use the version in the folder: "txt-based" that comes with incredibly limmited options! AMAZING! )
- ✨Uses MySQL if you are serious, not Sirius!
- ✨Missing option to delete or complete tasks :D
- ✨Has other options!

# Installlation instuctions...
DB queries:
```sql
CREATE TABLE log (
  id INT AUTO_INCREMENT PRIMARY KEY,
  task_id INT NOT NULL,
  event VARCHAR(2048) NOT NULL,
  timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

```sql
CREATE TABLE tasks (
  id INT AUTO_INCREMENT PRIMARY KEY,
  task VARCHAR(2048) NOT NULL,
  status ENUM('incomplete', 'completed') NOT NULL DEFAULT 'incomplete'
);
```

Sample of **1xtodo-dbcon.php** is referenced to but not in the repository for a milion reasons that shoul be out of reach from public.
```php
<?php
$servername = "localhost";
$username = "somethingusernamish";
$password = "somethingpasswordy-notpassword123!";
$dbname = "somethingdatabasenameish";
```
# Screenshot
![](https://prod.1x.no/img/tasklistscreenshot202302061038.png)