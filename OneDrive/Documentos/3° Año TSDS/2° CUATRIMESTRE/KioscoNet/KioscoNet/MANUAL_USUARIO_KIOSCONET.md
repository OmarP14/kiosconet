# 📘 MANUAL DE USUARIO - SISTEMA KIOSCONET

## Versión 3.1 | Sistema de Gestión para Kioscos

**Última actualización:** 7 de Noviembre de 2025

---

## ✨ NOVEDADES DE LA VERSIÓN 3.1

### 🎯 Mejora del Arqueo de Caja

**¡Nuevo!** El módulo de arqueo ahora diferencia entre efectivo físico y pagos electrónicos:

✅ **Desglose por Método de Pago**
- Visualización separada de: Efectivo, Tarjeta, Transferencia, Cuenta Corriente
- Tarjetas de colores para identificar cada método

✅ **Cálculo Automático de Efectivo Esperado**
- El sistema calcula cuánto efectivo DEBE haber en caja
- Suma ventas en efectivo + otros ingresos - egresos

✅ **Conteo Solo de Efectivo Físico**
- Campo específico para contar billetes y monedas
- Instrucciones claras: NO incluir tarjeta ni transferencias

✅ **Detección Inteligente de Diferencias**
- Alertas en tiempo real (verde/amarillo/rojo)
- Validación automática de sobrantes y faltantes

✅ **Guía de Verificación Posterior**
- Cómo validar pagos con tarjeta en el banco
- Cómo verificar MercadoPago y transferencias

**📖 Ver sección:** [Módulo de Caja → Realizar Arqueo](#realizar-arqueo-de-caja)

---

### ⚠️ Vista Mejorada de Stock Bajo

**¡Mejorado!** Ahora puede acceder a productos con stock bajo desde múltiples lugares:

✅ **Acceso Rápido Desde Ventas**
- Botón "Stock Bajo" en la barra superior del módulo de ventas
- Acceso directo sin salir del flujo de trabajo

✅ **Vista Detallada con Prioridades**
- Clasificación automática: URGENTE, ALTA, MEDIA, BAJA
- Indicadores visuales por nivel de criticidad
- Barra de progreso para cada producto

✅ **Cálculo de Inversión Total**
- Muestra el monto necesario para reabastecer todos los productos
- Ayuda a planificar compras según presupuesto

✅ **Acciones Rápidas de Reabastecimiento**
- Botón directo para ajustar stock desde la vista
- Modal pre-configurado con motivo "Reabastecimiento"
- Proceso optimizado en menos pasos

**📖 Ver sección:** [Módulo de Productos → Productos con Stock Bajo](#productos-con-stock-bajo)

---

## 📑 ÍNDICE

1. [Introducción](#introducción)
2. [Acceso al Sistema](#acceso-al-sistema)
3. [Pantalla Principal (Dashboard)](#pantalla-principal-dashboard)
4. [Módulo de Ventas](#módulo-de-ventas)
5. [Módulo de Productos](#módulo-de-productos) ⭐ ACTUALIZADO
6. [Módulo de Clientes](#módulo-de-clientes)
7. [Módulo de Proveedores](#módulo-de-proveedores)
8. [Módulo de Caja](#módulo-de-caja) ⭐ ACTUALIZADO
9. [Módulo de Usuarios](#módulo-de-usuarios)
10. [Módulo de Reportes](#módulo-de-reportes)
11. [Preguntas Frecuentes](#preguntas-frecuentes)
12. [Solución de Problemas](#solución-de-problemas)

---

## 📖 INTRODUCCIÓN

### ¿Qué es KioscoNet?

**KioscoNet** es un sistema completo de gestión diseñado específicamente para kioscos y pequeños comercios. Permite administrar ventas, inventario, clientes, proveedores, caja y generar reportes detallados de manera simple y eficiente.

### Características Principales

✅ **Gestión de Ventas** - Registra ventas con múltiples métodos de pago
✅ **Control de Inventario** - Administra productos con alertas de stock
✅ **Administración de Clientes** - CRM con cuentas corrientes
✅ **Control de Caja** - Seguimiento de ingresos y egresos en tiempo real
✅ **Reportes Completos** - Análisis de ventas, productos y rentabilidad
✅ **Múltiples Usuarios** - Sistema de roles (Administrador/Vendedor)
✅ **Comprobantes** - Generación de tickets y PDFs

### Tipos de Usuario

| Rol | Permisos |
|-----|----------|
| **Administrador** | Acceso completo a todos los módulos, configuración, usuarios y reportes |
| **Vendedor** | Acceso a ventas, consulta de productos, clientes y caja |

---

## 🔐 ACCESO AL SISTEMA

### Paso 1: Ingresar al Sistema

1. Abra su navegador web (Google Chrome, Firefox, Edge)
2. Ingrese la URL del sistema: `http://localhost/kiosconet` (o la URL proporcionada)
3. Aparecerá la pantalla de inicio de sesión

### Paso 2: Iniciar Sesión

1. **Usuario**: Ingrese su nombre de usuario
2. **Contraseña**: Ingrese su contraseña
3. Haga clic en el botón **"Iniciar Sesión"**

```
┌─────────────────────────────────┐
│      🏪 KIOSCONET              │
│    Sistema de Gestión          │
├─────────────────────────────────┤
│  Usuario:    [____________]    │
│  Contraseña: [____________]    │
│                                 │
│     [ Iniciar Sesión ]         │
└─────────────────────────────────┘
```

**📌 NOTA:** Si olvidó su contraseña, contacte al administrador del sistema.

### Paso 3: Cerrar Sesión

1. Haga clic en su nombre de usuario (esquina superior derecha)
2. Seleccione **"Cerrar Sesión"**

---

## 🏠 PANTALLA PRINCIPAL (DASHBOARD)

Al iniciar sesión, verá el **Dashboard** con información resumida de su negocio.

### Elementos del Dashboard

#### 1️⃣ **Tarjetas de Estadísticas**

El dashboard muestra 4 tarjetas con información clave:

| Tarjeta | Descripción |
|---------|-------------|
| **Ventas Hoy** 🛒 | Cantidad de ventas realizadas en el día |
| **Ingresos Hoy** 💵 | Total de dinero ingresado en el día |
| **Stock Bajo** ⚠️ | Productos que necesitan reposición |
| **Saldo Caja** 💰 | Dinero disponible en caja actual |

#### 2️⃣ **Accesos Rápidos**

Botones de acceso directo a las funciones más utilizadas:

- **Nueva Venta** (Azul) - Registrar una venta
- **Productos** (Verde) - Administrar inventario
- **Clientes** (Celeste) - Gestión de clientes
- **Caja** (Amarillo) - Movimientos de caja
- **Arqueo** (Gris) - Realizar arqueo de caja
- **Reportes** (Negro) - Ver estadísticas (solo Administrador)

#### 3️⃣ **Gráficos**

- **Ventas de los Últimos 7 Días**: Gráfico de líneas con el historial de ventas
- **Top Productos**: Gráfico de barras con los productos más vendidos

#### 4️⃣ **Alertas**

El sistema muestra automáticamente:
- 🔴 **Productos Vencidos**: Productos que superaron su fecha de vencimiento
- 🟡 **Productos por Vencer**: Productos que vencen en los próximos 30 días
- 🟠 **Stock Bajo**: Productos que llegaron al stock mínimo

#### 5️⃣ **Ventas Recientes**

Tabla con las últimas ventas realizadas, mostrando:
- Número de venta
- Cliente
- Fecha y hora
- Total
- Estado (Completada/Anulada)

---

## 🛒 MÓDULO DE VENTAS

### ¿Qué puedo hacer en este módulo?

- ✅ Registrar ventas nuevas
- ✅ Ver historial de ventas
- ✅ Consultar detalles de una venta
- ✅ Anular ventas (dentro de las 24 horas)
- ✅ Imprimir tickets y comprobantes
- ✅ Descargar comprobantes en PDF

---

### 📝 REGISTRAR UNA VENTA NUEVA

**Ubicación:** Dashboard → **Nueva Venta** (botón azul) o Menú → Ventas → Nueva Venta

#### Paso 1: Seleccionar Cliente

1. Haga clic en el campo **"Seleccionar Cliente"**
2. Busque y seleccione el cliente de la lista
   - Para ventas sin cliente específico, seleccione **"Cliente de Contado"**

**💡 TIP:** Puede crear un cliente nuevo desde este módulo usando el botón **"+ Nuevo Cliente"**

#### Paso 2: Seleccionar Lista de Precios

Elija el tipo de precio según el cliente:

- **Minorista** - Precio estándar (por defecto)
- **Mayorista** - Precio especial para mayoristas
- **Especial** - Precio promocional

#### Paso 3: Buscar y Agregar Productos

1. En el campo **"Buscar producto..."** escriba:
   - Nombre del producto
   - Código de barras
   - Parte del nombre

2. El sistema mostrará productos que coincidan con la búsqueda

3. Haga clic en el producto deseado o presione **Enter**

4. El producto se agregará automáticamente al carrito

**📊 Información mostrada por producto:**
- Nombre
- Código
- Stock disponible
- Precio unitario
- Subtotal

#### Paso 4: Ajustar Cantidades y Descuentos

**Para modificar la cantidad:**
1. Haga clic en el campo de cantidad del producto
2. Ingrese la cantidad deseada
3. El subtotal se actualizará automáticamente

**Para aplicar descuento individual:**
1. Haga clic en el ícono de descuento (%) del producto
2. Ingrese el porcentaje de descuento (ej: 10 para 10%)
3. El precio se recalculará

**Para eliminar un producto:**
- Haga clic en el ícono de basura (🗑️) al lado del producto

#### Paso 5: Aplicar Descuento Global (Opcional)

En la parte inferior, puede aplicar un descuento a toda la venta:
1. Ingrese el porcentaje en **"Descuento Global"**
2. El total se recalculará automáticamente

#### Paso 6: Seleccionar Método de Pago

Elija el método de pago según corresponda:

##### 💵 **PAGO EN EFECTIVO**

1. Seleccione **"Efectivo"**
2. Ingrese el **"Monto Recibido"** (dinero que entrega el cliente)
3. El sistema calculará automáticamente el **vuelto**
4. Si el monto es insuficiente, mostrará un error

##### 💳 **PAGO CON TARJETA**

1. Seleccione **"Tarjeta"**
2. Elija el tipo:
   - **Débito**
   - **Crédito**
3. Ingrese los **últimos 4 dígitos** de la tarjeta
4. Ingrese el **código de autorización** proporcionado por el terminal

##### 🏦 **PAGO CON TRANSFERENCIA**

1. Seleccione **"Transferencia"**
2. Ingrese el **número de transferencia**
3. Seleccione o ingrese el **banco**
4. Complete fecha y hora de la transferencia (opcional)

##### 📋 **PAGO CON CUENTA CORRIENTE**

1. Seleccione **"Cuenta Corriente"**
2. El sistema verificará:
   - ✅ Que el cliente tenga cuenta corriente habilitada
   - ✅ Que tenga crédito disponible
3. El saldo se descontará automáticamente del límite del cliente

##### 🔀 **PAGO MIXTO**

Si el cliente paga con varios métodos:

1. Seleccione **"Pago Mixto"**
2. Haga clic en **"+ Agregar Forma de Pago"**
3. Para cada método:
   - Seleccione el tipo (Efectivo, Tarjeta, etc.)
   - Ingrese el monto
   - Complete los datos requeridos según el método
4. El sistema validará que la suma sea igual al total

**Ejemplo de Pago Mixto:**
```
Total Venta: $5,000
- Efectivo: $3,000
- Tarjeta Débito: $2,000
─────────────────────
TOTAL: $5,000 ✓
```

#### Paso 7: Observaciones (Opcional)

En el campo **"Observaciones"** puede agregar notas sobre la venta:
- Solicitudes especiales del cliente
- Condiciones de entrega
- Cualquier información relevante

#### Paso 8: Finalizar la Venta

1. Revise el **resumen** de la venta:
   - Subtotal
   - Descuento
   - Total
   - Método de pago

2. Haga clic en el botón **"Procesar Venta"** (verde)

3. El sistema mostrará un mensaje de confirmación:
   ```
   ✓ ¡Venta procesada exitosamente!
   Venta N° V00000123
   Total: $5,000.00
   ```

4. Opciones después de procesar:
   - **Imprimir Ticket** - Imprime un ticket de venta
   - **Descargar PDF** - Descarga comprobante en PDF
   - **Nueva Venta** - Inicia otra venta
   - **Ver Detalles** - Muestra el detalle completo

**⚠️ IMPORTANTE:**
- El stock se descuenta automáticamente
- No se pueden vender productos sin stock
- Las ventas en cuenta corriente se registran en el saldo del cliente

---

### 📋 VER HISTORIAL DE VENTAS

**Ubicación:** Menú → Ventas → Listado de Ventas

#### ¿Qué puedo ver?

La lista de ventas muestra:

| Columna | Descripción |
|---------|-------------|
| **#ID** | Número único de venta |
| **N° Venta** | Número de comprobante (ej: V00000123) |
| **Cliente** | Nombre del cliente |
| **Fecha** | Fecha y hora de la venta |
| **Total** | Importe total |
| **Método Pago** | Efectivo, Tarjeta, etc. |
| **Estado** | Completada / Anulada |
| **Acciones** | Botones de acción |

#### Filtros Disponibles

**Por Fecha:**
- Hoy
- Ayer
- Última Semana
- Último Mes
- Personalizado (seleccionar rango)

**Por Cliente:**
- Seleccione un cliente específico de la lista

**Por Método de Pago:**
- Efectivo
- Tarjeta
- Transferencia
- Cuenta Corriente
- Pago Mixto

**Por Estado:**
- Completadas
- Anuladas
- Todas

#### Acciones Disponibles

**👁️ Ver Detalles:**
- Muestra información completa de la venta
- Lista de productos vendidos
- Datos del pago
- Información del cliente

**🖨️ Imprimir Ticket:**
- Genera ticket para impresora térmica
- Formato 80mm
- Incluye código QR para verificación

**📄 Descargar PDF:**
- Descarga comprobante en formato PDF
- Tamaño A4
- Listo para imprimir

**🔁 Reimprimir:**
- Permite reimprimir tickets
- Registra cada reimpresión

**❌ Anular Venta:**
- Solo disponible dentro de las 24 horas
- Devuelve el stock automáticamente
- Revierte movimientos de caja
- Requiere motivo de anulación

---

### 🔍 VER DETALLES DE UNA VENTA

**Ubicación:** Ventas → Listado → Botón "Ver" (ícono ojo)

#### Información Mostrada

**1. Datos Generales:**
- Número de venta
- Fecha y hora
- Usuario que realizó la venta
- Estado (Completada/Anulada)

**2. Información del Cliente:**
- Nombre completo
- Teléfono
- Email
- Dirección

**3. Detalle de Productos:**

Tabla con:
- Producto
- Cantidad
- Precio Unitario
- Descuento (%)
- Subtotal

**4. Totales:**
- Subtotal
- Descuento Global
- Comisión (si aplica)
- **TOTAL**

**5. Información de Pago:**

Según el método seleccionado:

**Efectivo:**
- Monto Recibido
- Vuelto

**Tarjeta:**
- Tipo (Débito/Crédito)
- Últimos 4 dígitos
- Código de Autorización

**Transferencia:**
- Número de Transferencia
- Banco
- Fecha y Hora

**Cuenta Corriente:**
- Saldo Anterior
- Nuevo Saldo
- Observaciones

**Pago Mixto:**
- Detalle de cada método
- Montos parciales

**6. Acciones Disponibles:**
- 🖨️ Imprimir Ticket
- 📄 Descargar PDF
- 🔁 Reimprimir
- ❌ Anular (si está dentro de las 24 horas)

---

### ❌ ANULAR UNA VENTA

**Ubicación:** Ventas → Ver Detalles → Botón "Anular Venta"

#### ¿Cuándo puedo anular una venta?

- ✅ Solo dentro de las **24 horas** desde que se realizó
- ✅ Solo si NO está anulada previamente

#### Pasos para Anular

1. Abra el detalle de la venta
2. Verifique que aparezca el botón **"Anular Venta"** (rojo)
3. Haga clic en **"Anular Venta"**
4. Ingrese el **motivo de anulación**:
   - Error en el registro
   - Devolución del cliente
   - Cancelación de la compra
   - Otro (especificar)
5. Confirme la anulación

#### ¿Qué sucede al anular?

El sistema automáticamente:
- ✅ Devuelve el stock de los productos
- ✅ Revierte el movimiento de caja
- ✅ Actualiza el saldo de cuenta corriente (si aplica)
- ✅ Marca la venta como "ANULADA"
- ✅ Registra el usuario y fecha de anulación

**⚠️ ADVERTENCIA:** La anulación NO se puede deshacer.

---

### 🖨️ IMPRIMIR TICKETS Y COMPROBANTES

#### Ticket para Impresora Térmica

**Formato:**
- Ancho: 80mm
- Incluye:
  - Logo del negocio
  - Datos del comercio
  - Número de venta
  - Fecha y hora
  - Detalle de productos
  - Totales
  - Método de pago
  - Código QR de verificación

**Pasos:**
1. Abra la venta
2. Clic en **"Imprimir Ticket"**
3. Se abrirá una ventana de impresión
4. Seleccione su impresora
5. Confirme la impresión

#### Comprobante PDF (Formato A4)

**Características:**
- Formato profesional
- Incluye todos los datos
- Listo para archivar
- Se puede enviar por email

**Pasos:**
1. Abra la venta
2. Clic en **"Descargar PDF"**
3. El archivo se descargará automáticamente
4. Nombre del archivo: `venta_V00000123_20250123.pdf`

---

## 📦 MÓDULO DE PRODUCTOS

### ¿Qué puedo hacer en este módulo?

- ✅ Ver listado de productos
- ✅ Crear productos nuevos
- ✅ Editar información de productos
- ✅ Ajustar stock
- ✅ Ver productos con stock bajo
- ✅ Controlar fechas de vencimiento
- ✅ Activar/Desactivar productos

---

### 📋 VER LISTADO DE PRODUCTOS

**Ubicación:** Menú → Productos → Listado

#### Información Mostrada

| Columna | Descripción |
|---------|-------------|
| **Imagen** | Foto del producto (si tiene) |
| **Código** | Código de barras o SKU |
| **Nombre** | Nombre del producto |
| **Categoría** | Categoría a la que pertenece |
| **Proveedor** | Proveedor principal |
| **Stock** | Cantidad disponible |
| **Precio Compra** | Costo del producto |
| **Precio Venta** | Precio al público (calculado) |
| **Estado** | Activo / Inactivo |
| **Acciones** | Botones |

#### Filtros Disponibles

**🔍 Búsqueda Rápida:**
- Por nombre
- Por código
- Por categoría

**📂 Por Categoría:**
- Desplegable con todas las categorías

**📊 Por Estado de Stock:**
- **Con Stock** - Productos disponibles
- **Stock Bajo** - Por debajo del mínimo
- **Sin Stock** - Agotados

**🔘 Por Estado:**
- Activos
- Inactivos
- Todos

#### Indicadores Visuales

**Stock:**
- 🟢 **Verde**: Stock normal
- 🟡 **Amarillo**: Stock bajo (≤ mínimo)
- 🔴 **Rojo**: Sin stock

**Vencimiento:**
- 🔴 **Rojo**: Vencido
- 🟠 **Naranja**: Vence en 7 días
- 🟡 **Amarillo**: Vence en 30 días

---

### ➕ CREAR UN PRODUCTO NUEVO

**Ubicación:** Productos → Botón "Nuevo Producto"

#### Paso 1: Información Básica

**Campos Obligatorios:**

1. **Nombre del Producto** *
   - Ejemplo: "Coca Cola 500ml"
   - Máximo 255 caracteres

2. **Proveedor** *
   - Seleccione de la lista
   - Si no existe, créelo primero en Módulo de Proveedores

3. **Precio de Compra** *
   - Ingrese el costo del producto
   - Ejemplo: 150.00
   - Sin el símbolo $

4. **Stock Inicial** *
   - Cantidad actual en inventario
   - Ejemplo: 50

5. **Stock Mínimo** *
   - Cantidad mínima antes de la alerta
   - Ejemplo: 10

**Campos Opcionales:**

6. **Código / Código de Barras**
   - Puede escanearlo con lector de códigos de barras
   - Ejemplo: 7790895001234

7. **Categoría**
   - Ejemplo: Bebidas, Golosinas, Snacks, etc.
   - Puede crear categorías nuevas escribiendo

8. **Fecha de Vencimiento**
   - Solo para productos perecederos
   - Formato: DD/MM/AAAA
   - Use el calendario

#### Paso 2: Imagen del Producto (Opcional)

1. Haga clic en **"Seleccionar Imagen"**
2. Elija una imagen desde su computadora
3. Formatos aceptados: JPG, PNG, GIF
4. Tamaño máximo: 2MB

**💡 TIP:** Use imágenes cuadradas (ej: 500x500px) para mejor visualización

#### Paso 3: Estado del Producto

**Producto Activo:**
- ✅ Marque la casilla "Producto Activo"
- Aparecerá en búsquedas y ventas

**Producto Inactivo:**
- ❌ Desmarque la casilla
- No aparecerá en ventas pero se mantiene en el sistema

#### Paso 4: Guardar

1. Revise todos los datos
2. Haga clic en **"Guardar Producto"** (verde)
3. El sistema validará:
   - Que no exista un código duplicado
   - Que todos los campos obligatorios estén completos
   - Que los valores numéricos sean válidos

**Mensaje de éxito:**
```
✓ ¡Producto creado exitosamente!
```

**Después de crear:**
- El producto aparecerá en el listado
- Estará disponible para ventas (si está activo)
- El stock se registrará en el inventario

---

### ✏️ EDITAR UN PRODUCTO

**Ubicación:** Productos → Listado → Botón "Editar" (ícono lápiz)

#### ¿Qué puedo modificar?

- ✅ Toda la información del producto
- ✅ Precio de compra
- ✅ Stock (ver sección "Ajustar Stock" para cambios)
- ✅ Proveedor
- ✅ Categoría
- ✅ Imagen
- ✅ Estado (Activo/Inactivo)

#### Pasos

1. Haga clic en el ícono de lápiz ✏️ del producto
2. Se abrirá el formulario con los datos actuales
3. Modifique los campos necesarios
4. Haga clic en **"Actualizar Producto"**

**⚠️ IMPORTANTE:**
- Los cambios en el precio NO afectan ventas anteriores
- Los cambios de proveedor solo afectan compras futuras

---

### 📊 AJUSTAR STOCK MANUALMENTE

**Ubicación:** Productos → Editar → Sección "Ajuste de Stock"

#### ¿Cuándo ajustar stock?

- 📦 Recepción de mercadería
- 📉 Pérdida o robo
- 🔄 Corrección de errores
- 🗑️ Productos dañados
- 📦 Inventario físico

#### Pasos

1. Edite el producto
2. En la sección **"Ajuste de Stock"**:
   - **Stock Actual**: Muestra la cantidad actual
   - **Nuevo Stock**: Ingrese la cantidad correcta
   - **Motivo**: Explique el cambio (obligatorio)

3. Ejemplos de motivos:
   - "Recepción de mercadería del proveedor"
   - "Ajuste por inventario físico"
   - "Producto dañado durante transporte"
   - "Corrección de error de carga"

4. Haga clic en **"Guardar Ajuste"**

**Registro de Auditoría:**
El sistema registra:
- Usuario que hizo el ajuste
- Fecha y hora
- Stock anterior y nuevo
- Diferencia (+/-)
- Motivo

**Ejemplo:**
```
┌─────────────────────────────────────┐
│ AJUSTE DE STOCK                     │
├─────────────────────────────────────┤
│ Producto: Coca Cola 500ml           │
│ Stock Actual: 25 unidades           │
│ Nuevo Stock: [50] unidades          │
│ Diferencia: +25                     │
│                                      │
│ Motivo del ajuste:                  │
│ [Recepción de mercadería]           │
│                                      │
│      [ Guardar Ajuste ]             │
└─────────────────────────────────────┘
```

---

### ⚠️ PRODUCTOS CON STOCK BAJO

**Ubicación:** Dashboard (alertas), Productos → "Stock Bajo", o Ventas → Botón "Stock Bajo"

#### ¿Qué muestra?

Productos donde:
```
Stock Actual ≤ Stock Mínimo
```

#### Cómo Acceder

**Opción 1: Desde Dashboard**
1. En la página principal verá alertas de stock bajo
2. Haga clic en **"Ver Todos los Productos con Stock Bajo"**

**Opción 2: Desde Módulo Productos**
1. Vaya a **Productos** en el menú
2. Haga clic en el botón **"Stock Bajo"** (ícono ⚠️)

**Opción 3: Desde Módulo Ventas**
1. Vaya a **Ventas** en el menú
2. En la barra superior, haga clic en el botón **"Stock Bajo"**
3. Se abrirá la vista dedicada de productos con stock bajo

#### Vista Detallada de Stock Bajo

La vista especializada muestra:

**📊 Alerta Principal:**
- Cantidad total de productos con stock bajo
- Mensaje de atención requerida

**📋 Tabla Completa con:**
- **Producto** (nombre y código)
- **Categoría** (badge de color)
- **Proveedor** (para contacto)
- **Stock Actual** (badge amarillo o rojo)
- **Stock Mínimo** (referencia)
- **Diferencia** (unidades faltantes)
- **Precio Compra** (unitario y total necesario)
- **Prioridad** (URGENTE, ALTA, MEDIA, BAJA)
  - 🔴 URGENTE: Sin stock (0 unidades)
  - 🔴 ALTA: ≤ 25% del mínimo
  - 🟡 MEDIA: ≤ 50% del mínimo
  - 🔵 BAJA: > 50% del mínimo
- **Acciones:**
  - Botón **"Reabastecer"** (ajuste rápido)
  - Botón **"Editar"** (modificar producto)

**💰 Total de Inversión:**
- Al pie de la tabla se muestra el costo total necesario para reabastecer todos los productos al mínimo

#### Acciones Disponibles

**1️⃣ Reabastecer Producto (Acción Rápida)**
1. Haga clic en el botón **"Reabastecer"** del producto
2. Se abre un modal con:
   - Stock actual
   - Campo para nuevo stock
   - Campo de motivo (pre-llenado con "Reabastecimiento")
3. Ingrese el nuevo stock
4. Haga clic en **"Guardar"**

**Ejemplo:**
```
┌─────────────────────────────────────┐
│ 🏪 AJUSTE DE STOCK                  │
├─────────────────────────────────────┤
│ ℹ️  Producto: Coca Cola 500ml       │
│                                      │
│ Stock Actual: [5] ← Solo lectura    │
│ Nuevo Stock:  [50] ← Editable       │
│                                      │
│ Motivo del ajuste:                  │
│ [Reabastecimiento] ← Pre-llenado    │
│                                      │
│   [Cancelar]  [💾 Guardar]          │
└─────────────────────────────────────┘
```

**2️⃣ Editar Producto**
- Click en botón **"Editar"** (ícono lápiz)
- Modifique datos completos del producto
- Útil para ajustar stock mínimo si es necesario

**3️⃣ Contactar Proveedor**
- La tabla muestra el proveedor de cada producto
- Use esa información para hacer el pedido
- Después registre la mercadería con "Reabastecer"

#### Estrategias Sugeridas

**1. Hacer Pedido al Proveedor:**
   - Revise la tabla completa
   - Anote productos del mismo proveedor
   - Contacte para realizar pedido grupal
   - Use el "Total Inversión" para presupuesto
   - Al recibir, use botón "Reabastecer"

**2. Ajustar Stock Mínimo:**
   - Si la alerta es frecuente, aumente el mínimo
   - Si es innecesaria, reduzca el mínimo
   - Edite el producto y modifique "Stock Mínimo"

**3. Desactivar Producto:**
   - Si ya no se vende
   - Si se discontinuó
   - Evitará alertas innecesarias

**4. Priorizar por Nivel:**
   - Atienda primero productos URGENTES (sin stock)
   - Luego productos de prioridad ALTA
   - Planifique los de MEDIA y BAJA

#### Consejos Útiles

💡 **Revise esta vista diariamente** para evitar quedarse sin stock de productos populares

💡 **Ordene por proveedor** para optimizar pedidos (contacte una vez por proveedor)

💡 **Use el cálculo de inversión** para planificar compras según presupuesto disponible

💡 **Configure bien el stock mínimo** de cada producto según su rotación

---

### 📅 CONTROL DE VENCIMIENTOS

El sistema controla automáticamente fechas de vencimiento.

#### Alertas de Vencimiento

**🔴 Productos Vencidos:**
- Pasaron la fecha de vencimiento
- No deberían venderse
- Acción: Retirar del stock

**🟡 Productos Próximos a Vencer (30 días):**
- Vencen en menos de 30 días
- Acción: Vender primero (FIFO)

**🟠 Productos Críticos (7 días):**
- Vencen en menos de 7 días
- Acción: Promoción especial

#### Visualización

**En el Listado:**
- Etiqueta de color según días restantes
- Ícono de calendario
- Fecha de vencimiento

**En Dashboard:**
- Sección especial de alertas
- Lista de productos vencidos
- Lista de próximos a vencer

#### Gestión de Vencidos

1. **Identificar:** Dashboard mostrará alertas
2. **Verificar:** Revise el producto físicamente
3. **Acciones posibles:**
   - Ajustar stock a 0 (baja)
   - Actualizar fecha si se renovó
   - Marcar como inactivo

---

### 🔘 ACTIVAR / DESACTIVAR PRODUCTOS

#### ¿Para qué sirve?

**Desactivar productos cuando:**
- Ya no se comercializan
- Están temporalmente sin stock
- Se discontinuaron
- Son de temporada

**Ventajas de desactivar:**
- ✅ Se mantiene el historial
- ✅ No se pierden datos de ventas antiguas
- ✅ No aparece en el buscador de ventas
- ✅ Se puede reactivar en cualquier momento

#### Cómo Activar/Desactivar

**Método 1: Desde el Listado**
1. Busque el producto
2. Haga clic en el interruptor **Activo/Inactivo**
3. El cambio es inmediato

**Método 2: Desde Editar**
1. Edite el producto
2. Marque/Desmarque la casilla **"Producto Activo"**
3. Guarde los cambios

**Indicadores:**
- 🟢 **Verde**: Producto Activo
- ⚫ **Gris**: Producto Inactivo

---

## 👥 MÓDULO DE CLIENTES

### ¿Qué puedo hacer en este módulo?

- ✅ Ver listado de clientes
- ✅ Crear clientes nuevos
- ✅ Editar información de clientes
- ✅ Administrar cuentas corrientes
- ✅ Ver historial de compras
- ✅ Consultar estado de cuenta
- ✅ Eliminar clientes

---

### 📋 VER LISTADO DE CLIENTES

**Ubicación:** Menú → Clientes → Listado

#### Información Mostrada

| Columna | Descripción |
|---------|-------------|
| **Nombre** | Nombre y apellido del cliente |
| **Teléfono** | Número de contacto |
| **Email** | Correo electrónico |
| **Tipo** | Minorista/Mayorista/Especial |
| **Saldo CC** | Saldo de cuenta corriente |
| **Límite** | Límite de crédito |
| **Compras** | Cantidad de compras realizadas |
| **Estado** | Activo/Inactivo |
| **Acciones** | Botones |

#### Indicadores de Cuenta Corriente

**Saldo CC:**
- 🟢 **$0.00**: Sin saldo (al día)
- 🔴 **Negativo (ej: -$500)**: Debe dinero
- 🔵 **Positivo (ej: +$200)**: Saldo a favor

#### Filtros Disponibles

**🔍 Búsqueda:**
- Por nombre
- Por apellido
- Por teléfono
- Por email

**💰 Por Saldo de CC:**
- Con saldo a favor
- Deudores (debe dinero)
- Sin movimientos

**🔘 Por Estado:**
- Activos
- Inactivos
- Todos

**📊 Ordenar por:**
- Nombre A-Z
- Más recientes
- Mayor saldo
- Mayor deuda

---

### ➕ CREAR UN CLIENTE NUEVO

**Ubicación:** Clientes → Botón "Nuevo Cliente"

#### Paso 1: Datos Personales

**Campos Obligatorios:**

1. **Nombre** *
   - Ejemplo: Juan
   - Solo letras

2. **Apellido** *
   - Ejemplo: Pérez
   - Solo letras

**Campos Opcionales:**

3. **Teléfono**
   - Ejemplo: 3804-123456
   - Con código de área

4. **Email**
   - Ejemplo: juan.perez@email.com
   - Debe ser único (no repetido)

5. **Dirección**
   - Dirección completa
   - Ejemplo: Av. San Martín 123, La Rioja

#### Paso 2: Tipo de Cliente

Seleccione el tipo:

**🏪 Minorista** (Por defecto)
- Cliente final
- Compra a precio normal
- Sin cuenta corriente (generalmente)

**📦 Mayorista**
- Compra por volumen
- Precio especial de mayorista
- Puede tener cuenta corriente

**⭐ Especial**
- Cliente VIP
- Precios promocionales
- Condiciones especiales

#### Paso 3: Cuenta Corriente (Opcional)

Si el cliente tendrá cuenta corriente:

1. Marque la opción **"Habilitar Cuenta Corriente"**

2. **Límite de Crédito:**
   - Monto máximo que puede deber
   - Ejemplo: 10000.00 (sin el símbolo $)
   - El cliente NO podrá comprar si supera este límite

**Ejemplo:**
```
Límite de Crédito: $10,000
Saldo Actual: -$2,500 (debe)
Crédito Disponible: $7,500
```

**⚠️ IMPORTANTE:**
- El saldo inicial es $0
- El saldo se actualiza automáticamente con las ventas
- El cliente debe pagar para liberar crédito

#### Paso 4: Estado del Cliente

**Cliente Activo:**
- ✅ Marcado por defecto
- Aparece en búsquedas y ventas

**Cliente Inactivo:**
- ❌ Para clientes que ya no compran
- Se mantiene el historial

#### Paso 5: Guardar

1. Revise los datos
2. Haga clic en **"Guardar Cliente"**
3. El sistema validará:
   - Email único
   - Formato correcto de email
   - Datos requeridos completos

**Mensaje de éxito:**
```
✓ Cliente 'Juan Pérez' creado exitosamente
```

---

### ✏️ EDITAR UN CLIENTE

**Ubicación:** Clientes → Listado → Botón "Editar" (ícono lápiz)

#### ¿Qué puedo modificar?

- ✅ Datos personales
- ✅ Tipo de cliente
- ✅ Límite de cuenta corriente
- ✅ Estado (Activo/Inactivo)

**⚠️ NO se puede modificar:**
- ❌ El saldo de cuenta corriente (ver "Ajustar Saldo CC")

#### Pasos

1. Haga clic en el ícono de lápiz ✏️
2. Modifique los campos necesarios
3. Haga clic en **"Actualizar Cliente"**

---

### 💰 ADMINISTRAR CUENTA CORRIENTE

#### Ver Estado de Cuenta

**Ubicación:** Clientes → Ver Cliente → Pestaña "Cuenta Corriente"

**Información mostrada:**
- Límite de crédito
- Saldo actual
- Crédito disponible
- Historial de movimientos

**Movimientos incluyen:**
- Ventas en cuenta corriente
- Pagos realizados
- Ajustes de saldo
- Fecha de cada movimiento

#### Registrar un Pago

**Ubicación:** Clientes → Ver Cliente → Botón "Registrar Pago"

**Paso a paso:**

1. Haga clic en **"Registrar Pago"**

2. Complete los datos:
   - **Monto del Pago** *
   - **Método de Pago** (Efectivo, Transferencia, etc.)
   - **Comprobante/Referencia** (opcional)
   - **Observaciones** (opcional)

3. Haga clic en **"Registrar"**

**El sistema:**
- ✅ Reduce el saldo de la CC
- ✅ Registra el movimiento
- ✅ Actualiza el crédito disponible
- ✅ Registra en caja (si es efectivo)

**Ejemplo:**
```
ANTES DEL PAGO:
Saldo CC: -$5,000 (debe)
Límite: $10,000
Disponible: $5,000

PAGO: $2,000

DESPUÉS DEL PAGO:
Saldo CC: -$3,000 (debe)
Límite: $10,000
Disponible: $7,000
```

#### Ajustar Saldo Manualmente

**Ubicación:** Clientes → Ver Cliente → "Ajustar Saldo CC"

**⚠️ Solo Administradores**

**¿Cuándo usar?**
- Corrección de errores
- Condonación de deuda
- Ajustes acordados
- Regularización de saldos

**Pasos:**

1. Haga clic en **"Ajustar Saldo"**

2. Complete:
   - **Nuevo Saldo** (positivo, negativo o cero)
   - **Motivo del Ajuste** * (obligatorio)

3. Ejemplos de motivos:
   - "Corrección de error de carga"
   - "Condonación de deuda acordada"
   - "Regularización por inventario"

4. Confirme el ajuste

**El sistema registra:**
- Usuario que hizo el ajuste
- Fecha y hora
- Saldo anterior y nuevo
- Diferencia
- Motivo

---

### 🔍 VER HISTORIAL DE COMPRAS

**Ubicación:** Clientes → Ver Cliente → Pestaña "Compras"

#### Información Mostrada

**Estadísticas:**
- Total gastado (histórico)
- Cantidad de compras
- Ticket promedio
- Última compra (fecha)

**Listado de Compras:**
Tabla con todas las ventas del cliente:
- Número de venta
- Fecha
- Total
- Método de pago
- Estado
- Ver detalles

**Productos Favoritos:**
Los 5 productos que más compra el cliente

---

### 🗑️ ELIMINAR UN CLIENTE

**Ubicación:** Clientes → Listado → Botón "Eliminar" (ícono basura)

**⚠️ Solo Administradores**

#### Restricciones

**NO se puede eliminar si:**
- ❌ Tiene saldo en cuenta corriente (≠ 0)
- ❌ Tiene compras en los últimos 30 días

**Razón:** Se perderían datos importantes del historial

#### Alternativa: Desactivar

En lugar de eliminar, es mejor **desactivar**:
- No aparecerá en búsquedas
- Se mantiene el historial
- Se puede reactivar

**Pasos para desactivar:**
1. Edite el cliente
2. Desmarque **"Cliente Activo"**
3. Guarde

---

## 🏭 MÓDULO DE PROVEEDORES

### ¿Qué puedo hacer en este módulo?

- ✅ Ver listado de proveedores
- ✅ Crear proveedores nuevos
- ✅ Editar información de proveedores
- ✅ Ver productos por proveedor
- ✅ Consultar historial de compras
- ✅ Eliminar proveedores

---

### 📋 VER LISTADO DE PROVEEDORES

**Ubicación:** Menú → Proveedores → Listado

#### Información Mostrada

| Columna | Descripción |
|---------|-------------|
| **Nombre** | Razón social o nombre del proveedor |
| **CUIT** | Número de CUIT |
| **Teléfono** | Número de contacto |
| **Email** | Correo electrónico |
| **Dirección** | Dirección física |
| **Productos** | Cantidad de productos que provee |
| **Estado** | Activo/Inactivo |
| **Acciones** | Botones |

---

### ➕ CREAR UN PROVEEDOR NUEVO

**Ubicación:** Proveedores → Botón "Nuevo Proveedor"

#### Campos del Formulario

**Obligatorios:**

1. **Nombre / Razón Social** *
   - Ejemplo: "Distribuidora San Martín S.A."

2. **CUIT** *
   - Formato: 20-12345678-9
   - Debe ser único

**Opcionales:**

3. **Teléfono**
   - Ejemplo: 0380-4123456

4. **Email**
   - Ejemplo: ventas@proveedor.com

5. **Dirección**
   - Dirección completa del proveedor

6. **Contacto**
   - Nombre de la persona de contacto
   - Ejemplo: "Juan Pérez (Vendedor)"

7. **Observaciones**
   - Notas sobre el proveedor
   - Condiciones de pago
   - Horarios de entrega

#### Guardar

1. Complete los datos
2. Haga clic en **"Guardar Proveedor"**

**Mensaje de éxito:**
```
✓ Proveedor creado exitosamente
```

---

### 🔍 VER DETALLES DE UN PROVEEDOR

**Ubicación:** Proveedores → Ver Proveedor

#### Información Mostrada

**1. Datos del Proveedor:**
- Toda la información registrada
- Estado actual

**2. Productos:**
Lista de productos provistos:
- Nombre
- Código
- Stock
- Precio de compra
- Estado

**3. Estadísticas:**
- Total de productos
- Productos activos
- Productos inactivos
- Stock valorizado (costo total)

---

## 💰 MÓDULO DE CAJA

### ¿Qué puedo hacer en este módulo?

- ✅ Ver movimientos de caja
- ✅ Registrar ingresos
- ✅ Registrar egresos
- ✅ Ver saldo actual
- ✅ Realizar arqueo de caja
- ✅ Cerrar caja
- ✅ Consultar historial

---

### 📊 VER MOVIMIENTOS DE CAJA

**Ubicación:** Menú → Caja → Movimientos

#### Panel de Estadísticas

En la parte superior se muestran 4 indicadores:

| Indicador | Descripción |
|-----------|-------------|
| **Saldo Actual** 💰 | Dinero total en caja ahora |
| **Ingresos Hoy** 📈 | Total de ingresos del día |
| **Egresos Hoy** 📉 | Total de egresos del día |
| **Ventas Hoy** 🛒 | Total de ventas del día |

#### Listado de Movimientos

Tabla con todos los movimientos:

| Columna | Descripción |
|---------|-------------|
| **Fecha/Hora** | Momento del movimiento |
| **Tipo** | Ingreso (verde) / Egreso (rojo) |
| **Concepto** | Descripción del movimiento |
| **Monto** | Importe |
| **Usuario** | Quien registró |
| **Saldo** | Saldo después del movimiento |
| **Acciones** | Ver detalles |

#### Tipos de Movimientos

**INGRESOS (Verde):**
- 💵 Ventas en efectivo
- 💳 Ventas con tarjeta
- 🏦 Ventas con transferencia
- 💰 Otros ingresos

**EGRESOS (Rojo):**
- 📦 Compra de mercadería
- 💡 Pago de servicios
- 💼 Gastos operativos
- 🔁 Anulación de ventas
- 💸 Retiros

#### Filtros

**Por Fecha:**
- Hoy
- Ayer
- Última semana
- Último mes
- Personalizado

**Por Tipo:**
- Solo Ingresos
- Solo Egresos
- Todos

**Por Usuario:**
- Seleccione un usuario específico

---

### ➕ REGISTRAR UN INGRESO

**Ubicación:** Caja → Movimientos → Botón "Registrar Ingreso"

#### ¿Cuándo registrar ingresos?

- Dinero recibido NO proveniente de ventas
- Aportes de capital
- Devoluciones
- Cobros diversos

**⚠️ NOTA:** Las ventas se registran automáticamente, NO las registre manualmente.

#### Pasos

1. Haga clic en **"Registrar Ingreso"**

2. Complete el formulario:

   **Concepto:** *
   - Descripción clara del ingreso
   - Ejemplo: "Aporte de capital socio"
   - Mínimo 3 caracteres

   **Monto:** *
   - Importe del ingreso
   - Ejemplo: 5000.00
   - Solo números, sin el símbolo $

   **Descripción:** (Opcional)
   - Detalles adicionales
   - Ejemplo: "Aporte en efectivo para compra de mercadería"

3. Haga clic en **"Registrar"**

**El sistema:**
- ✅ Suma el monto al saldo de caja
- ✅ Registra fecha, hora y usuario
- ✅ Muestra el nuevo saldo

**Ejemplo:**
```
┌─────────────────────────────────────┐
│ REGISTRAR INGRESO                   │
├─────────────────────────────────────┤
│ Saldo Actual: $15,000.00            │
│                                      │
│ Concepto: *                         │
│ [Aporte de capital socio]           │
│                                      │
│ Monto: *                            │
│ [5000.00]                           │
│                                      │
│ Descripción:                        │
│ [Aporte en efectivo...]             │
│                                      │
│      [ Cancelar ] [ Registrar ]     │
└─────────────────────────────────────┘

NUEVO SALDO: $20,000.00
```

---

### ➖ REGISTRAR UN EGRESO

**Ubicación:** Caja → Movimientos → Botón "Registrar Egreso"

#### ¿Cuándo registrar egresos?

- 📦 Compra de mercadería (pago al proveedor)
- 💡 Pago de servicios (luz, agua, internet)
- 💼 Gastos operativos (limpieza, papelería)
- 🔧 Mantenimiento
- 💸 Retiros del dueño
- 👷 Pago de salarios

#### Pasos

1. Haga clic en **"Registrar Egreso"**

2. Complete el formulario:

   **Concepto:** *
   - Descripción del gasto
   - Ejemplo: "Pago factura de luz"
   - Mínimo 3 caracteres

   **Monto:** *
   - Importe del egreso
   - Ejemplo: 2500.00

   **Descripción:** (Opcional)
   - Detalles adicionales
   - Número de factura
   - Proveedor

3. Haga clic en **"Registrar"**

**El sistema:**
- ✅ Resta el monto del saldo de caja
- ✅ Registra fecha, hora y usuario
- ✅ Muestra el nuevo saldo

**⚠️ ADVERTENCIA:**
Si el egreso deja el saldo en negativo, el sistema:
- Permite registrarlo
- Muestra una advertencia
- Registra en el log

---

### 🧮 REALIZAR ARQUEO DE CAJA

**Ubicación:** Menú → Caja → Arqueo

#### ¿Qué es un Arqueo?

Es el **conteo físico del dinero en efectivo** en caja para verificar que coincida con el sistema.

**⚠️ IMPORTANTE:** El arqueo ahora diferencia entre efectivo físico y pagos electrónicos.

#### ¿Cuándo hacer arqueo?

- ✅ Al final del día (cierre diario)
- ✅ Cambio de turno
- ✅ Semanalmente
- ✅ Cuando hay dudas sobre el saldo

---

#### Vista de Arqueo - Información Mostrada

**1️⃣ Panel de Resumen del Día:**

| Indicador | Descripción |
|-----------|-------------|
| **Total Ingresos** | Suma de todos los ingresos del día |
| **Total Egresos** | Suma de todos los egresos del día |
| **Ventas del Día** | Total de ventas (todos los métodos) |
| **Saldo del Día** | Ingresos - Egresos (neto) |

**2️⃣ ✨ NUEVO: Desglose por Método de Pago**

El sistema muestra **4 tarjetas de colores** con el detalle de cada método:

```
┌──────────────────────────────────────────────────┐
│ 💵 EFECTIVO              $10,500.00              │
│    ℹ️  Debe estar en caja física                 │
├──────────────────────────────────────────────────┤
│ 💳 TARJETA               $8,750.00               │
│    🏦 Va a cuenta bancaria                       │
├──────────────────────────────────────────────────┤
│ 🏦 TRANSFERENCIA         $5,200.00               │
│    🏦 Va a cuenta bancaria                       │
├──────────────────────────────────────────────────┤
│ 📋 CUENTA CORRIENTE      $2,450.00               │
│    🤝 Crédito a clientes                         │
└──────────────────────────────────────────────────┘

TOTAL VENTAS: $26,900.00
```

**Si hay pagos mixtos:**
```
💰 Pagos Mixtos: $1,500.00
Desglosados:
  - Efectivo: $800.00
  - Tarjeta: $500.00
  - Transferencia: $200.00
```

**3️⃣ ✨ NUEVO: Cálculo de Efectivo Esperado**

El sistema calcula automáticamente cuánto efectivo DEBE haber en la caja:

```
┌──────────────────────────────────────────────────┐
│ 💵 EFECTIVO ESPERADO EN CAJA                     │
├──────────────────────────────────────────────────┤
│ + Ventas en Efectivo           $10,500.00       │
│ + Otros Ingresos Efectivo      $1,200.00        │
│ - Egresos en Efectivo          $3,500.00        │
├──────────────────────────────────────────────────┤
│ = EFECTIVO QUE DEBE HABER      $8,200.00        │
└──────────────────────────────────────────────────┘

⚠️ Importante: Solo cuenta el efectivo físico
   (billetes y monedas). No incluyas tarjeta,
   transferencias ni MercadoPago.
```

**4️⃣ Movimientos del Día**

Tabla detallada con:
- Hora
- Tipo (Ingreso/Egreso)
- Concepto
- Usuario
- Monto

---

#### ✨ NUEVO: Realizar el Conteo de Efectivo

**Paso 1: Contar SOLO el Efectivo Físico**

⚠️ **MUY IMPORTANTE:**

**SÍ cuente:**
- ✅ Billetes en la caja
- ✅ Monedas en la caja
- ✅ Todo el efectivo físico

**NO cuente:**
- ❌ Dinero de tarjeta (va al banco)
- ❌ Transferencias (van al banco)
- ❌ MercadoPago (va a plataforma)
- ❌ Cuenta corriente (no es efectivo)

**Paso 2: Ingresar el Monto Contado**

1. En el campo **"💵 Efectivo Contado en Caja"**:
   - Ingrese el total de efectivo físico contado
   - Ejemplo: 8200.00
   - Campo grande y destacado

2. El sistema mostrará en tiempo real:
   - **Efectivo Esperado en Sistema:** $8,200.00
   - **Diferencia:** Se calcula automáticamente

**Paso 3: Ver la Comparación Automática**

El sistema compara automáticamente y muestra:

**✅ SIN DIFERENCIA ($0):**
```
✅ ¡Perfecto! El efectivo contado coincide con el sistema.
No hay diferencias en el arqueo de efectivo.
```

**⚠️ SOBRANTE:**
```
⚠️ Sobrante de Efectivo: $250.00
Hay MÁS dinero físico del que debería haber según el sistema
```

**❌ FALTANTE:**
```
❌ Faltante de Efectivo: $150.00
Hay MENOS dinero físico del que debería haber según el sistema
```

**Ejemplo completo:**
```
┌──────────────────────────────────────────────┐
│ CONTEO FÍSICO DE EFECTIVO                    │
├──────────────────────────────────────────────┤
│                                               │
│ 💵 Efectivo Contado en Caja:                 │
│ [  8200.00  ] ← Ingrese aquí                 │
│                                               │
│ 📊 Efectivo esperado en sistema: $8,200.00  │
│                                               │
│ ✅ ¡Perfecto! El efectivo coincide           │
│                                               │
│ 📝 Observaciones: (opcional)                 │
│ [...notas adicionales...]                    │
│                                               │
│ [ Registrar Cierre de Caja ]                 │
└──────────────────────────────────────────────┘
```

**Paso 4: Agregar Observaciones (si hay diferencia)**

Si hay sobrante o faltante, explique:
- "Vuelto mal dado en venta #123"
- "Billete roto reemplazado"
- "Error en arqueo anterior"
- "Pago recibido no registrado"

**Paso 5: Registrar Cierre de Caja**

1. Revise que todo esté correcto
2. Haga clic en **"Registrar Cierre de Caja"**
3. Si hay diferencia > $100, pedirá confirmación
4. Confirme el cierre

**El sistema registra:**
- ✅ Fecha y hora del cierre
- ✅ Usuario que realizó el arqueo
- ✅ Efectivo esperado
- ✅ Efectivo contado
- ✅ Diferencia (si la hay)
- ✅ Observaciones
- ✅ Desglose por método de pago

---

#### 🔍 Verificación Posterior

**Después del arqueo, verifique en sus plataformas:**

**1. Cuenta Bancaria:**
- Revise que los depósitos de tarjeta coincidan
- Verifique las transferencias recibidas
- Compare con el desglose del sistema

**2. MercadoPago / Billetera Digital:**
- Entre a la app/plataforma
- Verifique el saldo disponible
- Compare con el total mostrado en el sistema

**3. Cuentas Corrientes:**
- Revise que los saldos de clientes sean correctos
- No requiere validación de efectivo

**Ejemplo de verificación completa:**
```
ARQUEO DEL DÍA:
✅ Efectivo en caja:      $8,200 (contado y verificado)
🏦 Tarjeta (en banco):    $5,000 (verificar extracto mañana)
🏦 Transferencias:        $3,000 (verificar extracto mañana)
📱 MercadoPago:           $1,500 (verificado en app ✓)
📋 Cuenta Corriente:      $2,000 (registrado en sistema)
───────────────────────────────────
TOTAL DEL DÍA:           $19,700
```

---

#### ⚠️ ¿Qué hacer si hay diferencia?

**Si hay FALTANTE:**

1. **Revise:**
   - Vueltos mal dados
   - Ventas no registradas
   - Egresos olvidados
   - Errores de carga

2. **Investigue:**
   - Revise ticket por ticket del día
   - Verifique los movimientos de caja
   - Consulte con otros usuarios

3. **Registre:**
   - Anote el motivo en observaciones
   - Si no encuentra la causa: "Diferencia no identificada"
   - Complete el cierre de todas formas

**Si hay SOBRANTE:**

1. **Revise:**
   - Ingresos no registrados
   - Egresos registrados de más
   - Pagos recibidos no cargados
   - Arqueo anterior incorrecto

2. **Verifique:**
   - Que no sea dinero de otro día
   - Que no sea dinero personal
   - Que el conteo sea correcto

3. **Registre:**
   - Explique de dónde vino
   - Si no sabe: "Sobrante no identificado"

**Diferencias Comunes y Soluciones:**

| Diferencia | Causa Probable | Solución |
|------------|----------------|----------|
| -$10 a -$50 | Vuelto mal dado | Normal, registrar |
| +$10 a +$50 | Cobro de más / Cliente no reclamó vuelto | Normal, registrar |
| -$100+ | Error en registro o falta de dinero | Investigar urgente |
| +$100+ | Ingreso no registrado o error | Investigar urgente |
| Múltiplos de $100 | Billete no contado / contado dos veces | Recontar |

---

### 🔒 CERRAR CAJA

**Ubicación:** Caja → Arqueo → Botón "Cerrar Caja"

#### ¿Qué es Cerrar Caja?

Es el proceso de **finalizar el día comercial** después de hacer el arqueo.

#### ¿Cuándo cerrar?

- Al finalizar el día
- Después de hacer el arqueo
- Antes de hacer retiros grandes

#### Pasos

1. Realice el arqueo primero
2. Haga clic en **"Cerrar Caja"**
3. Confirme el cierre

**El sistema:**
- ✅ Registra el cierre con fecha y hora
- ✅ Guarda el saldo de cierre
- ✅ Registra diferencias si las hay
- ✅ Genera un comprobante de cierre

**Comprobante de Cierre incluye:**
- Saldo inicial
- Total ingresos
- Total egresos
- Saldo teórico
- Saldo físico
- Diferencia
- Usuario y fecha

---

## 👤 MÓDULO DE USUARIOS

### ¿Qué puedo hacer en este módulo?

**⚠️ Solo Administradores**

- ✅ Ver listado de usuarios
- ✅ Crear usuarios nuevos
- ✅ Editar información de usuarios
- ✅ Cambiar contraseñas
- ✅ Asignar roles
- ✅ Ver estadísticas de usuarios
- ✅ Eliminar usuarios

---

### 📋 VER LISTADO DE USUARIOS

**Ubicación:** Menú → Usuarios → Listado

#### Información Mostrada

| Columna | Descripción |
|---------|-------------|
| **Nombre** | Nombre completo del usuario |
| **Usuario** | Nombre de usuario para login |
| **Email** | Correo electrónico |
| **Rol** | Administrador / Vendedor |
| **Ventas** | Cantidad de ventas realizadas |
| **Último Acceso** | Última vez que ingresó |
| **Estado** | Activo / Inactivo |
| **Acciones** | Botones |

#### Indicadores de Rol

**🔴 Administrador:**
- Acceso total al sistema
- Badge rojo

**🔵 Vendedor:**
- Acceso limitado
- Badge azul

---

### ➕ CREAR UN USUARIO NUEVO

**Ubicación:** Usuarios → Botón "Nuevo Usuario"

#### Paso 1: Datos Personales

**Nombre Completo:** *
- Ejemplo: María González
- Solo letras y espacios
- Mínimo 3 caracteres

**Email:** *
- Ejemplo: maria.gonzalez@email.com
- Debe ser único
- Formato válido de email

#### Paso 2: Credenciales de Acceso

**Nombre de Usuario:** *
- Ejemplo: mgonzalez o maria.g
- Solo letras, números, puntos, guiones
- Mínimo 4 caracteres
- Debe ser único

**Contraseña:** *
- Mínimo 6 caracteres
- Puede incluir letras, números y símbolos
- Recomendación: Use combinación de mayúsculas, minúsculas y números

**Confirmar Contraseña:** *
- Debe coincidir con la contraseña

#### Paso 3: Asignar Rol

Seleccione el rol del usuario:

**🔴 Administrador:**

**Permisos:**
- ✅ Crear/editar productos
- ✅ Crear/editar clientes
- ✅ Crear/editar proveedores
- ✅ Registrar ventas
- ✅ Administrar caja
- ✅ Crear/editar usuarios
- ✅ Ver todos los reportes
- ✅ Configuración del sistema

**🔵 Vendedor:**

**Permisos:**
- ✅ Registrar ventas
- ✅ Consultar productos
- ✅ Consultar clientes
- ✅ Ver movimientos de caja
- ❌ NO puede crear usuarios
- ❌ NO puede editar productos
- ❌ NO puede ver reportes completos

#### Paso 4: Guardar

1. Revise todos los datos
2. Haga clic en **"Crear Usuario"**

**El sistema valida:**
- Usuario único
- Email único
- Contraseñas coincidentes
- Formato correcto

**Mensaje de éxito:**
```
✓ Usuario creado exitosamente
Usuario: mgonzalez
Contraseña: [la que ingresó]
```

**⚠️ IMPORTANTE:**
- Anote las credenciales
- Entréguelas al usuario de forma segura
- El usuario puede cambiar su contraseña después

---

### ✏️ EDITAR UN USUARIO

**Ubicación:** Usuarios → Listado → Botón "Editar"

#### ¿Qué puedo modificar?

- ✅ Nombre completo
- ✅ Email
- ✅ Nombre de usuario
- ✅ Rol
- ✅ Contraseña (ver sección siguiente)

**⚠️ Restricciones:**
- ❌ No puede cambiar su propio rol
- ❌ No puede eliminar el último administrador

#### Pasos

1. Haga clic en el ícono de lápiz ✏️
2. Modifique los campos necesarios
3. Para cambiar la contraseña:
   - Complete el campo "Nueva Contraseña"
   - Confirme la contraseña
   - Si deja en blanco, NO se cambia
4. Haga clic en **"Actualizar Usuario"**

---

### 🔑 CAMBIAR CONTRASEÑA

#### Cambiar Contraseña de Otro Usuario

**Ubicación:** Usuarios → Editar Usuario

1. Edite el usuario
2. Complete:
   - **Nueva Contraseña**
   - **Confirmar Contraseña**
3. Guarde los cambios
4. Informe al usuario su nueva contraseña

#### Cambiar Mi Propia Contraseña

**Ubicación:** Menú Superior → Mi Perfil

1. Haga clic en su nombre (esquina superior derecha)
2. Seleccione **"Mi Perfil"**
3. Haga clic en **"Cambiar Contraseña"**
4. Complete:
   - **Contraseña Actual** *
   - **Nueva Contraseña** *
   - **Confirmar Contraseña** *
5. Haga clic en **"Actualizar"**

**Seguridad:**
- Debe ingresar su contraseña actual
- La nueva debe tener mínimo 6 caracteres
- No use contraseñas obvias

---

### 📊 VER ESTADÍSTICAS DE UN USUARIO

**Ubicación:** Usuarios → Ver Usuario

#### Información Mostrada

**Resumen:**
- Nombre y datos de contacto
- Rol asignado
- Fecha de registro
- Último acceso

**Estadísticas de Ventas:**
- Total de ventas realizadas
- Ventas de hoy
- Ventas del mes
- Total facturado (histórico)
- Ticket promedio

**Movimientos de Caja:**
- Ingresos registrados
- Egresos registrados
- Último movimiento

**Últimas Ventas:**
Lista de las 10 últimas ventas:
- Número de venta
- Cliente
- Fecha
- Total
- Ver detalles

---

### 🗑️ ELIMINAR UN USUARIO

**Ubicación:** Usuarios → Listado → Botón "Eliminar"

**⚠️ Restricciones:**

**NO se puede eliminar si:**
- ❌ Es el último administrador del sistema
- ❌ Tiene ventas registradas (se mantiene historial)

**Alternativa: Desactivar**

En lugar de eliminar:
1. Edite el usuario
2. Desmarque **"Usuario Activo"**
3. No podrá iniciar sesión
4. Se mantiene el historial

---

## 📊 MÓDULO DE REPORTES

### ¿Qué puedo hacer en este módulo?

**⚠️ Solo Administradores**

- ✅ Reportes de Ventas
- ✅ Reportes de Productos
- ✅ Reportes de Clientes
- ✅ Reportes de Caja
- ✅ Análisis de Rentabilidad
- ✅ Exportar datos

---

### 📈 REPORTE DE VENTAS

**Ubicación:** Menú → Reportes → Ventas

#### Filtros

**Por Período:**
- Hoy
- Ayer
- Última semana
- Último mes
- Último año
- Personalizado (fecha inicio - fecha fin)

#### Información Mostrada

**Resumen General:**

```
┌─────────────────────────────────────┐
│ RESUMEN DE VENTAS                   │
├─────────────────────────────────────┤
│ Total de Ventas:      $125,000.00  │
│ Cantidad de Ventas:   150           │
│ Ticket Promedio:      $833.33       │
└─────────────────────────────────────┘
```

**Ventas por Método de Pago:**

Gráfico de torta mostrando:
- Efectivo: XX%
- Tarjeta: XX%
- Transferencia: XX%
- Cuenta Corriente: XX%
- Mixto: XX%

**Ventas por Vendedor:**

Tabla con:
- Vendedor
- Cantidad de ventas
- Total vendido
- Porcentaje del total

**Ventas por Día:**

Gráfico de líneas con:
- Eje X: Días del período
- Eje Y: Monto vendido
- Permite identificar picos y valles

**Listado Detallado:**

Tabla con todas las ventas:
- Número
- Fecha
- Cliente
- Total
- Método de pago
- Vendedor
- Estado

**Opciones:**
- 📊 Ver gráfico
- 📄 Exportar a Excel
- 🖨️ Imprimir reporte
- 📧 Enviar por email

---

### 📦 REPORTE DE PRODUCTOS

**Ubicación:** Menú → Reportes → Productos

#### Filtros

**Por Período:**
- Seleccione rango de fechas para análisis

**Por Categoría:**
- Todas
- Seleccione categoría específica

**Por Proveedor:**
- Todos
- Seleccione proveedor específico

#### Información Mostrada

**Resumen:**

```
┌─────────────────────────────────────┐
│ ESTADÍSTICAS DE PRODUCTOS           │
├─────────────────────────────────────┤
│ Productos Activos:        250       │
│ Productos Vendidos:       180       │
│ Unidades Vendidas:        5,420     │
│ Ingresos Totales:    $125,000.00   │
│ Stock Valorizado:    $45,000.00    │
│ Productos sin Mov.:       70        │
└─────────────────────────────────────┘
```

**Productos Más Vendidos:**

Top 10 productos:
- Posición (#1, #2, etc.)
- Nombre del producto
- Unidades vendidas
- Ingresos generados
- Gráfico de barras

**Productos con Menos Rotación:**

Productos que no se vendieron o vendieron poco:
- Útil para decidir discontinuar
- Evaluar estrategias de venta

**Stock Actual:**

Tabla con:
- Producto
- Stock actual
- Stock mínimo
- Valor unitario
- Valor total en stock
- Estado

**Análisis por Categoría:**

Gráfico circular:
- Ventas por categoría
- Porcentaje de cada una

---

### 👥 REPORTE DE CLIENTES

**Ubicación:** Menú → Reportes → Clientes

#### Información Mostrada

**Resumen General:**

```
┌─────────────────────────────────────┐
│ ESTADÍSTICAS DE CLIENTES            │
├─────────────────────────────────────┤
│ Total Clientes:           320       │
│ Clientes Activos:         280       │
│ Clientes con Compras:     195       │
│ Total Facturado:     $125,000.00   │
│ Compra Promedio:         $641.03    │
│ Clientes con Deuda:        45       │
│ Total Deuda:         $12,500.00    │
└─────────────────────────────────────┘
```

**Top Clientes:**

Los 10 clientes que más compraron:
- Nombre
- Total comprado
- Cantidad de compras
- Última compra
- Ticket promedio

**Clientes por Tipo:**

Gráfico:
- Minoristas
- Mayoristas
- Especiales

**Estado de Cuentas Corrientes:**

**Resumen CC:**
- Total de clientes con CC
- Clientes deudores
- Monto total adeudado
- Clientes con saldo a favor
- Límite de crédito otorgado vs usado

**Listado de Deudores:**

Tabla con:
- Cliente
- Saldo CC (deuda)
- Límite
- Porcentaje usado
- Días desde última compra
- Acciones (contactar, ver detalle)

---

### 💰 REPORTE DE CAJA

**Ubicación:** Menú → Reportes → Caja

#### Filtros

**Por Período:**
- Hoy
- Última semana
- Último mes
- Personalizado

**Por Tipo:**
- Ingresos
- Egresos
- Todos

#### Información Mostrada

**Resumen del Período:**

```
┌─────────────────────────────────────┐
│ RESUMEN DE CAJA                     │
├─────────────────────────────────────┤
│ Total Ingresos:      $125,000.00   │
│ Total Egresos:       $45,000.00    │
│ SALDO DEL PERÍODO:   $80,000.00    │
│                                      │
│ Cantidad Ingresos:        150       │
│ Cantidad Egresos:          45       │
│ Promedio Ingreso:        $833.33    │
│ Promedio Egreso:       $1,000.00    │
│                                      │
│ SALDO ACTUAL:        $80,000.00    │
└─────────────────────────────────────┘
```

**Ingresos por Concepto:**

Gráfico y tabla:
- Ventas: $XX
- Aportes: $XX
- Otros ingresos: $XX

**Egresos por Concepto:**

Gráfico y tabla:
- Compra de mercadería: $XX
- Servicios: $XX
- Gastos operativos: $XX
- Retiros: $XX
- Otros: $XX

**Movimientos por Día:**

Gráfico de barras apiladas:
- Verde: Ingresos del día
- Rojo: Egresos del día
- Línea: Saldo neto

**Flujo de Efectivo:**

Gráfico de líneas mostrando:
- Evolución del saldo en el tiempo
- Identificar tendencias

---

### 📊 ANÁLISIS DE RENTABILIDAD

**Ubicación:** Menú → Reportes → Rentabilidad

#### ¿Qué muestra?

Análisis de **ganancias** por producto comparando:
- Precio de venta
- Precio de costo
- Margen de ganancia
- Rentabilidad

#### Información Mostrada

**Resumen Global:**

```
┌─────────────────────────────────────┐
│ ANÁLISIS DE RENTABILIDAD            │
├─────────────────────────────────────┤
│ Ingresos Totales:    $125,000.00   │
│ Costos Totales:      $75,000.00    │
│ MARGEN BRUTO:        $50,000.00    │
│ % Margen Promedio:    40.00%       │
│ Productos Analizados:  180          │
└─────────────────────────────────────┘
```

**Productos por Rentabilidad:**

Tabla ordenada:
- Producto
- Unidades vendidas
- Ingresos (precio de venta × cantidad)
- Costos (precio de compra × cantidad)
- Margen bruto ($)
- Margen bruto (%)

**Ejemplo:**

| Producto | Vendidos | Ingresos | Costos | Margen $ | Margen % |
|----------|----------|----------|--------|----------|----------|
| Coca 500ml | 200 | $4,000 | $2,400 | $1,600 | 66.67% |
| Alfajor Jorg | 150 | $3,000 | $2,100 | $900 | 42.86% |

**Productos Más Rentables:**

Top 10 productos con mejor margen:
- Útil para priorizar ventas
- Promocionar productos rentables

**Productos Menos Rentables:**

Productos con bajo margen:
- Evaluar si aumentar precio
- Considerar cambiar de proveedor
- Discontinuar si no es estratégico

**Gráficos:**

- 📊 Margen por categoría
- 📈 Evolución de rentabilidad en el tiempo
- 🥧 Distribución de márgenes

---

### 📤 EXPORTAR REPORTES

Todos los reportes pueden exportarse en diferentes formatos:

#### Formatos Disponibles

**📄 PDF:**
- Listo para imprimir
- Mantiene formato
- Incluye gráficos

**📊 Excel:**
- Datos en tablas
- Permite análisis adicional
- Fórmulas y filtros

**📧 Email:**
- Envía el reporte por correo
- Seleccione formato (PDF o Excel)
- Ingrese email de destino

#### Pasos para Exportar

1. Aplique los filtros deseados
2. Haga clic en **"Exportar"**
3. Seleccione el formato
4. El archivo se descargará automáticamente

**Nombre del archivo:**
```
reporte_ventas_20250101_20250131.pdf
reporte_productos_enero2025.xlsx
```

---

## ❓ PREGUNTAS FRECUENTES

### Sobre el Sistema

**¿Necesito internet para usar KioscoNet?**
- Depende de cómo esté instalado
- Si es local: NO necesita internet
- Si es en la nube: SÍ necesita internet

**¿Puedo usar KioscoNet en mi celular?**
- SÍ, el sistema es responsive
- Se adapta a pantallas pequeñas
- Algunas funciones son más cómoda en PC

**¿Cuántos usuarios pueden estar conectados al mismo tiempo?**
- Ilimitados (depende del servidor)
- Cada usuario tiene su sesión independiente

### Sobre Ventas

**¿Puedo anular una venta del mes pasado?**
- NO, solo dentro de las 24 horas
- Esto protege la integridad del sistema
- Consulte a su administrador para casos especiales

**¿Qué pasa si pierdo la conexión durante una venta?**
- El sistema guarda borradores localmente
- Recupere la venta al reconectar
- No se pierde información

**¿Puedo hacer una venta sin stock?**
- NO, el sistema lo impide
- Debe ajustar el stock primero
- Esto evita ventas incorrectas

**¿Cómo aplico un descuento especial?**
- En la venta, use "Descuento Global"
- O aplique descuento por producto
- También puede crear precio "Especial" en el producto

### Sobre Productos

**¿Cómo actualizo precios de varios productos a la vez?**
- Actualmente debe hacerlo uno por uno
- Próximamente: actualización masiva

**¿Qué hago con productos vencidos?**
- Ajuste el stock a 0
- Marque como inactivo
- Registre un egreso en caja si corresponde

**¿Puedo tener dos productos con el mismo código?**
- NO, los códigos deben ser únicos
- El sistema lo validará

### Sobre Clientes

**¿Cómo aumento el límite de crédito de un cliente?**
- Edite el cliente
- Modifique el campo "Límite de Crédito"
- Guarde los cambios

**¿El cliente puede ver su estado de cuenta?**
- Depende de la configuración
- Por defecto, solo usuarios del sistema

**¿Puedo tener clientes duplicados?**
- Técnicamente sí
- NO recomendado: dificulta el seguimiento
- Use la búsqueda antes de crear

### Sobre Caja

**¿Por qué el saldo de caja es negativo?**
- Hay más egresos que ingresos
- Revise los movimientos del día
- Puede ser normal si hay retiros

**¿Debo cerrar caja todos los días?**
- Recomendado para mejor control
- No es obligatorio
- Facilita arqueos y auditorías

**✨ ¿Debo contar el dinero de tarjeta y transferencias en el arqueo?**
- ❌ **NO**. Solo cuente el efectivo físico (billetes y monedas)
- El dinero de tarjeta va al banco, no está en la caja
- Las transferencias van a la cuenta bancaria
- MercadoPago va a la plataforma digital
- El sistema muestra un desglose para que verifiques cada método por separado

**✨ ¿Qué es el "Efectivo Esperado" en el arqueo?**
- Es el cálculo automático del efectivo que DEBE haber en la caja
- Suma: Ventas en efectivo + Otros ingresos efectivo
- Resta: Egresos en efectivo
- Compare este número con el efectivo físico que contó

**✨ ¿Cómo verifico si el dinero de tarjeta es correcto?**
- El sistema muestra cuánto se cobró con tarjeta en el día
- Al día siguiente, revise el extracto bancario
- Verifique que los depósitos coincidan con el total del sistema
- Lo mismo aplica para transferencias

**¿Qué hago si hay diferencia en el arqueo de efectivo?**
- Investigue la causa (vueltos mal dados, ventas no registradas, etc.)
- Revise movimientos del día
- Verifique las ventas en efectivo
- Agregue observaciones explicando la diferencia
- Complete el cierre de todas formas

**✨ ¿Qué hago si el efectivo contado es mayor al esperado?**
- Puede ser dinero de otro día
- Pago recibido no registrado
- Error en arqueo anterior
- Cliente que no reclamó vuelto
- Anote la diferencia en observaciones

---

## 🔧 SOLUCIÓN DE PROBLEMAS

### Problemas de Acceso

#### No puedo iniciar sesión

**Síntomas:**
- Dice "Usuario o contraseña incorrectos"

**Soluciones:**
1. Verifique el usuario (mayúsculas/minúsculas)
2. Verifique la contraseña
3. Verifique el teclado (Bloq Mayús, idioma)
4. Contacte al administrador para resetear

#### Se cerró mi sesión automáticamente

**Causa:**
- Inactividad prolongada (seguridad)

**Solución:**
- Vuelva a iniciar sesión
- Sus datos NO se pierden

### Problemas en Ventas

#### No puedo encontrar un producto

**Soluciones:**
1. Verifique que esté **Activo**
2. Busque por código de barras
3. Busque por parte del nombre
4. Revise si existe en Productos

#### El botón "Procesar Venta" está deshabilitado

**Causas posibles:**
1. No hay productos en el carrito
2. No seleccionó cliente
3. No seleccionó método de pago
4. Datos incompletos (ej: monto recibido)

**Solución:**
- Complete todos los campos requeridos (*)
- El botón se habilitará automáticamente

#### Error: "Stock insuficiente"

**Causa:**
- Está intentando vender más de lo disponible

**Soluciones:**
1. Reduzca la cantidad
2. Verifique stock en Productos
3. Ajuste el stock si es necesario

### Problemas con Productos

#### No puedo editar un producto

**Causas:**
1. No tiene permisos (es vendedor)
2. Error de conexión

**Solución:**
- Inicie sesión como administrador
- Verifique la conexión

#### La imagen no se sube

**Causas:**
1. Archivo muy grande (máx 2MB)
2. Formato no válido (solo JPG, PNG, GIF)

**Soluciones:**
1. Reduzca el tamaño de la imagen
2. Convierta a formato válido

### Problemas con Caja

#### El saldo de caja no es correcto

**Pasos:**
1. Vaya a Reportes → Caja
2. Revise todos los movimientos
3. Busque duplicados o errores
4. Consulte al administrador

#### No puedo registrar un egreso

**Causa:**
- Monto mayor al disponible (validación)

**Solución:**
- El sistema permite egresos que dejen saldo negativo
- Verifique que ingresó el monto correctamente

### Problemas Generales

#### La página se ve mal / desorganizada

**Causa:**
- Caché del navegador

**Solución:**
1. Presione Ctrl + F5 (forzar recarga)
2. Borre caché del navegador
3. Cierre y abra el navegador

#### El sistema está muy lento

**Causas:**
1. Muchos datos cargados
2. Internet lento
3. Servidor saturado

**Soluciones:**
1. Use filtros para reducir datos
2. Verifique su conexión
3. Contacte soporte técnico

#### No se imprimen los tickets

**Soluciones:**
1. Verifique que la impresora esté conectada
2. Verifique que esté configurada como predeterminada
3. Haga una prueba de impresión desde Windows
4. Revise drivers de la impresora

---

## 📞 CONTACTO Y SOPORTE

### Soporte Técnico

Para asistencia técnica o consultas:

**Email:** soporte@kiosconet.com
**Teléfono:** [Su número de soporte]
**Horario:** Lunes a Viernes, 9:00 a 18:00 hs

### Manual y Documentación

**Manual en PDF:** Disponible en el sistema
**Videos Tutoriales:** [URL de videos]
**Base de Conocimiento:** [URL de KB]

---

## 📝 NOTAS FINALES

### Buenas Prácticas

✅ **Haga backups periódicos** de la base de datos
✅ **Realice arqueos diarios** para control
✅ **Actualice precios regularmente**
✅ **Revise stock bajo semanalmente**
✅ **Capacite a nuevos usuarios** antes de usarlo
✅ **Use contraseñas seguras**
✅ **No comparta credenciales**

### Seguridad

⚠️ **Cierre sesión** al terminar
⚠️ **No deje el sistema abierto** sin supervisión
⚠️ **Cambie contraseñas** periódicamente
⚠️ **Revise reportes** regularmente

---

## 🎯 CONCLUSIÓN

**KioscoNet** es una herramienta completa diseñada para facilitar la gestión de su kiosco. Este manual cubre todas las funcionalidades principales del sistema.

**Recuerde:**
- Ante cualquier duda, consulte este manual
- Contacte a soporte técnico si necesita ayuda
- Sugiera mejoras para futuras versiones

---

**Manual de Usuario KioscoNet v3.0**
*Última actualización: Enero 2025*
*Desarrollado por: [Su empresa]*

📘 **Fin del Manual**
