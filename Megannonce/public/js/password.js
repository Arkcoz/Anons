const togglePassword = document.querySelector("#togglePassword");
const password = document.querySelector("#registration_password");

const togglePassword_confirm = document.querySelector("#togglePassword_confirm");
const registration_confirm_password = document.querySelector("#registration_confirm_password");

togglePassword.addEventListener("click", function () {
    // toggle the type attribute
    const type = password.getAttribute("type") === "password" ? "text" : "password";
    password.setAttribute("type", type);
                    
    // toggle the icon
    this.classList.toggle("bi-eye");
});


togglePassword_confirm.addEventListener("click", function () {
    // toggle the type attribute
    const type = registration_confirm_password.getAttribute("type") === "password" ? "text" : "password";
    registration_confirm_password.setAttribute("type", type);
                    
    // toggle the icon
    this.classList.toggle("bi-eye");
});