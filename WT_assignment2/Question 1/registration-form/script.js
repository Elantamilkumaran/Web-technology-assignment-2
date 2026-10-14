const form = document.getElementById("regForm");

function setError(id, message) {
  const input = document.getElementById(id);
  document.getElementById(id + "Error").textContent = message;
  input.classList.toggle("invalid", message !== "");
  input.classList.toggle("valid", message === "");
  return message === "";
}

function validateName() {
  const v = document.getElementById("name").value.trim();
  if (v === "") return setError("name", "Name is required.");
  if (!/^[A-Za-z ]{3,}$/.test(v)) return setError("name", "Use letters only (min 3 characters).");
  return setError("name", "");
}

function validateRoll() {
  const v = document.getElementById("roll").value.trim();
  if (v === "") return setError("roll", "Roll number is required.");
  if (!/^[A-Za-z0-9]{5,12}$/.test(v)) return setError("roll", "Enter 5-12 letters/digits.");
  return setError("roll", "");
}

function validateEmail() {
  const v = document.getElementById("email").value.trim();
  if (v === "") return setError("email", "Email is required.");
  if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v)) return setError("email", "Enter a valid email address.");
  return setError("email", "");
}

function validateCourse() {
  if (document.getElementById("course").value === "")
    return setError("course", "Please select a course.");
  return setError("course", "");
}

function validatePassword() {
  const v = document.getElementById("password").value;
  if (v === "") return setError("password", "Password is required.");
  if (v.length < 8) return setError("password", "At least 8 characters required.");
  if (!/[A-Z]/.test(v)) return setError("password", "Add an uppercase letter.");
  if (!/[a-z]/.test(v)) return setError("password", "Add a lowercase letter.");
  if (!/[0-9]/.test(v)) return setError("password", "Add a number.");
  if (!/[^A-Za-z0-9]/.test(v)) return setError("password", "Add a special character.");
  return setError("password", "");
}

function validateFile() {
  const f = document.getElementById("file").files[0];
  if (!f) return setError("file", "Please upload a file.");
  const allowed = ["application/pdf", "image/jpeg", "image/png"];
  if (!allowed.includes(f.type)) return setError("file", "Only PDF, JPG or PNG allowed.");
  if (f.size > 2 * 1024 * 1024) return setError("file", "File must be under 2MB.");
  return setError("file", "");
}

// Live validation
document.getElementById("name").addEventListener("input", validateName);
document.getElementById("roll").addEventListener("input", validateRoll);
document.getElementById("email").addEventListener("input", validateEmail);
document.getElementById("course").addEventListener("change", validateCourse);
document.getElementById("password").addEventListener("input", validatePassword);
document.getElementById("file").addEventListener("change", validateFile);

form.addEventListener("submit", function (e) {
  e.preventDefault();
  const ok = [validateName(), validateRoll(), validateEmail(),
              validateCourse(), validatePassword(), validateFile()].every(Boolean);
  const msg = document.getElementById("successMsg");
  if (ok) {
    msg.textContent = "Registration successful!";
    form.reset();
    document.querySelectorAll(".valid").forEach(el => el.classList.remove("valid"));
  } else {
    msg.textContent = "";
  }
});
