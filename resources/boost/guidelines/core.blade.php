## Filament DevTool

### Introduction
The Filament DevTool package is a development tool designed to allow access to the "make" commands of filamentphp but to use the development of a package through the "canvas" command.

### Development and Testing
For development and testing in the Filament DevTool package, it implements the structure established in the orchestra/testbench, orchestra/canvas and orchestral/workbench package, this means that you can use the following commands to interact with the package:
- Use the command `vendor/bin/sail php vendor/bin/canvas` as alias of `vendor/bin/sail php artisan` for generating the necessary files within the Filament DevTool package.
- Use the command `vendor/bin/sail php vendor/bin/testbench` as other alias of `vendor/bin/sail php artisan` but for running the commands necessary for interacting with Laravel Aplication (skeleton).

### Testbench MCP (Model Context Protocol)
The project includes the MCP configuration for Testbench, Workbench and Canvas documentation.
