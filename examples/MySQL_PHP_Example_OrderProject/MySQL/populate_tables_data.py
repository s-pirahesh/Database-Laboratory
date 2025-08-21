import mysql.connector
from faker import Faker
import random

conn = mysql.connector.connect(
    host='localhost',
    port=3306,
    user='root',
    password='123456',
    database='orderdb'
)
cursor = conn.cursor()
fake = Faker()


city_ids = []
for _ in range(50):
    city_name = fake.city()
    cursor.execute("INSERT INTO city_tbl (cityName) VALUES (%s)", (city_name,))
    city_ids.append(cursor.lastrowid)

customer_ids = []
for _ in range(200):
    customer_first_name = fake.first_name()
    customer_last_name = fake.last_name()
    
    customerName = f'{customer_first_name} {customer_last_name}'#fake.name()
    customertel = fake.phone_number()
    customerAddress = fake.address()
    email = f"{customer_first_name[:2].lower()}.{customer_last_name.lower()}@{fake.free_email_domain()}"
    credit = round(random.uniform(0, 10000000), 2)
    status = random.choice(['active', 'inactive', 'suspended'])
    cityId = random.choice(city_ids)
    cursor.execute("""
        INSERT INTO customer_tbl (customerName, customertel, customerAddress,email, credit, status, cityId)
        VALUES (%s, %s, %s, %s, %s, %s, %s)
    """, (customerName, customertel, customerAddress, email, credit, status, cityId))
    customer_ids.append(cursor.lastrowid)

product_group_ids = []
for _ in range(40):
    product_group = fake.word()
    parent_id = random.choice(product_group_ids) if product_group_ids and random.random() < 0.8 else None
    cursor.execute("""
        INSERT INTO productGroup_tbl (ProductGroupTitle, ParentProductGroupID)
        VALUES (%s, %s)
    """, (product_group, parent_id))
    product_group_ids.append(cursor.lastrowid)

product_ids = []
for _ in range(400):
    productName = fake.word()
    minPrice = round(random.uniform(0, 1000), 2)
    ProductWeight = random.randint(0, 5000)
    ProductColor = fake.color_name()
    ProductGroupID = random.choice(product_group_ids)
    cursor.execute("""
        INSERT INTO product_tbl (ProductName, minPrice, ProductWeight, ProductColor, ProductGroupID)
        VALUES (%s, %s, %s, %s, %s)
    """, (productName, minPrice, ProductWeight, ProductColor, ProductGroupID))
    product_ids.append(cursor.lastrowid)

order_ids = []
for _ in range(1000):
    orderDate = fake.date_time_between(start_date="-3y", end_date="now")#fake.date_time_this_year()
    payType = random.choice(['cash', 'pos', 'cheque', 'online'])
    totalSum = round(random.uniform(0, 1000), 2)
    discountAmount = round(random.uniform(0, 100), 2)
    payablePrice = max(0, totalSum - discountAmount)
    customerId = random.choice(customer_ids)
    cursor.execute("""
        INSERT INTO Order_tbl (orderDate, payType, totalSum, discountAmount, payablePrice, customerId)
        VALUES (%s, %s, %s, %s, %s, %s)
    """, (orderDate, payType, totalSum, discountAmount, payablePrice, customerId))
    order_ids.append(cursor.lastrowid)

for _ in range(5000):
    productID = random.choice(product_ids)
    OrderId = random.choice(order_ids)
    fee = round(random.uniform(0, 100), 2)
    qty = random.randint(1, 10)

    cursor.execute("SELECT 1 FROM orderdetail_tbl WHERE productID=%s AND OrderId=%s", (productID, OrderId))
    if cursor.fetchone() is None:
        cursor.execute("""
            INSERT INTO orderdetail_tbl (productID, OrderId, fee, qty)
            VALUES (%s, %s, %s, %s)
        """, (productID, OrderId, fee, qty))

conn.commit()
cursor.close()
conn.close()
print("Database populated successfully!")
