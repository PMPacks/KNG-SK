<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\utils\TextFormat;
use pocketmine\command\CommandSender;

use RedCraftPE\RedSkyBlock\SkyBlock;
use RedCraftPE\RedSkyBlock\Commands\Island;

class Remove {

  private static $instance;

  public function __construct() {

    self::$instance = $this;
  }

  public function onRemoveCommand(CommandSender $sender, array $args): bool {
  	$this->NCDPrefix = SkyBlock::getInstance()->NCDPrefix;

    if ($sender->hasPermission("skyblock.members")) {

      if (count($args) < 2) {

        $sender->sendMessage($this->NCDPrefix."§cUsage: /is remove <player>");
        return true;
      } else {

        $skyblockArray = SkyBlock::getInstance()->skyblock->get("SkyBlock", []);
        $senderName = strtolower($sender->getName());
        $player = SkyBlock::getInstance()->getServer()->getPlayerExact(implode(" ", array_slice($args, 1)));
        if (!$player) {

          SkyBlock::getInstance()->NCDAddRemoveForm($sender, "§l§c↣ §f" . implode(" ", array_slice($args, 1)) . " §ckhông tồn tại hoặc không online.\n\n");
          return true;
        } else {

          if (array_key_exists($senderName, $skyblockArray)) {

            if (in_array($player->getName(), $skyblockArray[$senderName]["Members"])) {

              if ($player->getName() !== $sender->getName()) {

                unset($skyblockArray[$senderName]["Members"][array_search($player->getName(), $skyblockArray[$senderName]["Members"])]);
                SkyBlock::getInstance()->skyblock->set("SkyBlock", $skyblockArray);
                SkyBlock::getInstance()->skyblock->save();
                SkyBlock::getInstance()->NCDAddRemoveForm($sender, "§l§c↣ §f" . $player->getName() . " §ađã được xóa khỏi đảo.\n\n");
                return true;
              } else {

                SkyBlock::getInstance()->NCDAddRemoveForm($sender, "§l§c↣ §cBạn không thể xóa bạn khỏi đảo của bạn.\n\n");
                return true;
              }
            } else {

              SkyBlock::getInstance()->NCDAddRemoveForm($sender, "§l§c↣ §f" . $player->getName() . " §ckhông phải là thành viên của đảo bạn.\n\n");
              return true;
            }
          } else {

            SkyBlock::getInstance()->NCDAddRemoveForm($sender, "§l§c↣ §cBạn chưa có đảo nào cả.\n\n");
            return true;
          }
        }
      }
    } else {

      $sender->sendMessage($this->NCDPrefix."§cYou do not have the proper permissions to run this command.");
      return true;
    }
  }
}
