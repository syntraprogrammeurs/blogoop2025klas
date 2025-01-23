<?php

class User extends Db_Object
{

    //properties
    //public,private, protected
    public $id;
    public $username;
    public $password;
    public $first_name;
    public $last_name;

    //methods

    public static function verify_user($username,$password){
        global $database;
        $username = $database->escape_string($username);
        $password = $database->escape_string($password);

        // select * from users where username = $username and password = $password
        $sql = "SELECT * FROM ". self::$table_name ." WHERE ";
        $sql .= "username = ? ";
        $sql .= "AND password = ?";
        $sql .= " LIMIT 1";

        $the_result_array = self::find_this_query($sql,[$username,$password]);

        return !empty($the_result_array) ? array_shift($the_result_array) : false;
    }

    /* CRUD */
    protected static $table_name = 'users';
    /*properties als array voorzien*/
    public function get_properties(){
        return[
            'id'=> $this->id,
            'username'=>$this->username,
            'password'=>$this->password,
            'first_name'=>$this->first_name,
            'last_name'=>$this->last_name
        ];
    }


}