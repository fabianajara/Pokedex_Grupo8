let open = false;

// Animación para la barra de búsqueda
document.querySelector(".search-container img").addEventListener("click", () => {
    const inputField = document.querySelector(".search-container input");

    if (!open) {
        inputField.classList.add('active'); // Aparece el input
        document.querySelector(".search-container img").style.transform = "rotate(360deg)"; // Gira el icono
        open = true;
    } else {
        inputField.classList.remove('active'); // Se oculta el input
        document.querySelector(".search-container img").style.transform = "rotate(0deg)"; // Restaura el icono
        open = false;
    }
});

// Función para buscar usuarios por nombre
function buscarUsuarios() {
    let searchInput = document.getElementById('searchInput').value;

    // Limpiar mensajes previos
    document.getElementById('message').innerHTML = '';
    document.getElementById('error').innerHTML = '';

    if (!searchInput.trim()) {
        document.getElementById('error').innerHTML = 'Por favor ingresa un nombre o username para buscar.';
        return;
    }

    fetch('http://localhost:8000/backend/buscar_usuario.php', {
        method: 'POST',
        body: new URLSearchParams({
            'nombre_usuario': searchInput
        })
    })
        .then(response => response.json())
        .then(data => {
            if (data.exito) {
                mostrarUsuarios(data.usuarios);
            } else {
                document.getElementById('error').innerHTML = data.mensaje;
                document.getElementById('userList').innerHTML = '';
            }
        })
        .catch(error => {
            document.getElementById('error').innerHTML = 'Hubo un error al buscar usuarios.';
            console.error(error);
        });
}

// Mostrar los usuarios encontrados en la interfaz
function mostrarUsuarios(usuarios) {
    const userList = document.getElementById('userList');
    userList.innerHTML = '';  // Limpiar la lista anterior

    usuarios.forEach(usuario => {
        // Crear una card para cada usuario
        const userCard = document.createElement('div');
        userCard.classList.add('col-sm-6', 'col-lg-4');  // Clases de Bootstrap para responsividad

        userCard.innerHTML = `
                    <div class="card">
                        <div class="card-body text-center border-bottom">
                            <!-- Imagen de perfil del usuario -->
                            <img src="${usuario.usuario_imagen}" alt="Avatar de ${usuario.nombre}" class="rounded-circle mb-3" width="80" height="80">
                            <h5 class="fw-semibold mb-0">${usuario.nombre}</h5>
                            <span class="text-dark">@${usuario.username}</span>
                        </div>
                        <ul class="list-unstyled d-flex justify-content-center mb-0">
                            <li class="position-relative">
                                <a class="text-primary p-2" href="javascript:void(0)">
                                    <i class="ti ti-brand-facebook"></i>
                                </a>
                            </li>
                            <li class="position-relative">
                                <a class="text-danger p-2" href="javascript:void(0)">
                                    <i class="ti ti-brand-instagram"></i>
                                </a>
                            </li>
                            <li class="position-relative">
                                <a class="text-info p-2" href="javascript:void(0)">
                                    <i class="ti ti-brand-github"></i>
                                </a>
                            </li>
                            <li class="position-relative">
                                <a class="text-secondary p-2" href="javascript:void(0)">
                                    <i class="ti ti-brand-twitter"></i>
                                </a>
                            </li>
                        </ul>
                        <div class="card-body text-center">
                            <button class="btn btn-success" onclick="enviarSolicitud(${usuario.id_usuario})">Enviar solicitud de amistad</button>
                        </div>
                    </div>
                `;

        userList.appendChild(userCard);  // Agregar la card al contenedor
    });
}

// Función para enviar una solicitud de amistad
function enviarSolicitud(idAmigo) {
    fetch('http://localhost:8000/backend/enviar_solicitud.php', {
        method: 'POST',
        body: new URLSearchParams({
            'id_amigo': idAmigo
        })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('message').innerHTML = data.message;
            } else {
                document.getElementById('error').innerHTML = data.message;
            }
        })
        .catch(error => {
            document.getElementById('error').innerHTML = 'Hubo un error al enviar la solicitud de amistad.';
            console.error(error);
        });
}

// Llamar la función de buscar cuando se presiona Enter
document.getElementById('searchInput').addEventListener('keypress', function (e) {
    if (e.key === 'Enter') {
        buscarUsuarios();
    }
});