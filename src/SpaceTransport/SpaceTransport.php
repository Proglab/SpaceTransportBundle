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

    public function __construct(
        protected string $bearer,
        protected string $channel,
        HttpClientInterface $client = null,
        EventDispatcherInterface $dispatcher = null
    )
    {
        parent::__construct($client, $dispatcher);

    }

    protected function doSend(MessageInterface $message): SentMessage
    {
        if (!$message instanceof ChatMessage) {
            throw new UnsupportedMessageTypeException(__CLASS__, ChatMessage::class, $message);
        }
        
        if ($message->getNotification() === null) {
            $channel = $this->channel;
        } else {
            $channel = $message->getNotification()->getChannel() ?? $this->channel;
        }

        $channel = $this->client->request('GET', urldecode($this->host).'/api/http/chats/channels/all-channels?query='.$channel, [
            'auth_bearer' => $this->bearer,
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ]);

        $channelID = $channel->toArray()['data'][0]['channelId'];

        $result = $this->client->request('POST', urldecode($this->host).'/api/http/chats/channels/'.$channelID.'/messages', [
            'body' => json_encode([
                'text' => $message->getSubject()
            ], JSON_THROW_ON_ERROR),
            'auth_bearer' => $this->bearer,
            'headers' => [
                'Content-Type' => 'application/json',
            ],
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
