<?php

namespace NewsToChat\Service;

use GorkaLaucirica\HipchatAPIv2Client\API\RoomAPI;
use GorkaLaucirica\HipchatAPIv2Client\API\UserAPI;
use GorkaLaucirica\HipchatAPIv2Client\Auth\OAuth2;
use GorkaLaucirica\HipchatAPIv2Client\Client;
use GorkaLaucirica\HipchatAPIv2Client\Model\Message;

class HipChat
{
    /**
     * @var string
     */
    private $token;

    /**
     * @param string $token
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * @return Client
     */
    private function setupClient()
    {
        $auth = new OAuth2($this->token);
        $client = new Client($auth);

        return $client;
    }

    /**
     * send a private message
     * @param  string $user
     * @param  string $content
     * @return null
     */
    public function sendUserMessage($user = null, $content = null)
    {
        $client = $this->setupClient();
        $userApi = new UserAPI($client);
        $content = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $content);

        if ($user !== null && $content !== null) {
            $userApi->privateMessageUser($user, $content);
        }
    }

    /**
     * send a message to a room
     * @param  string $room
     * @param  string $content
     * @return null
     */
    public function sendRoomMessage($room = null, $content = null)
    {
        $client = $this->setupClient();
        $roomApi = new RoomAPI($client);
        $message = new Message;

        if ($room !== null && $content !== null) {
            $message->setMessage($content);
            $message->setColor('green');
            $message->setNotify(true);
            $roomApi->sendRoomNotification($room, $message);
        }
    }

    /**
     * @return array
     */
    public function getAllRooms()
    {
        $client = $this->setupClient();
        $roomApi = new RoomAPI($client);

        return $roomApi->getRooms();
    }

    /**
     * @return array
     */
    public function getAllUsers()
    {
        $maxGroupResults = 100;
        $maxTotalResults = 1000;
        $client = $this->setupClient();
        $userApi = new UserAPI($client);

        for ($startIndex = 0; $startIndex < $maxTotalResults; $startIndex = $startIndex + $maxGroupResults) {
            $userGroup[] = $userApi->getAllUsers([
                'start-index' => $startIndex,
                'max-results' => $maxGroupResults
            ]);
        }

        foreach ($userGroup as $users) {
            foreach ($users as $user) {
                $names[] = $user->getName();
            }
        }

        return $names;
    }
}
