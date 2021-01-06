<?php declare(strict_types=1);


namespace App\WebSocket\Ping;

use Swoft\Session\Session;
use Swoft\WebSocket\Server\Annotation\Mapping\MessageMapping;
use Swoft\WebSocket\Server\Annotation\Mapping\WsController;

/**
 * Class PingController
 * @WsController()
 * @package App\WebSocket\Ping
 */
class PingController
{

    /**
     * @MessageMapping()
     */
    public function index(): void
    {
        Session::mustGet()->push('hi, this is ping.index');
    }

    /**
     * @param string $data
     * @MessageMapping()
     */
    public function echo(string $data): void
    {
        Session::mustGet()->push('(ping.echo)Recv: ' . $data);
    }

    /**
     * @param string $data
     * @MessageMapping("ar")
     * @return string
     */
    public function autoReply(string $data): string
    {
        server()->push(1,"silence");
        return '(ping.ar)Recv: ' . $data;
    }

    /**
     * @param string $data
     * @MessageMapping("ping")
     */
    public function ping(string $data): void
    {
        $fd = context()->getRequest()->getFd();
        server()->push($fd,'pang');
    }

}
