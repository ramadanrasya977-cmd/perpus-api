<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Info(
    title: 'Perpus API',
    version: '1.0.0',
    description: 'REST API perpustakaan dengan autentikasi token Laravel Sanctum.',
)]
#[OA\Server(url: 'http://localhost:8000', description: 'Server pengembangan lokal')]
#[OA\SecurityScheme(
    securityScheme: 'sanctum',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'Sanctum token',
    description: 'Kirim token pada header Authorization: Bearer {token}.',
)]
#[OA\Schema(
    schema: 'User',
    type: 'object',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'string', example: 'Admin Perpustakaan'),
        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'admin@example.com'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', nullable: true),
    ],
)]
#[OA\Schema(
    schema: 'Book',
    type: 'object',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'title', type: 'string', example: 'Laskar Pelangi'),
        new OA\Property(property: 'author', type: 'string', example: 'Andrea Hirata'),
        new OA\Property(property: 'publisher', type: 'string', nullable: true, example: 'Bentang Pustaka'),
        new OA\Property(property: 'isbn', type: 'string', example: '9786022916630'),
        new OA\Property(property: 'published_year', type: 'integer', example: 2005),
        new OA\Property(property: 'stock', type: 'integer', example: 12),
        new OA\Property(property: 'description', type: 'string', nullable: true),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', nullable: true),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', nullable: true),
    ],
)]
#[OA\Schema(
    schema: 'BookInput',
    type: 'object',
    required: ['title', 'author', 'isbn', 'published_year', 'stock'],
    properties: [
        new OA\Property(property: 'title', type: 'string', maxLength: 255, example: 'Laskar Pelangi'),
        new OA\Property(property: 'author', type: 'string', maxLength: 255, example: 'Andrea Hirata'),
        new OA\Property(property: 'publisher', type: 'string', nullable: true, example: 'Bentang Pustaka'),
        new OA\Property(property: 'isbn', type: 'string', maxLength: 20, example: '9786022916630'),
        new OA\Property(property: 'published_year', type: 'integer', minimum: 1000, example: 2005),
        new OA\Property(property: 'stock', type: 'integer', minimum: 0, example: 12),
        new OA\Property(property: 'description', type: 'string', nullable: true, example: 'Novel tentang persahabatan.'),
    ],
)]
#[OA\Schema(
    schema: 'BookUpdate',
    type: 'object',
    properties: [
        new OA\Property(property: 'title', type: 'string', maxLength: 255),
        new OA\Property(property: 'author', type: 'string', maxLength: 255),
        new OA\Property(property: 'publisher', type: 'string', nullable: true),
        new OA\Property(property: 'isbn', type: 'string', maxLength: 20),
        new OA\Property(property: 'published_year', type: 'integer', minimum: 1000),
        new OA\Property(property: 'stock', type: 'integer', minimum: 0),
        new OA\Property(property: 'description', type: 'string', nullable: true),
    ],
)]
#[OA\Schema(
    schema: 'RegisterRequest',
    type: 'object',
    required: ['name', 'email', 'password', 'password_confirmation'],
    properties: [
        new OA\Property(property: 'name', type: 'string', example: 'Pengguna Baru'),
        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'user@example.com'),
        new OA\Property(property: 'password', type: 'string', format: 'password', minLength: 8, example: 'password123'),
        new OA\Property(property: 'password_confirmation', type: 'string', format: 'password', example: 'password123'),
    ],
)]
#[OA\Schema(
    schema: 'LoginRequest',
    type: 'object',
    required: ['email', 'password'],
    properties: [
        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'admin@example.com'),
        new OA\Property(property: 'password', type: 'string', format: 'password', example: 'password'),
    ],
)]
#[OA\Schema(
    schema: 'AuthResponse',
    type: 'object',
    properties: [
        new OA\Property(
            property: 'data',
            type: 'object',
            properties: [
                new OA\Property(property: 'user', ref: '#/components/schemas/User'),
                new OA\Property(property: 'token', type: 'string', example: '1|sanctum-token'),
            ],
        ),
    ],
)]
#[OA\Schema(
    schema: 'UserResponse',
    type: 'object',
    properties: [new OA\Property(property: 'data', ref: '#/components/schemas/User')],
)]
#[OA\Schema(
    schema: 'BookResponse',
    type: 'object',
    properties: [new OA\Property(property: 'data', ref: '#/components/schemas/Book')],
)]
#[OA\Schema(
    schema: 'BookCollectionResponse',
    type: 'object',
    properties: [
        new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/Book')),
        new OA\Property(property: 'links', type: 'object'),
        new OA\Property(property: 'meta', type: 'object'),
    ],
)]
#[OA\Schema(
    schema: 'MessageResponse',
    type: 'object',
    properties: [new OA\Property(property: 'message', type: 'string', example: 'Unauthenticated.')],
)]
#[OA\Schema(
    schema: 'ValidationError',
    type: 'object',
    properties: [
        new OA\Property(property: 'message', type: 'string', example: 'The given data was invalid.'),
        new OA\Property(property: 'errors', type: 'object', additionalProperties: new OA\AdditionalProperties(type: 'array', items: new OA\Items(type: 'string'))),
    ],
)]
final class ApiDocumentation {}
