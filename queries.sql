1. SELECT product FROM product;

2. SELECT * FROM product
ORDER BY weight

3. SELECT * FROM product ORDER BY id ASC LIMIT 5;

4. SELECT * FROM product ORDER BY id DESC LIMIT 5;

5. SELECT * FROM product WHERE price = (SELECT MIN(price) FROM product)

6. SELECT * FROM product WHERE price = (SELECT MAX(price) FROM product)




INSERT INTO product (name, price, weight, description)
VALUES
      ('nedved', 25, 33, NULL),
      ("666", 123, 44, '55'),
      (6668, 999, 55, NULL);
