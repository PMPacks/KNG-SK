<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\utils\TextFormat;
use pocketmine\command\CommandSender;

use RedCraftPE\RedSkyBlock\SkyBlock;
use RedCraftPE\RedSkyBlock\Commands\Island;

class Add {

  private static $instance;

  public function __construct() {

    self::$instance = $this;
  }

  public function onAddCommand(CommandSender $sender, array $args): bool {
  	$this->NCDPrefix = SkyBlock::getInstance()->NCDPrefix;

    if ($sender->hasPermission("skyblock.members")) {

      if (count($args) < 2) {

        $sender->sendMessage($this->NCDPrefix."§cUsage: /is add <player>");
        return true;
      } else {

        $senderName = strtolower($sender->getName());
        $limit = SkyBlock::getInstance()->cfg->get("MemberLimit");
        $skyblockArray = SkyBlock::getInstance()->skyblock->get("SkyBlock", []);
        $player = SkyBlock::getInstance()->getServer()->getPlayerExact(implode(" ", array_slice($args, 1)));
        if (!$player) {

          SkyBlock::getInstance()->NCDAddRemoveForm($sender, "§l§c↣ §f" . implode(" ", array_slice($args, 1)) . " §ckhông tồn tại hoặc không online.\n\n");
          return true;
        } else {

          if (array_key_exists($senderName, $skyblockArray)) {

            if (count($skyblockArray[$senderName]["Members"]) === $limit) {

              SkyBlock::getInstance()->NCDAddRemoveForm($sender, "§l§c↣ §cĐảo của bạn đã đạt đến số lượng thành viên tối đa.\n\n");
              return true;
            } else {

              if (in_array($player->getName(), $skyblockArray[$senderName]["Members"])) {

                SkyBlock::getInstance()->NCDAddRemoveForm($sender, "§l§c↣ §f" . $player->getName() . " §cđã là thành viên đảo của bạn.\n\n");
                return true;
              } else {

                if (!in_array($player->getName(), $skyblockArray[$senderName]["Banned"])) {

                  $skyblockArray[$senderName]["Members"][] = $player->getName();
                  SkyBlock::getInstance()->skyblock->set("SkyBlock", $skyblockArray);
                  SkyBlock::getInstance()->skyblock->save();
                  SkyBlock::getInstance()->NCDAddRemoveForm($sender, "§l§c↣ §f" . $player->getName() . " §ađã được thêm vào đảo.\n\n");
                  $player->sendMessage($this->NCDPrefix."§aNgười chơi §f" . $sender->getName() . " §ađã thêm bạn vào đảo của họ.");
                  return true;
                } else {

                  SkyBlock::getInstance()->NCDAddRemoveForm($sender, "§l§c↣ §f" . $player->getName() . " §cđã bị ban khỏi đảo.\n\n");
                  return true;
                }
              }
            }
          } else {

            SkyBlock::getInstance()->NCDAddRemoveForm($sender, "§l§c↣ §cBạn chưa có đảo nào cả.\n\n");
            return true;
          }
        }
      }
    } else {

      $sender->sendMessage($this->NCDPrefix."§cBạn không có quyền để sử dụng lệnh này.");
      return true;
    }
  }
}
