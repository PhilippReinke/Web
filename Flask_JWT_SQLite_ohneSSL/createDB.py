import sqlite3 as sq

# open (or create) database
conn = sq.connect("datenbank.db")

# create table users
conn.execute("CREATE TABLE users (id INTEGER PRIMARY KEY, public_id int, name varchar(50), password varchar(50), admin bool)")

# create table authors
conn.execute("CREATE TABLE authors (id INTEGER PRIMARY KEY, name varchar(50), book varchar(50), country varchar(50), booker_prize bool, user_id int)")

# show entries
cursor = conn.cursor()
cursor.execute("SELECT * FROM users")
rows = cursor.fetchall()
print(rows)

# close connection
conn.close()