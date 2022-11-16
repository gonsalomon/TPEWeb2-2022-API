<?php

class ApiModel
{
    private $db;
    function __construct()
    {
        $this->db = new PDO('mysql:host=localhost;' . 'dbname=db_mueble;charset=utf8', 'root', '');
    }

    function getAll($sortBy = null, $order = null, $page = null, $size = null, $filterBy = null, $value = null, $cond = null)
    {
        //ordenamiento
        if (isset($sortBy) && isset($order)) {
            $req = $this->db->prepare("SELECT id, comment, mueble FROM comments LEFT JOIN mueble ON comments.id_mueble=mueble.id_mueble ORDER BY $sortBy $order");
        }
        //paginación (como comento en el controller, traigo $size filas de la base de datos de acuerdo a qué página me dice $page que corresponde enviar)
        else if (isset($page) && isset($size)) {
            $offset = ($page * $size);
            $req = $this->db->prepare("SELECT id, comment, mueble FROM comments LEFT JOIN mueble ON comments.id_mueble=mueble.id_mueble ORDER BY id LIMIT $size OFFSET $offset");
        }
        //filtrado
        else if (isset($filterBy) && isset($value) && isset($cond)) {
            if ($cond == 'V' || $cond == 'v') {
                $parsedCond = '=';
            } else if ($cond == 'F' || $cond == 'f') {
                $parsedCond = '<>';
            } else {
                return null;
            }
            $req = $this->db->prepare("SELECT id, comment, mueble FROM comments LEFT JOIN mueble ON comments.id_mueble=mueble.id_mueble WHERE $filterBy $parsedCond '$value'");
        }
        //caso general
        else {
            $req = $this->db->prepare('SELECT id, comment, mueble FROM comments LEFT JOIN mueble ON comments.id_mueble=mueble.id_mueble');
        }
        $req->execute();
        return $req->fetchAll(PDO::FETCH_OBJ);
    }

    //traigo los nombres de los campos para checkear que los parámetros GET tengan sentido
    function getTableFields()
    {
        $req = $this->db->query('SELECT id, comment, mueble FROM comments LEFT JOIN mueble ON comments.id_mueble=mueble.id_mueble LIMIT 0');
        for ($i = 0; $i < $req->columnCount(); $i++) {
            $col = $req->getColumnMeta($i);
            $columns[] = $col['name'];
        }
        return $columns;
    }

    //no sé bien por qué querrías obtener un comentario por su id, pero queda
    function get($id)
    {
        $req = $this->db->prepare('SELECT id, comment, mueble FROM comments LEFT JOIN mueble ON comments.id_mueble=mueble.id_mueble WHERE id = ?');
        $req->execute([$id]);

        return $req->fetch(PDO::FETCH_OBJ);
    }

    //este GET sí tiene sentido en el contexto del trabajo: traer los comentarios de 'Alacena'
    function getFromMueble($mueble)
    {
        //AGUANTEN LAS TERNARIAS
        //is_string($mueble)? **mueble** (sí, las ternarias no se usan para acciones imperativas sino para declaraciones de valores, por eso usé if/else)
        if (is_string($mueble)) {
            $req = $this->db->prepare('SELECT id, comment, mueble FROM comments LEFT JOIN mueble ON comments.id_mueble=mueble.id_mueble WHERE mueble = ?');
            $req->execute([$mueble]);
        }
        //: **id_mueble**;
        else {
            $req = $this->db->prepare('SELECT id, comment, mueble.id_mueble, mueble FROM comments LEFT JOIN mueble ON comments.id_mueble=mueble.id_mueble WHERE mueble.id_mueble = ?');
            $req->execute([$mueble]);
        }
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
        //CÓMO ME VAS A PASAR UN COMENTARIO SIN INFORMAR A QUÉ MUEBLE SE LO HACÉS ahora no te inserto nada loco y encima te devuelvo null por jodido
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

    //editar un comentario no iba a estar disponible en los early drafts del trabajo pero lo dejo andando
    function edit($id, $comment, $id_mueble)
    {
        $req = $this->db->prepare('UPDATE comments SET comment = ?, id_mueble = ? WHERE id = ?');
        $req->execute([$comment, $id_mueble, $id]);
        //devuelvo el elemento editado
        return $this->get($id);
    }

    //borrar un comentario por id cumple con la consigna, pero sería más completo permitir borrar todos los comentarios de un mueble, por ejemplo... no me la quiero volar igual
    function delete($id)
    {
        $elemToDelete = $this->get($id);
        $req = $this->db->prepare('DELETE FROM comments WHERE id = ?');
        $req->execute([$id]);
        //devuelvo el elemento que acabo de borrar (por qué? No hay por qué)
        return $elemToDelete;
    }

    /*
    * dejo comentada la función para borrar todos los comentarios de un mueble que podría ir acá, faltaría validar en el controller si el string de mueble es válido (o
    * se debería enviar un bad request) y vincular esa función al router, con getInfo se podría obtener el nombre del mueble de necesitarse y tendrías una funcionalidad
    * completa (de nuevo, no me la quiero volar tantísimo así que acá sí que dejo)

    function deleteFromMueble($mueble){
        //borro por string
        if(is_string($mueble)){
            $req = $this->db->prepare('DELETE FROM comments(id, comment) INNER JOIN mueble(mueble) ON comments.id_mueble = mueble.id_mueble WHERE mueble = ? ');
            $req->execute([$mueble]);
        }
        //borro por id
        else{
            $req = $this->db->prepare('DELETE FROM comments WHERE id_mueble = ?');
            $req->execute([$mueble]);    
        }
    }
    */
}
