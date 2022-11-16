# Idea del servicio

La idea de la API es que se puedan gestionar comentarios sobre un mueble en particular.

Comentario para los profes que no se incluiría en un README.md estándar:
-Relacioné la tabla comments con la tabla mueble para poder asociar un comment a un mueble (N comments a 1 mueble)
-Dado que no se iba a consultar desde el frontend (el HTML generado en el server), se decidió aislar la API del resto del trabajo; pero la idea original era consumirla desde la 1ra parte del trabajo.

# Endpoints estándar que soporta el servicio

## GET (todos los comments): localhost/TPEWeb2-2022-API/comments

    Un GET general (toda la tabla).

## GET (/:ID):

    Hay 2 GET por ID distintos:
    -localhost/TPEWeb2-2022-API/comments/:ID : trae el comentario con cierto :ID
    -localhost/TPEWeb2-2022-API/mueblecomments/:ID : trae los comentarios asociados a un **id_mueble** (numérico) o a un **mueble** (string). Ejemplos:
        -localhost/TPEWeb2-2022-API/mueblecomments/3
        -localhost/TPEWeb2-2022-API/mueblecomments/alacena

## POST: localhost/TPEWeb2-2022-API/comments

    La información a insertarse va en formato JSON, vía raw (de ser incompleta o errónea, devuelvo un 400). De ser exitoso, se devuelve el comentario que se acaba de añadir (pudiendo constatar así el usuario que la operación fue exitosa).

## PUT (/:ID): localhost/TPEWeb2-2022-API/comments/:ID

    Se provee el ID del comentario a editar y se comprueba que toda la información esté en orden (incompleta o errónea? Devuelvo un 400).

## DELETE (/:ID): localhost/TPEWeb2-2022-API/comments/del/:ID

    Se borra un comentario con el id (de comentario) provisto.

# ADICIONALES

## Obtener los nombres de los campos de la tabla: localhost/TPEWeb2-2022-API/info

    Esta función se provee para luego poder emplear la información en los diferentes requerimientos adicionales. Devuelve un arreglo con los nombres de cada campo de la tabla.

## Ordenar por un campo a elección (sortBy,order)

    Se puede elegir por qué campo ordenar y en qué orden:
        localhost/TPEWeb2-2022-API/comments?sortBy=[nombre de algún campo, solicitar /info]&order=[ASC/DESC]

## Paginar (size)

    Hay que elegir el tamaño de la página indicando el parámetro size:
        localhost/TPEWeb2-2022-API/comments?size=[un número]
    Se devuelve un arreglo asociativo, con size comentarios por página. Dicho arreglo se encodea como JSON.

## Filtrar (filterBy,value,cond)

    Deben indicarse:
    -filterBy: un campo de la tabla por el que filtrar (comment/mueble)
    -value: el valor que dicho campo posee para la comparación (un substring/un número)
    -cond: si debe contener o no a dicho substring (V/F)
    Un ejemplo de esto sería localhost/TPEWeb2-2022-API/comments?filterBy=comment&value=feo&cond=F: dejaría afuera los comentarios que tuvieran la palabra 'feo'.

## Autenticación

    Autenticación por JWT: quien desee emplear la API para POST/PUT debe solicitar primero un token, vía
        localhost/TPEWeb2-2022-API/auth
    lo que devolvería un token. Dicho token debe almacenarse para su uso posterior (un request POST/PUT sin token da un 401, comentar las líneas de checkLoggedIn en el controller para testear dichos métodos REST).
    Los datos de autenticación son
    {
        'user': 'admin',
        'pass': 'admin'
    },
    a insertarse vía Thunder (extensión de VSCode).
