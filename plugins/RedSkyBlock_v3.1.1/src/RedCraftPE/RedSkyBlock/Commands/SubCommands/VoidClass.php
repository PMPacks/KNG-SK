<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\utils\TextFormat;
use pocketmine\command\CommandSender;

use RedCraftPE\RedSkyBlock\SkyBlock;
use RedCraftPE\RedSkyBlock\Commands\Island;

class VoidClass {

  private static $instance;

  public function __construct() {

    self::$instance = $this;
  }

  public function onVoidCommand(CommandSender $sender, array $args): bool {
  	$this->NCDPrefix = SkyBlock::getInstance()->NCDPrefix;

    if ($sender->hasPermission("skyblock.void")) {

      $void = SkyBlock::getInstance()->cfg->get("Void");

      if (count($args) < 2) {

        $sender->sendMessage($this->NCDPrefix."§cUsage: /is void <on/off>");
        return true;
      } else {

        if ($args[1] === "on") {

          $void = "on";
          SkyBlock::getInstance()->cfg->set("Void", $void);
          SkyBlock::getInstance()->cfg->save();
          $sender->sendMessage($this->NCDPrefix."§aThe void has been enabled.");
          return true;
        } else if ($args[1] === "off") {

          $void = "off";
          SkyBlock::getInstance()->cfg->set("Void", $void);
          SkyBlock::getInstance()->cfg->save();
          $sender->sendMessage($this->NCDPrefix."§aThe void has been disabled.");
          return true;
        } else {

          $sender->sendMessage($this->NCDPrefix."§cUsage: /is void <on/off>");
          return true;
        }
      }
    } else {

      $sender->sendMessage($this->NCDPrefix."§cBạn không có quyền để sử dụng lệnh này.");
      return true;
    }
  }
}
