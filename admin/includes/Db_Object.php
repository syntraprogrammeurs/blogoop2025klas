<?php

class Db_Object
{
    public static function find_this_query($sql, $values = []){
        global $database;
        $result = $database->query($sql,$values);
        $the_object_array = [];
        while($row = mysqli_fetch_assoc($result)){
            $the_object_array[] = static::instantie($row);
        }
        return $the_object_array;
    }
    public static function instantie($result){
        $calling_class = get_called_class(); //late static binding
        $the_object = new $calling_class;
        foreach($result as $the_attribute => $value){
            if($the_object->has_the_attribute($the_attribute)){
                $the_object->$the_attribute = $value;
            }
        }
        return $the_object;
    }
    public function has_the_attribute($the_attribute){
        $object_properties = get_object_vars($this);
        return array_key_exists($the_attribute, $object_properties);
    }

    public static function find_all(){
        return static::find_this_query("SELECT * FROM " . static::$table_name . " ORDER BY id DESC");
    }
    public static function find_by_id($id){
        //binden van parameters= ($user_id)=PREPARED STATEMENTS
        $result = static::find_this_query("SELECT * FROM " .static::$table_name . " WHERE id=?",[$id]);
        return !empty($result) ? array_shift($result): false;
    }

    /**
     * Creates a new record in the database.
     *
     * This function constructs an SQL INSERT query using the object's properties,
     * excluding the 'id'. The query is protected against SQL injection by using
     * prepared statements.
     *
     * @global Database $database The global database connection object.
     */
    public function create() {
        global $database;

        // Retrieve the table name from the static property
        $table = static::$table_name;

        // Get the properties of the object
        $properties = $this->get_properties();

        // Remove the 'id' from the properties if it exists
        if (array_key_exists('id', $properties)) {
            unset($properties['id']);
        }

        // Escape the values to protect against SQL injection
        $escaped_values = array_map([$database, 'escape_string'], $properties);

        // Create placeholders for the prepared statement
        $placeholders = array_fill(0, count($properties), '?');

        // Create a string of field names separated by commas
        $fields_string = implode(',', array_keys($properties));

        // Determine the data types for the prepared statement
        $types_string = "";
        foreach ($properties as $value) {
            if (is_int($value)) {
                $types_string .= "i";
            } elseif (is_float($value)) {
                $types_string .= "d";
            } else {
                $types_string .= "s";
            }
        }

        // Construct a fully prepared SQL INSERT statement
        $sql = "INSERT INTO $table ($fields_string) VALUES (" . implode(',', $placeholders) . ")";

        // Execute the query
        $database->query($sql, $escaped_values);
    }

    public function update(){
        global $database;
        //tabelnaam ophalen
        $table = static::$table_name;
        //properties
        $properties = $this->get_properties();
        unset($properties['id']);
        //waarden beschermen tegen sql injecties
        $escaped_values = array_map([$database,'escape_string'], $properties);

        //append the id value tot the end of the escaped values array
        $escaped_values[]= $this->id;
        $placeholders = array_fill(0,count($properties), '?');

        //een string van alle veldnamen gescheiden door komma's.
        $fields_string = implode(',',array_keys($properties));
        //datatypes string
        $types_string = "";
        foreach($properties as $value){
            if(is_int($value)){
                $types_string .= "i";
            }elseif(is_float($value)){
                $types_string .= "d";
            }else{
                $types_string .= "s";
            }
        }
        //create sql commando, prepared statement (? welk id)
        //$properties=['username',...,'last-name]
        // username = ?, ... last-name = ?

        $sql = "UPDATE $table SET " . implode(', ', array_map(fn($field) => "$field = ?", array_keys($properties))) . " WHERE id = ?";

        //array_map met fn($field) => "$field = ?":
        //
        //Hiermee wordt voor elk veld een correcte field = ?-toewijzing gemaakt.
        //Bijv.: username = ?, password = ?, first_name = ?, last_name = ?.

        //execute
        $database->query($sql,$escaped_values);
    }

    /**
     * Saves the object to the database.
     * If the object has an id, the `update` method is called.
     * Otherwise, the `create` method is called.
     */
    public function save(){
        return isset($this->id) ? $this->update() : $this->create();
    }

    public function delete(){
        global $database;
        $table = static::$table_name;
        $escaped_id = $database->escape_string($this->id);

        $sql = "DELETE FROM $table WHERE id = ?";
        $params = [$escaped_id];
        $database->query($sql,$params);
    }

}