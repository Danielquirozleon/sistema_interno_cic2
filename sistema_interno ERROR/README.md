# 🛡️ Sistema de Gestión de Procesos Internos

Un sistema robusto desarrollado en **PHP 8.x** bajo la arquitectura **MVC** (Modelo-Vista-Controlador), diseñado para automatizar flujos de trabajo, trámites y gestión de usuarios.

## 🚀 Características
- **Arquitectura MVC:** Separación clara entre lógica de negocio y vistas.
- **Flujos Dinámicos:** Creación de procesos con pasos y responsables configurables.
- **Modo Dark Nativo:** Interfaz adaptable con persistencia en `localStorage`.
- **Seguridad:** Control de acceso basado en roles (RBAC).
- **Responsive:** Construido con Bootstrap 5.3 e iconos de FontAwesome.

## 🛠️ Instalación en XAMPP
1. Clona este repositorio en `C:\xampp\htdocs\`.
2. Importa el archivo `database.sql` (incluido en la carpeta SQL) en tu **phpMyAdmin**.
3. Configura tus credenciales en `config/database.php`.
4. Accede desde tu navegador a `http://localhost/sistema_interno/`.

## 📂 Estructura del Proyecto
- `controllers/`: Lógica de control y peticiones.
- `models/`: Interacción con la base de datos (PDO).
- `views/`: Interfaces de usuario y layouts.
- `assets/`: Recursos estáticos (CSS, JS, Imágenes).