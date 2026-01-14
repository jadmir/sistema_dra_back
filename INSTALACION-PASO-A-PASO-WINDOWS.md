# 🪟 Instalación Paso a Paso - Windows Server

**Sistema DRA Backend - Guía de Instalación Manual**  
**Fecha:** 8 de enero de 2026  
**Tiempo estimado:** 60-90 minutos  
**Nivel:** Intermedio

---

## 📋 RESUMEN DE PROGRAMAS A INSTALAR

| # | Programa | Versión | Obligatorio |
|---|----------|---------|-------------|
| 1 | **PHP** | 8.2+ Thread Safe | ✅ Sí |
| 2 | **Composer** | 2.x | ✅ Sí |
| 3 | **MySQL** | 8.0+ | ✅ Sí |
| 4 | **IIS** | 10+ | ✅ Sí |
| 5 | **URL Rewrite Module** | 2.1 | ✅ Sí |
| 6 | **Visual C++ Redistributable** | 2015-2022 | ✅ Sí (para PHP) |
| 7 | **Git** | Latest | 🔵 Opcional |
| 8 | **Notepad++** | Latest | 🔵 Opcional |

---

## 🚀 PARTE 1: INSTALACIÓN DE SOFTWARE

### **PASO 1: Instalar IIS (Internet Information Services)**

#### 1.1 Abrir PowerShell como Administrador
```
- Click derecho en el menú Inicio
- Seleccionar "Windows PowerShell (Administrador)"
```

#### 1.2 Instalar IIS con características necesarias
```powershell
# Ejecutar este comando:
Install-WindowsFeature -name Web-Server -IncludeManagementTools

# Esperar a que termine (2-5 minutos)
```

#### 1.3 Verificar instalación
```powershell
# Abrir navegador y visitar:
http://localhost

# Debería mostrar la página por defecto de IIS
```

**✅ IIS instalado correctamente**

---

### **PASO 2: Instalar Visual C++ Redistributable**

⚠️ **IMPORTANTE:** PHP requiere estas librerías para funcionar.

#### 2.1 Descargar
```
URL: https://aka.ms/vs/17/release/vc_redist.x64.exe
```

#### 2.2 Instalar
```
1. Ejecutar el instalador descargado
2. Aceptar términos y condiciones
3. Click "Install"
4. Esperar a que termine
5. Reiniciar si lo solicita
```

**✅ Visual C++ instalado correctamente**

---

### **PASO 3: Instalar PHP 8.2**

#### 3.1 Descargar PHP
```
URL: https://windows.php.net/download/

Buscar: "PHP 8.2.x Thread Safe (x64)"
Ejemplo: php-8.2.14-Win32-vs16-x64.zip
```

⚠️ **MUY IMPORTANTE:** Descargar la versión **Thread Safe**, NO la "Non Thread Safe"

#### 3.2 Extraer archivos
```
1. Descargar el archivo .zip
2. Crear carpeta: C:\PHP
3. Extraer TODO el contenido del .zip en C:\PHP
4. Verificar que existe: C:\PHP\php.exe
```

#### 3.3 Configurar php.ini
```
1. Ir a: C:\PHP
2. Buscar archivo: php.ini-production
3. Copiar y renombrar a: php.ini
4. Abrir php.ini con Notepad o Notepad++
```

#### 3.4 Editar php.ini - Activar extensiones
Buscar estas líneas (están comentadas con `;`) y **quitar el punto y coma**:

```ini
# BUSCAR Y DESCOMENTAR (quitar el ;):

;extension=curl          →   extension=curl
;extension=fileinfo      →   extension=fileinfo
;extension=gd            →   extension=gd
;extension=mbstring      →   extension=mbstring
;extension=openssl       →   extension=openssl
;extension=pdo_mysql     →   extension=pdo_mysql
;extension=zip           →   extension=zip
;extension=sodium        →   extension=sodium

# También agregar al final del archivo:

[PHP]
memory_limit = 512M
upload_max_filesize = 20M
post_max_size = 20M
max_execution_time = 300
date.timezone = America/Lima
expose_php = Off
```

#### 3.5 Agregar PHP al PATH del sistema
```
1. Click derecho en "Este equipo" → Propiedades
2. Click en "Configuración avanzada del sistema"
3. Click en "Variables de entorno"
4. En "Variables del sistema", buscar "Path"
5. Click en "Editar"
6. Click en "Nuevo"
7. Agregar: C:\PHP
8. Click en "Aceptar" en todas las ventanas
9. CERRAR Y REABRIR PowerShell
```

#### 3.6 Verificar instalación de PHP
```powershell
# Abrir NUEVA ventana de PowerShell
php -v

# Debería mostrar algo como:
# PHP 8.2.14 (cli) (built: Dec 12 2023 15:09:25) (ZTS Visual C++ 2019 x64)
```

**✅ PHP 8.2 instalado y configurado**

---

### **PASO 4: Instalar URL Rewrite Module para IIS**

⚠️ **OBLIGATORIO:** Laravel requiere este módulo para que funcionen las rutas.

#### 4.1 Descargar
```
URL: https://www.iis.net/downloads/microsoft/url-rewrite

O directamente:
https://download.microsoft.com/download/1/2/8/128E2E22-C1B9-44A4-BE2A-5859ED1D4592/rewrite_amd64_en-US.msi
```

#### 4.2 Instalar
```
1. Ejecutar el instalador .msi
2. Click "Next"
3. Aceptar términos → Click "Next"
4. Click "Install"
5. Click "Finish"
```

#### 4.3 Verificar instalación
```
1. Abrir "Administrador de IIS" (IIS Manager)
2. Seleccionar el servidor (nombre de tu PC)
3. En el panel central, buscar el ícono "URL Rewrite"
4. Si aparece, está instalado correctamente
```

**✅ URL Rewrite instalado correctamente**

---

### **PASO 5: Instalar Composer**

#### 5.1 Descargar
```
URL: https://getcomposer.org/Composer-Setup.exe
```

#### 5.2 Instalar
```
1. Ejecutar Composer-Setup.exe
2. Click "Next"
3. Marcar "Add PHP to PATH" (si no está marcado)
4. En "Choose the command-line PHP", debería detectar: C:\PHP\php.exe
   - Si no lo detecta, seleccionar manualmente
5. Click "Next"
6. Click "Next" (proxy settings - dejar por defecto)
7. Click "Install"
8. Click "Finish"
9. CERRAR Y REABRIR PowerShell
```

#### 5.3 Verificar instalación
```powershell
# Abrir NUEVA ventana de PowerShell
composer --version

# Debería mostrar:
# Composer version 2.6.x
```

**✅ Composer instalado correctamente**

---

### **PASO 6: Instalar MySQL**

Tienes 2 opciones:

---

#### **OPCIÓN A: XAMPP (Más fácil - Recomendado para principiantes)**

##### 6.1 Descargar XAMPP
```
URL: https://www.apachefriends.org/download.html

Descargar: XAMPP para Windows (versión con PHP 8.x)
```

##### 6.2 Instalar XAMPP
```
1. Ejecutar el instalador
2. Seleccionar componentes:
   ✅ MySQL
   ✅ phpMyAdmin
   ❌ Apache (NO necesario, usamos IIS)
   ❌ FileZilla (opcional)
3. Carpeta de instalación: C:\xampp (por defecto)
4. Click "Next" → "Next" → "Install"
5. Click "Finish"
```

##### 6.3 Iniciar MySQL
```
1. Abrir "XAMPP Control Panel"
2. Click en "Start" junto a "MySQL"
3. MySQL debería mostrarse en verde
```

##### 6.4 Configurar contraseña de MySQL (IMPORTANTE)
```powershell
# Abrir PowerShell como Administrador
cd C:\xampp\mysql\bin

# Conectar a MySQL (sin contraseña inicialmente)
.\mysql.exe -u root

# Dentro de MySQL, ejecutar:
ALTER USER 'root'@'localhost' IDENTIFIED BY 'tu_password_segura_aqui';
FLUSH PRIVILEGES;
EXIT;
```

**✅ MySQL instalado con XAMPP**

---

#### **OPCIÓN B: MySQL Server (Instalación standalone)**

##### 6.1 Descargar MySQL
```
URL: https://dev.mysql.com/downloads/mysql/

Descargar: MySQL Installer for Windows
Archivo: mysql-installer-community-8.x.x.msi
```

##### 6.2 Instalar MySQL
```
1. Ejecutar el instalador
2. Seleccionar "Developer Default" o "Server only"
3. Click "Next" → "Execute" (instalar requisitos)
4. Click "Next"
5. Configuración:
   - Config Type: Development Computer
   - Port: 3306
   - Authentication: Use Strong Password Encryption
6. Establecer contraseña de root (¡IMPORTANTE! Guardarla)
7. Click "Next" → "Execute"
8. Click "Finish"
```

**✅ MySQL Server instalado**

---

### **PASO 7: Instalar Git (Opcional pero recomendado)**

#### 7.1 Descargar
```
URL: https://git-scm.com/download/win
```

#### 7.2 Instalar
```
1. Ejecutar el instalador
2. Click "Next" en todas las opciones (usar valores por defecto)
3. Click "Install"
4. Click "Finish"
```

#### 7.3 Verificar
```powershell
git --version
# Debería mostrar: git version 2.x.x
```

**✅ Git instalado (opcional)**

---

### **PASO 8: Instalar Notepad++ (Opcional pero útil)**

#### 8.1 Descargar
```
URL: https://notepad-plus-plus.org/downloads/
```

#### 8.2 Instalar
```
1. Ejecutar el instalador
2. Click "Next" → "I Agree" → "Next" → "Install"
3. Click "Finish"
```

**✅ Software base completamente instalado**

---

## 🗂️ PARTE 2: SUBIR Y CONFIGURAR EL PROYECTO

### **PASO 9: Subir archivos del proyecto**

#### 9.1 Crear carpeta del proyecto
```powershell
# Crear carpeta
New-Item -ItemType Directory -Path "C:\inetpub\wwwroot\sistema_dra_back" -Force
```

#### 9.2 Copiar archivos
```
Opciones:

A) Si tienes los archivos en un .zip:
   1. Descomprimir en: C:\inetpub\wwwroot\sistema_dra_back

B) Si usas Git:
   cd C:\inetpub\wwwroot
   git clone https://github.com/tu-usuario/sistema_dra_back.git

C) Si usas FTP/SFTP:
   - Usar FileZilla o WinSCP
   - Subir todos los archivos a: C:\inetpub\wwwroot\sistema_dra_back
```

#### 9.3 Verificar estructura
```powershell
cd C:\inetpub\wwwroot\sistema_dra_back
dir

# Deberías ver:
# app/
# bootstrap/
# config/
# database/
# public/
# routes/
# storage/
# vendor/ (si no existe, se creará después)
# artisan
# composer.json
# .env.example
```

**✅ Archivos subidos**

---

### **PASO 10: Crear base de datos**

#### 10.1 Conectar a MySQL
```powershell
# Si usas XAMPP:
cd C:\xampp\mysql\bin
.\mysql.exe -u root -p

# Si usas MySQL standalone:
mysql -u root -p

# Ingresar contraseña cuando la pida
```

#### 10.2 Crear base de datos y usuario
```sql
-- Dentro de MySQL, ejecutar estos comandos:

CREATE DATABASE sistema_dra CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE USER 'dra_user'@'localhost' IDENTIFIED BY 'DRA_2026_Segura!';

GRANT ALL PRIVILEGES ON sistema_dra.* TO 'dra_user'@'localhost';

FLUSH PRIVILEGES;

SHOW DATABASES;
-- Debería aparecer 'sistema_dra' en la lista

EXIT;
```

**✅ Base de datos creada**

---

### **PASO 11: Configurar archivo .env**

#### 11.1 Copiar archivo de ejemplo
```powershell
cd C:\inetpub\wwwroot\sistema_dra_back

# Copiar .env.example a .env
copy .env.example .env
```

#### 11.2 Editar archivo .env
```powershell
# Abrir con Notepad++
notepad++ .env

# O con Notepad normal
notepad .env
```

#### 11.3 Configurar valores importantes
```ini
# BUSCAR Y CAMBIAR ESTAS LÍNEAS:

APP_NAME="Sistema DRA"
APP_ENV=production
APP_KEY=                          # Se generará después
APP_DEBUG=false                   # ⚠️ IMPORTANTE: false en producción
APP_URL=http://tu-dominio.gob.pe  # O http://localhost si es prueba

LOG_CHANNEL=daily
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sistema_dra           # ← Nombre de la BD que creaste
DB_USERNAME=dra_user              # ← Usuario que creaste
DB_PASSWORD=DRA_2026_Segura!      # ← Contraseña que pusiste

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

JWT_SECRET=                       # Se generará después
JWT_TTL=60
JWT_REFRESH_TTL=20160
```

#### 11.4 Guardar el archivo
```
Ctrl + S (guardar)
Cerrar el editor
```

**✅ Archivo .env configurado**

---

### **PASO 12: Instalar dependencias de Composer**

#### 12.1 Navegar al proyecto
```powershell
cd C:\inetpub\wwwroot\sistema_dra_back
```

#### 12.2 Instalar dependencias
```powershell
composer install --no-dev --optimize-autoloader

# Esto puede tomar 5-10 minutos
# Descargará todas las librerías necesarias
```

Si hay error de memoria:
```powershell
php -d memory_limit=512M C:\ProgramData\ComposerSetup\bin\composer.phar install --no-dev
```

**✅ Dependencias instaladas**

---

### **PASO 13: Generar claves de seguridad**

#### 13.1 Generar APP_KEY
```powershell
php artisan key:generate

# Debería mostrar:
# Application key set successfully.
```

#### 13.2 Generar JWT_SECRET
```powershell
php artisan jwt:secret

# Debería mostrar:
# jwt-auth secret set successfully.
```

#### 13.3 Verificar que se actualizó .env
```powershell
type .env | findstr "APP_KEY JWT_SECRET"

# Deberías ver valores generados (no vacíos)
```

**✅ Claves generadas**

---

### **PASO 14: Configurar permisos de carpetas**

⚠️ **MUY IMPORTANTE:** Laravel necesita escribir en estas carpetas.

```powershell
cd C:\inetpub\wwwroot\sistema_dra_back

# Dar permisos a IIS_IUSRS
icacls "storage" /grant "IIS_IUSRS:(OI)(CI)F" /T
icacls "bootstrap\cache" /grant "IIS_IUSRS:(OI)(CI)F" /T

# Debería mostrar:
# Archivos procesados correctamente
```

**✅ Permisos configurados**

---

### **PASO 15: Ejecutar migraciones (crear tablas)**

```powershell
php artisan migrate --force

# Esto creará 38 tablas en la base de datos
# Puede tomar 1-2 minutos

# Al finalizar debería mostrar:
# Migration completed successfully.
```

#### Verificar tablas creadas
```powershell
php artisan tinker

# Dentro de tinker:
>>> \DB::table('usuarios')->count();
# Debería retornar: 0 (o más si hay datos)

>>> exit
```

**✅ Base de datos lista con todas las tablas**

---

### **PASO 16: Optimizar Laravel para producción**

```powershell
# Limpiar caches previos
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Generar caches optimizados
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimizar autoload
composer dump-autoload --optimize --no-dev
```

**✅ Laravel optimizado**

---

## 🌐 PARTE 3: CONFIGURAR IIS

### **PASO 17: Configurar FastCGI en IIS**

#### 17.1 Abrir IIS Manager
```
Inicio → Buscar "IIS" → Abrir "Administrador de Internet Information Services (IIS)"
```

#### 17.2 Configurar Handler Mapping
```
1. En el panel izquierdo, click en el NOMBRE DEL SERVIDOR (nivel raíz)
2. En el panel central, doble click en "Handler Mappings"
3. En el panel derecho, click en "Add Module Mapping..."
4. Configurar:
   - Request path: *.php
   - Module: FastCgiModule
   - Executable: C:\PHP\php-cgi.exe
   - Name: PHP_FastCGI
5. Click "OK"
6. Click "Yes" cuando pregunte si crear configuración FastCGI
```

**✅ FastCGI configurado**

---

### **PASO 18: Crear sitio web en IIS**

#### 18.1 Crear Application Pool
```
1. En IIS Manager, en el panel izquierdo
2. Click derecho en "Application Pools"
3. Click en "Add Application Pool..."
4. Configurar:
   - Name: Sistema_DRA_Pool
   - .NET CLR version: No Managed Code
   - Managed pipeline mode: Integrated
   - Start application pool immediately: ✅
5. Click "OK"
```

#### 18.2 Crear sitio web
```
1. En el panel izquierdo, click derecho en "Sites"
2. Click en "Add Website..."
3. Configurar:
   - Site name: Sistema DRA Backend
   - Application pool: Sistema_DRA_Pool
   - Physical path: C:\inetpub\wwwroot\sistema_dra_back\public
     ⚠️ IMPORTANTE: Debe apuntar a la carpeta PUBLIC
   - Binding:
     * Type: http
     * IP address: All Unassigned
     * Port: 80
     * Host name: (dejar vacío para localhost, o poner api.dra.gob.pe)
4. Click "OK"
```

#### 18.3 Detener sitio web por defecto
```
1. En "Sites", seleccionar "Default Web Site"
2. En el panel derecho, click en "Stop"
```

**✅ Sitio web creado en IIS**

---

### **PASO 19: Crear archivo web.config**

⚠️ **CRÍTICO:** Este archivo es necesario para que Laravel funcione en IIS.

#### 19.1 Crear archivo
```powershell
notepad C:\inetpub\wwwroot\sistema_dra_back\public\web.config
```

#### 19.2 Pegar este contenido:
```xml
<?xml version="1.0" encoding="UTF-8"?>
<configuration>
    <system.webServer>
        <!-- Reglas de reescritura para Laravel -->
        <rewrite>
            <rules>
                <rule name="Imported Rule 1" stopProcessing="true">
                    <match url="^(.*)/$" ignoreCase="false" />
                    <conditions>
                        <add input="{REQUEST_FILENAME}" matchType="IsDirectory" ignoreCase="false" negate="true" />
                    </conditions>
                    <action type="Redirect" redirectType="Permanent" url="/{R:1}" />
                </rule>
                <rule name="Imported Rule 2" stopProcessing="true">
                    <match url="^" ignoreCase="false" />
                    <conditions>
                        <add input="{REQUEST_FILENAME}" matchType="IsDirectory" ignoreCase="false" negate="true" />
                        <add input="{REQUEST_FILENAME}" matchType="IsFile" ignoreCase="false" negate="true" />
                    </conditions>
                    <action type="Rewrite" url="index.php" />
                </rule>
            </rules>
        </rewrite>
        
        <!-- Handler para PHP -->
        <handlers>
            <remove name="PHP_via_FastCGI" />
            <add name="PHP_via_FastCGI" path="*.php" verb="*" modules="FastCgiModule" scriptProcessor="C:\PHP\php-cgi.exe" resourceType="Either" requireAccess="Script" />
        </handlers>
        
        <!-- Documento por defecto -->
        <defaultDocument>
            <files>
                <clear />
                <add value="index.php" />
                <add value="index.html" />
            </files>
        </defaultDocument>
        
        <!-- Mostrar errores detallados (quitar en producción) -->
        <httpErrors errorMode="Detailed" />
        
        <!-- Seguridad -->
        <security>
            <requestFiltering>
                <!-- Ocultar carpetas sensibles -->
                <hiddenSegments>
                    <add segment="app" />
                    <add segment="bootstrap" />
                    <add segment="config" />
                    <add segment="database" />
                    <add segment="resources" />
                    <add segment="routes" />
                    <add segment="storage" />
                    <add segment="vendor" />
                    <add segment=".env" />
                    <add segment=".git" />
                </hiddenSegments>
                <!-- Límite de tamaño de subida: 20MB -->
                <requestLimits maxAllowedContentLength="20971520" />
            </requestFiltering>
        </security>
        
        <!-- Headers de seguridad -->
        <httpProtocol>
            <customHeaders>
                <remove name="X-Powered-By" />
                <add name="X-Content-Type-Options" value="nosniff" />
                <add name="X-Frame-Options" value="SAMEORIGIN" />
                <add name="X-XSS-Protection" value="1; mode=block" />
            </customHeaders>
        </httpProtocol>
    </system.webServer>
</configuration>
```

#### 19.3 Guardar y cerrar
```
Ctrl + S (guardar)
Cerrar Notepad
```

**✅ web.config creado**

---

### **PASO 20: Reiniciar IIS**

```powershell
iisreset

# Debería mostrar:
# Internet services successfully stopped
# Internet services successfully restarted
```

**✅ IIS reiniciado**

---

## ✅ PARTE 4: VERIFICACIÓN FINAL

### **PASO 21: Probar que todo funciona**

#### 21.1 Test básico desde el servidor
```powershell
# Test con curl (desde PowerShell)
curl http://localhost/api/prueba

# O abrir navegador y visitar:
http://localhost/api/prueba

# Debería mostrar:
{"message":"API is working"}
```

#### 21.2 Test de login
```powershell
# Crear un usuario de prueba primero
php artisan tinker

# Dentro de tinker:
>>> $usuario = new \App\Models\Usuario();
>>> $usuario->nombre = 'Admin';
>>> $usuario->usuario = 'admin';
>>> $usuario->password = bcrypt('admin123');
>>> $usuario->rol_id = 1;
>>> $usuario->save();
>>> exit
```

Ahora probar login:
```powershell
curl -X POST http://localhost/api/login -H "Content-Type: application/json" -d "{\"usuario\":\"admin\",\"password\":\"admin123\"}"

# Debería retornar un JSON con token JWT
```

**✅ Sistema funcionando correctamente**

---

## 🎉 ¡INSTALACIÓN COMPLETA!

### Resumen de lo instalado:

| Software | Ubicación | Verificación |
|----------|-----------|--------------|
| PHP 8.2 | C:\PHP | `php -v` |
| Composer | Program Files | `composer --version` |
| MySQL | C:\xampp\mysql | `mysql --version` |
| IIS | Windows Feature | Abrir IIS Manager |
| Proyecto | C:\inetpub\wwwroot\sistema_dra_back | Navegar a carpeta |

### URLs importantes:

- **API:** http://localhost/api/prueba
- **Documentación:** Ver archivos .md en el proyecto
- **phpMyAdmin (XAMPP):** http://localhost/phpmyadmin
- **Logs Laravel:** C:\inetpub\wwwroot\sistema_dra_back\storage\logs\laravel.log

### Próximos pasos:

1. ✅ Configurar firewall para permitir acceso externo
2. ✅ Configurar SSL/HTTPS (certificado)
3. ✅ Crear usuarios reales en la base de datos
4. ✅ Configurar backup automático
5. ✅ Entregar documentación API al equipo frontend

---

## 📞 En caso de problemas

### Ver logs de errores:
```powershell
# Logs de Laravel
type C:\inetpub\wwwroot\sistema_dra_back\storage\logs\laravel.log

# Logs de IIS
type C:\inetpub\logs\LogFiles\W3SVC1\u_ex*.log | Select-Object -Last 50
```

### Comandos útiles:
```powershell
# Reiniciar IIS
iisreset

# Limpiar caches de Laravel
php artisan optimize:clear

# Ver estado de MySQL
Get-Service | Where-Object {$_.Name -like "*mysql*"}
```

---

**✅ Sistema completamente instalado y funcionando**

**Tiempo total:** 60-90 minutos  
**Última actualización:** 8 de enero de 2026

---

## 📋 CHECKLIST FINAL

Marca cada paso completado:

- [ ] IIS instalado
- [ ] Visual C++ Redistributable instalado
- [ ] PHP 8.2 instalado y en PATH
- [ ] php.ini configurado (extensiones activas)
- [ ] URL Rewrite Module instalado
- [ ] Composer instalado
- [ ] MySQL instalado y corriendo
- [ ] Base de datos `sistema_dra` creada
- [ ] Usuario `dra_user` creado con permisos
- [ ] Archivos del proyecto en C:\inetpub\wwwroot\sistema_dra_back
- [ ] .env configurado con datos correctos
- [ ] composer install ejecutado
- [ ] APP_KEY generada
- [ ] JWT_SECRET generada
- [ ] Permisos de storage configurados
- [ ] Migraciones ejecutadas (38 tablas)
- [ ] Laravel optimizado (caches)
- [ ] FastCGI configurado en IIS
- [ ] Application Pool creado
- [ ] Sitio web creado en IIS (apuntando a /public)
- [ ] web.config creado en carpeta public
- [ ] IIS reiniciado
- [ ] Test de /api/prueba exitoso
- [ ] Test de login exitoso

**Si todos los checkboxes están marcados, el sistema está 100% listo** ✅
