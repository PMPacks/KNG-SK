<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\utils\TextFormat;
use pocketmine\command\CommandSender;

use RedCraftPE\RedSkyBlock\SkyBlock;

class Custom {

  private static $instance;

  public function __construct() {

    self::$instance = $this;
  }
  public function onCustomCommand(CommandSender $sender, array $args): bool {
  	$this->NCDPrefix = SkyBlock::getInstance()->NCDPrefix;

    if ($sender->hasPermission("skyblock.custom")) {

      if (count($args) < 2) {

        $sender->sendMessage( $this->NCDPrefix."§cUsage: /is custom <on/off>");
        return true;
      } else {

        if ($args[1] === "on") {

          if (SkyBlock::getInstance()->skyblock->get("Blocks") === []) {

            $sender->sendMessage($this->NCDPrefix."§cBạn phải tạo và đặt cài đặt trước đảo tùy chỉnh trước khi bật đảo tùy chỉnh.");
            return true;
          } else {

            SkyBlock::getInstance()->skyblock->set("Custom", true);
            SkyBlock::getInstance()->skyblock->save();
            $sender->sendMessage($this->NCDPrefix."§aCustom đảo đã được kích hoạt!");
            return true;
          }
        } else if ($args[1] === "off") {

          SkyBlock::getInstance()->skyblock->set("Custom", false);
          SkyBlock::getInstance()->skyblock->save();
          $sender->sendMessage($this->NCDPrefix."§aCustom đảo đã được tắt!");
          return true;
        } else {

          $sender->sendMessage($this->NCDPrefix."§cUsage: /is custom <on/off>");
          return true;
        }
      }
    } else {

      $sender->sendMessage($this->NCDPrefix."§cBạn không có quyền để sử dụng lệnh này.");
      return true;
    }
  }
}
