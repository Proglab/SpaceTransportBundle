<?php

namespace Proglab\SpaceTransportBundle\SpaceTransport;

use Symfony\Component\Notifier\Notification\Notification;

class SpaceNotification extends Notification
{
    protected ?string $channel = null;

    public function __construct(string $title = '', array $channels = [], ?string $icon = null)
    {
        parent::__construct($title, $channels, $icon);
    }

    public function getChannel(): ?string
    {
        return $this->channel;
    }

    public function setChannel(?string $channel): self
    {
        $this->channel = $channel;

        return $this;
    }
}
