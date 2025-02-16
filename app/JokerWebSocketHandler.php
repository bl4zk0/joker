<?php

namespace App;

use BeyondCode\LaravelWebSockets\Apps\App;
use BeyondCode\LaravelWebSockets\Dashboard\DashboardLogger;
use BeyondCode\LaravelWebSockets\Facades\StatisticsLogger;
use BeyondCode\LaravelWebSockets\QueryParameters;
use BeyondCode\LaravelWebSockets\WebSockets\Channels\ChannelManager;
use BeyondCode\LaravelWebSockets\WebSockets\Exceptions\ConnectionsOverCapacity;
use BeyondCode\LaravelWebSockets\WebSockets\Exceptions\UnknownAppKey;
use BeyondCode\LaravelWebSockets\WebSockets\Exceptions\WebSocketException;
use BeyondCode\LaravelWebSockets\WebSockets\Messages\PusherMessageFactory;
use Exception;
use Ratchet\ConnectionInterface;
use Ratchet\RFC6455\Messaging\MessageInterface;
use Ratchet\WebSocket\MessageComponentInterface;
use App\Jobs\PlayerBotJob;
use App\Jobs\WShelperJob;
use Illuminate\Broadcasting\BroadcastException;

class JokerWebSocketHandler implements MessageComponentInterface
{
    /** @var \BeyondCode\LaravelWebSockets\WebSockets\Channels\ChannelManager */
    protected $channelManager;

    public function __construct(ChannelManager $channelManager)
    {
        $this->channelManager = $channelManager;
    }

    public function onOpen(ConnectionInterface $connection)
    {
        $this
            ->verifyAppKey($connection)
            ->limitConcurrentConnections($connection)
            ->generateSocketId($connection)
            ->establishConnection($connection);
    }

    public function onMessage(ConnectionInterface $connection, MessageInterface $message)
    {
        $message = PusherMessageFactory::createForMessage($message, $connection, $this->channelManager);

        $message->respond();

        StatisticsLogger::webSocketMessage($connection);
    }

    public function onClose(ConnectionInterface $connection)
    {   
        // Resolve socket_id to user_id
        $socket_id = $connection->socketId;
        $app_id = $connection->app->id;

        // Get all channels
        $channels = $this->channelManager->getChannels($app_id);

        // identify disconnected user... this is very slow
        foreach ($channels as $channelName => $channel) {
            if (strpos($channelName, "presence-") === 0) {
                $users = $channel->getUsers();
                if (array_key_exists($socket_id, $users)) {
                    $user_id = $users[$socket_id]->user_id;
                  
                    //move this to $game->playerLeft() move to a job??
                    $user = User::find((int) $user_id);
                    $player = $user->player;
                    $game = $player->game;

                    if ($game === null) {
                        break;
                    }

                    if ($game->state == 'start' || $game->state == 'ready') {
                        $player->update(['game_id' => null, 'position' => null]);
                        $game->refresh();
                        if ($user->is($game->creator) && $game->players->count() > 0) {
                            $game->update(['user_id' => $game->players[0]->user->id]);
                            $game->reposition();

                            WShelperJob::dispatch(false, $game->id, $user->username, 'Left', $game->players, $game->user_id);
                        } elseif ($game->players->count() == 0) {
                            $game->delete();
                        } else {
                            $game->reposition();
                            WShelperJob::dispatch(false, $game->id, $user->username, 'Left', $game->players);
                        }
                        WShelperJob::dispatch(true);
                    } else {
                        if ($game->players()->where('disconnected', true)->count() == 3) {
                            $game->delete();
                            break;
                        }
                        // this one is complicated because of timings
                        $player->update(['disconnected' => true]);
                        WShelperJob::dispatch(false, $game->id, $user->username, 'Left');

                        if ($game->turn == $player->position) {
                            PlayerBotJob::dispatch($game->players[$game->turn], $game)->delay(now()->addSeconds(5));
                        }
                    }
                    break;
                }
            }
        }

        $this->channelManager->removeFromAllChannels($connection);
        
        DashboardLogger::disconnection($connection);
        
        StatisticsLogger::disconnection($connection);
    }

    public function onError(ConnectionInterface $connection, Exception $exception)
    {
        if ($exception instanceof WebSocketException) {
            $connection->send(json_encode(
                $exception->getPayload()
            ));
        }
    }

    protected function verifyAppKey(ConnectionInterface $connection)
    {
        $appKey = QueryParameters::create($connection->httpRequest)->get('appKey');

        if (! $app = App::findByKey($appKey)) {
            throw new UnknownAppKey($appKey);
        }

        $connection->app = $app;

        return $this;
    }

    protected function limitConcurrentConnections(ConnectionInterface $connection)
    {
        if (! is_null($capacity = $connection->app->capacity)) {
            $connectionsCount = $this->channelManager->getConnectionCount($connection->app->id);
            if ($connectionsCount >= $capacity) {
                throw new ConnectionsOverCapacity();
            }
        }

        return $this;
    }

    protected function generateSocketId(ConnectionInterface $connection)
    {
        $socketId = sprintf('%d.%d', random_int(1, 1000000000), random_int(1, 1000000000));

        $connection->socketId = $socketId;

        return $this;
    }

    protected function establishConnection(ConnectionInterface $connection)
    {
        $connection->send(json_encode([
            'event' => 'pusher:connection_established',
            'data' => json_encode([
                'socket_id' => $connection->socketId,
                'activity_timeout' => 30,
            ]),
        ]));

        DashboardLogger::connection($connection);

        StatisticsLogger::connection($connection);

        return $this;
    }
}