# Filament DevTool

[![Latest Version on Packagist](https://img.shields.io/packagist/v/joserick/filament-devtool.svg)](https://packagist.org/packages/joserick/filament-devtool)
[![License](https://img.shields.io/packagist/l/joserick/filament-devtool.svg)](https://github.com/joserick/filament-devtool/blob/main/LICENSE)

Herramienta de desarrollo para agilizar la creación de componentes de **FilamentPHP v5** dentro de paquetes de Laravel. Reemplaza los comandos `make:` originales de Filament con versiones que generan el código directamente en la estructura `src/` del paquete en lugar de hacerlo en el esqueleto de la aplicación Laravel.

## Características

- 🚀 **Todos los comandos `make:` de Filament** reescritos para desarrollo de paquetes
- 📦 Generación de archivos en `src/` usando [Orchestra Canvas](https://github.com/orchestral/canvas)
- 🐳 Entorno de desarrollo con Docker/Sail incluido
- 🧪 Integración con [Orchestra Testbench](https://github.com/orchestral/testbench) para testing de paquetes
- ⚡ Sin necesidad de una aplicación Laravel completa para desarrollar componentes

## Requisitos

- PHP 8.3 o superior
- [Composer](https://getcomposer.org/) 2.2+
- [Docker](https://www.docker.com/) (para el entorno de desarrollo con Sail)

## Instalación

### Como dependencia de desarrollo en tu paquete

```bash
composer require --dev joserick/filament-devtool
```

El paquete se registrará automáticamente gracias al descubrimiento de Laravel.

### Configurar el entorno de desarrollo

Clona el repositorio y ejecuta el script de instalación:

```bash
git clone https://github.com/joserick/filament-devtool.git
cd filament-devtool
bash install.sh
```

El script `install.sh`:

1. Instala las dependencias de Composer usando Docker
2. Construye la imagen Sail para PHP 8.5
3. Configura los permisos del directorio `vendor/`

Una vez finalizado, inicia los contenedores:

```bash
vendor/bin/sail up -d
```

## Uso

### Comandos disponibles

Todos los comandos `make:` de Filament están disponibles a través de Canvas. Ejecútalos usando el alias `canvas`:

```bash
vendor/bin/sail php vendor/bin/canvas
```

#### Comandos incluidos

| Comando | Descripción |
|---|---|
| `make:filament-resource` | Crea un Resource de panel |
| `make:filament-page` | Crea una Page de panel |
| `make:filament-widget` | Crea un Widget |
| `make:filament-cluster` | Crea un Cluster de panel |
| `make:filament-relation-manager` | Crea un Relation Manager |
| `make:form-field` | Crea un campo de formulario |
| `make:form` | Crea un componente de formulario |
| `make:livewire-form` | Crea un formulario Livewire |
| `make:rich-content-custom-block` | Crea un bloque personalizado para Rich Content |
| `make:table-column` | Crea una columna de tabla |
| `make:table` | Crea un componente de tabla |
| `make:livewire-table` | Crea una tabla Livewire |
| `make:infolist-entry` | Crea una entrada de Infolist |
| `make:schema-component` | Crea un componente de Schema |
| `make:schema` | Crea un Schema |
| `make:livewire-schema` | Crea un Schema Livewire |
| `make:filament-importer` | Crea un Importer |
| `make:filament-exporter` | Crea un Exporter |
| `make:filament-issue` | Crea un Issue |

#### Ejemplos

```bash
# Crear un recurso
vendor/bin/sail php vendor/bin/canvas make:filament-resource User

# Crear un campo de formulario
vendor/bin/sail php vendor/bin/canvas make:form-field ColorPicker

# Crear una página
vendor/bin/sail php vendor/bin/canvas make:filament-page Settings
```

Los archivos generados se colocarán automáticamente en `src/` siguiendo la estructura del paquete.

### Alias de terminal recomendados

Agrega estos alias a tu `~/.bashrc` o `~/.zshrc`:

```bash
alias sail='vendor/bin/sail'
alias canvas='vendor/bin/sail php vendor/bin/canvas'
alias testbench='vendor/bin/sail php vendor/bin/testbench'
```

### Testing

Ejecuta las pruebas con Pest:

```bash
vendor/bin/sail php vendor/bin/pest
```

Para ejecutar un archivo de prueba específico:

```bash
vendor/bin/sail php vendor/bin/pest tests/Feature/MakeResourceCommandTest.php
```

### Testbench

Usa Testbench como alias de Artisan para interactuar con la aplicación Laravel esqueleto:

```bash
vendor/bin/sail php vendor/bin/testbench make:model User
vendor/bin/sail php vendor/bin/testbench migrate
vendor/bin/sail php vendor/bin/testbench route:list
```

## Estructura del proyecto

```
├── src/                          # Código fuente del paquete
│   ├── DevToolServiceProvider.php
│   └── Commands/                 # Comandos make: reescritos
│       ├── MakeResourceCommand.php
│       ├── MakeFieldCommand.php
│       ├── MakeTableCommand.php
│       └── ...
├── workbench/                    # App Laravel skeleton para testing
│   ├── app/
│   ├── database/
│   └── routes/
├── vendor/                       # Dependencias
├── canvas.yaml                   # Configuración de Orchestra Canvas
├── testbench.yaml                # Configuración de Orchestra Testbench
├── compose.yml                   # Docker Compose para Sail
└── install.sh                    # Script de instalación
```

## Licencia

MIT License © [Joserick](https://github.com/joserick)
