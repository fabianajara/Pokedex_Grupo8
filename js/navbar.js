document.addEventListener("DOMContentLoaded", function () {
    const loginButton = document.getElementById("loginButton");
    const registerButton = document.getElementById("registerButton");
    const userSection = document.getElementById("userSection");
    const userName = document.getElementById("userName");
    const userImage = document.getElementById("userImage");
    const logoutButton = document.getElementById("logoutButton");

    // Obtener datos del usuario desde localStorage
    const user = JSON.parse(localStorage.getItem("usuario"));

    if (user) {
        // Eliminar los botones de login y registro
        loginButton.style.display = "none";
        registerButton.style.display = "none";

        // Mostrar el nombre y la imagen del usuario
        userName.textContent = `Hola, ${user.nombre}`;
        userImage.src = user.imagen || 'https://static.vecteezy.com/system/resources/previews/021/548/095/original/default-profile-picture-avatar-user-avatar-icon-person-icon-head-icon-profile-picture-icons-default-anonymous-user-male-and-female-businessman-photo-placeholder-social-network-avatar-portrait-free-vector.jpg';
        
        // Mostrar la sección de usuario
        userSection.style.display = "flex";

        // Manejar el evento de cierre de sesión
        logoutButton.addEventListener("click", function () {
            localStorage.removeItem("usuario"); // Eliminar los datos del usuario
            window.location.reload(); // Recargar la página para actualizar la interfaz
        });
    } else {
        // Si no hay usuario logueado, mostrar los botones de login y registro
        loginButton.style.display = "inline";
        registerButton.style.display = "inline";
    }
});
