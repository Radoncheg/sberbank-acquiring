CREATE DATABASE market;
USE market;

CREATE TABLE product
(
  id  INT NOT NULL AUTO_INCREMENT,
  title VARCHAR(255) NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET GLOBAL local_infile = 1;
LOAD DATA LOCAL INFILE
 'C:/market/products.csv'
 INTO TABLE product
 FIELDS TERMINATED BY ',' ENCLOSED BY '"'
 LINES TERMINATED BY '\r\n'
 (title, price);

CREATE TABLE status (
  id TINYINT NOT NULL AUTO_INCREMENT,
  text VARCHAR(255) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO status (text) VALUES ('не обработан'), ('ошибка'), ('в процессе'), ('успешно обработан');

CREATE TABLE orders
(
  id  INT NOT NULL AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  quantity SMALLINT NOT NULL,
  product_id INT NOT NULL,
  status_id TINYINT NOT NULL DEFAULT 1,
  date_at TIMESTAMP DEFAULT NOW(),
  PRIMARY KEY (id),
  FOREIGN KEY orders_fk1 (product_id) REFERENCES product (id),
  FOREIGN KEY orders_fk2 (status_id) REFERENCES status (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;