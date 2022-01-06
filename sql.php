<?php
    include "/web/api/link.php";

    class Sql{
        private $sql_link;
        private $passowd_list;

        public function __construct($user_name, $db_name, $config_path="/web/config"){
            global $sql_link;
            global $passowd_list;

            $this -> read_config($config_path);
            $this -> link($user_name, $db_name);
        }

        public function read_config($config_path){
            global $passowd_list;

            $config = fopen($config_path, "r");
            $config_data = fread($config, filesize($config_path));
            $this -> $passowd_list = json_decode($config_data, true);
            
            fclose($config);
        }

        public function link($user_name, $db_name, $host="127.0.0.1"){
            global $sql_link;
            global $passowd_list;
            
            if($sql_link != null){
                $this -> $sql_link = null;
            }

            $user_passowd = $this -> $passowd_list[$user_name];
            $dsn = "mysql:host=".$host.";dbname=".$db_name;
            try{
                $this -> $sql_link = new PDO($dsn, $user_name, $user_passowd, array(
                    PDO::ATTR_PERSISTENT => true
                ));
            }catch(PDOException $err){
                $link_ = new Link();
                $link_ -> json([], 4003, "api连接数据库失败 错误信息:".$err -> getMessage());
                exit();
            }
        }

        public function read($sql_com){
            global $sql_link;

            try{
                $sql_run = $this -> $sql_link -> query($sql_com);
                $data = $sql_run -> fetchAll();
            }catch(PDOException $err){
                $link_ = new Link();
                $link_ -> json([], 4003, "api数据库查询失败 错误信息:".$err -> getMessage());
                exit();
            }
            return $data;
        }

        public function write($sql_com, $err_msg="重复数据"){
            global $sql_link;

            try{
                $this -> $sql_link -> exec($sql_com);
            }catch(PDOException $err){
                $err_code = $err -> getCode();

                $link_ = new Link();
                if($err_code == 23000){
                    $link_ -> json([], 4004, $err_msg);
                    exit();
                }
                $link_ -> json([], 4003, "api数据库写入失败 错误码:".$err_code);
                exit();
            }
        }

    }