# 🚀 Proyecto Laravel Dra back

---

## 📦 Requisitos

Antes de comenzar, asegúrate de tener instalado:

* [PHP 8.2+](https://www.php.net/)
* [Composer](https://getcomposer.org/)
* [Node.js 18+](https://nodejs.org/)
* [MySQL o PostgreSQL](https://www.mysql.com/)

---

## ⚙️ Instalación

Sigue estos pasos para correr el proyecto en una nueva máquina:

1. **Clonar el repositorio**

   ```bash
   git clone https://github.com/TU_USUARIO/TU_REPOSITORIO.git
   cd TU_REPOSITORIO
   ```

2. **Instalar dependencias de PHP**

   ```bash
   composer install
   ```

3. **Instalar dependencias de Node (si usas Vite/Tailwind)**

   ```bash
   npm install
   ```

4. **Configurar archivo `.env`**

   * Copia el archivo de ejemplo:

     ```bash
     cp .env.example .env
     ```
   * Edita el archivo `.env` y configura:

     ```env
     DB_DATABASE=nombre_base_datos
     DB_USERNAME=usuario
     DB_PASSWORD=contraseña

     JWT_SECRET=clave_super_secreta
     ```

5. **Generar clave de Laravel**

   ```bash
   php artisan key:generate
   ```

6. **Ejecutar migraciones y seeders**

   ```bash
   php artisan migrate --seed  o
   php artisan db:seed --class=RolesPermisosSeeder
   ```

7. **Levantar el servidor**

   ```bash
   php artisan serve
   ```

   El sistema estará disponible en:
   👉 `http://127.0.0.1:8000`

8. **(Opcional) Levantar Vite**

   ```bash
   npm run dev
   ```

---

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
