<?php

namespace Proglab\SpaceTransportBundle\SpaceTransport;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Symfony\Component\Notifier\Message\ChatMessage;
use Symfony\Component\Notifier\Transport\Dsn;

class SpaceHandler extends AbstractProcessingHandler
{
    public function __construct(protected SpaceTransportFactory $spaceFactory, $level = Logger::CRITICAL, bool $bubble = true)
    {
        $this->level = $level;
        parent::__construct($level, $bubble);
    }

    protected function write(array $record): void
    {
        $transport = $this->spaceFactory->create(new Dsn($_ENV['SPACE_DSN']));
        $message = new ChatMessage($record['formatted']);
        $transport->send($message);
    }
}
