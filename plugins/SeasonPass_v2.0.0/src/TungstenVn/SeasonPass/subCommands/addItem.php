<?php

namespace TungstenVn\SeasonPass\subCommands;

use TungstenVn\SeasonPass\commands\commands;

use pocketmine\item\Item;
use pocketmine\Player;
class addItem
{

    public function __construct(commands $cmds,Player $sender,array $args)
    {
        $value = $this->checkRequirement($sender, $args,$cmds);
        if ($value === null) {
            return;
        }
        $this->addItemToDataBase($cmds, $value[0], $value[1], $value[2], $sender);
    }

    public function addItemToDataBase(commands $cmds,Item $item,int $type,int $idSlot,Player $sender)
    {
        if ($type == 0) {
            $array = $cmds->ssp->getConfig()->getNested('normalPass');
            $item = utf8_encode(serialize($item));
            $array[$idSlot] = $item;
            $cmds->ssp->getConfig()->setNested("normalPass", $array);
            $cmds->ssp->getConfig()->save();
            $sender->sendMessage("§l§6[§eKingNight§6]§e Thêm vật phẩm vào §aNormal Pass §ethành công.");
        } else {
            $array = $cmds->ssp->getConfig()->getNested('royalPass');
            $item = utf8_encode(serialize($item));
            $array[$idSlot] = $item;
            $cmds->ssp->getConfig()->setNested("royalPass", $array);
            $cmds->ssp->getConfig()->save();
            $sender->sendMessage("§l§6[§eKingNight§6]§e Thêm vật phẩm vào §aRoyal Pass §ethành công.");
        }
    }

    public function checkRequirement(Player $sender, $args,commands $cmds)
    {
        if (!$sender->isOp()) {
            $sender->sendMessage("§l§6● §aSử Dụng:§b /ssp additem (type) (idSlot) (level to get)");
            return null;
        }
        $item = $sender->getInventory()->getItemInHand();
        if ($item == Item::get(0, 0, 0)) {
            $sender->sendMessage("§l§6[§eKingNight§6]§e Bạn cần phải cầm vật phẩm trên tay!");
            return null;
        }
        if (!isset($args[1]) or !isset($args[2]) or !isset($args[3])) {
            $sender->sendMessage("§l§6● §aSử Dụng:§b /ssp additem (type) (idSlot) (level to get)");
            return null;
        }
        if ($args[1] != 0 && $args[1] != 1) {
            $sender->sendMessage("§l§6[§eKingNight§6]§e Số lượng cần là 0 hoặc 1.");
            return null;
        }
        if(!is_numeric($args[2])){
            $sender->sendMessage("§l§6[§eKingNight§6]§e Ô thứ tự cần phải là số.");
            return null;
        }
        if($args[1] == 0){
            $cmds->ssp->getConfig()->setNested("marker.normalPass.".$args[2],(int) $args[3]);
            $cmds->ssp->getConfig()->save();
        }else{
            $cmds->ssp->getConfig()->setNested("marker.royalPass.".$args[2],(int) $args[3]);
            $cmds->ssp->getConfig()->save();
        }
        return [$item, $args[1], $args[2]];
    }

}