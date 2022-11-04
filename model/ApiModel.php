<?php

class ApiModel
{
    private $db;
    function __construct()
    {
        $this->db = new PDO('mysql:host=localhost;' . 'dbname=db_mueble;charset=utf8', 'root', '');
    }

    function getComments($elem, $id, $orderBy = null, $order = null)
    {
        if (!isset($orderBy)) {
            if ($elem == 'mueble') {
                $req = $this->db->prepare('SELECT * from comments WHERE id_mueble = ?');
                $req->execute($id);

                return $req->fetchAll(PDO::FETCH_OBJ);
            } else {
                $req = $this->db->prepare('SELECT * from comments WHERE id_categoria = ?');
                $req->execute($id);

                return $req->fetchAll(PDO::FETCH_OBJ);
            }
        } else {
            if ($elem == 'mueble') {
                $req = $this->db->prepare('SELECT * from comments WHERE id_mueble = ? ORDER BY ?' . $order == 'ascending' ? ' ASC;' : ' DESC;');
                $req->execute($id, $orderBy);

                return $req->fetchAll(PDO::FETCH_OBJ);
            } else {
                $req = $this->db->prepare('SELECT * from comments WHERE id_categoria = ? ORDER BY ?' . $order == 'ascending' ? ' ASC;' : ' DESC;');
                $req->execute($id, $orderBy);

                return $req->fetchAll(PDO::FETCH_OBJ);
            }
        }
    }

    function getComment($id)
    {
        $req = $this->db->prepare('SELECT * from comments WHERE id_comment = ?');
        $req->execute($id);

        return $req->fetch(PDO::FETCH_OBJ);
    }

    function addComment($elem, $id, $comment)
    {
    }

    //editar un comentario no tiene sentido, sólo dejo que un usuario pueda borrar los suyos propios, y un admin todos
    function deleteComment($id_comment = null, $elem = null, $id = null)
    {
        if (!isset($elem)) {
            $req = $this->db->prepare('DELETE FROM comments WHERE id_comment = ?');
            $req->execute($id_comment);
        } else {
            if ($elem == 'mueble') {
                $req = $this->db->prepare('DELETE * FROM comments WHERE id_mueble = ?');
                $req->execute($id);
            } else {
                $req = $this->db->prepare('DELETE * FROM comments WHERE id_categoria = ?');
                $req->execute($id);
            }
        }
    }
}
