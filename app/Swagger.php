<?php

namespace App;

/**
 * @OA\Info(
 *     title="Gestion Compte API",
 *     version="1.0.0",
 *     description="API pour la gestion des comptes bancaires"
 * )
 * @OA\Server(
 *     url="https://gestion-compte-amycolle.onrender.com",
 *     description="Serveur de production"
 * )
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *     @OA\Property(property="email_verified_at", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class Swagger
{
    // Cette classe sert uniquement à contenir les annotations Swagger globales
}