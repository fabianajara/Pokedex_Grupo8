document.addEventListener("DOMContentLoaded", function () {
    const pokemonesContainer = document.getElementById("pokemonesContainer");

    // Función para obtener los Pokémon desde el backend
    function obtenerPokemones() {
        fetch('http://localhost:8000/backend/pokemon_crud.php')  // La URL de tu archivo PHP
            .then(response => response.json())  // Parsear la respuesta como JSON
            .then(pokemones => {
                // Limpiar contenedor antes de agregar nuevas filas
                pokemonesContainer.innerHTML = '';

                // Si se obtienen pokemones, mostrar en la tabla
                if (Array.isArray(pokemones) && pokemones.length > 0) {
                    pokemones.forEach(pokemon => {
                        const row = document.createElement("tr");

                        row.innerHTML = `
                            <td><input type="text" id="nombre_${pokemon.id}" value="${pokemon.nombre}"></td>
                            <td><input type="text" id="tipo_${pokemon.id}" value="${pokemon.tipo}"></td>
                            <td><input type="number" id="peso_${pokemon.id}" value="${pokemon.peso}"></td>
                            <td><input type="text" id="tamano_${pokemon.id}" value="${pokemon.tamano}"></td>
                            <td><img src="${pokemon.foto}" alt="${pokemon.nombre}" width="50"></td>
                            <td>
                                <button class="btn btn-warning btn-sm" onclick="guardarEdicion(${pokemon.id})">Guardar cambios</button>
                                <button class="btn btn-danger btn-sm" onclick="eliminarPokemon(${pokemon.id})">Eliminar</button>
                            </td>
                        `;

                        pokemonesContainer.appendChild(row);
                    });
                } else {
                    pokemonesContainer.innerHTML = "<tr><td colspan='6'>No se encontraron Pokémon.</td></tr>";
                }
            })
            .catch(error => {
                console.error('Error al obtener los Pokémon:', error);
                pokemonesContainer.innerHTML = "<tr><td colspan='6'>Error al cargar los Pokémon.</td></tr>";
            });
    }

    // Función para guardar la edición de un Pokémon
    window.guardarEdicion = function (id) {
        const nombre = document.getElementById(`nombre_${id}`).value;
        const tipo = document.getElementById(`tipo_${id}`).value;
        const peso = document.getElementById(`peso_${id}`).value;
        const tamano = document.getElementById(`tamano_${id}`).value;

        // Verificar que los campos no estén vacíos
        if (!nombre || !tipo || !peso || !tamano) {
            alert("Todos los campos son requeridos.");
            return;
        }

        // Crear un objeto con los datos del Pokémon
        const pokemonData = {
            id,
            nombre,
            tipo,
            peso,
            tamano
        };

        // Realizar la solicitud PUT para actualizar el Pokémon
        fetch('http://localhost:8000/backend/pokemon_crud.php', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'  // Asegúrate de enviar JSON
            },
            body: JSON.stringify(pokemonData)  // Enviar los datos en formato JSON
        })
            .then(response => {
                if (response.ok) {
                    return response.json();
                } else {
                    throw new Error("Error al actualizar el Pokémon");
                }
            })
            .then(data => {
                alert(data.mensaje);
                if (data.mensaje.includes('actualizado')) {
                    obtenerPokemones();  // Volver a cargar la lista de Pokémon
                }
            })
            .catch(error => {
                console.error('Error al editar el Pokémon:', error);
            });
    };

    // Función para eliminar un Pokémon
    window.eliminarPokemon = function (id) {
        if (confirm('¿Estás seguro de que quieres eliminar este Pokémon?')) {
            fetch('http://localhost:8000/backend/pokemon_crud.php', {
                method: 'DELETE',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ id })
            })
                .then(response => response.json())
                .then(data => {
                    alert(data.mensaje);
                    obtenerPokemones();  // Volver a cargar la lista de Pokémon
                })
                .catch(error => {
                    console.error('Error al eliminar el Pokémon:', error);
                });
        }
    };

    // Obtener los Pokémon cuando cargue la página
    obtenerPokemones();
});