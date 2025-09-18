<?php
namespace App\Swagger;

use OpenApi\Annotations as OA;


    /****
     * @OA\info(
     * title="Api en Laravel",
     * version="1.0.0",
     * description="Documentacion bien chida tiooooooo con Laravel 11 y Passport"
     * )
     * 
     * @OA\Server(
     * url="L5_SWAGGER_CONST_HOST",
     * description ="Server Local"
     * )
     * 
     * @OA\SecurityScheme(
     * securityScheme="bearerAuth",
     * type="http",
     * shcema="bearer",
     * bearerFormat="JWT"
     * )
     * 
     * @OA\Tag(name="Auth", description="Autenticacion y Perfil")
     * @OA\Tag(name="Posts", description="Posts bien lindos para los usuarios")
     */
class OpenApi{}