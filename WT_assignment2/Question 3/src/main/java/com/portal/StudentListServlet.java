package com.portal;

import jakarta.servlet.ServletException;
import jakarta.servlet.annotation.WebServlet;
import jakarta.servlet.http.*;
import java.io.IOException;
import java.sql.*;
import java.util.*;

/** Fetches all students from MySQL and shows them on students.jsp */
@WebServlet("/students")
public class StudentListServlet extends HttpServlet {

    @Override
    protected void doGet(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {

        String sql = "SELECT s.id, s.name, s.roll_no, s.email, s.course, "
                   + " (SELECT f.id FROM student_files f WHERE f.student_id = s.id AND f.category = 'profile' LIMIT 1) AS profile_id, "
                   + " (SELECT COUNT(*) FROM student_files f WHERE f.student_id = s.id) AS file_count "
                   + "FROM students s ORDER BY s.name";

        List<Student> students = new ArrayList<>();
        try (Connection con = DBUtil.getConnection();
             PreparedStatement ps = con.prepareStatement(sql);
             ResultSet rs = ps.executeQuery()) {
            while (rs.next()) {
                Student s = new Student();
                s.setId(rs.getInt("id"));
                s.setName(rs.getString("name"));
                s.setRollNo(rs.getString("roll_no"));
                s.setEmail(rs.getString("email"));
                s.setCourse(rs.getString("course"));
                s.setProfileFileId(rs.getInt("profile_id"));     // 0 when there is no profile image
                s.setFileCount(rs.getInt("file_count"));
                students.add(s);
            }
        } catch (SQLException e) {
            request.setAttribute("error", "Database error: " + e.getMessage());
        }

        request.setAttribute("students", students);
        request.getRequestDispatcher("/WEB-INF/views/students.jsp").forward(request, response);
    }
}
