<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\utils\TextFormat;
use pocketmine\command\CommandSender;

use RedCraftPE\RedSkyBlock\Commands\Island;
use RedCraftPE\RedSkyBlock\SkyBlock;

use pocketmine\Server;
use pocketmine\Player;

use jojoe77777\FormAPI\SimpleForm;

class Top {

  private static $instance;

  public function __construct() {

    self::$instance = $this;
  }

  public function onTopCommand(CommandSender $sender): bool {
  	$this->NCDPrefix = SkyBlock::getInstance()->NCDPrefix;

    if ($sender->hasPermission("skyblock.top")) {

      $skyblockArray = SkyBlock::getInstance()->skyblock->get("SkyBlock", []);
      $first = "N/A";
      $firstValue = 0;
      $second = "N/A";
      $secondValue = 0;
      $third = "N/A";
      $thirdValue = 0;
      $fourth = "N/A";
      $fourthValue = 0;
      $fifth = "N/A";
      $fifthValue = 0;
      $values = [];
      $copyOfArray = $skyblockArray;

      foreach(array_keys($skyblockArray) as $user) {

        $value = $skyblockArray[$user]["Value"];
        $values[] = $value;
        rsort($values);
      }

      $counter = count($skyblockArray);
      if ($counter > 5) {

        $counter = 5;
      }

      for ($i = 1; $i <= count($skyblockArray); $i++) {

        $value = $values[$i - 1];

        $NameIndex = array_search($value, array_column($copyOfArray, "Value"));
        $keys = array_keys($copyOfArray);
        $NameValue = $copyOfArray[$keys[$NameIndex]];
        $Name = array_search($NameValue, $copyOfArray);

        if ($i === 1) {$first = $NameValue["Members"][0]; $firstValue = $value;}
        if ($i === 2) {$second = $NameValue["Members"][0]; $secondValue = $value;}
        if ($i === 3) {$third = $NameValue["Members"][0]; $thirdValue = $value;}
        if ($i === 4) {$fourth = $NameValue["Members"][0]; $fourthValue = $value;}
        if ($i === 5) {$fifth = $NameValue["Members"][0]; $fifthValue = $value;}

        unset($copyOfArray[$Name]);
      }
		$this->NCDTopForm($sender, "§l§c↣ §aTop 5 Island SkyBlock\n\n" .
			"§l§c↣ §cTOP 1: §f{$first} §cđạt được: §e{$firstValue} §cđiểm\n\n" .
			"§l§c↣ §cTOP 2: §f{$second} §cđạt được: §e{$secondValue} §cđiểm\n\n" .
			"§l§c↣ §cTOP 3: §f{$third} §cđạt được: §e{$thirdValue} §cđiểm\n\n" .
			"§l§c↣ §cTOP 4: §f{$fourth} §cđạt được: §e{$fourthValue} §cđiểm\n\n" .
			"§l§c↣ §cTOP 5: §f{$fifth} §cđạt được: §e{$fifthValue} §cđiểm\n\n");
      return true;
    } else {

      $sender->sendMessage($this->NCDPrefix."§cBạn không có quyền để sử dụng lệnh này.");
      return true;
    }
  }
	
	# CODE FORM BY NGUYỄN CÔNG DANH (NCD)
	public function NCDTopForm(Player $sender, string $text) {
		$form = new SimpleForm(function (Player $sender, ?int $data = null) {
			$result = $data;
			if ($result === null) {
				SkyBlock::getInstance()->NCDMenuForm($sender, "");
				return;
			}
			switch ($result) {
				case 0:
				SkyBlock::getInstance()->NCDMenuForm($sender, "");
				break;
			}
		});
		$form->setTitle("§l§b♦ §cTop Island §f(§cNCD§f) §b♦");
		$form->setContent($text);
		$form->addButton("§l§e• §cSubmit §e•");
		$form->sendToPlayer($sender);
		return $form;
	}
}
	