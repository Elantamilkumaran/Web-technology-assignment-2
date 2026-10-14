package com.portal;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;

/** Opens the JDBC connection and holds the upload-folder location. */
public class DBUtil {

    private static final String URL  = "jdbc:mysql://localhost:3306/college_db";
    private static final String USER = env("DB_USER", "root");
    private static final String PASS = env("DB_PASS", "");

    // Folder where the Question 2 PHP project saves the uploaded files.
    // Change this path to match your computer.
    public static final String UPLOAD_DIR =
            env("UPLOAD_DIR", "C:/xampp/htdocs/student-crud/uploads");

    public static Connection getConnection() throws SQLException {
        try {
            Class.forName("com.mysql.cj.jdbc.Driver");   // MySQL Connector/J
        } catch (ClassNotFoundException e) {
            throw new SQLException("MySQL JDBC driver jar not found in WEB-INF/lib", e);
        }
        return DriverManager.getConnection(URL, USER, PASS);
    }

    private static String env(String name, String defaultValue) {
        String v = System.getenv(name);
        return (v == null || v.isEmpty()) ? defaultValue : v;
    }
}
