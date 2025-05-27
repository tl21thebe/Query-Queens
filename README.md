# Query-Queens
 The CompareIt price comparison web application designed to address the shortcomings of older platforms like PriceCheck, offering a modern, user-friendly interface and real-time price aggregation from online and physical retailers worldwide. The platform enables users to browse categories, filter by criteria such as brand or rating, view detailed product information, compare prices across stores, and contribute reviews, creating a more engaging and efficient shopping experience.

How to Build and Run the Application
Follow the steps below to successfully set up and launch the Query-Queens web application on your local machine:

1. Clone the Repository
Visit the Query-Queens GitHub repository and ensure you are on the main branch.
Clone the repository to your local machine using the following command
git clone https://github.com/your-username/Query-Queens.git
Alternatively, pull the latest changes from the main branch:
git pull origin main
2. Open the Project in VS Code
Navigate to the cloned repository folder on your computer.
Open the folder in Visual Studio Code.
3. Set Up the Local Server Using XAMPP
Launch XAMPP and start both the Apache and MySQL services.
Locate your XAMPP installation folder (usually found in C:\xampp).
Inside the htdocs directory, copy and paste the entire Query-Queens project folder.
4. Run the Application in Your Browser
Open any web browser and navigate to the following URL:
http://localhost/Query-Queens/index.html
You will be directed to the launch page, which contains a button that navigates to the login page.
5. Account Access
If you do not have an account, click the Sign Up link on the login page to register.
Alternatively, you may explore the site without registering by using the CompareIt feature in the navigation bar.

Default Login Credentials:
You may use the following test credentials to access the application:
Customer Account:
Name: Didi
Surname: Tlaka
Email: didi@gmail.com
Password: Diditlaka@1
Admin Account:
Name: Sam
Surname: Pucket
Email: sam@gmail.com
Password: Sampucket@1

Bonus Features Implemented
1. Security First – Secure Your App
Implemented secure password hashing using PASSWORD_DEFAULT in PHP.
This uses the bcrypt algorithm with automatic salting, offering strong protection against brute-force attacks.
2. Data Visualization Dashboard
Developed an interactive dashboard displaying:
User activity trends
Price history graphs
Top-rated products
Brand and category distribution charts
3. Advanced Git Workflow
Adopted a collaborative development workflow by using feature-specific branches:
Frontend (PHP, JavaScript, CSS)
Backend
Ensured modular development and smoother integration by merging all branches into the main branch upon completion.

If you need further assistance or encounter issues, feel free to reach out to the development team.
