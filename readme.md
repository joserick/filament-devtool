
# Filament DevTool

[![Latest Version on Packagist](https://img.shields.io/packagist/v/joserick/filament-devtool.svg)](https://packagist.org/packages/joserick/filament-devtool)
[![License](https://img.shields.io/packagist/l/joserick/filament-devtool.svg)](https://github.com/joserick/filament-devtool/blob/main/LICENSE)

Filament DevTool is an agile tool that enables the creation of **FilamentPHP v5** components within Laravel packages under development. It replaces Filament's original `make:` commands with versions that generate code directly in the package's `src/` structure instead of the Laravel application skeleton.

## Features

- 🚀 **All Filament `make:` commands** rewritten for package development
- 📦 File generation in `src/` using [Orchestra Canvas](https://github.com/orchestral/canvas)
- 🐳 Docker/Sail development environment included
- 🧪 Integration with [Orchestra Testbench](https://github.com/orchestral/testbench) for package testing
- ⚡ No need for a full Laravel application to develop components

## Requirements

- PHP 8.3 or higher
- [Composer](https://getcomposer.org/) 2.2+
- [Docker](https://www.docker.com/) (for the Sail development environment)

## Installation

### As a development dependency in your package

```bash
composer require --dev joserick/filament-devtool
```

The package will be automatically registered thanks to Laravel's package discovery.

### Using the Sail/Docker development environment

Run the installation script directly with `curl`:

```bash
curl -s https://filament-devtool.joserick.com/install | bash
```

The `install.sh` script:

1. Clones the `joserick/filament-devtool` repository
2. Copies `stubs/compose.stub` to `compose.yml`
3. Installs Composer dependencies using Docker
4. Builds the Sail image for PHP 8.5
5. Sets up permissions for the `vendor/` directory

Once finished, start the containers:

```bash
cd filament-devtool && vendor/bin/sail up -d
```

## Usage

### Available commands

All Filament `make:` commands are available through Canvas. Run them using the `canvas` alias:

```bash
vendor/bin/sail php vendor/bin/canvas
```

#### Included commands

| Command | Description |
|---|---|
| `make:filament-resource` | Creates a panel Resource |
| `make:filament-page` | Creates a panel Page |
| `make:filament-widget` | Creates a Widget |
| `make:filament-cluster` | Creates a panel Cluster |
| `make:filament-relation-manager` | Creates a Relation Manager |
| `make:form-field` | Creates a form field |
| `make:form` | Creates a form component |
| `make:livewire-form` | Creates a Livewire form |
| `make:rich-content-custom-block` | Creates a custom block for Rich Content |
| `make:table-column` | Creates a table column |
| `make:table` | Creates a table component |
| `make:livewire-table` | Creates a Livewire table |
| `make:infolist-entry` | Creates an Infolist entry |
| `make:schema-component` | Creates a Schema component |
| `make:schema` | Creates a Schema |
| `make:livewire-schema` | Creates a Livewire Schema |
| `make:filament-importer` | Creates an Importer |
| `make:filament-exporter` | Creates an Exporter |
| `make:filament-issue` | Creates an Issue |

#### Examples

```bash
# Create a resource
vendor/bin/sail php vendor/bin/canvas make:filament-resource User

# Create a form field
vendor/bin/sail php vendor/bin/canvas make:form-field ColorPicker

# Create a page
vendor/bin/sail php vendor/bin/canvas make:filament-page Settings
```

Generated files are automatically placed in `src/` following the package structure (`canvas.yaml`).

### Recommended terminal aliases

Add these aliases to your `~/.bashrc` or `~/.zshrc`:

```bash
alias sail='vendor/bin/sail'
alias canvas='vendor/bin/sail php vendor/bin/canvas'
alias testbench='vendor/bin/sail php vendor/bin/testbench'
```

### Testing

Run tests with Pest:

```bash
vendor/bin/sail php vendor/bin/pest
```

To run a specific test file:

```bash
vendor/bin/sail php vendor/bin/pest tests/Feature/MakeResourceCommandTest.php
```

### Testbench

Use Testbench as an Artisan alias to interact with the Laravel skeleton application:

```bash
vendor/bin/sail php vendor/bin/testbench make:model User
vendor/bin/sail php vendor/bin/testbench migrate
vendor/bin/sail php vendor/bin/testbench route:list
```

## Project structure

```
├── src/                          # Package source code
│   ├── DevToolServiceProvider.php
│   └── Commands/                 # Rewritten make: commands
│       ├── MakeResourceCommand.php
│       ├── MakeFieldCommand.php
│       ├── MakeTableCommand.php
│       └── ...
├── workbench/                    # Laravel skeleton app for testing
│   ├── app/
│   ├── database/
│   └── routes/
├── vendor/                       # Dependencies
├── canvas.yaml                   # Orchestra Canvas configuration
├── testbench.yaml                # Orchestra Testbench configuration
├── compose.yml                   # Docker Compose for Sail
└── install.sh                    # Installation script
```

## License

MIT License © [Joserick](https://github.com/joserick)
