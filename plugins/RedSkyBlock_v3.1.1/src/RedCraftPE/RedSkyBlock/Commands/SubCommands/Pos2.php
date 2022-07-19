<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\utils\TextFormat;
use pocketmine\command\CommandSender;

use RedCraftPE\RedSkyBlock\SkyBlock;

class Pos2 {

  private static $instance;

  public function __construct() {

    self::$instance = $this;
  }
  public function onPos2Command(CommandSender $sender): bool {
  	$this->NCDPrefix = SkyBlock::getInstance()->NCDPrefix;

    if ($sender->hasPermission("skyblock.pos")) {

      $xPos = round($sender->getX());
      $yPos = round($sender->getY());
      $zPos = round($sender->getZ());

      SkyBlock::getInstance()->skyblock->set("x2", $xPos);
      SkyBlock::getInstance()->skyblock->set("y2", $yPos);
      SkyBlock::getInstance()->skyblock->set("z2", $zPos);
      SkyBlock::getInstance()->skyblock->set("Pos2", true);
      SkyBlock::getInstance()->skyblock->save();
      $sender->sendMessage("§l§cSkyBlock §e↣ §aPosition 2 has been set at" . TextFormat::WHITE . " {$xPos}, {$yPos}, {$zPos}.");
      return true;
    } else {

      $sender->sendMessage("§l§cSkyBlock §e↣ §cYou do not have the proper permissions to run this command.");
      return true;
    }
  }
}
