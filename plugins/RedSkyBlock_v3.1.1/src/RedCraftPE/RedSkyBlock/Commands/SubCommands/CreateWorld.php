<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\utils\TextFormat;
use pocketmine\command\CommandSender;

use RedCraftPE\RedSkyBlock\Generators\WorldGenerator;
use RedCraftPE\RedSkyBlock\SkyBlock;
use RedCraftPE\RedSkyBlock\Commands\Island;

class CreateWorld {

  private static $instance;

  private $worldGenerator;

  public function __construct($plugin) {

    self::$instance = $this;

    $this->plugin = $plugin;

    $this->worldGenerator = new WorldGenerator($this->plugin);
  }

  public function onCreateWorldCommand(CommandSender $sender, array $args): bool {
  	$this->NCDPrefix = SkyBlock::getInstance()->NCDPrefix;

    if ($sender->hasPermission("skyblock.createworld")) {

        if (count($args) < 2) {

          $sender->sendMessage($this->NCDPrefix."§cUsage: /is createworld <world name>");
          return true;
        } else {

          $world = (string) implode(" ", array_slice($args, 1));

          if (SkyBlock::getInstance()->getServer()->isLevelLoaded($world)) {

            $sender->sendMessage($this->NCDPrefix."§cThế giới bạn đang cố gắng tạo ra đã tồn tại.");
            return true;
          } else {

            $this->worldGenerator->generateWorld($world);
            SkyBlock::getInstance()->cfg->set("SkyBlockWorld", $world);
            SkyBlock::getInstance()->cfg->save();
            $sender->sendMessage($this->NCDPrefix."§aThế giới §f" . $world . " §ađã được tạo và đặt làm thế giới SkyBlock trong máy chủ này.");
            return true;
          }
        }
    } else {

      $sender->sendMessage($this->NCDPrefix."§cBạn không có quyền để sử dụng lệnh này.");
      return true;
    }
  }
}
