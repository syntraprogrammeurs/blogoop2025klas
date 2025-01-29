<?php

class Blog extends Db_object
{
    public $id;
    public $author_id;
    public $photo_id;
    public $title;
    public $description;
    public $created_at;
    public $deleted_at;
    protected static $table_name = 'blogs';

    public function get_properties(){
        return[
            'id'=> $this->id,
            'author_id'=>$this->author_id,
            'photo_id'=>$this->photo_id,
            'title'=>$this->title,
            'description'=>$this->description,
            'created_at'=>$this->created_at,
            'deleted_at'=>$this->deleted_at
        ];
    }
}









