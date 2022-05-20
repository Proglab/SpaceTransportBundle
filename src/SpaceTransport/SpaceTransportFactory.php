<?php

namespace Proglab\SpaceTransportBundle\SpaceTransport;

use Symfony\Component\Notifier\Exception\UnsupportedSchemeException;
use Symfony\Component\Notifier\Transport\AbstractTransportFactory;
use Symfony\Component\Notifier\Transport\Dsn;
use Symfony\Component\Notifier\Transport\TransportInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SpaceTransportFactory extends AbstractTransportFactory
{
    protected function getSupportedSchemes(): array
    {
        return ['space'];
    }

    public function create(Dsn $dsn): TransportInterface
    {
        $scheme = $dsn->getScheme();

        if ('space' !== $scheme) {
            throw new UnsupportedSchemeException($dsn, 'space', $this->getSupportedSchemes());
        }

        $bearer = $this->getUser($dsn);
        $channel =  $dsn->getOption('channel');
        $host = $dsn->getHost();
        return (new SpaceTransport($bearer, $channel, $this->client, $this->dispatcher))->setHost($host);
    }
}
