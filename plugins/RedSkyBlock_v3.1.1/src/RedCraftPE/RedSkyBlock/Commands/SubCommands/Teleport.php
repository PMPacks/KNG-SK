<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\utils\TextFormat;
use pocketmine\command\CommandSender;
use pocketmine\level\Position;

use RedCraftPE\RedSkyBlock\SkyBlock;
use RedCraftPE\RedSkyBlock\Commands\Island;

class Teleport {

  private static $instance;

  public function __construct() {

    self::$instance = $this;
  }

  public function onTeleportCommand(CommandSender $sender, array $args): bool {
  	$this->NCDPrefix = SkyBlock::getInstance()->NCDPrefix;

    if ($sender->hasPermission("skyblock.create")) {

      $levelName = SkyBlock::getInstance()->cfg->get("SkyBlockWorld");

      if ($levelName === "") {

        $sender->sendMessage($this->NCDPrefix."§cYou must set a SkyBlock world in order for this plugin to function properly.");
        return true;
      } else {

        if (SkyBlock::getInstance()->getServer()->isLevelLoaded($levelName)) {

          $level = SkyBlock::getInstance()->getServer()->getLevelByName($levelName);
        } else {

          if (SkyBlock::getInstance()->getServer()->loadLevel($levelName)) {

            SkyBlock::getInstance()->getServer()->loadLevel($levelName);
            $level = SkyBlock::getInstance()->getServer()->getLevelByName($levelName);
          } else {

            $sender->sendMessage($this->NCDPrefix."§cThe world currently set as the SkyBlock world does not exist.");
            return true;
          }
        }
      }

      $level = SkyBlock::getInstance()->getServer()->getLevelByName($levelName);
      $skyblockArray = SkyBlock::getInstance()->skyblock->get("SkyBlock", []);
      $senderName = strtolower($sender->getName());

      if (count($args) < 2) {

        if (array_key_exists($senderName, $skyblockArray)) {

          $x = $skyblockArray[$senderName]["Area"]["start"]["X"];
          $z = $skyblockArray[$senderName]["Area"]["start"]["Z"];

          $sender->teleport(new Position($skyblockArray[$senderName]["Spawn"]["X"], $skyblockArray[$senderName]["Spawn"]["Y"], $skyblockArray[$senderName]["Spawn"]["Z"], $level));
          $sender->sendMessage($this->NCDPrefix."§aChào mừng đến với hòn đảo của bạn.");
          return true;
        } else {

          SkyBlock::getInstance()->getServer()->getCommandMap()->dispatch($sender, "is ncdcreate");
          return true;
        }
      } else {

        if ($sender->hasPermission("skyblock.tp")) {

          $name = strtolower(implode(" ", array_slice($args, 1)));

          if (array_key_exists($name, $skyblockArray)) {

            if ($skyblockArray[$name]["Locked"] === false || in_array($sender->getName(), $skyblockArray[$name]["Members"])) {

              if (!in_array($sender->getName(), $skyblockArray[$name]["Banned"])) {
                $x = $skyblockArray[$name]["Area"]["start"]["X"];
                $z = $skyblockArray[$name]["Area"]["start"]["Z"];

                $sender->teleport(new Position($skyblockArray[$name]["Spawn"]["X"], $skyblockArray[$name]["Spawn"]["Y"], $skyblockArray[$name]["Spawn"]["Z"], $level));
                $sender->setFlying(false);
                $sender->setAllowFlight(false);
                $sender->sendMessage($this->NCDPrefix."§aChào mừng đến với hòn đảo của §f{$skyblockArray[$name]["Name"]}§a.");
                return true;
              } else {

                SkyBlock::getInstance()->NCDWarpForm($sender, "§l§c↣ §f" . $skyblockArray[$name]["Members"][0] . " §cđã cấm bạn vào đảo của họ.\n\n");
                return true;
              }
            } else {

              SkyBlock::getInstance()->NCDWarpForm($sender, "§l§c↣ §f" . $skyblockArray[$name]["Members"][0] . "'§cs is locked.\n\n");
              return true;
            }
          } else {

            SkyBlock::getInstance()->NCDWarpForm($sender, "§l§c↣ §f" . implode(" ", array_slice($args, 1)) . " §ckhông có đảo nào cả.\n\n");
            return true;
          }
        } else {

          $sender->sendMessage($this->NCDPrefix."§cYou do not have the proper permissions to run this command.");
          return true;
        }
      }
    } else {

      $sender->sendMessage($this->NCDPrefix."§cBạn không có quyền để sử dụng lệnh này.");
      return true;
    }
  }
}
