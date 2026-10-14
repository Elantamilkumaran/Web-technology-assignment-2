package com.portal;

/** Small helper used by the JSP pages. */
public class Util {
    // Escapes HTML so database text cannot inject scripts into the page
    public static String esc(String s) {
        if (s == null) return "";
        return s.replace("&", "&amp;").replace("<", "&lt;").replace(">", "&gt;")
                .replace("\"", "&quot;").replace("'", "&#39;");
    }
}
