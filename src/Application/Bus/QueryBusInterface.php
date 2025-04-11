<?php
// src/Application/Bus/QueryBusInterface.php
namespace App\Application\Bus;

interface QueryBusInterface
{
    public function handle(object $query): mixed;
}
