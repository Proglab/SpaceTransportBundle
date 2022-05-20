<?php

namespace Proglab\SpaceTransportBundle\SpaceTransport;

use Symfony\Component\Notifier\Message\ChatMessage;
use Symfony\Component\Notifier\Message\MessageInterface;
use Symfony\Component\Notifier\Message\SentMessage;
use Symfony\Component\Notifier\Transport\AbstractTransport;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Notifier\Exception\UnsupportedMessageTypeException;

final class SpaceTransport extends AbstractTransport
{
    private const TRANSPORT = 'space';
    private string $transport;

    public function __construct(protected string $bearer, protected string $channel, HttpClientInterface $client = null, EventDispatcherInterface $dispatcher = null, ?ParameterBagInterface $parameterBag = null)
    {
        parent::__construct($client, $dispatcher);
        $this->transport = $parameterBag->get('space_transport.url');

    }

    protected function doSend(MessageInterface $message): SentMessage
    {
        if (!$message instanceof ChatMessage) {
            throw new UnsupportedMessageTypeException(__CLASS__, ChatMessage::class, $message);
        }

        $channel = $message->getNotification()->getChannel();

        $result = $this->client->request('POST', $this->transport, [
            'body' => json_encode([
                'content' => [
                    'className' => 'ChatMessage.Text',
                    'text' => $message->getSubject()
                ],
                'channel' => 'channel:name:'.$channel ?? $this->channel,
            ]),
            'auth_bearer' => $this->bearer
        ]);

        $httpInfo = $result->getInfo();
        $sentMessage = new SentMessage($message, (string) $this);
        $sentMessage->setMessageId((int) $httpInfo['start_time']);
        return $sentMessage;
    }

    public function supports(MessageInterface $message): bool
    {
        return $message instanceof ChatMessage && self::TRANSPORT === $message->getTransport();
    }

    public function __toString(): string
    {
        return self::TRANSPORT;
    }
}
