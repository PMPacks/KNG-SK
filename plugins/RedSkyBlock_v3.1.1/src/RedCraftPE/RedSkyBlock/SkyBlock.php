<?php

namespace RedCraftPE\RedSkyBlock;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\block\BlockFactory;
use pocketmine\Player;

use RedCraftPE\RedSkyBlock\Commands\Island;
use RedCraftPE\RedSkyBlock\Tasks\Generate;
use RedCraftPE\RedSkyBlock\Blocks\Lava;

use jojoe77777\FormAPI\{SimpleForm, CustomForm, ModalForm};

class SkyBlock extends PluginBase {
	
	public $NCDPrefix = "§f→§c[§aSkyBlock§c] §f=>> ";
	
  private $eventListener;

  private static $instance;

  private $island;

  public function onEnable(): void {
$this->pointapi = $this->getServer()->getPluginManager()->getPlugin("PointAPI");
    if ($this->cfg->get("SkyBlockWorld") === "") {

      $this->getLogger()->info("§l§cSkyBlock §e↣ §aĐể plugin này hoạt động bình thường, bạn phải đặt thế giới SkyBlock trong máy chủ của mình. (In order for this plugin to function properly, you must set a SkyBlock world in your server).");
      $this->level = null;
    } else {

      $this->level = $this->getServer()->getLevelByName($this->cfg->get("SkyBlockWorld"));
      if (!($this->getServer()->isLevelLoaded($this->cfg->get("SkyBlockWorld")))) {

        if ($this->getServer()->loadLevel($this->cfg->get("SkyBlockWorld"))) {

          $this->getServer()->loadLevel($this->cfg->get("SkyBlockWorld"));
          $this->level = $this->getServer()->getLevelByName($this->cfg->get("SkyBlockWorld"));
          $this->getLogger()->info("§l§cSkyBlock §e↣ §a SkyBlock is running on the world {$this->level->getFolderName()}");
        } else {

          $this->getLogger()->info("§l§cSkyBlock §e↣ §c The level currently set as the SkyBlock world does not exist.");
          $this->level = null;
        }
      } else {

        if ($this->getServer()->isLevelLoaded($this->level->getFolderName())) {

           $this->getLogger()->info(TextFormat::GREEN . "SkyBlock is running on level {$this->level->getFolderName()}");
        } else {

          $this->getServer()->loadLevel($this->level->getFolderName());
          $this->getLogger()->info(TextFormat::GREEN . "SkyBlock is running on level {$this->level->getFolderName()}");
        }
      }
    }
    $this->eventListener = new EventListener($this, $this->level);
    $this->island = new Island($this);
    self::$instance = $this;
  }
  public function onLoad(): void {

		BlockFactory::registerBlock(new Lava(), true);

    if (!is_dir($this->getDataFolder())) {

      @mkdir($this->getDataFolder());
    }
    if (!file_exists($this->getDataFolder() . "skyblock.yml")) {

      $this->saveResource("skyblock.yml");
      $this->skyblock = new Config($this->getDataFolder() . "skyblock.yml", Config::YAML);
    } else {

      $this->skyblock = new Config($this->getDataFolder() . "skyblock.yml", Config::YAML);
    }
    if (!file_exists($this->getDataFolder() . "config.yml")) {

      $this->saveResource("config.yml");
      $this->cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);
    } else {

      $this->cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);
    }

    if (!$this->cfg->exists("PVP")) {

      $this->cfg->set("PVP", "off");
      $this->cfg->save();
    }

    $this->cfg->reload();
    $this->skyblock->reload();
  }
  public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {

    switch(strtolower($command->getName())) {

      case "island":

        return $this->island->onIslandCommand($sender, $command, $label, $args);
      break;
    }
    return false;
  }

  //API FUNCTIONS:
  public static function getInstance(): self {

    return self::$instance;
  }
  public function calcRank(string $name): string {

    $skyblockArray = $this->skyblock->get("SkyBlock", []);
    $users = [];

    if (!array_key_exists($name, $skyblockArray)) {

      return "N/A";
    }

    foreach(array_keys($skyblockArray) as $user) {

      $userValue = $skyblockArray[$user]["Value"];
      $users[$user] = $userValue;
    }

    arsort($users);
    $rank = array_search($name, array_keys($users)) + 1;

    return strval($rank);
  }
  public function getIslandName(Player $player): string {

    $skyblockArray = $this->skyblock->get("SkyBlock", []);
    $name = strtolower($player->getName());

    if (!array_key_exists($name, $skyblockArray)) {

      return "N/A";
    }

    return $skyblockArray[$name]["Name"];
  }
  public function getMembers(Player $player): string {

    $skyblockArray = $this->skyblock->get("SkyBlock", []);
    $name = strtolower($player->getName());

    if (!array_key_exists($name, $skyblockArray)) {

      return "N/A";
    }

    return implode(", ", $skyblockArray[$name]["Members"]);
  }
  public function getValue(Player $player): string {

    $skyblockArray = $this->skyblock->get("SkyBlock", []);
    $name = strtolower($player->getName());

    if (!array_key_exists($name, $skyblockArray)) {

      return "N/A";
    }

    return strval($skyblockArray[$name]["Value"]);
  }
  public function getBanned(Player $player): string {

    $skyblockArray = $this->skyblock->get("SkyBlock", []);
    $name = strtolower($player->getName());

    if (!array_key_exists($name, $skyblockArray)) {

      return "N/A";
    }

    return implode(", ", $skyblockArray[$name]["Banned"]);
  }
  public function getLockedStatus(Player $player): string {

    $skyblockArray = $this->skyblock->get("SkyBlock", []);
    $name = strtolower($player->getName());

    if (!array_key_exists($name, $skyblockArray)) {

      return "N/A";
    }

    if ($skyblockArray[$name]["Locked"]) {

      return "Yes";
    } else {

      return "No";
    }
  }
  public function getSize(Player $player): string {

    $skyblockArray = $this->skyblock->get("SkyBlock", []);
    $name = strtolower($player->getName());

    if (!array_key_exists($name, $skyblockArray)) {

      return "N/A";
    }

    $startX = intval($skyblockArray[$name]["Area"]["start"]["X"]);
    $startZ = intval($skyblockArray[$name]["Area"]["start"]["Z"]);
    $endX = intval($skyblockArray[$name]["Area"]["end"]["X"]);
    $endZ = intval($skyblockArray[$name]["Area"]["end"]["Z"]);

    $length = $endX - $startX;
    $width = $endZ - $startZ;

    return "{$length} x {$width}";
  }
	
	# CODE FORM BY NGUYỄN CÔNG DANH (NCD)
	public function NCDMenuForm(Player $player, string $text) {
		$form = new SimpleForm(function (Player $player, ?int $data = null) {
			$result = $data;
			if ($result === null) {
				return;
			}
			switch ($result) {
				case 0:
				$this->getServer()->getCommandMap()->dispatch($player, "is ncdcreate");
				break;
				case 1:
				$this->NCDWarpForm($player, "");
				break;
				case 2:
				$this->NCDSettingsForm($player);
				break;
				case 3:
				$this->NCDMoRongForm($player, "");
				break;
				case 4:
				$this->NCDInfoForm($player, "");
				break;
				case 5:
				$this->getServer()->getCommandMap()->dispatch($player, "is ncdtop");
				break;
			}
		});
		$form->setTitle("§l§e⇨ §bS§ak§cy§eB§bl§ao§cc§bk§e⇦");
		$form->setContent($text."§l§c⇨ §eT§bo§ap §cĐ§eả§bo§a : §f" . $this->getValue($player));
		$form->addButton("§l§c⇨ §eV§bề §aĐ§cả§eo§c⇦");
		$form->addButton("§l§c➱ §eĐ§bế§an §cĐ§eả§bo §aN§cg§eư§bờ§ai §cK§eh§bá§ac §c⇦");
		$form->addButton("§l§c⇨ §eQ§bu§aả§cn §eL§bí §aĐ§cả§ao §c⇦");
		$form->addButton("§l§c⇨ §eM§bở §aR§cộ§en§bg §aĐ§cả§bo §c⇦");
		$form->addButton("§l§c⇨ §eT§br§aa §cC§eứ§bu §aĐ§cả§eo §c⇦");
		$form->addButton("§l§c⇨ §eT§bo§ap §cĐ§eả§bo §c⇦");
		$form->sendToPlayer($player);
		return $form;
	}
	
	public function NCDWarpForm($player, string $text)
	{
		$form = new CustomForm(function(Player $player, $data) {
			$result = $data;
			if ($result === null) {
				$this->NCDMenuForm($player, "");
				return false;
			}
			if (empty($data[1])) {
				$this->NCDMenuForm($player, "");
				return true;
			}
			$this->getServer()->getCommandMap()->dispatch($player, "is ncdtp " . $data[1]);
			return false;
		});
		$form->setTitle("§l§c♦ §eĐ§bế§an §cđ§eả§bo §an§cg§eư§bờ§bi §ak§ch§eá§bc §c♦");
		$form->addLabel($text);
		$form->addInput("§l§e⇨ §aN§bh§eậ§cp §et§bê§an §cn§eg§bư§aờ§ci §ec§bh§aơ§ci:", "Nhập tên người chơi vào đây");
		$form->sendToPlayer($player);
	}
	
	public function NCDInfoForm($player, string $text)
	{
		$list = [];
		foreach ($this->getServer()->getOnlinePlayers() as $p) {
			$list[] = $p->getName();
		}
		$this->playerList[$player->getName()] = $list;
		$form = new CustomForm(function(Player $player, $data) {
			$result = $data;
			if ($result === null) {
				$this->NCDMenuForm($player, "");
				return false;
			}
			$index = $data[1];
			$playerName = $this->playerList[$player->getName()][$index];
			if ($playerName instanceof Player) {
			}
			$this->getServer()->getCommandMap()->dispatch($player, "is ncdinfo " . $playerName);
			return false;
		});
		$form->setTitle("§l§c♦ §eT§br§aa §cc§eứ§bu §ađ§cả§eo §c♦");
		$form->addLabel($text);
		$form->addDropdown("§l§c⇨ §eChọn người chơi:", $this->playerList[$player->getName()]);
		$form->sendToPlayer($player);
	}
	
	public function NCDMoRongForm(Player $player, $text) {
		$form = new SimpleForm(function (Player $player, ?int $data = null) {
			$result = $data;
			if ($result === null) {
				$this->NCDMenuForm($player, "");
				return;
			}
			switch ($result) {
				case 0:
				$point = $this->pointapi->myPoint($player);
				if ($point >= 150) {
					$this->pointapi->reducePoint($sender, $cost);
					$this->NCDMoRongForm($player, "§c↣ §aBạn đã mở rộng đảo được 25 block\n");
				} else {
					$this->NCDMoRongForm($player, "§c↣ §fBạn không đủ point để mở rộng đảo\n");
				}
				break;
				case 1:
				$point = $this->pointapi->myPoint($player);
				if ($point >= 300) {
					$this->pointapi->reducePoint($sender, $cost);
					$this->NCDMoRongForm($player, "§c↣ §aBạn đã mở rộng đảo được 50 block\n");
				} else {
					$this->NCDMoRongForm($player, "§c↣ §fBạn không đủ point để mở rộng đảo\n");
				}
				break;
			}
		});
		$point = $this->pointapi->myPoint($player);
		$form->setTitle("§l§c♦ §eM§bở §ar§cộ§en§bg §ađ§cả§eo (Bảo Trì Chức Năng Này) §c♦");
		$form->setContent($text."§l§c↣ §eS§bố §aP§co§ei§bn§at §cC§eủ§ba §aB§cạ§en: §f".$point);
		$form->addButton("§l§e• §c2§b5 §aB§cl§eo§bc§ak §9- §c150 Point §e•");
		$form->addButton("§l§e• §c5§a0 §eB§bl§ao§cc§ek §9- §c300 Point §e•");
		$form->sendToPlayer($player);
		return $form;
	}
	
	# Settings Form By Nguyễn Công Danh (NCD)
	public function NCDSettingsForm(Player $player) {
		$form = new SimpleForm(function (Player $player, ?int $data = null) {
			$result = $data;
			if ($result === null) {
				$this->NCDMenuForm($player, "");
				return;
			}
			switch ($result) {
				case 0:
				$this->NCDReNameForm($player, "");
				break;
				case 1:
				$this->getServer()->getCommandMap()->dispatch($player, "is ncdsetspawn");
				break;
				case 2:
				$this->getServer()->getCommandMap()->dispatch($player, "is ncdlock");
				break;
				case 3:
				$this->NCDAddRemoveForm($player, "");
				break;
				case 4:
				$this->NCDKickForm($player, "");
				break;
				case 5:
				$this->NCDBanUnBanForm($player, "");
				break;
				case 6:
				$this->NCDSettingSkyBlock($player, "");
				break;
			}
		});
		$form->setTitle("§l§eC§bà§ai §cĐ§eặ§bt §aS§ck§ey§bB§al§co§ec§bk");
		$form->setContent("§l§c⇨ §eM§be§am§cb§ee§br§as: §f" . $this->getMembers($player));
		$form->addButton("§l§c⇨ §eĐ§bổ§ai §ct§eê§bn §c⇦");
		$form->addButton("§l§c⇨ §eĐ§bặ§at §cc§eh§bỗ §ah§cồ§ei §bs§ai§cn§eh §bc§aủ§ca §eđ§bả§ao §c⇦");
		$form->addButton("§l§c⇨ §eK§bh§ao§cá§e/§bM§aở §ck§eh§aó§ba §cđ§eả§ao §c⇦");
		$form->addButton("§l§c⇨ §eT§bh§aê§cm§e/§bX§aó§ca §et§bh§aà§cn§eh §bv§ai§cê§en§c⇦");
		$form->addButton("§l§c⇨ §eK§bi§ac§ck §en§bg§aư§cờ§ei §bc§ah§cơ§ei §c⇦");
		$form->addButton("§l§c⇨ §eC§bấ§am§c/§eH§bủ§ay §cc§eấ§bm §an§cg§eư§sờ§bi §cc§eh§aơ§bi §c⇦");
		$form->addButton("§l§c⇨ §eC§bà§ai §eđ§bặ§at §cđ§eả§ao §c⇦");
		$form->sendToPlayer($player);
		return $form;
	}
	
	public function NCDReNameForm($player, string $text)
	{
		$form = new CustomForm(function(Player $player, $data) {
			$result = $data;
			if ($result === null) {
				$this->NCDSettingsForm($player);
				return false;
			}
			if (empty($data[1])) {
				$this->NCDSettingsForm($player);
				return true;
			}
			$this->getServer()->getCommandMap()->dispatch($player, "is ncdrename " . $data[1]);
			return false;
		});
		$form->setTitle("§l§c♦ §eĐ§bổ§ai §ct§eê§bn §ađ§cả§eo | KNG VN §c♦");
		$form->addLabel($text);
		$form->addInput("§l§c⇨ §eN§bh§aậ§cp §et§bê§an:", "Nhập tên đảo mới vào đây");
		$form->sendToPlayer($player);
	}
	
	public function NCDAddRemoveForm($player, string $text)
	{
		$list = [];
		foreach ($this->getServer()->getOnlinePlayers() as $p) {
			$list[] = $p->getName();
		}
		$this->playerList[$player->getName()] = $list;
		$form = new CustomForm(function(Player $player, $data) {
			$result = $data;
			if ($result === null) {
				$this->NCDSettingsForm($player);
				return false;
			}
			$index = $data[1];
			$playerName = $this->playerList[$player->getName()][$index];
			if ($playerName instanceof Player) {
			}
			switch ($data[2]) {
				case 0:
				$this->getServer()->getCommandMap()->dispatch($player, "is ncdadd " . $playerName);
				break;
				case 1:
				$this->getServer()->getCommandMap()->dispatch($player, "is ncdremove " . $playerName);
				break;
			}
			return false;
		});
		$form->setTitle("§l§c♦ §eT§bh§aê§cm§e/§eX§bó§aa §ct§eh§aà§bn§ch §ev§ai§bê§an §c♦");
		$form->addLabel($text."§l§c⇨ §eT§bh§aà§cn§eh §bv§bi§cê§an: §f" . $this->getMembers($player) . "\n");
		$form->addDropdown("§l§c⇨ §eC§bh§aọ§cn §en§bg§aư§cờ§ei §bc§ah§cơ§ei:", $this->playerList[$player->getName()]);
		$form->addToggle("§l§c⇨ §eG §bạ §at §cs §ea §an §bg §cđ §eể §ax §bó §ca");
		$form->sendToPlayer($player);
	}
	
	public function NCDKickForm($player, string $text)
	{
		$list = [];
		foreach ($this->getServer()->getOnlinePlayers() as $p) {
			$list[] = $p->getName();
		}
		$this->playerList[$player->getName()] = $list;
		$form = new CustomForm(function(Player $player, $data) {
			$result = $data;
			if ($result === null) {
				$this->NCDSettingsForm($player);
				return false;
			}
			$index = $data[1];
			$playerName = $this->playerList[$player->getName()][$index];
			if ($playerName instanceof Player) {
			}
			$this->getServer()->getCommandMap()->dispatch($player, "is kick " . $playerName);
			return false;
		});
		$form->setTitle("§l§c♦ §eKick thành viên | KNG VN §c♦");
		$form->addLabel($text);
		$form->addDropdown("§l§c⇨ §eChọn người chơi:", $this->playerList[$player->getName()]);
		$form->sendToPlayer($player);
	}
	
	public function NCDBanUnBanForm($player, string $text)
	{
		$list = [];
		foreach ($this->getServer()->getOnlinePlayers() as $p) {
			$list[] = $p->getName();
		}
		$this->playerList[$player->getName()] = $list;
		$form = new CustomForm(function(Player $player, $data) {
			$result = $data;
			if ($result === null) {
				$this->NCDSettingsForm($player);
				return false;
			}
			$index = $data[1];
			$playerName = $this->playerList[$player->getName()][$index];
			if ($playerName instanceof Player) {
			}
			switch ($data[2]) {
				case 0:
				$this->getServer()->getCommandMap()->dispatch($player, "is ncdban " . $playerName);
				break;
				case 1:
				$this->getServer()->getCommandMap()->dispatch($player, "is ncdunban " . $playerName);
				break;
			}
			return false;
		});
		$form->setTitle("§l§c♦ §eCấm/Bỏ cấm thành viên | KNG VN §c♦");
		$form->addLabel($text."§l§c⇨ §eDanh sách bị cấm: §f" . $this->getBanned($player) . "\n");
		$form->addDropdown("§l§c⇨ §cChọn người chơi:", $this->playerList[$player->getName()]);
		$form->addToggle("§l§c⇨ §cGạt sang để bỏ cấm");
		$form->sendToPlayer($player);
	}
	
	public function NCDSettingSkyBlock($player, string $text)
	{
		$form = new CustomForm(function(Player $player, $data) {
			$result = $data;
			if ($result === null) {
				$this->NCDSettingsForm($player);
				return false;
			}
			switch ($data[1]) {
				case 0:
				$name = strtolower($player->getName());
				$skyblockArray = $this->skyblock->get("SkyBlock", []);
				$skyblockArray[$name]["Settings"]["PVP"] = "off";
				$this->skyblock->set("SkyBlock", $skyblockArray);
				$this->skyblock->save();
				break;
				case 1:
				$name = strtolower($player->getName());
				$skyblockArray = $this->skyblock->get("SkyBlock", []);
				$skyblockArray[$name]["Settings"]["PVP"] = "on";
				$this->skyblock->set("SkyBlock", $skyblockArray);
				$this->skyblock->save();
				
				break;
			}
			switch ($data[2]) {
				case 0:
				$name = strtolower($player->getName());
				$skyblockArray = $this->skyblock->get("SkyBlock", []);
				$skyblockArray[$name]["Settings"]["Pickup"] = "off";
				$this->skyblock->set("SkyBlock", $skyblockArray);
				$this->skyblock->save();
				$this->NCDSettingSkyBlock($player, "§l§c↣ §aCài đặt đảo của bạn đã được cập nhật!\n");
				break;
				case 1:
				$name = strtolower($player->getName());
				$skyblockArray = $this->skyblock->get("SkyBlock", []);
				$skyblockArray[$name]["Settings"]["Pickup"] = "on";
				$this->skyblock->set("SkyBlock", $skyblockArray);
				$this->skyblock->save();
				$this->NCDSettingSkyBlock($player, "§l§c↣ §aCài đặt đảo của bạn đã được cập nhật!\n");
				break;
			}
			return false;
		});
		$name = strtolower($player->getName());
		$form->setTitle("§l§c♦ §eCài đặt đảo | KNG VN §c♦");
		$form->addLabel($text."\n§l§c⇨ §eGạt sang bên trái để tắt.\n");
		$skyblockArray = $this->skyblock->get("SkyBlock", []);
		if ($skyblockArray[$name]["Settings"]["PVP"] === "on") {
			$form->addToggle("§l§c⇨ §eTắt/Bật PvP", true);
		} else {
			$form->addToggle("§l§c⇨ §eTắt/Bật PvP", false);
		}
		if ($skyblockArray[$name]["Settings"]["Pickup"] === "on") {
			$form->addToggle("§l§c⇨ §eTắt/Bật Pickup Protection", true);
		} else {
			$form->addToggle("§l§c⇨ §eTắt/Bật Pickup Protection", false);
		}
		$form->sendToPlayer($player);
	}
}