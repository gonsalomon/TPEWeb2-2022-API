<?php

class ApiModel
{
    private $db;
    function __construct()
    {
        $this->db = new PDO('mysql:host=localhost;' . 'dbname=db_mueble;charset=utf8', 'root', '');
    }

    function getAll($sortBy = null, $order = null, $page = null, $size = null, $filter = null, $value = null)
    {
        //ordenamiento
        if (isset($sortBy) && isset($order)) {
            $req = $this->db->prepare("SELECT id, comment, mueble FROM comments LEFT JOIN mueble ON comments.id_mueble=mueble.id_mueble ORDER BY $sortBy $order");
            $req->execute();
        }
        //paginación
        else if (isset($page) && isset($size)) {
            $req = $this->db->prepare("SELECT id, comment, mueble FROM comments LEFT JOIN mueble ON comments.id_mueble=mueble.id_mueble ORDER BY (SELECT NULL) OFFSET $page*$size ROWS FETCH NEXT $size ROWS ONLY");
            $req->execute();
        }
        //filtrado
        else if (false) {
            //TODO
        }
        //caso general
        else {
            $req = $this->db->prepare('SELECT id, comment, mueble FROM comments LEFT JOIN mueble ON comments.id_mueble=mueble.id_mueble');
            $req->execute();
        }
        return $req->fetchAll(PDO::FETCH_OBJ);
    }

    function getTableFields()
    {
        $req = $this->db->query('SELECT * FROM comments LIMIT 0');
        for ($i = 0; $i < $req->columnCount(); $i++) {
            $col = $req->getColumnMeta($i);
            $columns[] = $col['name'];
        }
        return $columns;
    }

    function get($id)
    {
        $req = $this->db->prepare('SELECT id, comment, mueble FROM comments LEFT JOIN mueble ON comments.id_mueble=mueble.id_mueble WHERE id = ?');
        $req->execute([$id]);

        return $req->fetch(PDO::FETCH_OBJ);
    }

    function getFromMueble($id)
    {
        $req = $this->db->prepare('SELECT id, comment, comments.id_mueble, mueble FROM comments LEFT JOIN mueble ON comments.id_mueble=mueble.id_mueble WHERE id_mueble = ?');
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
        $elemToDelete = $this->get($id);
        $req = $this->db->prepare('DELETE FROM comments WHERE id = ?');
        $req->execute([$id]);
        //devuelvo el elemento que acabo de borrar
        return $elemToDelete;
    }
}
