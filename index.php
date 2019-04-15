<?php
    // cannot use localhost
    $g_servername = "127.0.0.1";
    $g_username = "root";
    $g_password = "password";
    $g_conn = false;

    main();

    function main() {
        connectDb();

        fetch();
    }
    
    function connectDb() {
        global $g_servername;
        global $g_username;
        global $g_password;
        global $g_conn;

        try {
            $g_conn = new PDO("mysql:host=$g_servername;dbname=momenton", $g_username, $g_password);
            $g_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Connected successfully"; 
        }
        catch(PDOException $e)
        {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    function fetch() {
        global $g_conn;
        $sql = "select * from employee";
        $res = $g_conn->query($sql)->fetchAll();
        
        var_dump($res);
    }