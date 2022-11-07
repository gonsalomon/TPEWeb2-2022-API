<?php

class ApiModel
{
    private $db;
    function __construct()
    {
        $this->db = new PDO('mysql:host=localhost;' . 'dbname=db_mueble;charset=utf8', 'root', '');
    }

    function getAll($sortBy = null, $order = null)
    {
        if (isset($sortBy) && isset($order)) {
            $req = $this->db->prepare("SELECT * FROM comments ORDER BY $sortBy $order");
            $req->execute();
        } else {
            $req = $this->db->prepare('SELECT * FROM comments');
            $req->execute();
        }
        return $req->fetchAll(PDO::FETCH_OBJ);
    }

    function get($id)
    {
        $req = $this->db->prepare('SELECT * FROM comments WHERE id = ?');
        $req->execute([$id]);

        return $req->fetch(PDO::FETCH_OBJ);
    }

    function getFromMueble($id)
    {
        $req = $this->db->prepare('SELECT * FROM comments WHERE id_mueble = ?');
        $req->execute($id);
        return $req->fetchAll(PDO::FETCH_OBJ);
    }

    function insert($comment, $id_mueble)
    {
        if ($id_mueble) {
            $req = $this->db->prepare('INSERT INTO comments (comment, id_mueble) VALUES (?,?)');
            $req->execute([$comment, $id_mueble]);
            //devuelvo el comentario recién añadido
            return $this->get($this->db->lastInsertId());
        }
        return null;
    }

    //función necesaria para informar al usuario de la API cuáles son los IDs asociados a cada mueble
    function getInfo()
    {
        $req = $this->db->prepare('SELECT id_mueble, mueble FROM mueble');
        $req->execute();
        $res = $req->fetchAll(PDO::FETCH_OBJ);
        return $res;
    }

    function edit($id, $comment, $id_mueble)
    {
        $req = $this->db->prepare('UPDATE comments SET comment = ?, id_mueble = ? WHERE id = ?');
        $req->execute([$comment, $id_mueble, $id]);
        //devuelvo el elemento editado
        return $this->get($id);
    }

    function delete($id)
    {
        $req = $this->db->prepare('DELETE FROM comments WHERE id = ?');
        $req->execute([$id]);
        //devuelvo todos los elementos (el usuario puede constatar que el elemento que solicitó ya no se encuentra)
        return $this->getAll();
    }
}
