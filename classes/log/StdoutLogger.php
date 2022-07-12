<?php


namespace log;


class StdoutLogger implements ILogger
{

    public function log(string $message)
    {
        print $message;
    }
}