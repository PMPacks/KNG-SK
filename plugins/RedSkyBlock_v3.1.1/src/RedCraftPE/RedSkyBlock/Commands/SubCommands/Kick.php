<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\utils\TextFormat;
use pocketmine\command\CommandSender;

use RedCraftPE\RedSkyBlock\SkyBlock;
use RedCraftPE\RedSkyBlock\Commands\Island;

class Kick {

  private static $instance;

  public function __construct() {

    self::$instance = $this;
  }

  public function onKickCommand(CommandSender $sender, array $args): bool {
  	$this->NCDPrefix = SkyBlock::getInstance()->NCDPrefix;

    if ($sender->hasPermission("skyblock.kick")) {

      $senderName = strtolower($sender->getName());
      $skyblockArray = SkyBlock::getInstance()->skyblock->get("SkyBlock", []);

      if (array_key_exists($senderName, $skyblockArray)) {

        if (count($args) < 2) {

          $sender->sendMessage($this->NCDPrefix."§cUsage: /is kick <player>");
          return true;
        } else {

          $player = SkyBlock::getInstance()->getServer()->getPlayerExact(implode(" ", array_slice($args, 1)));
          if (!$player) {

            SkyBlock::getInstance()->NCDKickForm($sender, "§l§c↣ §f" . implode(" ", array_slice($args, 1)) . " §ckhông tồn tại hoặc không online.\n\n");
            return true;
          } else {

            if ($player !== $sender) {

              $playerX = $player->getX();
              $playerY = $player->getY();
              $playerZ = $player->getZ();
              $startX = $skyblockArray[$senderName]["Area"]["start"]["X"];
              $startY = $skyblockArray[$senderName]["Area"]["start"]["Y"];
              $startZ = $skyblockArray[$senderName]["Area"]["start"]["Z"];
              $endX = $skyblockArray[$senderName]["Area"]["end"]["X"];
              $endY = $skyblockArray[$senderName]["Area"]["end"]["Y"];
              $endZ = $skyblockArray[$senderName]["Area"]["end"]["Z"];

              if ($playerX > $startX && $playerY > $startY && $playerZ > $startZ && $playerX < $endX && $playerY < $endY && $playerZ < $endZ) {

                $player->teleport($player->getSpawn());
                $player->sendMessage($this->NCDPrefix."§aNgười chơi §f" . $sender->getName() . " §ađã đuổi bạn khỏi đảo của họ.");
                SkyBlock::getInstance()->NCDKickForm($sender, "§l§c↣ §f" . $player->getName() . " §ađã bị đuổi khỏi đảo của bạn.\n\n");
                return true;
              } else {

                SkyBlock::getInstance()->NCDKickForm($sender, "§l§c↣ §f" . $player->getName() . " §ckhông có trên đảo của bạn.\n\n");
                return true;
              }
            } else {

              SkyBlock::getInstance()->NCDKickForm($sender, "§l§c↣ §cBạn không thể tự đuổi mình ra khỏi đảo của bạn.\n\n");
              return true;
            }
          }
        }
      } else {

        SkyBlock::getInstance()->NCDKickForm($sender, "§l§c↣ §cBạn chưa có đảo nào cả.\n\n");
        return true;
      }
    } else {

      $sender->sendMessage($this->NCDPrefix."§cBạn không có quyền để sử dụng lệnh này.");
      return true;
    }
  }
}
