<?php
class MuebleModel
{
    private $db;

    function __construct()
    {
        $this->db = new PDO('mysql:host=localhost;' . 'dbname=db_mueble;charset=utf8', 'root', '');
    }

    function getMuebles()
    {
        $req = $this->db->prepare("SELECT * FROM mueble LEFT JOIN categoria ON mueble.id_categoria = categoria.id_categoria");
        $req->execute();

        return $req->fetchAll(PDO::FETCH_OBJ);
    }

    function getMueblesCat($id)
    {
        $req = $this->db->prepare("SELECT * FROM mueble WHERE id_categoria = ?");
        $req->execute(array($id));

        return $req->fetchAll(PDO::FETCH_OBJ);
    }

    function getCategorias()
    {
        $req = $this->db->prepare("SELECT * FROM categoria");
        $req->execute();

        return $req->fetchAll(PDO::FETCH_OBJ);;
    }

    function getMueble($id)
    {
        $req = $this->db->prepare("SELECT * FROM mueble LEFT JOIN categoria ON mueble.id_categoria = categoria.id_categoria WHERE id_mueble = ?");
        $req->execute(array($id));

        return $req->fetch(PDO::FETCH_OBJ);
    }

    function getCategoria($id)
    {
        $req = $this->db->prepare("SELECT * FROM categoria WHERE id_categoria = ?");
        $req->execute(array($id));

        return $req->fetch(PDO::FETCH_OBJ);;
    }

    function addMueble($nombre, $descripcion, $precio, $id_categoria)
    {
        $req = $this->db->prepare("INSERT INTO mueble(mueble, descripcion, precio, id_categoria) VALUES (?, ?, ?, ?)");
        $req->execute(array($nombre, $descripcion, $precio, $id_categoria));
    }

    function addCategoria($nombre, $descripcion)
    {
        $req = $this->db->prepare("INSERT INTO categoria(nombre, descripcion) VALUES (?, ?)");
        $req->execute(array($nombre, $descripcion));
    }

    function updateMueble($id_mueble, $mueble, $descripcion, $precio, $id_categoria)
    {
        $req = $this->db->prepare("UPDATE mueble SET mueble = ?, descripcion = ?, precio = ?, id_categoria=? WHERE id_mueble=?");
        $req->execute(array($mueble, $descripcion, $precio, $id_categoria, $id_mueble));
        $req->fetch(PDO::FETCH_OBJ);

        $muebleUpdated = $this->getMueble($id_mueble);
        return $muebleUpdated;
    }

    function updateCategoria($id_categoria, $nombre, $descripcion)
    {
        $req = $this->db->prepare("UPDATE categoria SET categoria = ?, detalles = ? WHERE id_categoria = ?");
        $req->execute(array($id_categoria, $nombre, $descripcion));
        //uso un array para devolver la categoría recién modificada con sus respectivos muebles
        $res = array();
        $res[0] = $this->getCategoria($id_categoria);
        $res[1] = $this->getMueblesCat($id_categoria);
        return $res;
    }

    function deleteMueble($id_mueble)
    {
        $req = $this->db->prepare("DELETE FROM mueble WHERE id_mueble= ?");
        $req->execute(array($id_mueble));
    }

    function deleteCategoria($id_categoria)
    {
        $req = $this->db->prepare("DELETE FROM categoria WHERE id_categoria = ?");
        $req->execute(array($id_categoria));
    }
}
