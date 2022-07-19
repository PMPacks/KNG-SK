<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\utils\TextFormat;
use pocketmine\command\CommandSender;

use RedCraftPE\RedSkyBlock\SkyBlock;
use RedCraftPE\RedSkyBlock\Commands\Island;

class Name {

  private static $instance;

  public function __construct() {

    self::$instance = $this;
  }

  public function onNameCommand(CommandSender $sender, array $args): bool {
  	$this->NCDPrefix = SkyBlock::getInstance()->NCDPrefix;

    if ($sender->hasPermission("skyblock.name")) {

      $skyblockArray = SkyBlock::getInstance()->skyblock->get("SkyBlock", []);
      $senderName = strtolower($sender->getName());
      if (array_key_exists($senderName, $skyblockArray)) {

        $name = $skyblockArray[$senderName]["Name"];

        if (count($args) < 2) {
        	SkyBlock::getInstance()->NCDReNameForm($sender, "§l§c↣ §aTên đảo của bạn là: §f" . $name . "§a.\n");
          return true;
        } else {

          $name = (string) implode(" ", array_slice($args, 1));
          $skyblockArray[$senderName]["Name"] = $name;
          SkyBlock::getInstance()->skyblock->set("SkyBlock", $skyblockArray);
          SkyBlock::getInstance()->skyblock->save();
          SkyBlock::getInstance()->NCDReNameForm($sender, "§l§c↣ §aBạn đã đặt tên đảo thành: §f" . $name . "§a.\n");
          return true;
        }
      } else {

        SkyBlock::getInstance()->NCDReNameForm($sender, "§l§c↣ §cBạn chưa có đảo nào cả.\n");
        return true;
      }
    } else {

      $sender->sendMessage("§l§cSkyBlock §e↣ §cYou do not have the proper permissions to run this command.");
      return true;
    }
  }
}
