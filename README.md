# Idea del servicio

La idea de la API es que se puedan agregar (y gestionar) comentarios sobre un mueble en particular. Por ese motivo, la tabla comments tiene asociado a 'id_mueble' la tabla 'mueble'.

Dado que no se iba a consultar desde el frontend (el HTML generado en el server), se decidió aislar la API del resto del trabajo; pero la idea original era consumirla desde la 1ra parte del trabajo.

# Endpoints que soporta el servicio

## GET (todos los comments): localhost/TPEWeb2-2022-API/comments

    Un GET general (toda la tabla).

## GET (/:ID): localhost/TPEWeb2-2022-API/comment/:ID || localhost/TPEWeb2-2022-API/mueblecomment/:ID

    El ID en el segundo caso es empleado para saber para qué mueble (en la tabla, id_mueble) es que pretendo obtener comentarios (obtener un solo comentario no es muy razonable). De todas formas, el get/id por defecto lo obtiene; el otro se llama mueblecomment.

## POST (/:ID): localhost/TPEWeb2-2022-API/comments/add/

    La información a insertarse va por POST[] (de ser incompleta o errónea, devuelvo un 400). De ser exitoso, se devuelve el comentario que se acaba de añadir (constatando así el usuario que la operación fue exitosa).

## PUT (/:ID): localhost/TPEWeb2-2022-API/comments/:ID

    Se provee el ID del comentario a editar y se comprueba que toda la información esté en orden (no vacía, no nula, etc).

## DELETE (/:ID) localhost/TPEWeb2-2022-API/comments/del/:ID

    Se borra un comentario con el id (de comentario) provisto.

# ADICIONALES

## Ordenar por un campo a elección

    Se puede elegir por qué campo ordenar y en qué orden (con un 400 si el campo o el orden están mal escritos):
        api/comments?sortBy=[nombre de algún campo, ver tabla comments en DB]&order=[ASC/DESC]

## Autenticación

    Se usa una autenticación por JWT, para la que quien desea emplear la API debe solicitar primero un token, vía
        localhost/TPEWeb2-2022-API/auth
    lo que devolvería un token. Dicho token debe almacenarse para su uso posterior (un request POST/PUT sin token da un 401).
