## Build Setup

```bash
# install dependencies
$ composer install / $ composer update

# migrate the models in your database (set first .env)
$ php artisan migrate

# serve with hot reload at localhost:8000
$ php artisan serve


## Nota adiciona (Docker)

Estuve probando con sail y realice la configuracion de docker inicial, tuve que activar el modo hyper 
y habilitar en mi setup el la vistualizacion, despues instale un distro de ubunto 18.04 LTS Free inicio y probe
instalando un proyecto de prueba segund la doc de laravel y se inicio pero llego a al 8/15 de la instllacion y se cancelo
lo mismo me paso con desde el proyecto, asi que no me fue posible probarlo 
en mi sistema (Posiblemente tenga algo sin configurar o inhabilitado ya que hace un tiempo realice algo de virtualizacion con Androit Studio y Flutter)

## Endpoints RentingCarzFootball

Este repositorio de caracter evaluativo, contiene la API desarrollada con Laravel, con las funciones de:
    - Conexion a la api de https://www.football-data.org/
    - Lista de todas las competiciones disponibles.
    - Lista una competencia en particular.
    - Lista todos los equipos para una competencia en particular. (Necesaria la menbresion €25)
    - Lista posiciones para una competencia en particular (Necesaria la menbresion €25)
    - Lista todos los partidos de una competición en particular.

## Stack

Laravel (MySQL) - Vue (Nuxt 2 - CompositionApi) 

# Acerca de la prueba

Para esta prueba queremos que realices una aplicacion donde muestre tus conocimientos adquiridos, procuramos que no sea demasiado larga y disfrutes realizandola. Las tecnologia ha usar son la combinacion o cualquiera de estas (PHP, Javascript, Typescript, Yii, Laravel, Vue, React)


## Objetivo

Crear una aplicacion que contenga:

    - API para consultar los proximos partidos de futbol europeo a realizarse (puede escoger la liga de su preferencia) 
    - Front en donde visualizar los resultados (no importa la apariencia pero que sea legible).
    - Debe almacenar los partidos en una base de datos, procure que no se repitan.

El orden de los pasos es de libre eleccion

## Recursos
`
Api para usar https://www.football-data.org/
`

## Entregables

    - El proyecto debe estar cargado en el repositorio personal de GitHub del aspirante.
    - El proyecto debe permitir correr docker-compose build y un docker-compose up para revision
    - Debe existir almenos un commit por cada paso importante en los objetivos
