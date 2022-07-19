<?php

namespace GreenJajot\Marry;

use pocketmine\plugin\PluginBase;
use jojoe77777\FormAPI;
use muqsit\invmenu\inventories\BaseFakeInventory;
use pocketmine\nbt\tag\{CompoundTag, IntTag, StringTag, IntArrayTag};
use pocketmine\utils\Config;
use pocketmine\Player; 
use pocketmine\Server;
use pocketmine\event\Listener;
use pocketmine\command\{Command,CommandSender, CommandExecutor, ConsoleCommandSender};
use pocketmine\event\Event;
use pocketmine\event\player\PlayerJoinEvent;
use muqsit\invmenu\{InvMenu,InvMenuHandler};
use muqsit\invmenu\inventories\ChestInventory;
use pocketmine\item\Item;
use pocketmine\block\Block;
use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\inventory\transaction\action\SlotChangeAction;
use pocketmine\scheduler\TaskScheduler;
class Main extends PluginBase implements Listener{
    public $marrylist;
	public function onEnable(){
	    $this->getServer()->getPluginManager()->registerEvents($this, $this);
$this->joinlist = new Config($this->getDataFolder() . "joinlist.yml", Config::YAML);
$this->marrylist = new Config($this->getDataFolder() . "marrylist.yml", Config::YAML);
		$this->getLogger()->info("Marry enable");
		if (!InvMenuHandler::isRegistered()) {
			InvMenuHandler::register($this);
		}
	}
	
	public function onJoin(PlayerJoinEvent $ev){
		$p = $ev->getPlayer()->getName();
		if(!($this->joinlist->exists($p))){
		    $this->joinlist->set($p, 0);
		    $this->getServer()->dispatchCommand(new ConsoleCommandSender(),'setuperm "'.$p.'" pchat.command.setsuffix');
			$this->getServer()->getCommandMap()->dispatch($ev->getPlayer(),"setsuffix Độc-Thân");
			$this->getServer()->dispatchCommand(new ConsoleCommandSender(),'unsetuperm "'.$p.'" pchat.command.setsuffix');
	      	$this->joinlist->save();
		}
	}
	public function onCommand(CommandSender $sender, Command $command, String $label, array $args) : bool {
		   switch($command->getName()){
               case "kethon":
               $this->listform($sender);
			    $this->marrylist->load($this->getDataFolder() . "marrylist.yml", Config::YAML);
					    $this->marrylist = new Config($this->getDataFolder() . "marrylist.yml", Config::YAML);
			    $ar = $this->marrylist->getAll();
	    foreach($ar as $arr=>$al){
	    $arr1 = explode(" ❤ ", $arr);
	    if($sender->getName() == $arr1[0]){
	    $player2 = $arr1[1];
	    $this->Marry($sender,$player2);
	    return true;
	    }else if($sender->getName() == $arr1[1]){
	    $player2 = $arr1[0];
	    $this->Marry($sender,$player2);
	    return true;
	    }
	}
	    $this->NoMarry($sender);
					break;
	}
	return true;
	}
	public function listform(Player $player){
        print("UI");
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createCustomForm(function (Player $player,$data){
            if ($data === null){
                return;
            }else{
            $player1 = $this->getServer()->getPlayer((string) $data[1]);
            $player2 = $player;
            if($player1 == null){
                $player->sendMessage("§aKhông Thể Tìm Thấy Người Này");
                return;
            }
            $name1 = $player1->getName();
            $name2 = $player2->getName();
            if($name1 == $name2){
                $player->sendMessage("§aBạn Không Thể Cưới Chính Mình");
                return;
            }
            $ar = $this->marrylist->getAll();
	    foreach($ar as $arr=>$al){
	    $arr1 = explode(" ❤ ", $arr);
	    if($name1 == $arr1[0]){
	    $player->sendMessage("§aNgười Này Đã Kết Hôn Rồi");
	    return;
	    }else if($name1 == $arr1[1]){
	    $player->sendMessage("§aNgười Này Đã Kết Hôn Rồi");
	    return;
	    }
	}
            $this->request($player1,$player2);
            $player->sendMessage("§l-> §bBạn Đã Gửi Thành Công Cho ".$name1." §f(§aHãy Đợi Người Kia Đồng Ý§f)");
            }});
        $form->addLabel("§lNhập Tên Người Bạn Muốn Kết Hôn Vào");
        $form->setTitle("§lKết Hôn");
        $form->addInput("§lNgười Bạn Muốn Kết Hôn");
        $form->sendToPlayer($player);
        return $form;
    }

    public function request(Player $player1,Player $player2) {
		$name2 = $player2->getName();
		$menu = InvMenu::create(InvMenu::TYPE_DOUBLE_CHEST);
		$menu->setName("§l§c♥︎§b Yêu Cầu Kết Hôn§c ♥︎ Từ: §8".$name2);
		$menu->readonly(true);
		$minv = $menu->getInventory();
		$air = Item::get(Item::AIR);
		$ai = $air;
		$red = Item::get(360)->setCustomName("§e§l✚§d Từ chối");
		$request = Item::get(175)->setCustomName("§c§l❤§e Kết Hôn");
		$request->setNamedTagEntry(new StringTag("marry", "request"));
		$green = Item::get(79)->setCustomName("§b§l✏§a Đồng ý");
		$green->setNamedTagEntry(new StringTag("marry", "yes"));
		$red->setNamedTagEntry(new StringTag("marry", "no"));
		//$d = Item::get(264)->setCustomName("§bDiamond Kit");
		//$g = Item::get(266)->setCustomName("§6Gold Kit");
		//$b = Item::get(7)->setCustomName("§8Bedrock Kit");
		$minv->setItem(0, $air);
		$minv->setItem(1, $air);
		$minv->setItem(2, $air);
		$minv->setItem(3, $air);
		$minv->setItem(4, $air);
		$minv->setItem(5, $air);
		$minv->setItem(6, $ai);
		$minv->setItem(7, $ai);
		$minv->setItem(8, $ai);
		$minv->setItem(9, $air);
		$minv->setItem(10, $air);
		$minv->setItem(11, $air);
		$minv->setItem(12, $air);
		$minv->setItem(13, $air);
		$minv->setItem(14, $air);
		$minv->setItem(15, $ai);
		$minv->setItem(16, $ai);
		$minv->setItem(17, $ai);
		$minv->setItem(18, $air);
		$minv->setItem(19, $air);
		$minv->setItem(20, $green);
		$minv->setItem(21, $air);
		$minv->setItem(22, $air);
		$minv->setItem(23, $air);
		$minv->setItem(24, $red);
		$minv->setItem(25, $air);
		$minv->setItem(26, $ai);
		$minv->setItem(27, $air);
		$minv->setItem(28, $air);
		$minv->setItem(29, $air);
		$minv->setItem(30, $air);
		$minv->setItem(31, $air);
		$minv->setItem(32, $air);
		$minv->setItem(33, $ai);
		$minv->setItem(34, $ai);
		$minv->setItem(35, $ai);
		$minv->setItem(36, $air);
		$minv->setItem(37, $air);
		$minv->setItem(38, $air);
		$minv->setItem(39, $air);
		$minv->setItem(40, $air);
		$minv->setItem(41, $air);
		$minv->setItem(42, $ai);
		$minv->setItem(43, $ai);
		$minv->setItem(44, $ai);
		$minv->setItem(45, $air);
		$minv->setItem(46, $air);
		$minv->setItem(47, $air);
		$minv->setItem(48, $air);
		$minv->setItem(49, $air);
		$minv->setItem(50, $air);
        $minv->setItem(51, $ai);
		$minv->setItem(52, $air);
		$minv->setItem(53, $air);
		$menu->send($player1);
		$menu->setListener([new RequestListener($this, $player2),"onTransaction"]);
	}
	
	public function NoMarry(Player $player) {
		$menu = InvMenu::create(InvMenu::TYPE_DOUBLE_CHEST);
		$menu->setName("§l§b☘ §aBạn Đang Độc Thân§b ☘");
		$menu->readonly();
		$minv = $menu->getInventory();
		$air = Item::get(Item::AIR);
		$ai = Item::get(Item::AIR);
		$request = Item::get(175)->setCustomName("§c§l❤§e Kết Hôn");
		$request->setNamedTagEntry(new StringTag("marry", "request"));
		//$d = Item::get(264)->setCustomName("§bDiamond Kit");
		//$g = Item::get(266)->setCustomName("§6Gold Kit");
		//$b = Item::get(7)->setCustomName("§8Bedrock Kit");
		$minv->setItem(0, $air);
		$minv->setItem(1, $air);
		$minv->setItem(2, $air);
		$minv->setItem(3, $air);
		$minv->setItem(4, $air);
		$minv->setItem(5, $air);
		$minv->setItem(6, $ai);
		$minv->setItem(7, $ai);
		$minv->setItem(8, $ai);
		$minv->setItem(9, $air);
		$minv->setItem(10, $air);
		$minv->setItem(11, $air);
		$minv->setItem(12, $air);
		$minv->setItem(13, $air);
		$minv->setItem(14, $air);
		$minv->setItem(15, $ai);
		$minv->setItem(16, $ai);
		$minv->setItem(17, $ai);
		$minv->setItem(18, $air);
		$minv->setItem(19, $air);
		$minv->setItem(20, $air);
		$minv->setItem(21, $air);
		$minv->setItem(22, $request);
		$minv->setItem(23, $air);
		$minv->setItem(24, $air);
		$minv->setItem(25, $air);
		$minv->setItem(26, $ai);
		$minv->setItem(27, $air);
		$minv->setItem(28, $air);
		$minv->setItem(29, $air);
		$minv->setItem(30, $air);
		$minv->setItem(31, $air);
		$minv->setItem(32, $air);
		$minv->setItem(33, $ai);
		$minv->setItem(34, $ai);
		$minv->setItem(35, $ai);
		$minv->setItem(36, $air);
		$minv->setItem(37, $air);
		$minv->setItem(38, $air);
		$minv->setItem(39, $air);
		$minv->setItem(40, $air);
		$minv->setItem(41, $air);
		$minv->setItem(42, $ai);
		$minv->setItem(43, $ai);
		$minv->setItem(44, $ai);
		$minv->setItem(45, $air);
		$minv->setItem(46, $air);
		$minv->setItem(47, $air);
		$minv->setItem(48, $air);
		$minv->setItem(49, $air);
		$minv->setItem(50, $air);
        $minv->setItem(51, $ai);
		$minv->setItem(52, $air);
		$minv->setItem(53, $air);
		$menu->send($player);
		$menu->setListener([new MarryListener($this, $menu->getInventory()), "onTransaction"]);
	}
	public function Marry(Player $player, $player2) {
		
		$menu = InvMenu::create(InvMenu::TYPE_DOUBLE_CHEST);
		$menu->setName("§l§e★ §6Menu Kết Hôn§e ★");
		$menu->readonly();
		$minv = $menu->getInventory();
		$air = Item::get(Item::AIR);
		$ai = $air;
		$name2 = $player2;
		$thongtin = Item::get(339)->setCustomName("§l§c♥ Người Yêu Của Bạn:§l§e ".$name2);
		$inv = Item::get(54)->setCustomName("§l§e☘ Xem Hành Trang Người Yêu");
		$tele = Item::get(122)->setCustomName("§d§lϟ§6 Dịch Chuyến Tới Người Yêu");
		$tele->setNamedTagEntry(new StringTag("marry", "tp"));
		$lyhon = Item::get(397, 5)->setCustomName("§d§lϟ§6 Ly Hôn Người Yêu");
		$lyhon->setNamedTagEntry(new StringTag("marry", "lyhon"));
		$inv->setNamedTagEntry(new StringTag("marry", "invsee"));
		//$d = Item::get(264)->setCustomName("§bDiamond Kit");
		//$g = Item::get(266)->setCustomName("§6Gold Kit");
		//$b = Item::get(7)->setCustomName("§8Bedrock Kit");
		$minv->setItem(0, $air);
		$minv->setItem(1, $air);
		$minv->setItem(2, $air);
		$minv->setItem(3, $air);
		$minv->setItem(4, $air);
		$minv->setItem(5, $air);
		$minv->setItem(6, $ai);
		$minv->setItem(7, $ai);
		$minv->setItem(8, $ai);
		$minv->setItem(9, $air);
		$minv->setItem(10, $air);
		$minv->setItem(11, $air);
		$minv->setItem(12, $air);
		$minv->setItem(13, $air);
		$minv->setItem(14, $air);
		$minv->setItem(15, $ai);
		$minv->setItem(16, $ai);
		$minv->setItem(17, $ai);
		$minv->setItem(18, $air);
		$minv->setItem(19, $air);
		$minv->setItem(20, $air);
		$minv->setItem(21, $air);
		$minv->setItem(22, $thongtin);
		$minv->setItem(23, $air);
		$minv->setItem(24, $air);
		$minv->setItem(25, $air);
		$minv->setItem(26, $ai);
		$minv->setItem(27, $air);
		$minv->setItem(28, $air);
		$minv->setItem(29, $tele);
		$minv->setItem(30, $air);
		$minv->setItem(31, $air);
		$minv->setItem(32, $air);
		$minv->setItem(33, $inv);
		$minv->setItem(34, $ai);
		$minv->setItem(35, $ai);
		$minv->setItem(36, $air);
		$minv->setItem(37, $air);
		$minv->setItem(38, $air);
		$minv->setItem(39, $air);
		$minv->setItem(40, $air);
		$minv->setItem(41, $air);
		$minv->setItem(42, $ai);
		$minv->setItem(43, $ai);
		$minv->setItem(44, $ai);
		$minv->setItem(45, $air);
		$minv->setItem(46, $air);
		$minv->setItem(47, $air);
		$minv->setItem(48, $air);
		$minv->setItem(49, $air);
		$minv->setItem(50, $air);
        $minv->setItem(51, $ai);
		$minv->setItem(52, $air);
		$minv->setItem(53, $lyhon);
		$menu->send($player);
		$menu->setListener([new AlreadyMarryListener($this, $player2),"onTransaction"]);
	}
	public function onDisable ()
	{
		$this->getLogger()->info("Plugin đã dừng !");
	}
}