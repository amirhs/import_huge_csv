<?php
// src/Application/Bus/CommandHandlerInterface.php
namespace App\Application\Bus;

interface CommandHandlerInterface
{
    public function __invoke(object $command);
}
