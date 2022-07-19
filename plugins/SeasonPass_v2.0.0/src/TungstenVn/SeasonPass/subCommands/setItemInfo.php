<?php

namespace TungstenVn\SeasonPass\subCommands;

use pocketmine\Player;

use pocketmine\item\Item;
class setItemInfo
{

    public function __construct(int $type,Player $sender,array $args)
    {
        $value = $this->checkRequirement($sender, $args,$type);
        if ($value === null) {
            return;
        }
        if($type == 0){
            $this->setItemName($sender,$value);
        }else{
            $this->setItemLore($sender,$value);
        }
    }

    public function setItemName(Player $sender,string $txt)
    {
        $item = $sender->getInventory()->getItemInHand();
        $item->setCustomName("§r".$txt);
        $sender->getInventory()->setItemInHand($item);
        $sender->sendMessage("§l§6[§eKingNight§9§6]§e Thay đổi tên thành công.");
    }
    public function setItemLore(Player $sender,string $txt)
    {
        $item = $sender->getInventory()->getItemInHand();
        $item->setLore(["§r".$txt]);
        $sender->getInventory()->setItemInHand($item);
        $sender->sendMessage("§l§6[§eKingNight§6]§e Thay đổi lore thành công.");
    }
    public function checkRequirement(Player $sender,array $args,int $type)
    {
        if (!$sender->isOp()) {
            $sender->sendMessage("§l§6● §aSử Dụng:§b /ssp setlor|setname");
            return null;
        }
        $item = $sender->getInventory()->getItemInHand();
        if ($item == Item::get(0, 0, 0)) {
            $sender->sendMessage("§l§6[§eKingNight§6]§e Bạn cần phải cầm vật phẩm trên tay!");
            return null;
        }
        unset($args[0]);
        $txt = implode(" ",$args);
        $txt = str_replace("/n","\n",$txt);
        return $txt;
    }

}