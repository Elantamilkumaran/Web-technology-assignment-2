package com.portal;

import jakarta.servlet.*;
import jakarta.servlet.annotation.WebFilter;
import jakarta.servlet.http.*;
import java.io.IOException;

/** Session handling: only logged-in users can reach these URLs. */
@WebFilter(urlPatterns = {"/students", "/profile", "/file"})
public class AuthFilter implements Filter {

    @Override
    public void doFilter(ServletRequest req, ServletResponse res, FilterChain chain)
            throws IOException, ServletException {
        HttpServletRequest request = (HttpServletRequest) req;
        HttpServletResponse response = (HttpServletResponse) res;

        HttpSession session = request.getSession(false);          // do not create a new one
        boolean loggedIn = session != null && session.getAttribute("user") != null;

        if (!loggedIn) {
            response.sendRedirect(request.getContextPath() + "/login?msg=Please+login+first");
            return;
        }
        // Stop the browser caching pages, so "Back" after logout shows nothing private
        response.setHeader("Cache-Control", "no-store");
        chain.doFilter(req, res);
    }
}
