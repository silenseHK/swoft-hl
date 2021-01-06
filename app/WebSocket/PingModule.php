<?php


namespace App\WebSocket;

use Swoft\Db\DB;
use Swoft\Http\Message\Response;
use Swoft\Redis\Redis;
use Swoft\WebSocket\Server\Annotation\Mapping\OnClose;
use Swoft\WebSocket\Server\Annotation\Mapping\OnHandshake;
use Swoft\WebSocket\Server\Annotation\Mapping\OnOpen;
use Swoft\WebSocket\Server\Annotation\Mapping\WsModule;
use App\WebSocket\Ping\PingController;
use Swoft\Http\Message\Request;
use Swoft\WebSocket\Server\MessageParser\TokenTextParser;
use Swoole\WebSocket\Server;

/**
 * Class PingModule
 * @WsModule(
 *     "/ping",
 *     messageParser=TokenTextParser::class,
 *     controllers={PingController::class}
 * )
 * @package App\WebSocket
 */
class PingModule
{
    /**
     * @OnHandshake()
     * @param Request $request
     * @param Response $response
     * @return array
     */
    public function onHandshake(Request $request, Response $response): array
    {
        $query_string = $request->getUriQuery();
        var_dump($query_string);
        $data = decrypt($query_string);
        var_dump($data);
        if(!$data){
            $response->withContent('参数缺失');
            return [false, $response];
        }
        $user_id = intval($data);
        if(!$user_id){
            $response->withContent('参数缺失');
            return [false, $response];
        }
        $fd = $request->getFd();
        try{
            ##将user_id加入在线集合中
            Redis::sadd("ONLINE_USER_ID", (string)$user_id);
            ##将user_id和fd绑定
            Redis::hset("USER_ID_BIND_FD", (string)$user_id, (string)$fd);
            ##将fd和user_id绑定
            Redis::hset("FD_BIND_USER_ID", (string)$fd, (string)$user_id);
        }catch(\Exception $e){
            $response->withContent($e->getMessage());
            return [false, $response];
        }
        return [true, $response];
    }

    /**
     * @OnOpen()
     * @param Request $request
     * @param int $fd
     */
    public function onOpen(Request $request, int $fd): void
    {
        server()->push($fd, 'hello, welcome! :)');
    }

    /**
     * @OnClose()
     * @param Server $server
     * @param int $fd
     */
    public function onClose(Server $server, int $fd)
    {
        ##通过fd查找user_id
        $user_id = Redis::hget("FD_BIND_USER_ID", (string)$fd);
        if($user_id){
            ##将user_id从在线集合中删除
            Redis::srem("ONLINE_USER_ID", (string)$user_id);
            ##将user_id和fd解绑
            Redis::hdel("USER_ID_BIND_FD", (string)$user_id);
            ##将fd和user_id解绑
            Redis::hdel("FD_BIND_USER_ID", (string)$fd);
        }
    }

}
