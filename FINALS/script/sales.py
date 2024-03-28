import sys
import mysql.connector
import json

# Get the shoe_id from the command-line arguments
shoe_id = sys.argv[1]

# Connect to the database
mydb = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="shoepe"
)

mycursor = mydb.cursor()

# Select sales data for the given shoe
mycursor.execute("SELECT date, amount FROM sales WHERE shoe_id = %s", (shoe_id,))

results = mycursor.fetchall()

dates = [row[0].strftime('%Y-%m-%d') for row in results]
sales = [row[1] for row in results]

# Return the sales data as JSON
print(json.dumps({'dates': dates, 'sales': sales}))