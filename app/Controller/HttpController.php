<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Controller;

use Hyperf\HttpMessage\Uri\Uri;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\WebSocketClient\Client;
use Hyperf\WebSocketClient\ClientFactory;
use Hyperf\WebSocketServer\Sender;

/**
 * @AutoController
 */
class HttpController extends Controller
{
    public function index()
    {
        $fd = $this->request->input('fd');

        $sender = $this->container->get(Sender::class);

        $sender->push((int) $fd, 'Hello Hyperf.');

        return $this->response->success(1);
    }

    public function client()
    {
        $client = di()->get(ClientFactory::class)->create('127.0.0.1:9502/ws');
        // $client = new Client(new Uri('ws://127.0.0.1:9502/ws'));

        $client->push('xxxx');
        $client->recv();
        // $client->recv();

        return $client->recv();
    }
}
