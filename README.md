# Idea del servicio
La idea con los comentarios era que se pudiera comentar:
-Todos los muebles
-Todas las categorías
-Un mueble
-Una categoría
Por lo tanto, se requeriría informar si es mueble o categoría. Si se informara un id, se trataría de un elemento individual; de otra manera estoy solicitando los comentarios de todos los elementos.

Dado que no se iba a consultar desde el frontend (el HTML generado en el server), se decidió aislar la API del resto del trabajo; pero la idea original era consumirla desde la 1ra parte del trabajo.
# Endpoints que soporta el servicio
## BASE URL:
    localhost/TPEWeb2-2022-parte2/comments
## GET (todos los comments): 
    Un GET plano (sin datos) no obtendría nada y se devolvería un 400 (Bad Request). Se enviaría como mínimo una identificación de si se decide consultar muebles o categorías, para lo que se devuelven los comentarios para <todos los muebles> o <todas las categorías>.
## GET (/:ID):
    De nuevo, se necesita indispensablemente la identificación de mueble/categoría (con un 400 de no proveerse). Si se puede avanzar, se obtienen todos los comentarios de UN mueble/UNA categoría.
## GET (/:ID):
    Para qué querría pedir UN solo comentario? Como, en función de la idea de funcionamiento, dicha idea no tiene sentido, se la descartó y no se implementó como un request válido; sí tiene sentido cuando quiero borrar uno de esos comentarios, pero para qué voy a traerme el comentario que voy a borrar?
## POST (/:TYPE,:ID)
    Como en las ideas previas, tengo que cruzar la información de sobre qué sección/elemento debo comentar. El resto de la info va por POST[] (de ser incompleta, devuelvo un 400). De ser exitoso, no se debería devolver nada.
## PUT
    Como se provee la función de poder eliminar un comentario al usuario que lo agregó, no tiene sentido editarlo, por lo que dicha función no se incorporó.
## DELETE (/:TYPE,:ID,:ID_COMMENT)
    De nuevo, identifico si quiero borrar de muebles/categorías, luego de cuál, y si quisiera borrar un solo comentario, proveo su id_comment. 