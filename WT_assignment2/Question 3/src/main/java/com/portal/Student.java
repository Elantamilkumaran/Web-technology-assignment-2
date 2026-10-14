package com.portal;

public class Student {
    private int id;
    private String name, rollNo, email, course;
    private int profileFileId;   // id of the profile image in student_files (0 = none)
    private int fileCount;

    public int getId() { return id; }
    public void setId(int id) { this.id = id; }
    public String getName() { return name; }
    public void setName(String name) { this.name = name; }
    public String getRollNo() { return rollNo; }
    public void setRollNo(String rollNo) { this.rollNo = rollNo; }
    public String getEmail() { return email; }
    public void setEmail(String email) { this.email = email; }
    public String getCourse() { return course; }
    public void setCourse(String course) { this.course = course; }
    public int getProfileFileId() { return profileFileId; }
    public void setProfileFileId(int profileFileId) { this.profileFileId = profileFileId; }
    public int getFileCount() { return fileCount; }
    public void setFileCount(int fileCount) { this.fileCount = fileCount; }
}
