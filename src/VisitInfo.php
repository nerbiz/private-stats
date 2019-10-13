<?php

namespace Nerbiz\PrivateStats;

use Jenssegers\Date\Date;

class VisitInfo
{
    /**
     * The timestamp of the visit
     * @var int
     */
    protected $timestamp;

    /**
     * The hashed remote IP address of the visit
     * @var string
     */
    protected $ipHash;

    /**
     * The URL of the visit
     * @var string
     */
    protected $url;

    /**
     * The referring URL of the visit
     * @var string|null
     */
    protected $referringUrl;

    /**
     * Fill the properties of this instance
     * @return void
     */
    public function setCurrentValues(): void
    {
        $this->timestamp = time();
        // Hash the IP address for anonymity
        $this->ipHash = hash('sha256', Server::getRemoteAddress());
        $this->url = Server::getRequestUri();
        $this->referringUrl = Server::getReferrer();
    }

    /**
     * @return int
     */
    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    /**
     * @return string
     */
    public function getIpHash(): string
    {
        return $this->ipHash;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string|null
     */
    public function getReferringUrl(): ?string
    {
        return $this->referringUrl;
    }

    /**
     * @return string
     */
    public function getDate(): string
    {
        return Date::createFromTimestamp($this->getTimestamp())
            ->format('Y-m-d H:i:s');
    }
}
