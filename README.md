<div align="center">

<br>

# Tradia

### Compra en volumen. Sin complicaciones.
### Bulk buying. Made simple.

<br>

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)

<br>

<img src="Multimedia/ResgitroTradia.png" alt="Tradia" width="80%">

<br>
<br>

**[🇬🇧 English](#-english)** &nbsp;·&nbsp; **[🇲🇽 Español](#-español)**

<br>

</div>

---

<br>

<a id="-english"></a>

# 🇬🇧 English

<div align="center">

An e-commerce platform built for bulk purchases:<br>
a clear catalog, a fast cart and a straightforward checkout.

</div>

<br>

## Why Tradia

<table>
<tr>
<td width="33%" valign="top">

### 📦 Volume
Designed for wholesale orders, with large quantities and a frictionless flow.

</td>
<td width="33%" valign="top">

### ⚡ Simplicity
From catalog to checkout in a few steps. No distractions, nothing extra.

</td>
<td width="33%" valign="top">

### 🛠️ Control
A panel for sellers to register, edit and manage their products.

</td>
</tr>
</table>

<br>

## Features

**For buyers**

- User registration and login
- Product catalog with detail view
- Shopping cart
- Checkout and order confirmation
- Personal user panel

**For sellers**

- Register new products
- Edit and update existing products
- Product management panel

<br>

## Tech stack

| | Technology | Purpose |
|:--:|:--|:--|
| 🐘 | **PHP** | Server-side logic and views |
| 🗄️ | **MySQL** | Users, products and orders database |
| 🎨 | **HTML5 + CSS3** | Interface and per-screen styles |

<br>

## Project structure

```
Tradia/
│
├── 📁 BD/                        Database connection and processes
│   ├── C.php                     Connection
│   ├── registra.php              User registration
│   ├── registroproducto.php      Product creation
│   ├── actualizar_producto.php   Product update
│   ├── MostarProducto.php        Product query
│   └── procesopago.php           Payment processing
│
├── 📁 PHP/                       Application views
│   ├── index.php                 Home
│   ├── login.php                 Login
│   ├── RegistroU.php             User sign-up
│   ├── Panelusuario.php          User panel
│   ├── producto.php              Catalog
│   ├── detalles.php              Product details
│   ├── carrito.php               Cart
│   ├── pago.php                  Payment
│   ├── confirmacion.php          Order confirmation
│   ├── panel_productos.php       Product panel
│   └── editarproducto.php        Product editing
│
├── 📁 CSS/                       Per-screen stylesheets
│
└── 📁 Multimedia/                Images and assets
```

<br>

## Getting started

**1. Clone the repository**

```bash
git clone https://github.com/G1L08/Tradia.git
```

**2. Place it on your local server**

Move the folder to your server's public directory (for example `htdocs` in XAMPP).

**3. Create the database**

Create a MySQL database and import your `.sql` script.

**4. Configure the connection**

Edit `BD/C.php` with your credentials:

```php
$servidor = "localhost";
$usuario  = "root";
$password = "";
$basedatos = "tradia";
```

**5. Open it in your browser**

```
http://localhost/Tradia/PHP/index.php
```

<br>

## Purchase flow

```
   Sign up  →  Catalog  →  Details  →  Cart  →  Payment  →  Confirmation
```

<br>

## Roadmap

- [ ] Automatic volume discounts
- [ ] Order history
- [ ] Fully responsive design
- [ ] Product search and filters

<br>

---

<br>

<a id="-español"></a>

# 🇲🇽 Español

<div align="center">

Una plataforma e-commerce pensada para quienes compran en grandes cantidades:<br>
catálogo claro, carrito ágil y un proceso de pago directo.

</div>

<br>

## Por qué Tradia

<table>
<tr>
<td width="33%" valign="top">

### 📦 Volumen
Pensada para compras al mayoreo, con cantidades grandes y un flujo sin fricción.

</td>
<td width="33%" valign="top">

### ⚡ Simplicidad
Del catálogo al pago en pocos pasos. Sin distracciones, sin pasos de más.

</td>
<td width="33%" valign="top">

### 🛠️ Control
Panel para que los vendedores registren, editen y administren sus productos.

</td>
</tr>
</table>

<br>

## Características

**Para compradores**

- Registro e inicio de sesión de usuarios
- Catálogo de productos con vista de detalles
- Carrito de compras
- Proceso de pago y pantalla de confirmación
- Panel de usuario personal

**Para vendedores**

- Registro de nuevos productos
- Edición y actualización de productos existentes
- Panel de administración de productos

<br>

## Tecnologías

| | Tecnología | Uso |
|:--:|:--|:--|
| 🐘 | **PHP** | Lógica del servidor y vistas |
| 🗄️ | **MySQL** | Base de datos de usuarios, productos y pedidos |
| 🎨 | **HTML5 + CSS3** | Interfaz y estilos por pantalla |

<br>

## Estructura del proyecto

```
Tradia/
│
├── 📁 BD/                        Conexión y procesos con la base de datos
│   ├── C.php                     Conexión
│   ├── registra.php              Registro de usuarios
│   ├── registroproducto.php      Alta de productos
│   ├── actualizar_producto.php   Actualización de productos
│   ├── MostarProducto.php        Consulta de productos
│   └── procesopago.php           Procesamiento del pago
│
├── 📁 PHP/                       Vistas de la aplicación
│   ├── index.php                 Inicio
│   ├── login.php                 Inicio de sesión
│   ├── RegistroU.php             Registro de usuario
│   ├── Panelusuario.php          Panel del usuario
│   ├── producto.php              Catálogo
│   ├── detalles.php              Detalle de producto
│   ├── carrito.php               Carrito
│   ├── pago.php                  Pago
│   ├── confirmacion.php          Confirmación de compra
│   ├── panel_productos.php       Panel de productos
│   └── editarproducto.php        Edición de producto
│
├── 📁 CSS/                       Hojas de estilo por pantalla
│
└── 📁 Multimedia/                Imágenes y recursos
```

<br>

## Instalación

**1. Clona el repositorio**

```bash
git clone https://github.com/G1L08/Tradia.git
```

**2. Colócalo en tu servidor local**

Mueve la carpeta al directorio público de tu servidor (por ejemplo `htdocs` en XAMPP).

**3. Crea la base de datos**

Crea una base de datos en MySQL e impórtala con tu script `.sql`.

**4. Configura la conexión**

Edita `BD/C.php` con tus credenciales:

```php
$servidor = "localhost";
$usuario  = "root";
$password = "";
$basedatos = "tradia";
```

**5. Ábrelo en el navegador**

```
http://localhost/Tradia/PHP/index.php
```

<br>

## Flujo de compra

```
   Registro  →  Catálogo  →  Detalles  →  Carrito  →  Pago  →  Confirmación
```

<br>

## Hoja de ruta

- [ ] Descuentos automáticos por volumen
- [ ] Historial de pedidos
- [ ] Diseño totalmente responsivo
- [ ] Búsqueda y filtros de productos

<br>

---

<br>

<div align="center">

## Contact · Contacto

### Made by **José Gil Ramírez Onofre**
### Hecho por **José Gil Ramírez Onofre**

<br>

[![Gmail](https://img.shields.io/badge/Gmail-D14836?style=for-the-badge&logo=gmail&logoColor=white)](mailto:ramirezonofrejosegil@gmail.com)
[![LinkedIn](https://img.shields.io/badge/LinkedIn-0A66C2?style=for-the-badge&logo=linkedin&logoColor=white)](https://www.linkedin.com/in/jose-gil-ramirez-onofre-461b41342/)
[![GitHub](https://img.shields.io/badge/GitHub-G1L08-181717?style=for-the-badge&logo=github&logoColor=white)](https://github.com/G1L08)

<br>

<sub>ramirezonofrejosegil@gmail.com</sub>

<br>
