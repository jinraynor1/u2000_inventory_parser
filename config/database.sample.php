<?php

return array(

    'master' =>
        array(
            'driver' => 'oci',
            'username' => 'your_username',
            'password' => 'your_password',
            'dsn' => '//localhost:1521/xe',
            'quote_identifier_character' => '"',
            'query_session' => array(
                "alter session set nls_date_format='YYYY/MM/DD HH24:MI:SS')"
            )

        ),

);