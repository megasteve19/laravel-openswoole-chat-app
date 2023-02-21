<?php

namespace App\Console\Commands;

use App\Models\Message;
use App\Models\User;
use Illuminate\Console\Command;
use OpenSwoole\Http\Request;
use OpenSwoole\WebSocket\Frame;
use OpenSwoole\WebSocket\Server;

class SocketServeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'socket:serve';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Starts websocket.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $server = new Server('0.0.0.0', 81);

        $server->on('Start', $this->onStart(...));

        $server->on('Open', $this->onOpen(...));

        $server->on('Message', $this->onMessage(...));

        $server->on('Close', $this->onClose(...));

        $server->on('Disconnect', $this->onDisconnect(...));

        $server->start();
    }

    public function onStart(Server $server): void
    {
        $this->info('Server started at 127.0.0.1:81');
    }

    public function onOpen(Server $server, Request $request): void
    {
        $this->warn('New connection opened: ' . $request->fd);
    }

    public function onMessage(Server $server, Frame $frame): void
    {
        $this->info('received message: ' . $frame->data);

        $data = json_decode($frame->data, true);

        if ($data['action'] === 'register')
        {
            $user = User::find($data['user_id']);

            $user->update(['socket_id' => $frame->fd]);
        }
        elseif ($data['action'] === 'pushMessage')
        {
            $message = Message::create(['user_id' => $data['user_id'], 'content' => $data['content']])->load('user');

            $socketIds = User::whereNotNull('socket_id')->pluck('socket_id')->values();

			// $this->info('ids: ' . implode(', ', $socketIds));

            foreach ($socketIds as $fd)
            {
                $server->push($fd, json_encode(['event' => 'newMessage', 'data' => $message->toArray()]));
            }
        }
    }

    public function onClose(Server $server, int $fd): void
    {
        User::where('socket_id', $fd)->update(['socket_id' => null]);

        $this->warn('Connection closed: ' . $fd);
    }

    public function onDisconnect(Server $server, int $fd): void
    {
        User::where('socket_id', $fd)->update(['socket_id' => null]);

        $this->error('Connection disconnected: ' . $fd);
    }
}
