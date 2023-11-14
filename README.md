# Proyecto de Alquileres RapiBnB

## Descripción
Este proyecto de máquinas de programación 3 tiene como objetivo desarrollar un sitio web de alquileres, similar a Airbnb, cumpliendo con los requisitos establecidos y siguiendo una narrativa específica.

## Estructura del Proyecto
El proyecto está dividido en dos partes: el frontend y el backend, organizados según el patrón de diseño MVC. Esta estructura facilita la gestión de los distintos componentes del sistema.

## Herramientas y Tecnologías
Frontend: HTML, CSS, JavaScript
Backend: [Nombre del Framework o Lenguaje Utilizado]
Patrón de Diseño: Modelo-Vista-Controlador (MVC)
Mini ORM y Router incorporados para una mejor gestión de la base de datos y las rutas.
Clase Session y Modelo
La clase Session se encarga de proporcionar información sobre el usuario, como su rol, ID y estado de verificación, siendo útil en diversas validaciones. El modelo complementa esta funcionalidad al interactuar con la base de datos.

## Consultas SQL y Backup
La carpeta consultas_sql contiene las consultas utilizadas en el proyecto, mientras que el backup con usuarios y publicaciones facilita las pruebas. Utiliza el usuario 'root' y contraseña vacía para simplificar el proceso de prueba.

## Desafíos Superados
Una de las mayores dificultades fue la implementación de las rentas. Se resolvió mediante una única página que presenta la información de rentar/ofertar tanto para las propias publicaciones como para aquellas hechas a las publicaciones de otros usuarios. La evaluación de publicaciones se realiza solo cuando la reserva está finalizada, permitiendo evaluar y responder en la misma tabla de reservas.

## Constantes y Mejoras Posibles
Se han desarrollado algunas constantes para mejorar la legibilidad del código. Aunque aún hay espacio para mejoras, este proyecto refleja el aprendizaje continuo, especialmente en el uso de MVC y JavaScript.


## Cuentas para probar el sitio web

ADMIN:
user: administrador
pw: Administrador123@

User no verificado:

user: Iri
pw: Irina123@

User verificado

user: lucho
pw: Lucho123@

rapibnb_test.sql es el backup de la base de datos. debe de crear una base de datos que se llame rapibnb_test y ahi importar los datos del backup.


## Contacto
Si tienes preguntas o sugerencias, no dudes en contactarme: lucho.aguilar.hl.38@gmail.com
