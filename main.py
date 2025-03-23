import mysql.connector
import tkinter as tk
from tkinter import ttk, messagebox
import bcrypt  # Using bcrypt to handle password hashing

# Database connection parameters
DB_HOST = "dbs.spskladno.cz"
DB_USER = "student2"
DB_PASSWORD = "spsnet"
DB_NAME = "vyuka2"

# Function to connect to the database
def create_connection():
    try:
        conn = mysql.connector.connect(
            host=DB_HOST,
            user=DB_USER,
            password=DB_PASSWORD,
            database=DB_NAME
        )
        return conn
    except mysql.connector.Error as err:
        messagebox.showerror("Database Error", f"Failed to connect to MySQL: {err}")
        exit()

# Function to validate login
def validate_login():
    username = username_entry.get()
    password = password_entry.get()

    if not username or not password:
        messagebox.showerror("Login Error", "Please enter both username and password.")
        return

    conn = create_connection()
    cursor = conn.cursor()

    # SQL query to fetch the user based on the username
    cursor.execute("SELECT id, username, password FROM userdata WHERE username = %s", (username,))
    user = cursor.fetchone()

    if user:
        stored_password = user[2]  # The hashed password in the database
        if bcrypt.checkpw(password.encode('utf-8'), stored_password.encode('utf-8')): 
            messagebox.showinfo("Login Success", f"Welcome {user[1]}!")
            login_window.destroy()  # Close login window
            open_product_list(user[0])  # Open the product list window
        else:
            messagebox.showerror("Login Error", "Invalid username or password.")
    else:
        messagebox.showerror("Login Error", "Invalid username or password.")
    
# Function to open the product list window
def open_product_list(user_id):
    global root

    root = tk.Tk()
    root.title("Product List")
    root.geometry("600x400")

    tk.Label(root, text="Filter Products", font=("Arial", 14, "bold")).pack(pady=5)

    filter_frame = tk.Frame(root)
    filter_frame.pack(pady=5)

    tk.Label(filter_frame, text="Min Price:").grid(row=0, column=0)
    min_price_entry = tk.Entry(filter_frame)
    min_price_entry.grid(row=0, column=1)

    tk.Label(filter_frame, text="Max Price:").grid(row=0, column=2)
    max_price_entry = tk.Entry(filter_frame)
    max_price_entry.grid(row=0, column=3)

    tk.Label(filter_frame, text="Search:").grid(row=0, column=4)
    search_entry = tk.Entry(filter_frame)
    search_entry.grid(row=0, column=5)

    filter_button = tk.Button(filter_frame, text="Filter", command=lambda: fetch_products(min_price_entry, max_price_entry, search_entry, product_list, user_id))
    filter_button.grid(row=0, column=6, padx=5)

    # Product list
    columns = ("ID", "Name", "Description", "Price", "Seller", "UserID")
    product_list = ttk.Treeview(root, columns=columns, show="headings")

    for col in columns[:-1]:  # Hide UserID column
        product_list.heading(col, text=col)
        product_list.column(col, width=100)

    product_list.column("ID", width=40)
    product_list.column("Price", width=70)

    product_list.pack(pady=10, fill=tk.BOTH, expand=True)

    # Delete Button
    delete_button = tk.Button(root, text="Delete Selected", command=lambda: delete_product(product_list, user_id))
    delete_button.pack(pady=5)

    fetch_products(min_price_entry, max_price_entry, search_entry, product_list, user_id)  # Load products initially

    root.mainloop()

# Function to fetch products based on filters
def fetch_products(min_price_entry, max_price_entry, search_entry, product_list, user_id):
    min_price = min_price_entry.get() or "0"
    max_price = max_price_entry.get() or "100000"
    search = search_entry.get() or ""

    try:
        min_price = float(min_price)
        max_price = float(max_price)
    except ValueError:
        messagebox.showerror("Error", "Price must be a number!")
        return

    conn = create_connection()
    cursor = conn.cursor()

    query = """
        SELECT p.id, p.name, p.description, p.price, u.username, u.id 
        FROM Products1 p 
        JOIN userdata u ON p.author_id = u.id
        WHERE p.price BETWEEN %s AND %s AND p.name LIKE %s
    """
    cursor.execute(query, (min_price, max_price, f"%{search}%"))
    
    results = cursor.fetchall()

    # Clear the treeview
    for row in product_list.get_children():
        product_list.delete(row)

    # Insert new results
    if not results:
        messagebox.showinfo("No Products", "No products found.")
    
    for product in results:
        product_list.insert("", "end", values=product)

# Function to delete a selected product
def delete_product(product_list, user_id):
    selected_item = product_list.selection()
    if not selected_item:
        messagebox.showerror("Delete Error", "Please select a product to delete.")
        return

    item = product_list.item(selected_item)
    product_id = item["values"][0]  # Product ID
    owner_id = item["values"][5]  # User ID of the product owner

    if user_id != owner_id:
        messagebox.showerror("Delete Error", "You can only delete your own products.")
        return

    confirm = messagebox.askyesno("Confirm Delete", "Are you sure you want to delete this product?")
    if not confirm:
        return

    conn = create_connection()
    cursor = conn.cursor()
    cursor.execute("DELETE FROM Products1 WHERE id = %s AND author_id = %s", (product_id, user_id))
    conn.commit()
    conn.close()

    messagebox.showinfo("Success", "Product deleted successfully.")
    product_list.delete(selected_item)

# GUI Setup for Login Window
login_window = tk.Tk()
login_window.title("Login")
login_window.geometry("300x200")

tk.Label(login_window, text="Username:", font=("Arial", 12)).pack(pady=5)
username_entry = tk.Entry(login_window, font=("Arial", 12))
username_entry.pack(pady=5)

tk.Label(login_window, text="Password:", font=("Arial", 12)).pack(pady=5)
password_entry = tk.Entry(login_window, show="*", font=("Arial", 12))
password_entry.pack(pady=5)

login_button = tk.Button(login_window, text="Login", font=("Arial", 12), command=validate_login)
login_button.pack(pady=15)

login_window.mainloop()
