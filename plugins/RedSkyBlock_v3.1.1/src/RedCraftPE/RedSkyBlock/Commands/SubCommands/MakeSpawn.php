<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

use RedCraftPE\RedSkyBlock\SkyBlock;

class MakeSpawn {

  private static $instance;

  public function __construct() {

    self::$instance = $this;
  }
  public function onMakeSpawnCommand(CommandSender $sender): bool {
  	$this->NCDPrefix = SkyBlock::getInstance()->NCDPrefix;

    if ($sender->hasPermission("skyblock.makespawn")) {

      if (SkyBlock::getInstance()->skyblock->get("Blocks") !== []) {

        $xPos = ceil($sender->getX());
        $yPos = ceil($sender->getY());
        $zPos = ceil($sender->getZ());

        $x = min(0 + SkyBlock::getInstance()->skyblock->get("x1"), 0 + SkyBlock::getInstance()->skyblock->get("x2"));
        $y = min(SkyBlock::getInstance()->skyblock->get("y1"), SkyBlock::getInstance()->skyblock->get("y2"));
        $z = min(0 + SkyBlock::getInstance()->skyblock->get("z1"), 0 + SkyBlock::getInstance()->skyblock->get("z2"));

        $distanceFromX1 = $xPos - $x;
        $distanceFromY1 = ($yPos - $y) + 1;
        $distanceFromZ1 = $zPos - $z;

        SkyBlock::getInstance()->skyblock->set("CustomX", $distanceFromX1);
        SkyBlock::getInstance()->skyblock->set("CustomY", $distanceFromY1);
        SkyBlock::getInstance()->skyblock->set("CustomZ", $distanceFromZ1);
        SkyBlock::getInstance()->skyblock->save();
        $sender->sendMessage($this->NCDPrefix."§aToạ độ hồi sinh của đảo bạn đã được đặt ở §f{$distanceFromX1}§a, §f{$distanceFromY1}§a, §f{$distanceFromZ1}§a.");
        return true;
      } else {

        $sender->sendMessage($this->NCDPrefix."§cBạn phải tạo và đặt giá trị đặt trước đảo tùy chỉnh trước khi tạo điểm spawn đảo tùy chỉnh.");
        return true;
      }
    } else {

      $sender->sendMessage($this->NCDPrefix."§cBạn không có quyền để sử dụng lệnh này.");
      return true;
    }
  }
}
