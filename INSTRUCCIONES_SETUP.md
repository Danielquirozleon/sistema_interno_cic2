# 🔧 Instrucciones de Configuración - Sistema Interno CIC2

## ✅ Cambios Realizados

### 1. **AuthController.php - CORREGIDO**
- ✅ Ruta de include corregida: `__DIR__ . "/../config/database.php`"
- ✅ Agregada validación de conexión a BD
- ✅ Rutas de redirección actualizadas a rutas absolutas

## 📋 Pasos Siguientes

### 1. **Crear la Base de Datos**
Ejecuta el archivo `database_setup.sql` en tu cliente MySQL:

```bash
mysql -u root < database_setup.sql
```

O copia y pega el contenido en phpMyAdmin.

### 2. **Verificar Conexión MySQL**
Asegúrate que:
- ✅ MySQL está corriendo (`sudo systemctl start mysql` en Linux)
- ✅ Usuario `root` existe con contraseña vacía
- ✅ Base de datos `sistema_interno` fue creada

### 3. **Probar el Login**
- Usuario: `admin`
- Contraseña: `admin123`

### 4. **Estructura de Carpetas**
La estructura debe ser:
```
sistema_interno ERROR/
├── index.php
├── ruta.php
├── generar.php
├── config/
│   ├── database.php (SIN CAMBIOS)
│   └── constants.php
├── controllers/
│   ├── AuthController.php (✅ ACTUALIZADO)
│   └── ...otros controllers
├── views/
│   ├── auth/
│   │   └── login.php
│   ├── dashboard.php
│   └── ...otras vistas
└── ...otras carpetas
```

## 🐛 Si aún tienes problemas

1. **"No se encuentra el archivo"**
   - Verifica que la ruta `sistema_interno ERROR/config/database.php` existe

2. **"No se pudo conectar a la base de datos"**
   - Verifica que MySQL está corriendo
   - Ejecuta `database_setup.sql`
   - Revisa credenciales en `config/database.php`

3. **"El usuario no existe"**
   - Verifica que la tabla `usuarios` tiene al menos el usuario `admin`
   - Ejecuta: `SELECT * FROM usuarios;` en MySQL

## 📝 Notas de Seguridad

- ⚠️ La carpeta se llama "ERROR" - considera renombrarla a algo más apropiado
- ⚠️ Los archivos `generar.php` y `ruta.php` deberían estar en `.gitignore`
- ⚠️ Nunca guardes contraseñas en texto plano (ahora usa hash)