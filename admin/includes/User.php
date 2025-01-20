<?php

class User
{
    //properties
    //public,private, protected
    public $id;
    public $username;
    public $password;
    public $first_name;
    public $last_name;
    //methods
    public static function find_this_query($sql, $values=[]){
        global $database;
        $result = $database->query($sql, $values);
        return $result;
    }
    public static function find_all_users(){
        return self::find_this_query("SELECT * FROM users");
    }
    public static function find_user_by_id($user_id){
        //binden van parameters= ($user_id)=PREPARED STATEMENTS
        return self::find_this_query("SELECT * FROM users WHERE id=?",[$user_id]);
    }

}