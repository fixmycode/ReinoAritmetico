# Reino Aritmético
## Aplicación de Escritorio

Cliente de escritorio para Windows/Mac, donde se conectan los clientes móviles.

---

###Instalacion de dependencias
Para compilar los ejecutables para windows y mac se debe tener instalado [npm](https://www.npmjs.org/), [grunt](http://gruntjs.com/) y [bower](http://bower.io/)
simplemente escribir:

```bash
npm install
cd src
npm install
bower install
```

Esto instala los modulos de los que depende el software.

###Compilación

Para generar los archivos ejecutables se debe ejecutar la tarea de grunt nodewebkit:

```bash
grunt nodewebkit
```

una vez terminado, se crea la carpeta **webkitbuilds**, dentro están los releases para **Mac** y
**Windows**
