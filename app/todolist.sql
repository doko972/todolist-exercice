CREATE TABLE task(
   Id_task INT AUTO_INCREMENT,
   display_order INT NOT NULL,
   description VARCHAR(150) NOT NULL,
   date_create DATETIME NOT NULL,
   ckeck_done BOOLEAN NOT NULL,
   PRIMARY KEY(Id_task)
);
