# Idea del servicio

La idea de la API es que se puedan agregar (y gestionar) comentarios sobre un mueble en particular.

Dado que no se iba a consultar desde el frontend (el HTML generado en el server), se decidió aislar la API del resto del trabajo; pero la idea original era consumirla desde la 1ra parte del trabajo.

# Endpoints que soporta el servicio

## GET (todos los comments): localhost/TPEWeb2-2022-API/comments

    Un GET necesita, claramente, saber de qué mueble se busca obtener los comentarios; de todas maneras, es provisto este método para su evaluación.

## GET (/:ID): localhost/TPEWeb2-2022-API/comment/:ID

    El ID es empleado para saber para qué mueble (en la tabla, id_mueble) es que pretendo obtener comentarios (obtener un solo comentario no es muy razonable).

## POST (/:ID) localhost/TPEWeb2-2022-API/comments/add/:ID

    El ID es para saber a qué mueble (id_mueble) quiero insertar el comentario; la información a insertarse va por POST[] (de ser incompleta, devuelvo un 400). De ser exitoso, no se debería devolver nada.

## DELETE (/:ID) localhost/TPEWeb2-2022-API/comments/del/:ID

    Se borra un comentario con el id provisto. La autenticación se manejaría desde el front, pero dado que no es necesario, se lo dejó fuera.

# ADICIONALES

## Filtrado

    En vez de optar por el filtrado que responde a la aprobación, decidí implementar un filtrado donde se pudiera elegir por qué campo filtrar (con un 400 si el campo o el orden están mal escritos) y en qué orden: api/comments?sortBy=[]&order=[asc|desc]

## Autenticación

    Se usa una autenticación por JWT, para la que quien desea emplear la API debe solicitar primero un token, vía
        localhost/TPEWeb2-2022-API/token
    lo que devolvería un token. Dicho token debe almacenarse para su uso posterior (un request sin token da un 401).
