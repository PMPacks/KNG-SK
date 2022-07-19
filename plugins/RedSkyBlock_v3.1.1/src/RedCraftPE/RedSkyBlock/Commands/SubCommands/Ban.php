<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\utils\TextFormat;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\command\PluginIdentifiableCommand;

use RedCraftPE\RedSkyBlock\SkyBlock;
use RedCraftPE\RedSkyBlock\Commands\Island;

class Ban {

  private static $instance;

  public function __construct() {

    self::$instance = $this;
  }

  public function onBanCommand(CommandSender $sender, array $args): bool {
  	$this->NCDPrefix = SkyBlock::getInstance()->NCDPrefix;

    if ($sender->hasPermission("skyblock.ban")) {

      if (count($args) < 2) {

        $sender->sendMessage($this->NCDPrefix."§cUsage: /is ban <player>");
        return true;
      } else {

        $senderName = strtolower($sender->getName());
        $skyblockArray = SkyBlock::getInstance()->skyblock->get("SkyBlock", []);
        $player = SkyBlock::getInstance()->getServer()->getPlayerExact(implode(" ", array_slice($args, 1)));
        if (!$player) {

          SkyBlock::getInstance()->NCDBanUnBanForm($sender, "§l§c↣ §f" . implode(" ", array_slice($args, 1)) . " §ckhông tồn tại hoặc không online.\n\n");
          return true;
        } else {

          if ($player->getName() === $sender->getName()) {

            SkyBlock::getInstance()->NCDBanUnBanForm($sender, "§l§c↣ §cBạn không thể tự cấm mình.\n\n");
            return true;
          }
          if (array_key_exists($senderName, $skyblockArray)) {

            if (in_array($player->getName(), $skyblockArray[$senderName]["Banned"])) {

              SkyBlock::getInstance()->NCDBanUnBanForm($sender, "§l§c↣ §f{$player->getName()}" . " §cđã bị cấm khỏi đảo của bạn.\n\n");
              return true;
            } else {

              $skyblockArray[$senderName]["Banned"][] = $player->getName();
              if (in_array($player->getName(), $skyblockArray[$senderName]["Members"])) {

                unset($skyblockArray[$senderName]["Members"][array_search($player->getName(), $skyblockArray[$senderName]["Members"])]);
              }
              SkyBlock::getInstance()->skyblock->set("SkyBlock", $skyblockArray);
              SkyBlock::getInstance()->skyblock->save();
              SkyBlock::getInstance()->NCDBanUnBanForm($sender, "§l§c↣ §f" . $player->getName() . " §ađã được cấm khỏi đảo của bạn.\n\n");
              $player->sendMessage($this->NCDPrefix."§aNgười chơi §f" . $sender->getName() . " §ađã cấm bạn vào đảo của họ.");
              return true;
            }
          } else {

            SkyBlock::getInstance()->NCDBanUnBanForm($sender, "§l§c↣ §cBạn chưa có đảo nào cả.\n\n");
            return true;
          }
        }
      }
    } else {

      $sender->sendMessage($this->NCDPrefix."§cBạn không có quyền để sử dụng lệnh này.");
      return true;
    }
  }
}
