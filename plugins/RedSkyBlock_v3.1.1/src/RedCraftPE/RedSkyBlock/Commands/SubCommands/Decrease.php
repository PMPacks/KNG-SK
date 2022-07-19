<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

use RedCraftPE\RedSkyBlock\SkyBlock;
use RedCraftPE\RedSkyBlock\Commands\Island;

class Decrease {

  private static $instance;

  public function __contruct() {

    self::$instance = $this;
  }

  public function onDecreaseCommand(CommandSender $sender, array $args): bool {
  	$this->NCDPrefix = SkyBlock::getInstance()->NCDPrefix;

    if ($sender->hasPermission("skyblock.size")) {

      if (count($args) < 3) {

        $sender->sendMessage($this->NCDPrefix."§cUsage: /is decrease <amount> <player>");
        return true;
      } else {

        if (is_numeric($args[1]) && intval($args[1]) > 0) {

          $name = strtolower(implode(" ", array_slice($args, 2)));
          $player = SkyBlock::getInstance()->getServer()->getPlayerExact(implode(" ", array_slice($args, 2)));
          $amount = intval($args[1]);
          if ($player) {

            $player->sendMessage($this->NCDPrefix."§aGiới hạn của hòn đảo của bạn đã được giảm xuống {$amount}");
          }
          $skyblockArray = SkyBlock::getInstance()->skyblock->get("SkyBlock", []);

          if (array_key_exists($name, $skyblockArray)) {

            $startX = $skyblockArray[$name]["Area"]["start"]["X"];
            $startZ = $skyblockArray[$name]["Area"]["start"]["Z"];
            $endX = $skyblockArray[$name]["Area"]["end"]["X"];
            $endZ = $skyblockArray[$name]["Area"]["end"]["Z"];

            $startX += $amount;
            $startZ += $amount;
            $endX -= $amount;
            $endZ -= $amount;

            $skyblockArray[$name]["Area"]["start"]["X"] = $startX;
            $skyblockArray[$name]["Area"]["start"]["Z"] = $startZ;
            $skyblockArray[$name]["Area"]["end"]["X"] = $endX;
            $skyblockArray[$name]["Area"]["end"]["Z"] = $endZ;

            if ($startX > $endX || $startZ > $endZ) {

              $sender->sendMessage($this->NCDPrefix."§cSố tiền bạn đã nhập lớn hơn đảo của {$name}. Chiến dịch bị Bỏ rơi.");
              return true;
            }

            SkyBlock::getInstance()->skyblock->set("SkyBlock", $skyblockArray);
            SkyBlock::getInstance()->skyblock->save();
            $sender->sendMessage($this->NCDPrefix."§aNgười chơi §f" . $name . " §agiới hạn đảo đã được giảm xuống {$amount}");
            return true;
          } else {

            $sender->sendMessage($this->NCDPrefix."§aNgười chơi §f" . $name . " §akhông có đảo nào cả!");
            return true;
          }
        } else {

          $sender->sendMessage($this->NCDPrefix."§cUsage: /is decrease <amount> <player>");
          return true;
        }
      }
    } else {

      $sender->sendMessage($this->NCDPrefix."§cBạn không có quyền để sử dụng lệnh này.");
      return true;
    }
  }
}
