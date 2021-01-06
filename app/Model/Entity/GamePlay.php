<?php declare(strict_types=1);


namespace App\Model\Entity;


use Swoft\Db\Annotation\Mapping\Column;
use Swoft\Db\Annotation\Mapping\Entity;
use Swoft\Db\Annotation\Mapping\Id;
use Swoft\Db\Eloquent\Model;

/**
 * Class GamePlay
 * @Entity(table="cx_game_play")
 * @package App\Model\Entity
 */
class GamePlay extends Model
{

    /**
     * @Id(incrementing=true)
     * @Column(name="id", prop="id")
     * @var int|null|string
     */
    private $id;

    /**
     * @Column()
     * @var string|null
     */
    private $number;

}
