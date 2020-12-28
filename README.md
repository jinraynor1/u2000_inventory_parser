**Carga de inventario XML a base de datos**

Puedes probar la aplicacion con docker:

`docker-compose up`


Para configurar la base de datos debes renombrar el fichero `config/database.sample.php` a `config/database.sample.php`


Luego ingresa a la instancia php con este comando:
`docker exec -it u2000inventoryparser_php_1 bash`


- Prueba la creacion del esquema inicial usando este comando
`php src/build_schema_from_files.php`

- Prueba la carga de datos al esquema ya creado con este comando

`php src/load_xml_files.php`



     