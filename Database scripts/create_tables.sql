drop table hyperAV_stockorderdetails;
drop table hyperAV_orderdetails;
drop table hyperAV_stock;
drop table hyperAV_products;
drop table hyperAV_supplier;
drop table hyperAV_manufacturer;
drop table hyperAV_orders;
drop table hyperAV_staff;
drop table hyperAV_location;
drop table hyperAV_customer;



CREATE TABLE hyperAV_customer (
customerID int(10) AUTO_INCREMENT,
cuFName varchar(25) NOT NULL,
cuLName varchar(50) NOT NULL,
cuAddress1 varchar(50) NOT NULL,
cuAddress2 varchar(50),
cuTown varchar(15) NOT NULL,
cuPostcode varchar(15) NOT NULL,
cuTelephone varchar(11) NOT NULL,
cuEmail varchar(50) NOT NULL,
cuPassword varchar(50) NOT NULL,
CONSTRAINT customerID_PK PRIMARY KEY (customerID),
CONSTRAINT cuEmail_UQ UNIQUE (cuEmail)
);

create table hyperAV_location (
locationID int(10) AUTO_INCREMENT,
loName varchar(25) NOT NULL,
loAddress1 varchar(40) NOT NULL,
loAddress2 varchar(40),
loTown varchar(20) NOT NULL,
loPostcode varchar(15) NOT NULL,
loTelephone varchar(20) NOT NULL,
CONSTRAINT locationID_PK PRIMARY KEY (locationID),
CONSTRAINT loTelephone_UQ UNIQUE (loTelephone)
);

CREATE TABLE hyperAV_staff (
staffID int(10) AUTO_INCREMENT,
stFName varchar(25) NOT NULL,
stLName varchar(50) NOT NULL,
stAddress1 varchar(50) NOT NULL,
stAddress2 varchar(50),
stTown varchar(15) NOT NULL,
stPostcode varchar(15) NOT NULL,
stTelephone varchar(20) NOT NULL,
stEmail varchar(50) NOT NULL,
stPassword varchar(50) NOT NULL,
stJobRole varchar(50) NOT NULL,
locationID int(10),
CONSTRAINT staffID_PK PRIMARY KEY (staffID),
CONSTRAINT loID_FK FOREIGN KEY (locationID) REFERENCES hyperAV_location(locationID)
);

CREATE TABLE hyperAV_orders (
orderID int(10) AUTO_INCREMENT,
orDate Date NOT NULL,
orTotal decimal(10,2) NOT NULL,
orDeliverDate DATE NOT NULL,
orPaymentMethod varchar(15) NOT NULL,
customerID int(10),
staffID int(10),
CONSTRAINT orderID_PK PRIMARY KEY (orderID),
CONSTRAINT customerID_FK FOREIGN KEY (customerID) REFERENCES hyperAV_customer(customerID) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT staffID_FK FOREIGN KEY (staffID) REFERENCES hyperAV_staff(staffID)ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT orDelDt_GTE_orDt CHECK (orDeliverDate >= orDate),
CONSTRAINT orDate_Today CHECK (orDate = DATE(orDate))
);

create table hyperAV_manufacturer (
manufacturerID int(10) AUTO_INCREMENT,
maName varchar(25) NOT NULL,
maAddress1 varchar(40) NOT NULL,
maAddress2 varchar(40),
maTown varchar(20) NOT NULL,
maPostcode varchar(15) NOT NULL,
maTelephone varchar(20) NOT NULL,
maEmail varchar(50),
CONSTRAINT manufacturerID_PK PRIMARY KEY (manufacturerID),
CONSTRAINT maTelephone_UQ UNIQUE (maTelephone)
);

create table hyperAV_supplier (
supplierID INT(10) NOT NULL AUTO_INCREMENT,
suName varchar(25) NOT NULL,
suAddress1 varchar(40) NOT NULL,
suAddress2 varchar(40) NOT NULL,
suTown varchar(20) NOT NULL,
suPostcode varchar(15) NOT NULL,
suTelephone varchar(20) NOT NULL,
suEmail varchar(50),
CONSTRAINT supplierID_PK PRIMARY KEY (supplierID),
CONSTRAINT suTelephone_UQ UNIQUE (suTelephone)
);

CREATE TABLE hyperAV_products (
prModelNo VARCHAR(15) NOT NULL,
prName VARCHAR(25) NOT NULL,
prDescription VARCHAR(100) NOT NULL,
prPrice DECIMAL(6,2) NOT NULL,
prCategory VARCHAR(30) NOT NULL,
manufacturerID INT(10) NOT NULL,
minStockLevel INT(6) NOT NULL,
maxStockLevel INT(6),
CONSTRAINT prModelNo_PK PRIMARY KEY (prModelNo),
CONSTRAINT prModelNo_UQ UNIQUE (prModelNo)
);

CREATE TABLE hyperAV_stock (
stockID INT(10) AUTO_INCREMENT,
prModelNo VARCHAR(15) NOT NULL,
locationID INT(10) NOT NULL,
stQuantity INT(7) NOT NULL,
CONSTRAINT stockID_PK PRIMARY KEY (stockID),
CONSTRAINT prModelNo_FK FOREIGN KEY (prModelNo) REFERENCES hyperAV_products (prModelNo)ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT locationID_FK FOREIGN KEY (locationID) REFERENCES hyperAV_location (locationID)ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE hyperAV_orderdetails (
orderID INT(10) NOT NULL,
stockID INT(10) NOT NULL,
odQuantity INT(3) NOT NULL,
CONSTRAINT orderID_FK FOREIGN KEY (orderID) REFERENCES hyperAV_orders (orderID) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT stockID_FK FOREIGN KEY (stockID) REFERENCES hyperAV_stock (stockID) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT odQuantGTZero CHECK (odQuantity > 0),
CONSTRAINT oIDsID_PK PRIMARY KEY (orderID, stockID)
);

CREATE TABLE hyperAV_stockorderdetails
(stockID int(10) NOT NULL,
supplierID INT(10) NOT NULL,
stOrderDate Date NOT NULL,
stDeliveryDate Date,
stOrderQuantity INT(3) NOT NULL,
CONSTRAINT stOrdDate_sysdate CHECK (stOrderDate = DATE(stOrderDate)),
CONSTRAINT storderQuantGrTZero CHECK (stOrderQuantity > 0),
CONSTRAINT stOrderID_FK FOREIGN KEY (stockID) REFERENCES hyperAV_stock(stockID) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT stOderserSupID_FK FOREIGN KEY(supplierID) REFERENCES hyperAV_supplier(supplierID)ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT stockOrderDetails_PK PRIMARY KEY (stockID,supplierID,stOrderDate)
);
