<?php

declare(strict_types=1);

namespace muqsit\chestshop;

use muqsit\chestshop\button\ButtonFactory;
use muqsit\chestshop\category\Category;
use muqsit\chestshop\category\CategoryConfig;
use muqsit\chestshop\category\CategoryEntry;
use muqsit\chestshop\database\Database;
use muqsit\chestshop\economy\EconomyManager;
use muqsit\chestshop\ui\ConfirmationUI;
use muqsit\chestshop\libs\muqsit\invmenu\InvMenuHandler;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

final class Loader extends PluginBase{

	/** @var Database */
	private $database;

	/** @var ConfirmationUI|null */
	private $confirmation_ui;

	/** @var ChestShop */
	private $chest_shop;

	public function onEnable() : void{
		$this->initVirions();
		$this->database = new Database($this);

		if($this->getConfig()->getNested("confirmation-ui.enabled", false)){
			$this->confirmation_ui = new ConfirmationUI($this);
		}

		$this->chest_shop = new ChestShop($this->database);

		ButtonFactory::init($this);
		CategoryConfig::init($this);
		EconomyManager::init($this);

		$this->database->load($this->chest_shop);
		$this->getLogger()->info("
██╗░░░██╗██╗███████╗████████╗  ██╗░░██╗░█████╗░░█████╗░
██║░░░██║██║██╔════╝╚══██╔══╝  ██║░░██║██╔══██╗██╔══██╗
╚██╗░██╔╝██║█████╗░░░░░██║░░░  ███████║██║░░██║███████║
░╚████╔╝░██║██╔══╝░░░░░██║░░░  ██╔══██║██║░░██║██╔══██║
░░╚██╔╝░░██║███████╗░░░██║░░░  ██║░░██║╚█████╔╝██║░░██║
░░░╚═╝░░░╚═╝╚══════╝░░░╚═╝░░░  ╚═╝░░╚═╝░╚════╝░╚═╝░░╚═╝

░░███╗░░░█████╗░░█████╗░██╗░██╗
░████║░░██╔══██╗██╔══██╗╚═╝██╔╝
██╔██║░░██║░░██║██║░░██║░░██╔╝░
╚═╝██║░░██║░░██║██║░░██║░██╔╝░░
███████╗╚█████╔╝╚█████╔╝██╔╝██╗
╚══════╝░╚════╝░░╚════╝░╚═╝░╚═╝

██████╗░██╗░░░██╗  
██╔══██╗╚██╗░██╔╝  
██████╦╝░╚████╔╝░  
██╔══██╗░░╚██╔╝░░  
██████╦╝░░░██║░░░  
╚═════╝░░░░╚═╝░░░  

░█████╗░██╗░░░░░██╗░█████╗░██╗░░██╗███████╗██████╗░████████╗██████╗░░█████╗░███╗░░██╗
██╔══██╗██║░░░░░██║██╔══██╗██║░██╔╝██╔════╝██╔══██╗╚══██╔══╝██╔══██╗██╔══██╗████╗░██║
██║░░╚═╝██║░░░░░██║██║░░╚═╝█████═╝░█████╗░░██║░░██║░░░██║░░░██████╔╝███████║██╔██╗██║
██║░░██╗██║░░░░░██║██║░░██╗██╔═██╗░██╔══╝░░██║░░██║░░░██║░░░██╔══██╗██╔══██║██║╚████║
╚█████╔╝███████╗██║╚█████╔╝██║░╚██╗███████╗██████╔╝░░░██║░░░██║░░██║██║░░██║██║░╚███║
░╚════╝░╚══════╝╚═╝░╚════╝░╚═╝░░╚═╝╚══════╝╚═════╝░░░░╚═╝░░░╚═╝░░╚═╝╚═╝░░╚═╝╚═╝░░╚══╝
     ");
	}

	private function initVirions() : void{
		if(!InvMenuHandler::isRegistered()){
			InvMenuHandler::register($this);
		}
	}

	public function onDisable() : void{
		$this->database->close();
	}

	public function getConfirmationUi() : ?ConfirmationUI{
		return $this->confirmation_ui;
	}

	public function getChestShop() : ChestShop{
		return $this->chest_shop;
	}

	public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
		if(!($sender instanceof Player)){
			$sender->sendMessage(TextFormat::RED . "This command can only be executed as a player.");
			return true;
		}

		if(isset($args[0])){
			switch($args[0]){
				case "addcat":
				case "addcategory":
					if($sender->hasPermission("chestshop.command.add")){
						$button = $sender->getInventory()->getItemInHand();
						if(!$button->isNull()){
							if(isset($args[1])){
								$name = implode(" ", array_slice($args, 1));
								$success = true;
								try{
									$this->chest_shop->addCategory(new Category($name, $button));
								}catch(\InvalidArgumentException $e){
									$sender->sendMessage(TextFormat::RED . $e->getMessage());
									$success = false;
								}
								if($success){
									$sender->sendMessage(
										TextFormat::GREEN . "§aĐã tạo danh mục với tên §f[§l§a• §c {$name}§a •§f]§a thành công" . TextFormat::RESET . TextFormat::GREEN . "!" . TextFormat::EOL .
										TextFormat::GRAY . "§aSử dụng " . TextFormat::GREEN . "/{$label} additem {$name} <số tiền>" . TextFormat::GRAY . " để liệt kê món đồ trong tay bạn!"
									);
								}
								return true;
							}
						}else{
							$sender->sendMessage(TextFormat::RED . "Hãy cầm một món đồ trên tay bạn. Mục đó sẽ được sử dụng làm biểu tượng trong §b/{$label}.");
							return true;
						}
					}else{
						$sender->sendMessage(TextFormat::RED . "Bạn đoán xem bạn có quyền sử dụng lệnh này hay không :>.");
						return true;
					}
					$sender->sendMessage(TextFormat::RED . "§aSử dụng: §b/{$label} {$args[0]} <tên>");
					return true;
				case "removecat":
				case "removecategory":
					if($sender->hasPermission("chestshop.command.remove")){
						if(isset($args[1])){
							$name = implode(" ", array_slice($args, 1));
							$success = true;
							try{
								$this->chest_shop->removeCategory($name);
							}catch(\InvalidArgumentException $e){
								$sender->sendMessage(TextFormat::RED . $e->getMessage());
								$success = false;
							}
							if($success){
								$sender->sendMessage(TextFormat::GREEN . "§aĐã xoá thành công danh mục§f[§l§a• §c{$name}§a •§f]" . TextFormat::RESET . TextFormat::GREEN . "!");
							}
							return true;
						}
					}else{
						$sender->sendMessage(TextFormat::RED . "Bạn đoán xem bạn có quyền để sử dụng lệnh này không :>.");
						return true;
					}
					$sender->sendMessage(TextFormat::RED . "§eSử dụng: §b/{$label} {$args[0]} <name>");
					return true;
				case "additem":
					if($sender->hasPermission("chestshop.command.add")){
						if(isset($args[1]) && isset($args[2])){
							$category = null;
							try{
								$category = $this->chest_shop->getCategory($args[1]);
							}catch(\InvalidArgumentException $e){
								$sender->sendMessage(TextFormat::RED . $e->getMessage());
							}
							if($category !== null){
								$item = $sender->getInventory()->getItemInHand();
								if(!$item->isNull()){
									$price = (float) $args[2];
									if($price >= 0.0){
										$category->addEntry(new CategoryEntry($item, $price));
										$sender->sendMessage(TextFormat::GREEN . "§aĐã thêm thành công §f[§l§a• §c{$item->getName()}§a •§f]" . TextFormat::RESET . TextFormat::GREEN . " §avào danh mục §f[§l§a• §c{$category->getName()}§a •§f]" . TextFormat::GREEN . " thành công!");
									}else{
										$sender->sendMessage(TextFormat::RED . "Số tiền§b {$args[2]} §ckhông hợp lệ");
									}
								}else{
									$sender->sendMessage(TextFormat::RED . "Vui lòng cầm vật phẩm mà bạn muốn thêm trên tay bạn.");
								}
							}
							return true;
						}
					}else{
						$sender->sendMessage(TextFormat::RED . "Bạn đoán xem bạn có quyền để sử dụng lệnh này không :>.");
						return true;
					}
					$sender->sendMessage(TextFormat::RED . "§eSử dụng: §b/{$label} {$args[0]} <danh mục> <giá>");
					return true;
				case "removeitem":
					if($sender->hasPermission("chestshop.command.remove")){
						if(isset($args[1])){
							$category = null;
							try{
								$category = $this->chest_shop->getCategory($args[1]);
							}catch(\InvalidArgumentException $e){
								$sender->sendMessage(TextFormat::RED . $e->getMessage());
							}
							if($category !== null){
								$item = $sender->getInventory()->getItemInHand();
								if(!$item->isNull()){
									$removed = $category->removeItem($item);
									if($removed > 0){
										$sender->sendMessage(TextFormat::GREEN . "Đã xoá thành công §f[§l§a• §c{$removed}§a •§f] §avật phẩm§b " . ($removed > 1 ? "s" : "") . " §akhỏi danh mục §f[§l§a• §c{$category->getName()}§a •§f]");
									}else{
										$sender->sendMessage(TextFormat::RED . "Không tìm thấy vật phẩm §f[§l§a• §c{$item->getName()}§a •§f]" . TextFormat::RESET . TextFormat::RED . " trong danh mục §f[§l§a• §c{$category->getName()}§a •§f].");
									}
								}else{
									$sender->sendMessage(TextFormat::RED . "Vui lòng cầm vật phẩm bạn muốn thêm trên tay.");
								}
							}
							return true;
						}
					}else{
						$sender->sendMessage(TextFormat::RED . "Bạn đoán xem bạn có quyền để sử dụng lệnh này không :>.");
						return true;
					}
					$sender->sendMessage(TextFormat::RED . "§eSử dụng: §b/{$label} {$args[0]} <danh mục>");
					return true;
				case "help":
					if($sender->hasPermission("chestshop.command.remove")){
						$sender->sendMessage(
							TextFormat::BOLD . TextFormat::GOLD . "ChestShop Commands" . TextFormat::RESET . TextFormat::EOL .
							TextFormat::YELLOW . "/{$label} addcategory <name>" . TextFormat::GRAY . " - Thêm danh mục mới vào trong Shop." . TextFormat::EOL .
							TextFormat::YELLOW . "/{$label} removecategory <name>" . TextFormat::GRAY . " - Xoá danh mục trong shop." . TextFormat::EOL .
							TextFormat::YELLOW . "/{$label} additem <category> <price>" . TextFormat::GRAY . " - Thêm vật phẩm trên tay của bạn vào trong danh mục." . TextFormat::EOL .
							TextFormat::YELLOW . "/{$label} removeitem <category>" . TextFormat::GRAY . " - Xoá vật phẩm trên tay bạn khỏi danh mục."
						);
					}else{
						$sender->sendMessage(TextFormat::RED . "Bạn đoán xem bạn có quyền để sử dụng lệnh này không :>.");
						return true;
					}
					return true;
			}
		}

		$this->chest_shop->send($sender);
		return true;
	}
}