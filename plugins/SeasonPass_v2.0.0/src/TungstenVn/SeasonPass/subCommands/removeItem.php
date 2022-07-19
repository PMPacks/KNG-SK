<?php

namespace TungstenVn\SeasonPass\subCommands;

use TungstenVn\SeasonPass\commands\commands;

use pocketmine\item\Item;
use pocketmine\Player;
class removeItem
{

    public function __construct(commands $cmds,Player $sender,array $args)
    {
        $value = $this->checkRequirement($sender, $args);
        if ($value === null) {
            return;
        }
        if($value[0] == 0){
            $normalPass = $cmds->ssp->getConfig()->getNested("normalPass");
            if(!isset($normalPass[$value[1]])){
                $sender->sendMessage("§l§6[§eKingNight§6]§e Không có vật phẩm này ở ô thứ tự đó.");
                return;
            }else{
                $name = unserialize(utf8_decode($normalPass[$value[1]]))->getName();
                unset($normalPass[$value[1]]);
                $cmds->ssp->getConfig()->setNested("normalPass",$normalPass);
                $cmds->ssp->getConfig()->save();
                $sender->sendMessage("§l§6[§eKingNight§6]§e Bạn đã xóa vật phẩm khỏi §aNormal Pass §ethành công.");
                return;
            }
        }else{
            $royalPass = $cmds->ssp->getConfig()->getNested("royalPass");
            if(!isset($royalPass[$value[1]])){
                $sender->sendMessage("§l§6[§eKingNight§6]§e Không có vật phẩm này ở ô thứ tự đó.");
                return;
            }else{
                $name = unserialize(utf8_decode($royalPass[$value[1]]))->getName();
                unset($royalPass[$value[1]]);
                $cmds->ssp->getConfig()->setNested("royalPass",$royalPass);
                $cmds->ssp->getConfig()->save();
                $sender->sendMessage("§l§6[§eKingNight§6]§e Bạn đã xóa vật phẩm khỏi §aRoyal Pass§e thành công.");
                return;
            }
        }
    }

    public function checkRequirement(Player $sender, $args)
    {
        if (!$sender->isOp()) {
            $sender->sendMessage("§l§6● §aSử Dụng:§b /ssp removeitem (type) (idSlot)");
            return null;
        }
        if (!isset($args[1]) or !isset($args[2])) {
            $sender->sendMessage("§l§6● §aSử Dụng:§b /ssp removeitem (type) (idSlot)");
            return null;
        }
        if ($args[1] != 0 && $args[1] != 1) {
            $sender->sendMessage("§l§6[§eKingNight§6]§e Số lượng cần là 0 hoặc 1.");
            return null;
        }
        return [$args[1],$args[2]];
    }

}