<?php


namespace App\Http\Controller;

use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\Http\Server\Annotation\Mapping\RequestMethod;

/**
 * Class ProductController
 * @Controller()
 * @package App\Http\Controller
 */
class ProductController
{
    /**
     * @RequestMapping(route="", method={RequestMethod::GET})
     */
    public function index()
    {
        return response()->withData(['a'=>'bb']);
    }

    /**
     * @RequestMapping(route="add", method={RequestMethod::GET})
     * @return \Swoft\Http\Message\Response|\Swoft\WebSocket\Server\Message\Response
     */
    public function add()
    {
        return returnJson(['name'=>123]);
    }

}
