<?php

namespace SmartQuickView\Original\Events\Handler;

Abstract Class GlobalEventsValidator
{
    abstract public function canBeExecuted() : bool;
}