<?php 

namespace KaYuuVN\BlackSmith;

use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\event\Listener;
use pocketmine\item\Item;
use pocketmine\Server;
use pocketmine\Player;
use pocketmine\level\sound\AnvilUseSound;
use pocketmine\plugin\PluginBase;
use jojoe77777\FormAPI\{CustomForm, SimpleForm, ModalForm};
use onebone\economyapi\EconomyAPI;
use pocketmine\inventory\PlayerInventory;
use pocketmine\block\Block;
use pocketmine\level\Level;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\Tool;
use pocketmine\item\Armor;

Class Main extends PluginBase
{
    public function onEnable():void 
    {
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        $this->exp = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
    }
   
    public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args):bool
    {
		if (!($sender instanceof Player)) {
			$this->getLogger()->notice("Please Don't Use that command in here.");
			return true;
		}
        if ($cmd->getName() === "fixmenu" ){
            $this->Choose($sender, 0);
        }
        return true;
    }

    public function Choose($sender, $status, $price = null, $len = null)
    {
        $form = new SimpleForm(function (Player $sender, $data) {
        	$result = $data;
			if ($result == null) {
				return true;
			}
			switch ($result) {
				case 0:
				break;

				case 1:
				$this->SetNameItem($sender);
				break;

				case 2:
				$this->SetLoreItem($sender);
				break;
				
				case 3:
				$this->SetFixItem($sender);
				break;
				}
			});
		$form->setTitle(" §6•§l§eT§bH§aỢ §cR§eÈ§bN§6•");
		if ($status == 0) {
		    $form->setContent(" §eC§ah§ạọ§bn §ch§eà§an§bh §cđ§eộ§an§bg\n");
		} elseif($status == 1) {
		    $form->setContent("§eB§bạ§an §ck§eh§aô§bn§cg §ec§bó §ađ§củ §et§ai§bề§cn§e!");
		} elseif($status == 2) {
		    if ($len != 0) {
		    $form->setContent(" Tổng số tiền: §a".$price."\n§r Độ dài ký tự: §a".$len."\n");
		    } else {
		    	$form->setContent(" Tổng số tiền: §a".$price);
		    }
		}
		$form->addButton("§l§fTrở Lại");
		$form->addButton("§l§fĐổi Tên Vật Phẩm");
        $form->addButton("§l§fThêm Mô Tả Vật Phẩm");
        $form->addButton("§l§fSửa Chữa Vật Phẩm");
		$form->sendToPlayer($sender);
    }


	public function SetNameItem($sender) 
	{
        $form = new CustomForm(function (Player $sender, $data) {

		if ($data == null){
			$this->Choose($sender, 0);
			return true; 
		}

        $name = $sender->getName();
        $money = $this->exp->myMoney($sender);
		$cost = strlen($data[1])*100;
		if ($money >= $cost) {
            $item = $sender->getInventory()->getItemInHand();
            $item->setCustomname($data[1]);
            $sender->getInventory()->setItemInHand($item);
            $sender->getLevel()->addSound(new AnvilUseSound($sender));
            $this->Choose($sender, 2, $cost, strlen($data[1]));
            $this->exp->reduceMoney($sender, $cost);
        } else {
        	$this->Choose($sender, 1);
        }
            });
        $form->setTitle("§l§fTHỢ RÈN");
        $form->addLabel("§l§o§fVui lòng nhập tên cần đổi vào ô bên dưới");
		$form->addInput("§fĐổi tên vật phẩm");
		$form->sendToPlayer($sender);
	}
	
	public function SetLoreItem($sender)
	{
        $form = new CustomForm(function (Player $sender, $data) {

		if ($data == null){
			$this->Choose($sender, 0);
			return true;
		}
        $name = $sender->getName();
        $money = $this->exp->myMoney($sender);
		$cost = strlen($data[1])*50;
		if ($money >= $cost) {
            $item = $sender->getInventory()->getItemInHand();
            $item->setLore(explode("\\n", $data[1]));
            $sender->getInventory()->setItemInHand($item);
            $sender->getLevel()->addSound(new AnvilUseSound($sender));
            $this->Choose($sender, 2, $cost, strlen($data[1]));
            $this->exp->reduceMoney($sender, $cost);
        } else {
        	$this->Choose($sender, 1);
        }

        	});
        $form->setTitle("§l§fTHỢ RÈN");
        $form->addLabel("§l§o§fVui lòng nhập mô tả vào ô bên dưới!");
		$form->addInput(" §fĐổi mô tả chi tiết vật phẩm");
		$form->sendToPlayer($sender);
	}
	public function SetFixItem($sender){
		$economy = EconomyAPI::getInstance();
          $mymoney = $economy->myMoney($sender);
		     $item = $sender->getInventory()->getItemInHand();
          $meta = $item->getDamage();
          $cash = $meta * 3;
          if($mymoney >= $cash){
            $economy->reduceMoney($sender, $cash);
            $item = $sender->getInventory()->getItemInHand();
				      if($item instanceof Armor or $item instanceof Tool){
				        $id = $item->getId();
					      $meta = $item->getDamage();
					      $sender->getInventory()->removeItem(Item::get($id, $meta, 1));
					      $newitem = Item::get($id, 0, 1);
					      if($item->hasCustomName()){
						       $newitem->setCustomName($item->getCustomName());
						    }
					      if($item->hasEnchantments()){
						        foreach($item->getEnchantments() as $enchants){
						            $newitem->addEnchantment($enchants);
						       }
						     }
					      $sender->getInventory()->addItem($newitem);
					      $sender->sendMessage("§c[§6Sửa §eĐồ§c]§r §l§aVật Phẩm§6.". $item->getName() . "§a Đã được sửa chữa với giá§6 $cash ");
					      return true;
					    } else {
				        	$sender->sendMessage("§c[§6Sửa §eĐồ§c]§r §l§cVật phẩm trên tay phải là công cụ hoặc giáp!");
					        return false;
					    }
            return true;
          } else {
            $sender->sendMessage("§c[§6Sửa §eĐồ§c]§r §l§cBạn không đủ§6 $cash §atiền để sửa chữa");
            return true;
          }
      }
  }