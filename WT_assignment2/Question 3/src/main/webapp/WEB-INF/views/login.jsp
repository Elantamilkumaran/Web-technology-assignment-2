<%@ page contentType="text/html;charset=UTF-8" import="com.portal.Util" %>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Student Portal</title>
  <link rel="stylesheet" href="<%= request.getContextPath() %>/style.css">
</head>
<body>
<div class="login">
  <h2>Student Portal Login</h2>

  <% String error = (String) request.getAttribute("error");
     String msg = request.getParameter("msg"); %>
  <% if (error != null) { %>
    <div class="alert error"><%= Util.esc(error) %></div>
  <% } else if (msg != null) { %>
    <div class="alert info"><%= Util.esc(msg) %></div>
  <% } %>

  <form method="post" action="login">
    <label>Username</label>
    <input type="text" name="username" required>
    <label>Password</label>
    <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>
  <p class="hint">Demo login: admin / admin123</p>
</div>
</body>
</html>
