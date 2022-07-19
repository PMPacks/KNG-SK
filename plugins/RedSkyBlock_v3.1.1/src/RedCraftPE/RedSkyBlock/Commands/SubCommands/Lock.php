<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\utils\TextFormat;
use pocketmine\command\CommandSender;

use RedCraftPE\RedSkyBlock\SkyBlock;
use RedCraftPE\RedSkyBlock\Commands\Island;

class Lock {

  private static $instance;

  public function __construct() {

    self::$instance = $this;
  }

  public function onLockCommand(CommandSender $sender): bool {
  	$this->NCDPrefix = SkyBlock::getInstance()->NCDPrefix;

    if ($sender->hasPermission("skyblock.lock")) {

      $senderName = strtolower($sender->getName());
      $skyblockArray = SkyBlock::getInstance()->skyblock->get("SkyBlock", []);

      if (array_key_exists($senderName, $skyblockArray)) {

        if ($skyblockArray[$senderName]["Locked"] === true) {
          SkyBlock::getInstance()->getServer()->getCommandMap()->dispatch($sender, "is ncdunlock");
          return true;
        } else {

          $skyblockArray[$senderName]["Locked"] = true;
          SkyBlock::getInstance()->skyblock->set("SkyBlock", $skyblockArray);
          SkyBlock::getInstance()->skyblock->save();
          $sender->sendMessage($this->NCDPrefix."§aĐảo của bạn đã được khóa.");
          return true;
        }
      } else {

        $sender->sendMessage($this->NCDPrefix."§cYou do not have an island yet.");
        return true;
      }
    } else {

      $sender->sendMessage($this->NCDPrefix."§cYou do not have the proper permissions to run this command.");
      return true;
    }
  }
}
