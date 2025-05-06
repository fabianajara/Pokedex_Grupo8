# 🧢 Pokédex App

Una aplicación web interactiva de Pokédex desarrollada con **PHP**, **HTML**, **CSS** y **Bootstrap**, donde los usuarios pueden crear perfiles, agregar Pokémon a sus equipos y conectarse con otros entrenadores. La app incluye control de acceso por roles, lo que permite mostrar distintas funcionalidades y pantallas según el tipo de usuario.

## 📽️ Vista previa

![App Demo](gifReadme/pokedex.gif) <!-- Reemplaza con la ruta real del GIF -->

---

## 🔑 Roles de usuario

La aplicación cuenta con distintos roles, cada uno con permisos específicos:

- **Entrenador** 🧑‍🎓  
  - Crear y gestionar su equipo de Pokémon  
  - Agregar amigos  
  - Visualizar su perfil y los de sus amigos  

- **Enfermero** 🧑‍⚕️  
  - Acceso a información de cuidado de Pokémon  
  - Visualización parcial de datos de entrenadores  

- **Administrador** 🧑‍💼  
  - Agregar y eliminar Pokémon de la base de datos  
  - Ver todos los usuarios y equipos  
  - Acceso completo a todas las funciones del sistema  

---

## ⚙️ Funcionalidades principales

- 🔐 **Registro e inicio de sesión con manejo de sesiones en PHP**
- 👥 **Sistema de amigos entre entrenadores**
- 🧳 **Gestión de equipos Pokémon por usuario**
- 📄 **Vistas dinámicas según el rol**
- 🧾 **Panel de administración para gestión global**

---

## 🛠️ Tecnologías utilizadas

- **HTML5** – Maquetación de la estructura de la aplicación  
- **CSS3** – Estilos personalizados  
- **Bootstrap** – Diseño responsive y componentes visuales  
- **PHP** – Lógica del servidor y manejo de roles/sesiones  
- **MySQL** – Base de datos para usuarios, roles y Pokémon  

---

## 🚀 Instalación y uso

1. Clona el repositorio:
   ```bash
   git clone https://github.com/tu-usuario/pokedex-app.git
   cd pokedex-app
