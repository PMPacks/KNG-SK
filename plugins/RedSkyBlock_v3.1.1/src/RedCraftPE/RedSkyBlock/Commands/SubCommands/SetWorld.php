<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\utils\TextFormat;
use pocketmine\command\CommandSender;

use RedCraftPE\RedSkyBlock\SkyBlock;
use RedCraftPE\RedSkyBlock\Commands\Island;

class SetWorld {

  private static $instance;

  public function __construct() {

    self::$instance = $this;
  }

  public function onSetWorldCommand(CommandSender $sender): bool {
  	$this->NCDPrefix = SkyBlock::getInstance()->NCDPrefix;

    if ($sender->hasPermission("skyblock.setworld")) {

      $world = $sender->getLevel()->getFolderName();
      SkyBlock::getInstance()->cfg->set("SkyBlockWorld", $world);
      SkyBlock::getInstance()->cfg->save();
      $sender->sendMessage($this->NCDPrefix."§f" . $world . " §ahas been set as the SkyBlock world on this server.");
      return true;
    } else {

      $sender->sendMessage($this->NCDPrefix."§cYou do not have the proper permissions to run this command.");
      return true;
    }
  }
}
