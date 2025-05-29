# Crime-Record-Management-System-For-Ghana-Police-Service-Using-Core-PHP-
The Ghana Police Crime Record System is a secure web-based application designed to help the Ghana Police Service efficiently manage and track crime reports, investigations, and case assignments. The system ensures data confidentiality, user accountability, and streamlined communication between police officers, administrators, and CID units.

🔐 Key Features
User Authentication: Secure login system for police officers and administrators.

Role-Based Access Control: Admins can assign cases to CIDs; CIDs only view their assigned cases.

Crime Reporting: Officers can submit detailed reports for incidents.

Case Management: Admins can monitor and manage the progress of crime investigations.

CID Module: CIDs view and investigate assigned cases.

Clean UI: Professional, responsive Bootstrap-powered interface.

Secure Session Management: Prevents unauthorized access and ensures session integrity.

🛠️ Tech Stack
PHP (Core Logic)

MySQL (Database)

Bootstrap 5 (Frontend Styling)

HTML/CSS/JavaScript

MVC Pattern (for scalability and structure)

📁 Project Structure
bash
Copy
Edit
/public         → Login, logout, and public-facing pages  
/views          → Dashboard, report views, CID views  
/controllers    → PHP classes to handle business logic  
/models         → (Optional) Database interactions  
/config         → DB connection and core settings  
/assets         → CSS, images, and scripts  
🔒 Security Notes
Only authenticated users can access system features.

Admin-only actions like case assignment are protected.

Input sanitization and session validation are enforced.
Users Credentials:
#Admin
email:admin@gmail.com
password: 1234
#CID
email: cid@gmail.com
password: 1234
#officer
email:officer@gmail.com
password: 1234

📞 Contact
For feedback, contributions, or support, feel free to reach out:

Name: Yahaya
Email: [your-email@example.com]
Phone: +233XXXXXXXXX
GitHub: github.com/yourusername
