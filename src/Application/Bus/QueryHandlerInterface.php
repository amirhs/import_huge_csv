<?php
// src/Application/Bus/QueryHandlerInterface.php
namespace App\Application\Bus;

interface QueryHandlerInterface
{
    public function __invoke(object $query);
}
