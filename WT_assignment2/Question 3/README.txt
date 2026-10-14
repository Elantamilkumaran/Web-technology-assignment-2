STUDENT PORTAL (Servlets + JSP + JDBC + MySQL)
Uses Tomcat 10 or newer (jakarta.servlet). For Tomcat 9, change every
"jakarta.servlet" import in the .java files to "javax.servlet".

SETUP
1. Import  portal.sql  in phpMyAdmin (adds the users table; login = admin / admin123).
   The students/files data comes from the Question 2 PHP project (same database).
2. Download MySQL Connector/J (platform independent .zip) from
   https://dev.mysql.com/downloads/connector/j/  and copy the
   mysql-connector-j-x.x.x.jar into  src/main/webapp/WEB-INF/lib/
3. Open  src/main/java/com/portal/DBUtil.java  and set:
     - DB user / password (default root, empty)
     - UPLOAD_DIR = the uploads folder of the Question 2 project
       e.g. C:/xampp/htdocs/student-crud/uploads
4. Eclipse: File > Import > Existing Projects (or create a Dynamic Web Project and
   copy src/main/java and src/main/webapp into it). Right-click > Run on Server (Tomcat 10).
5. Open http://localhost:8080/<project-name>/  and log in.

PAGES
 /login     login form             /students  list of students (loop + conditions)
 /profile   one student + files    /file?id=  streams image/PDF from the upload folder
 /logout    ends the session
