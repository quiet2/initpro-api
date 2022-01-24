<?php

/**
* This file is part of the initpro/kassa-sdk library
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Initpro\KassaSdk;

class GroupManager
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var array List of registered queues
     */
    private $queues = [];

    /**
     * @var string|null Name of the default queue
     */
    private $defaultGroup = null;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Registers an queue
     *
     * @param string $name Group name
     * @param string $id Group ID
     *
     * @return GroupManager
     */
    public function registerGroup($name, $id)
    {
        $this->queues[$name] = $id;

        return $this;
    }

    /**
     * Sets default queue
     *
     * @param string $name Group name
     *
     * @return GroupManager
     */
    public function setDefaultGroup($name)
    {
        if (!$this->hasGroup($name)) {
            throw new \InvalidArgumentException(sprintf('Unknown queue "%s"', $name));
        }

        $this->defaultGroup = $name;

        return $this;
    }

    /**
     * Whether queue registered
     *
     * @param string $name Group name
     *
     * @return bool
     */
    public function hasGroup($name)
    {
        return array_key_exists($name, $this->queues);
    }

    /**
     * Sends a check to queue
     *
     * @param Check|CorrectionCheck $check Check instance
     * @param string $queueName Group name
     *
     * @return mixed
     */
    public function putCheck($check, $queueName = null)
    {
        if ($queueName === null) {
            if ($this->defaultGroup === null) {
                throw new \LogicException('Default queue is not set');
            }
            $queueName = $this->defaultGroup;
        }

        if (!$this->hasGroup($queueName)) {
            throw new \InvalidArgumentException(sprintf('Unknown queue "%s"', $queueName));
        }

        $path = sprintf('lk/api/v1/groups/%s/payment', $this->queues[$queueName]);
        return $this->client->sendRequest($path, $check->asArray());
    }

    /**
     * Whether queue active
     *
     * @param string $name Group name
     *
     * @return bool
     */
    public function isGroupActive($name)
    {
        if (!$this->hasGroup($name)) {
            throw new \InvalidArgumentException(sprintf('Unknown queue "%s"', $name));
        }
        $path = sprintf('lk/api/v1/groups/%s', $this->queues[$name]);
        $data = $this->client->sendRequest($path);
        return is_array($data) && array_key_exists('state', $data) ? $data['state'] == 'active' : false;
    }
}
