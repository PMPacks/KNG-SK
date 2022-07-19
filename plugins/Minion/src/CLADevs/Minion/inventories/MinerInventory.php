<?php

declare(strict_types=1);

namespace CLADevs\Minion\inventories;

use CLADevs\Minion\entities\MinionEntity;
use CLADevs\Minion\entities\types\MinerMinion;
use CLADevs\Minion\EventListener;
use CLADevs\Minion\utils\Configuration;
use onebone\coinapi\CoinAPI;
use pocketmine\item\Item;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class MinerInventory extends HopperInventory{

    /** @var MinerMinion */
    protected $entity;

    public function __construct(Position $position, MinionEntity $entity){
        parent::__construct($position, $entity);
        $this->setItem(4, $this->getLevelItem());
    }

    public function getLevelItem(): Item{
        $item = Item::get(Item::EMERALD);
        $item->setCustomName(TextFormat::LIGHT_PURPLE . "CẤP: " . TextFormat::YELLOW .($lvl = $this->entity->getLevelM()));
        $item->setLore([TextFormat::LIGHT_PURPLE . "Cấp tiếp theo: " . $this->entity->getCost($lvl). TextFormat::YELLOW . " Coins" ]);
        return $item;
    }

    public function onListener(Player $player, Item $sourceItem, EventListener $listener): void{
        parent::onListener($player, $sourceItem, $listener);
        $entity = $this->entity;
        switch($sourceItem->getId()){
            case Item::EMERALD:
                if(($lvl = $entity->getLevelM()) >= Configuration::getMaxLevel()){
                    $player->sendMessage(TextFormat::RED . "Bạn đã nâng osin đến cấp cuối cùng!");
                    return;
                }
                if(class_exists('onebone\coinapi\CoinAPI')){
                    if(CoinAPI::getInstance()->myCoin($player) < $entity->getCost($lvl)){
                        $player->sendMessage(TextFormat::RED . "Bạn không đủ Coins.");
                        return;
                    }
                    $entity->namedtag->setInt("level", $entity->namedtag->getInt("level") + 1);
                    $player->sendMessage(TextFormat::GREEN . "Đã nâng lên cấp: " . $entity->getLevelM());
                    CoinAPI::getInstance()->reduceCoin($player, $entity->getCost($lvl));
                }
                break;
        }
        $this->onClose($player);
    }
}