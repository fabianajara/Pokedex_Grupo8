document.addEventListener("DOMContentLoaded", function () {
  // Obtener los elementos del DOM
  const loginButton = document.getElementById("loginButton");
  const registerButton = document.getElementById("registerButton");
  const userSection = document.getElementById("userSection");
  const userName = document.getElementById("userName");
  const userImage = document.getElementById("userImage");
  const logoutButton = document.getElementById("logoutButton");
  const pokemonesButton = document.getElementById("pokemonesButton");

  // Obtener los datos del usuario desde localStorage
  const user = JSON.parse(localStorage.getItem("usuario"));

  // Ocultar o mostrar elementos según si el usuario está logueado
  if (user) {
    // Si el usuario está logueado
    loginButton.classList.add("hidden");
    registerButton.classList.add("hidden");

    // Mostrar la sección de usuario
    userSection.classList.remove("hidden");
    userName.textContent = `Hola, ${user.nombre}`;
    userImage.src = user.imagen || 'https://static.vecteezy.com/system/resources/previews/021/548/095/original/default-profile-picture-avatar-user-avatar-icon-person-icon-head-icon-profile-picture-icons-default-anonymous-user-male-and-female-businessman-photo-placeholder-social-network-avatar-portrait-free-vector.jpg';

    // Mostrar el botón de "Pokemones"
    pokemonesButton.classList.remove("hidden");

    // Manejar el evento de cierre de sesión
    logoutButton.addEventListener("click", function () {
      localStorage.removeItem("usuario"); // Eliminar los datos del usuario
      window.location.reload(); // Recargar la página para actualizar la interfaz
    });
  } else {
    // Si el usuario no está logueado
    loginButton.classList.remove("hidden");
    registerButton.classList.remove("hidden");

    // Asegurarse de que la sección del usuario esté oculta
    userSection.classList.add("hidden");

    // Asegurarse de que el botón de "Pokemones" esté oculto
    pokemonesButton.classList.add("hidden");
  }
});