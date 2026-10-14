package com.portal;

import jakarta.servlet.ServletException;
import jakarta.servlet.annotation.WebServlet;
import jakarta.servlet.http.*;
import java.io.IOException;
import java.sql.*;
import java.util.*;

/** Fetches one student + all file records (paths) and shows profile.jsp */
@WebServlet("/profile")
public class StudentProfileServlet extends HttpServlet {

    @Override
    protected void doGet(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {

        int id;
        try {
            id = Integer.parseInt(request.getParameter("id"));
        } catch (NumberFormatException e) {
            response.sendError(HttpServletResponse.SC_BAD_REQUEST, "Invalid student id");
            return;
        }

        try (Connection con = DBUtil.getConnection()) {
            Student student = null;
            try (PreparedStatement ps = con.prepareStatement(
                    "SELECT id, name, roll_no, email, course FROM students WHERE id = ?")) {
                ps.setInt(1, id);
                try (ResultSet rs = ps.executeQuery()) {
                    if (rs.next()) {
                        student = new Student();
                        student.setId(rs.getInt("id"));
                        student.setName(rs.getString("name"));
                        student.setRollNo(rs.getString("roll_no"));
                        student.setEmail(rs.getString("email"));
                        student.setCourse(rs.getString("course"));
                    }
                }
            }
            if (student == null) {
                response.sendError(HttpServletResponse.SC_NOT_FOUND, "Student not found");
                return;
            }

            List<StudentFile> files = new ArrayList<>();
            try (PreparedStatement ps = con.prepareStatement(
                    "SELECT id, category, original_name, file_path, file_type, file_size, uploaded_at "
                  + "FROM student_files WHERE student_id = ? ORDER BY uploaded_at")) {
                ps.setInt(1, id);
                try (ResultSet rs = ps.executeQuery()) {
                    while (rs.next()) {
                        StudentFile f = new StudentFile();
                        f.setId(rs.getInt("id"));
                        f.setCategory(rs.getString("category"));
                        f.setOriginalName(rs.getString("original_name"));
                        f.setFilePath(rs.getString("file_path"));
                        f.setFileType(rs.getString("file_type"));
                        f.setFileSize(rs.getLong("file_size"));
                        f.setUploadedAt(rs.getString("uploaded_at"));
                        files.add(f);
                    }
                }
            }

            request.setAttribute("student", student);
            request.setAttribute("files", files);
            request.getRequestDispatcher("/WEB-INF/views/profile.jsp").forward(request, response);

        } catch (SQLException e) {
            response.sendError(HttpServletResponse.SC_INTERNAL_SERVER_ERROR, "Database error: " + e.getMessage());
        }
    }
}
