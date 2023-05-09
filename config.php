
<?php   
     
return $config = 
[
    'db' => [
        'server' => getenv('DB_SERVER'),
        'dbname' => getenv('DB_NAME'),
        'dbpass' => getenv('DB_PASSWORD'),
        'dbuser' => getenv('DB_USER'),
    ],
];