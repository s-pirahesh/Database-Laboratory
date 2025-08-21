CREATE DATABASE IF NOT EXISTS orderdb DEFAULT CHARSET utf8 DEFAULT COLLATE utf8_general_ci;

USE orderdb;

CREATE TABLE IF NOT EXISTS city_tbl (
    cityId      SMALLINT PRIMARY KEY AUTO_INCREMENT,
    cityName    VARCHAR(25) NOT NULL
);

CREATE TABLE IF NOT EXISTS customer_tbl (
    customerId          INT PRIMARY KEY AUTO_INCREMENT,
    customerName        VARCHAR(30) NOT NULL,
    customertel         VARCHAR(30) UNIQUE,
    customerAddress     VARCHAR(100),
	email				VARCHAR(50),
    credit              DECIMAL(16,4) NOT NULL DEFAULT 10000000,
    status              ENUM('active', 'inactive', 'suspended') NOT NULL DEFAULT 'active',
    cityId              SMALLINT NOT NULL,
    CONSTRAINT FK_customer_City_CityId FOREIGN KEY (cityId) REFERENCES city_tbl (cityId) ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS Order_tbl (
    orderID             INT PRIMARY KEY AUTO_INCREMENT,
    orderDate           DATETIME NOT NULL,
    payType             ENUM('cash', 'pos', 'cheque', 'online') NOT NULL,
    totalSum            DECIMAL(16,4) NOT NULL DEFAULT 0,
    discountAmount      DECIMAL(16,4) NOT NULL DEFAULT 0,
    payablePrice        DECIMAL(16,4) NOT NULL DEFAULT 0,
    customerId          INT NOT NULL,
    CONSTRAINT FK_Order_customer_customerId FOREIGN KEY (customerId) REFERENCES customer_tbl (customerId) ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS productGroup_tbl (
    ProductGroupID          SMALLINT PRIMARY KEY AUTO_INCREMENT,
    ProductGroupTitle       VARCHAR(50) NOT NULL,
    ParentProductGroupID    SMALLINT,
    CONSTRAINT FK_productGroup_productGroup_ParentProductGroupID FOREIGN KEY (ParentProductGroupID) REFERENCES productGroup_tbl (ProductGroupID) ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS product_tbl (
    productID           INT PRIMARY KEY AUTO_INCREMENT,
    ProductName         VARCHAR(30) NOT NULL,
    minPrice            DECIMAL(16,4) NOT NULL DEFAULT 0,
    ProductWeight       INT DEFAULT 0,
    ProductColor        VARCHAR(30),
    ProductGroupID      SMALLINT NOT NULL,
    CONSTRAINT FK_product_productGroup_ProductGroupID FOREIGN KEY (ProductGroupID) REFERENCES productGroup_tbl (ProductGroupID) ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS orderdetail_tbl (
    productID           INT,
    OrderId             INT,
    fee                 DECIMAL(16,4) NOT NULL,
    qty                 INT NOT NULL,
    PRIMARY KEY (productID ASC, OrderId DESC),
    CONSTRAINT FK_orderdetail_Order_OrderId FOREIGN KEY (OrderId) REFERENCES Order_tbl (OrderId),
    CONSTRAINT FK_orderdetail_Product_ProductId FOREIGN KEY (productID) REFERENCES product_tbl (productID)
);
