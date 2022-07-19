<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\utils\TextFormat;
use pocketmine\command\CommandSender;

use RedCraftPE\RedSkyBlock\SkyBlock;
use RedCraftPE\RedSkyBlock\Commands\Island;

use jojoe77777\FormAPI\SimpleForm;

class Info {

  private static $instance;

  public function __construct() {

    self::$instance = $this;
  }

  public function onInfoCommand(CommandSender $sender, array $args): bool {
  	$this->NCDPrefix = SkyBlock::getInstance()->NCDPrefix;

    if ($sender->hasPermission("skyblock.info")) {

      $skyblockArray = SkyBlock::getInstance()->skyblock->get("SkyBlock");

      if (count($args) < 2) {

        $sender->sendMessage("§l§cSkyBlock §e↣ §cUsage: /is info <player>");
        return true;
      } else {

        $name = strtolower(implode(" ", array_slice($args, 1)));
        if (array_key_exists($name, $skyblockArray)) {

          $islandName = $skyblockArray[$name]["Name"];
          $owner = $skyblockArray[$name]["Members"][0];
          $membersArray = $skyblockArray[$name]["Members"];
          $members = implode(", ", $membersArray);
          $memberCount = count($skyblockArray[$name]["Members"]);
          $bannedArray = $skyblockArray[$name]["Banned"];
          $bannedCount = count($skyblockArray[$name]["Banned"]);
          $banned = implode(", ", $bannedArray);
          $rank = SkyBlock::getInstance()->calcRank($name);
          $value = $skyblockArray[$name]["Value"];
          if ($skyblockArray[$name]["Locked"] === true) {

            $isLocked = "Đã đóng đảo";
          } else {

            $isLocked = "Đang mở đảo";
          }
           SkyBlock::getInstance()->NCDInfoForm($sender, "§l§c↣ §eIsland Info: {$owner}\n" .
				"§l§c↣ §aChủ đảo: §f{$owner}\n" .
				"§l§c↣ §aTên đảo: §f{$islandName}\n" .
				"§l§c↣ §aRank đảo: §f{$rank}\n" .
				"§l§c↣ §aGiá trị của đảo: §f{$value}\n" .
				"§l§c↣ §aSố thành viên: §f{$memberCount}\n" .
				"§l§c↣ §aSố người bị cấm: §f{$bannedCount}\n" .
				"§l§c↣ §aNgười bị cấm: §f{$banned}\n" .
				"§l§c↣ §aTình trạng đảo: §f{$isLocked}\n");
          return true;
        } else {

          SkyBlock::getInstance()->NCDInfoForm($sender, "§l§c↣ §f" . implode(" ", array_slice($args, 1)) . " §ckhông có đảo nào cả..");
          return true;
        }
      }
    } else {

      $sender->sendMessage($this->NCDPrefix.",§cBạn không có quyền để sử dụng lệnh này.");
      return true;
    }
  }
}