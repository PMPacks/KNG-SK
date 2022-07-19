<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\utils\TextFormat;
use pocketmine\command\CommandSender;

use RedCraftPE\RedSkyBlock\SkyBlock;

class SetSpawn {

  private static $instance;

  public function __construct() {

    self::$instance = $this;
  }

  public function onSetSpawnCommand(CommandSender $sender): bool {
  	$this->NCDPrefix = SkyBlock::getInstance()->NCDPrefix;

    if ($sender->hasPermission("skyblock.setspawn")) {

      $senderName = strtolower($sender->getName());
      $xPos = round($sender->getX());
      $yPos = round($sender->getY());
      $zPos = round($sender->getZ());
      $skyblockArray = SkyBlock::getInstance()->skyblock->get("SkyBlock", []);
      $startX = $skyblockArray[$senderName]["Area"]["start"]["X"];
      $startY = $skyblockArray[$senderName]["Area"]["start"]["Y"];
      $startZ = $skyblockArray[$senderName]["Area"]["start"]["Z"];
      $endX = $skyblockArray[$senderName]["Area"]["end"]["X"];
      $endY = $skyblockArray[$senderName]["Area"]["end"]["Y"];
      $endZ = $skyblockArray[$senderName]["Area"]["end"]["Z"];

      if (array_key_exists($senderName, $skyblockArray)) {

        if ($xPos > $startX && $yPos > $startY && $zPos > $startZ && $xPos < $endX && $yPos < $endY && $zPos < $endZ) {

          $skyblockArray[$senderName]["Spawn"]["X"] = $xPos;
          $skyblockArray[$senderName]["Spawn"]["Y"] = $yPos;
          $skyblockArray[$senderName]["Spawn"]["Z"] = $zPos;

          SkyBlock::getInstance()->skyblock->set("SkyBlock", $skyblockArray);
          SkyBlock::getInstance()->skyblock->save();
          $sender->sendMessage($this->NCDPrefix."§aToạ độ hồi sinh của đảo bạn đã được đặt ở §f{$xPos}§a, §f{$yPos}§a, §f{$zPos}§a.");
          return true;
        } else {

          $sender->sendMessage($this->NCDPrefix."§cBạn phải ở trong đảo của mình để thiết lập vị trí spawn của đảo!");
          return true;
        }
      } else {

        SkyBlock::getInstance()->NCDMenuForm($sender, "§cBạn chưa có đảo nào cả.\n");
        return true;
      }
    } else {

      $sender->sendMessage($this->NCDPrefix."§cBạn không có quyền để sử dụng lệnh này.");
      return true;
    }
  }
}
