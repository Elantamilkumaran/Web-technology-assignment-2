package com.portal;

import jakarta.servlet.annotation.WebServlet;
import jakarta.servlet.http.*;
import java.io.*;
import java.sql.*;

/**
 * Streams an uploaded image/document from the upload folder to the browser.
 * Usage:  file?id=5   (shows in browser)   |   file?id=5&download=1  (downloads)
 * The browser only sends the file id - the real path is looked up in MySQL,
 * so users cannot ask for any other file on the server.
 */
@WebServlet("/file")
public class FileServlet extends HttpServlet {

    @Override
    protected void doGet(HttpServletRequest request, HttpServletResponse response) throws IOException {
        int id;
        try {
            id = Integer.parseInt(request.getParameter("id"));
        } catch (NumberFormatException e) {
            response.sendError(HttpServletResponse.SC_BAD_REQUEST, "Invalid file id");
            return;
        }

        String path = null, type = null, name = null;
        try (Connection con = DBUtil.getConnection();
             PreparedStatement ps = con.prepareStatement(
                     "SELECT file_path, file_type, original_name FROM student_files WHERE id = ?")) {
            ps.setInt(1, id);
            try (ResultSet rs = ps.executeQuery()) {
                if (rs.next()) {
                    path = rs.getString("file_path");
                    type = rs.getString("file_type");
                    name = rs.getString("original_name");
                }
            }
        } catch (SQLException e) {
            response.sendError(HttpServletResponse.SC_INTERNAL_SERVER_ERROR, "Database error");
            return;
        }

        if (path == null) {
            response.sendError(HttpServletResponse.SC_NOT_FOUND, "File record not found");
            return;
        }

        // Use only the file name from the DB path, inside the upload folder
        File file = new File(DBUtil.UPLOAD_DIR, new File(path).getName());
        if (!file.exists() || !file.isFile()) {
            response.sendError(HttpServletResponse.SC_NOT_FOUND, "File missing on disk");
            return;
        }

        boolean download = "1".equals(request.getParameter("download"));
        response.setContentType(type);
        response.setContentLengthLong(file.length());
        response.setHeader("X-Content-Type-Options", "nosniff");
        response.setHeader("Content-Disposition",
                (download ? "attachment" : "inline") + "; filename=\"" + name.replace("\"", "") + "\"");

        // Stream the file in small chunks
        try (InputStream in = new FileInputStream(file);
             OutputStream out = response.getOutputStream()) {
            byte[] buffer = new byte[4096];
            int n;
            while ((n = in.read(buffer)) != -1) {
                out.write(buffer, 0, n);
            }
        }
    }
}
