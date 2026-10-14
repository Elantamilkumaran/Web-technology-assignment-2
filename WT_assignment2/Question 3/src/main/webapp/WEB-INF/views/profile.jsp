<%@ page contentType="text/html;charset=UTF-8" import="java.util.*, com.portal.*" %>
<%
    Student s = (Student) request.getAttribute("student");
    List<StudentFile> files = (List<StudentFile>) request.getAttribute("files");
    String user = (String) session.getAttribute("user");

    // find the profile image (if any)
    StudentFile profile = null;
    for (StudentFile f : files) {
        if ("profile".equals(f.getCategory())) { profile = f; break; }
    }
%>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><%= Util.esc(s.getName()) %> - Student Portal</title>
  <link rel="stylesheet" href="<%= request.getContextPath() %>/style.css">
</head>
<body>
<div class="navbar">
  <h2>Student Portal</h2>
  <div><span>Logged in as <b><%= Util.esc(user) %></b></span> <a href="students">All Students</a> <a href="logout">Logout</a></div>
</div>

<div class="container">
  <h3>Student Profile</h3>
  <div class="panel profile">
    <% if (profile != null) { %>
      <img class="avatar" src="file?id=<%= profile.getId() %>" alt="Profile image">
    <% } else { %>
      <div class="avatar empty">No photo</div>
    <% } %>
    <div>
      <p><b>Name:</b> <%= Util.esc(s.getName()) %></p>
      <p><b>Roll No:</b> <%= Util.esc(s.getRollNo()) %></p>
      <p><b>Email:</b> <%= Util.esc(s.getEmail()) %></p>
      <p><b>Course:</b> <%= Util.esc(s.getCourse()) %></p>
    </div>
  </div>

  <h3>Documents</h3>
  <div class="panel">
    <%
        int docCount = 0;
        for (StudentFile f : files) { if (!"profile".equals(f.getCategory())) docCount++; }
    %>
    <% if (docCount == 0) { %>
      <div class="empty-msg">No documents uploaded for this student.</div>
    <% } else { %>
      <div class="files">
        <% for (StudentFile f : files) {
             if ("profile".equals(f.getCategory())) continue; %>
          <div class="file">
            <% if (f.isImage()) { %>
              <a href="file?id=<%= f.getId() %>" target="_blank">
                <img src="file?id=<%= f.getId() %>" alt="<%= Util.esc(f.getOriginalName()) %>">
              </a>
            <% } else { %>
              <div class="pdf">PDF</div>
            <% } %>
            <p><b><%= Util.esc(f.getCategory().replace('_', ' ').toUpperCase()) %></b></p>
            <p><%= Util.esc(f.getOriginalName()) %></p>
            <p><%= String.format("%.1f", f.getFileSize() / 1024.0) %> KB</p>
            <a class="btn" href="file?id=<%= f.getId() %>" target="_blank">Open</a>
            <a class="btn gray" href="file?id=<%= f.getId() %>&download=1">Download</a>
          </div>
        <% } %>
      </div>
    <% } %>
  </div>
  <a class="btn gray" href="students">&larr; Back to list</a>
</div>
</body>
</html>
