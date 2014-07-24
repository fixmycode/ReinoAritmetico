# Reino Aritmético
## Aplicación de Servidor

Servidor para la aplicación. Se encarga de mantener los datos y prestar servicios a la aplicación móvil y de escritorio.

---

### Requerimientos

- Se debe tener instalado [www.vagrantup.com](www.vagrantup.com) (pide virtualbox o vmware)

### Iniciar el servidor
```bash
$ vagrant up
```

Luego esperar a que se instale la maquina virtual y se provisione el software.

Dirigirse a [http://reino-aritmetico.app.192.168.10.10.xip.io/](http://reino-aritmetico.app.192.168.10.10.xip.io/) para ver el servidor en vivo. Tambien se puede agregar la dirección IP manualmente al archivo **/etc/hosts** ([How do I modify my hosts file?](http://www.rackspace.com/knowledge_center/article/how-do-i-modify-my-hosts-file)) en el computador host (el no virtual, el de verdad, el que tienes en tus manos):

```bash
192.168.10.10   reino-aritmetico.app
```
y entrar usando: [http:/reino-aritmetico.app](reino-aritmetico.app)

### Instalando dependencias manualmente

Ingresar por ssh a la maqina virtual:

```bash
vagrant ssh
```

una vez dentro, dirigirse a la carpeta del proyecto y ejecutar composer y migrar la base de datos

```bash
cd /vagrant/src
php composer update --dev
...
php artisan migrate
```

*nota: se puede seedear la base de datos usando:*

```bash
php artisan db:seed
ó
php artisan migrate:refresh --seed
```

Las credenciales para ingresar al servidor son:

- **SSH**: 2222 -> Forwards To 22
- **HTTP**: 8000 -> Forwards To 80
- **MySQL**: 33060 -> Forwards To 3306
- **Postgres**: 54320 -> Forwards To 5432

Para leer mas de laravel y vagrant leer la documentación oficial de homestead [http://laravel.com/docs/homestead](http://laravel.com/docs/homestead)
