<%@ page contentType="text/html;charset=UTF-8" import="java.util.*, com.portal.*" %>
<%
    List<Student> students = (List<Student>) request.getAttribute("students");
    String error = (String) request.getAttribute("error");
    String user = (String) session.getAttribute("user");
%>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Students - Student Portal</title>
  <link rel="stylesheet" href="<%= request.getContextPath() %>/style.css">
</head>
<body>
<div class="navbar">
  <h2>Student Portal</h2>
  <div><span>Logged in as <b><%= Util.esc(user) %></b></span> <a href="logout">Logout</a></div>
</div>

<div class="container">
  <h3>All Students (<%= students.size() %>)</h3>

  <% if (error != null) { %>
    <div class="alert error"><%= Util.esc(error) %></div>
  <% } %>

  <% if (students.isEmpty()) { %>
    <div class="empty-msg">No students found. Add some using the Question 2 admin panel.</div>
  <% } else { %>
  <div class="grid">
    <% for (Student s : students) { %>
      <div class="card">
        <% if (s.getProfileFileId() > 0) { %>
          <img class="avatar" src="file?id=<%= s.getProfileFileId() %>" alt="Profile of <%= Util.esc(s.getName()) %>">
        <% } else { %>
          <div class="avatar empty">No photo</div>
        <% } %>
        <h3><%= Util.esc(s.getName()) %></h3>
        <p>Roll No: <%= Util.esc(s.getRollNo()) %></p>
        <p><%= Util.esc(s.getCourse()) %></p>
        <p><%= s.getFileCount() %> file<%= s.getFileCount() == 1 ? "" : "s" %> uploaded</p>
        <a class="btn" href="profile?id=<%= s.getId() %>">View Profile</a>
      </div>
    <% } %>
  </div>
  <% } %>
</div>
</body>
</html>
