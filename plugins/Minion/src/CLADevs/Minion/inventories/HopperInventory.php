<?php

declare(strict_types=1);

namespace CLADevs\Minion\inventories;

use CLADevs\Minion\entities\MinionEntity;
use CLADevs\Minion\EventListener;
use CLADevs\Minion\Main;
use pocketmine\block\Block;
use pocketmine\block\Chest;
use pocketmine\inventory\CustomInventory;
use pocketmine\item\Item;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\nbt\NetworkLittleEndianNBTStream;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\BlockActorDataPacket;
use pocketmine\network\mcpe\protocol\types\WindowTypes;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class HopperInventory extends CustomInventory{

    /** @var Vector3|Position */
    protected $holder;
    /** @var MinionEntity */
    protected $entity;

    public function __construct(Position $position, MinionEntity $entity){
        parent::__construct($position);
        $this->entity = $entity;
        $this->setItem(0, $this->getDestoryItem());
        $this->setItem(2, $this->getChestItem());
    }

    public function getName(): string{
        return "Hopper";
    }

    public function getDefaultSize(): int{
        return 5;
    }

    public function getNetworkType(): int{
        return WindowTypes::HOPPER;
    }

    public function onOpen(Player $who): void{
        $holder = $this->getHolder();
        $block = Block::get(Block::HOPPER_BLOCK);
        $block->x = $holder->getX();
        $block->y = $holder->getY();
        $block->z = $holder->getZ();
        if($holder instanceof Position){
            $block->level = $holder->getLevel();
        }else{
            $block->level = $this->getEntity()->getLevel();
        }
        $who->getLevel()->sendBlocks([$who], [$block]);
        $w = new NetworkLittleEndianNBTStream;
        $nbt = new CompoundTag("", []);
        $nbt->setString("id", "Hopper");
        $nbt->setString("CustomName", TextFormat::GOLD . "Cài Đặt OSIN");
        $pk = new BlockActorDataPacket();
        $pk->x = $holder->getX();
        $pk->y = $holder->getY();
        $pk->z = $holder->getZ();
        $pk->namedtag = $w->write($nbt);
        $who->dataPacket($pk);
        parent::onOpen($who);
    }

    public function onClose(Player $who): void{
        $holder = $this->getHolder();
        $block = Block::get(Block::AIR);
        $block->x = $holder->getX();
        $block->y = $holder->getY();
        $block->z = $holder->getZ();
        if($holder instanceof Position){
            $block->level = $holder->getLevel();
        }else{
            $block->level = $this->getEntity()->getLevel();
        }
        $who->getLevel()->sendBlocks([$who], [$block]);
        parent::onClose($who);
    }

    /**
     * @return Position|Vector3
     */
    public function getHolder(){
        return $this->holder;
    }

    public function getEntity(): MinionEntity{
        return $this->entity;
    }

    public function getDestoryItem(): Item{
        $item = Item::get(Item::REDSTONE_DUST);
        $item->setCustomName(TextFormat::RED . "Thu Hồi OSIN");
        return $item;
    }

    public function getChestItem(): Item{
        $islinked = $this->entity->isChestLinked() ? "Đã liên kết" : "Chưa liên kết";
        $item = Item::get(Item::CHEST);
        $item->setCustomName(TextFormat::DARK_GREEN . "Liên kết Chest");
        $item->setLore([" ",  TextFormat::YELLOW . "Thông Tin: " . TextFormat::WHITE . $islinked, TextFormat::YELLOW . "Toạ Độ: " . TextFormat::WHITE . $this->entity->getChestCoordinates()]);
        return $item;
    }

    public function onListener(Player $player, Item $sourceItem, EventListener $listener): void{
        $entity = $this->getEntity();
        switch($sourceItem->getId()){
            case Item::REDSTONE_DUST:
                $listener->removeLinkable($player);
                $entity->flagForDespawn();
                $player->getInventory()->addItem(Main::asItem($this->entity::NAME, $player, $entity->getLevelM()));
                break;
            case Item::CHEST:
                if($entity->getLookingBehind() instanceof Chest){
                    $player->sendMessage(TextFormat::RED . "Vui lòng phá hủy chest đã liên kết với osin để liên kết chest khác.");
                    return;
                }
                if($listener->isLinkable($player)){
                    $player->sendMessage(TextFormat::RED . "Bạn đang trong chế độ liên kết.");
                    return;
                }
                $listener->addLinkable($player, $entity);
                $player->sendMessage(TextFormat::LIGHT_PURPLE . "Vui lòng nhấp vào chest mà bạn muốn liên kết.");
                break;
        }
        $this->onClose($player);
    }
}