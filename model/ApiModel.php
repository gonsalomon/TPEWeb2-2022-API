<?php

class ApiModel
{
    private $db;
    function __construct()
    {
        $this->db = new PDO('mysql:host=localhost;' . 'dbname=db_mueble;charset=utf8', 'root', '');
    }

    function getAll()
    {
        $req = $this->db->prepare('SELECT * from comments');
        $req->execute();

        return $req->fetchAll(PDO::FETCH_OBJ);
    }

    function get($id)
    {
        $req = $this->db->prepare('SELECT * from comments WHERE id_mueble= ?');
        $req->execute($id);

        return $req->fetchAll(PDO::FETCH_OBJ);
    }

    function insert($comment, $id_mueble)
    {
        $req = $this->db->prepare('INSERT INTO comments (comment) VALUES (?)');
        $req->execute($comment);

        return $this->getAll();
    }

    function delete($id)
    {
        $req = $this->db->prepare('DELETE FROM comments WHERE id = ?');
        $req->execute($id);
    }
}
