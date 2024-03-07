import numpy as np 
import matplotlib.pyplot as plt  
import mysql.connector

# Replace with database credentials
mydb = mysql.connector.connect(
    host="localhost",
    user="root",
    password="Your_Password",
    database="Database_Name"
)


mycursor = mydb.cursor()

mycursor.execute("SELECT * FROM sales")

results = mycursor.fetchall()

dates = [row[0] for row in results]
sales = [row[1] for row in results]

dates = np.array(dates)
sales = np.array(sales)

plt.plot(dates, sales)
plt.xlabel('Date')
plt.ylabel('Sales')
plt.title('Sales Over Time')
plt.show()
