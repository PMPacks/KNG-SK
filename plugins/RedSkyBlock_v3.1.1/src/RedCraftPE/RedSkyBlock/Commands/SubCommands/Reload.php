<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\utils\TextFormat;
use pocketmine\command\CommandSender;

use RedCraftPE\RedSkyBlock\SkyBlock;
use RedCraftPE\RedSkyBlock\Commands\Island;

class Reload {

  private static $instance;

  public function __construct() {

    self::$instance = $this;
  }

  public function onReloadCommand(CommandSender $sender): bool {
  	$this->NCDPrefix = SkyBlock::getInstance()->NCDPrefix;

    if ($sender->hasPermission("skyblock.reload")) {

      SkyBlock::getInstance()->skyblock->reload();
      SkyBlock::getInstance()->cfg->reload();
      $sender->sendMessage("§l§cSkyBlock §e↣ §aAll SkyBlock data has been reloaded.");
      return true;
    } else {

      $sender->sendMessage("§l§cSkyBlock §e↣ §cYou do not have the proper permissions to run this command.");
      return true;
    }
  }
}
