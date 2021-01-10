**Carga de inventario XML a base de datos**

Puedes probar la aplicacion con docker:

`docker-compose up`

- Para configurar la busqueda de ficheros a cargar debes renombrar el fichero `config/file_scanner.sample.php` a `config/file_scanner.php`
  

- Para configurar la base de datos debes renombrar el fichero `config/database.sample.php` a `config/database.sample.php`



- Luego ingresa a la instancia php con este comando:
`docker exec -it u2000inventoryparser_php_1 bash`


- El siguiente comando busca ficheros nuevos, crea el esquema y carga la informacion a la base de datos: `php src/load_xml_files.php`



     