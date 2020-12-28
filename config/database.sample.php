<?php

return array(

    'master' =>
        array(
            'driver' => 'oci',
            'username' => 'smithj',
            'password' => 'pwd4smithj',
            'dsn' => 'dbname=(DESCRIPTION =(ADDRESS_LIST =(ADDRESS = (PROTOCOL = TCP)(HOST = oracle)(PORT = 1521)))(CONNECT_DATA =(SERVICE_NAME = xe)) )',
            'quote_identifier_character' => '"',
            'queries_session' => array(
                "alter session set nls_date_format='YYYY/MM/DD HH24:MI:SS'"
            )

        ),

);