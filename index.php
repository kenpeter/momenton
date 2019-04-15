<?php
    // cannot use localhost
    $g_servername = "127.0.0.1";
    $g_username = "root";
    $g_password = "password";
    $g_conn = false;

    main();

    function main() {
        connectDb();

        $id = 150;
        $res = recurFetch($id, []);
        print_r($res[1][0]);
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

    function fetch($id) {
        global $g_conn;
        $sql = "
    SELECT 
        id, name 
    FROM 
        employee em
    JOIN 
        employee_closure em_c
    ON 
        em.id = em_c.descendant
    WHERE 
        em_c.ancestor = '$id'
    ";
        $data = $g_conn->query($sql)->fetchAll();

        $arr = [];
        foreach ($data as $row) {
            $obj = [];
            $obj['id'] = $row['id'];
            $obj['name'] = $row['name'];
            $arr[] = $obj;
        }

        return $arr;
    }

    function recurFetch($id) {
        $arr = fetch($id);

        if(count($arr) <= 1) {
            return $arr;
        }

        $tmpArr = [];
        $target = false;
        foreach($arr as $item) {
            $tmpId = $item['id'];

            if($tmpId === $id) {
                $target = $item;
                continue;
            }
                
            $tmpArr[] = recurFetch($tmpId);
        }

        return [$target, $tmpArr];
    }