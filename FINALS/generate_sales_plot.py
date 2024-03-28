import sys
import numpy as np
import matplotlib.pyplot as plt
import mysql.connector
import os

def get_shoe_name(shoe_id, mycursor):
    # Fetch shoe name from the shoes table for the specific shoe ID
    query = "SELECT shoename FROM shoes WHERE id = %s"
    mycursor.execute(query, (shoe_id,))
    result = mycursor.fetchone()

    if result:
        return result[0]  # Return the shoe name
    else:
        return None

def generate_sales_plot(shoe_id):
    # Connect to your MySQL database
    mydb = mysql.connector.connect(
        host="localhost",
        user="root",
        password="",
        database="shoepe"
    )

    mycursor = mydb.cursor()

    # Fetch data from history table for the specific shoe
    query = "SELECT purchase_date, price FROM history WHERE shoe_id = %s"
    mycursor.execute(query, (shoe_id,))
    results = mycursor.fetchall()

    if not results:
        print(f"Error: No sales data found for Shoe ID {shoe_id}")
        return

    dates = [row[0] for row in results]
    prices = [row[1] for row in results]

    # Get shoe name
    shoe_name = get_shoe_name(shoe_id, mycursor)
    if not shoe_name:
        print(f"Error: Shoe ID {shoe_id} not found.")
        return

    # Plotting
    plt.figure(figsize=(10, 6))
    plt.plot(dates, prices, marker='o', linestyle='-', color='b')
    plt.xlabel('Date')
    plt.ylabel('Price')
    plt.title(f'Sales Plot for {shoe_name}')
    plt.xticks(rotation=45, ha='right')
    plt.tight_layout()

    # Save the plot as an image
    plot_directory = os.path.join(os.getcwd(), 'plots')  # Adjust the path
    os.makedirs(plot_directory, exist_ok=True)
    plot_filename = os.path.join(plot_directory, f'shoe_sales_plot_{shoe_id}.png')

    try:
        plt.savefig(plot_filename)
        print(plot_filename)  # Print the plot filename for PHP to capture
    except Exception as e:
        print("Error saving plot:", e)
        return

    # Modify plot filename to relative path
    relative_plot_filename = os.path.join('plots', f'shoe_sales_plot_{shoe_id}.png')

    # Insert plot information into shoe_plots table with relative path
    insert_query = "INSERT INTO shoe_plots (shoe_id, plot_filename) VALUES (%s, %s)"
    insert_values = (shoe_id, relative_plot_filename)
    mycursor.execute(insert_query, insert_values)
    mydb.commit()

    # Close the plot to prevent displaying it
    plt.close()

if __name__ == "__main__":
    if len(sys.argv) < 2:
        print("Error: Shoe ID not provided.")
        sys.exit(1)

    shoe_id = sys.argv[1]
    generate_sales_plot(shoe_id)

