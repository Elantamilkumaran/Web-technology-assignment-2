package com.portal;

import jakarta.servlet.ServletException;
import jakarta.servlet.annotation.WebServlet;
import jakarta.servlet.http.*;
import java.io.IOException;
import java.sql.*;

@WebServlet("/login")
public class LoginServlet extends HttpServlet {

    @Override
    protected void doGet(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        HttpSession session = request.getSession(false);
        if (session != null && session.getAttribute("user") != null) {
            response.sendRedirect("students");                    // already logged in
            return;
        }
        request.getRequestDispatcher("/WEB-INF/views/login.jsp").forward(request, response);
    }

    @Override
    protected void doPost(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        String username = request.getParameter("username");
        String password = request.getParameter("password");

        if (username == null || username.trim().isEmpty() || password == null || password.isEmpty()) {
            request.setAttribute("error", "Username and password are required.");
            request.getRequestDispatcher("/WEB-INF/views/login.jsp").forward(request, response);
            return;
        }

        String sql = "SELECT username FROM users WHERE username = ? AND password = SHA2(?, 256)";
        try (Connection con = DBUtil.getConnection();
             PreparedStatement ps = con.prepareStatement(sql)) {
            ps.setString(1, username.trim());
            ps.setString(2, password);
            try (ResultSet rs = ps.executeQuery()) {
                if (rs.next()) {
                    request.changeSessionId();                    // protects against session fixation
                    HttpSession session = request.getSession(true);
                    session.setAttribute("user", rs.getString("username"));
                    session.setMaxInactiveInterval(15 * 60);      // auto logout after 15 minutes
                    response.sendRedirect("students");
                    return;
                }
            }
            request.setAttribute("error", "Invalid username or password.");
        } catch (SQLException e) {
            request.setAttribute("error", "Database error: " + e.getMessage());
        }
        request.getRequestDispatcher("/WEB-INF/views/login.jsp").forward(request, response);
    }
}
