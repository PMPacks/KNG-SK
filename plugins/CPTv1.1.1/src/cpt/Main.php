<?php

namespace cpt;

use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\command\{Command, CommandSender, ConsoleCommandSender};
use pocketmine\event\player\{PlayerJoinEvent, PlayerQuitEvent};

class Main extends PluginBase implements Listener {
    
    
public function onEnable() {
		$this->getLogger()->info("§4Plugin cây phát tài đã được bật bởi LetTIHL");
$this->getServer()->getPluginManager()->registerEvents($this ,$this);
$this->eco = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
@mkdir($this->getDataFolder(), 0744, true);
$this->n = new Config($this->getDataFolder()."nuoc.yml",Config::YAML);
$this->cc = new Config($this->getDataFolder()."phanboncc.yml",Config::YAML);
$this->tc = new Config($this->getDataFolder()."phanbontc.yml",Config::YAML);
$this->sc = new Config($this->getDataFolder()."phanbonsc.yml",Config::YAML);
$this->kc = new Config($this->getDataFolder()."kimcuong.yml",Config::YAML);
$this->sat = new Config($this->getDataFolder()."sat.yml",Config::YAML);

//Tiều Phu
$this->go17 = new Config($this->getDataFolder()."go17.yml",Config::YAML);
//Nông dân
$this->lua = new Config($this->getDataFolder()."lua.yml",Config::YAML);
$this->carot = new Config($this->getDataFolder()."carot.yml",Config::YAML);
$this->khoai = new Config($this->getDataFolder()."khoai.yml",Config::YAML);
$this->dua = new Config($this->getDataFolder()."dua.yml",Config::YAML);
$this->bingo = new Config($this->getDataFolder()."bingo.yml",Config::YAML);
$this->mia = new Config($this->getDataFolder()."mia.yml",Config::YAML);
//end
$this->vang = new Config($this->getDataFolder()."vang.yml",Config::YAML);
$this->da = new Config($this->getDataFolder()."da.yml",Config::YAML);
$this->cap = new Config($this->getDataFolder()."cap.yml",Config::YAML);
$this->kn = new Config($this->getDataFolder()."kinhnghiem.yml",Config::YAML);
}

public function onJoin(PlayerJoinEvent $ev) {
if(!$this->n->exists($ev->getPlayer()->getName())) {
$this->n->set($ev->getPlayer()->getName(), 0);
$this->n->save();
    }
if(!$this->cc->exists($ev->getPlayer()->getName())) {
$this->cc->set($ev->getPlayer()->getName(), 0);
$this->cc->save();
    }
if(!$this->sc->exists($ev->getPlayer()->getName())) {
$this->sc->set($ev->getPlayer()->getName(), 0);
$this->sc->save();
    }
if(!$this->tc->exists($ev->getPlayer()->getName())) {
$this->tc->set($ev->getPlayer()->getName(), 0);
$this->tc->save();
    }
if(!$this->kc->exists($ev->getPlayer()->getName())) {
$this->kc->set($ev->getPlayer()->getName(), 0);
$this->kc->save();
    }
if(!$this->sat->exists($ev->getPlayer()->getName())) {
$this->sat->set($ev->getPlayer()->getName(), 0);
$this->sat->save();
    }
if(!$this->vang->exists($ev->getPlayer()->getName())) {
$this->vang->set($ev->getPlayer()->getName(), 0);
$this->vang->save();
    }
if(!$this->da->exists($ev->getPlayer()->getName())) {
$this->da->set($ev->getPlayer()->getName(), 0);
$this->da->save();
    }
if(!$this->cap->exists($ev->getPlayer()->getName())) {
$this->cap->set($ev->getPlayer()->getName(), 1);
$this->cap->save();
    }
if(!$this->kn->exists($ev->getPlayer()->getName())) {
$this->kn->set($ev->getPlayer()->getName(), 0);
$this->kn->save();
    }
    // gỗ
if(!$this->go17->exists($ev->getPlayer()->getName())) {
$this->go17->set($ev->getPlayer()->getName(), 0);
$this->go17->save();
}
}

public function onQuit(PlayerQuitEvent $ev) {
$this->kc->save();
$this->sat->save();
$this->vang->save();
$this->da->save();
$this->kn->save();
$this->cap->save();
$this->cc->save();
$this->tc->save();
$this->sc->save();
$this->n->save();
$this->go17->save();
$this->lua->save();
$this->carot->save();
$this->bingo->save();
$this->dua->save();
$this->mia->save();
$this->khoai->save();
}
////nông sản
public function onbreak1(BlockBreakEvent $ev) {
		if(!$ev->isCancelled()){
if($ev->getBlock()->getId() == 59){
$this->lua->set($ev->getPlayer()->getName(), ($this->lua->get($ev->getPlayer()->getName()) + 1));
$this->lua->save();
if($this->lua->get($ev->getPlayer()->getName()) > 99) {
$this->lua->set($ev->getPlayer()->getName(), ($this->lua->get($ev->getPlayer()->getName()) - 100));
$this->lua->save();
$this->sc->set($ev->getPlayer()->getName(), ($this->sc->get($ev->getPlayer()->getName()) + 1));
$this->sc->save();
$ev->getPlayer()->sendMessage("§l§c•§ebạn đã nhận được phân bón sơ cấp từ nhiệm vụ");
            }
        }
if($ev->getBlock()->getId() == ("141:1")){
$this->carot->set($ev->getPlayer()->getName(), ($this->carot->get($ev->getPlayer()->getName()) + 1));
$this->carot->save();
if($this->carot->get($ev->getPlayer()->getName()) > 99) {
$this->carot->set($ev->getPlayer()->getName(), ($this->carot->get($ev->getPlayer()->getName()) - 100));
$this->carot->save();
$this->sc->set($ev->getPlayer()->getName(), ($this->sc->get($ev->getPlayer()->getName()) + 1));
$this->sc->save();
$ev->getPlayer()->sendMessage("§l§c•§ebạn đã nhận được phân bón sơ cấp từ nhiệm vụ");
            }
        }
if($ev->getBlock()->getId() == ("142:0")){
$this->khoai->set($ev->getPlayer()->getName(), ($this->khoai->get($ev->getPlayer()->getName()) + 1));
$this->khoai->save();
if($this->khoai->get($ev->getPlayer()->getName()) > 99) {
$this->khoai->set($ev->getPlayer()->getName(), ($this->khoai->get($ev->getPlayer()->getName()) - 100));
$this->khoai->save();
$this->sc->set($ev->getPlayer()->getName(), ($this->sc->get($ev->getPlayer()->getName()) + 1));
$this->sc->save();
$ev->getPlayer()->sendMessage("§l§c•§ebạn đã nhận được phân bón sơ cấp từ nhiệm vụ");
            }
        }
if($ev->getBlock()->getId() == 103){
$this->dua->set($ev->getPlayer()->getName(), ($this->dua->get($ev->getPlayer()->getName()) + 1));
$this->dua->save();
if($this->dua->get($ev->getPlayer()->getName()) > 99) {
$this->dua->set($ev->getPlayer()->getName(), ($this->dua->get($ev->getPlayer()->getName()) - 100));
$this->dua->save();
$this->sc->set($ev->getPlayer()->getName(), ($this->sc->get($ev->getPlayer()->getName()) + 1));
$this->sc->save();
$ev->getPlayer()->sendMessage("§l§c•§ebạn đã nhận được phân bón sơ cấp từ nhiệm vụ");
            }
        }
if($ev->getBlock()->getId() == 86){
$this->bingo->set($ev->getPlayer()->getName(), ($this->bingo->get($ev->getPlayer()->getName()) + 1));
$this->bingo->save();
if($this->bingo->get($ev->getPlayer()->getName()) > 99) {
$this->bingo->set($ev->getPlayer()->getName(), ($this->lua->get($ev->getPlayer()->getName()) - 100));
$this->bingo->save();
$this->sc->set($ev->getPlayer()->getName(), ($this->sc->get($ev->getPlayer()->getName()) + 1));
$this->sc->save();
$ev->getPlayer()->sendMessage("§l§c•§ebạn đã nhận được phân bón sơ cấp từ nhiệm vụ");
            }
        }
if($ev->getBlock()->getId() == 83){
$this->mia->set($ev->getPlayer()->getName(), ($this->mia->get($ev->getPlayer()->getName()) + 1));
$this->mia->save();
if($this->mia->get($ev->getPlayer()->getName()) > 99) {
$this->mia->set($ev->getPlayer()->getName(), ($this->mia->get($ev->getPlayer()->getName()) - 100));
$this->mia->save();
$this->sc->set($ev->getPlayer()->getName(), ($this->sc->get($ev->getPlayer()->getName()) + 1));
$this->sc->save();
$ev->getPlayer()->sendMessage("§l§c•§ebạn đã nhận được phân bón sơ cấp từ nhiệm vụ");
            }
        }
   ///Gỗ     
if($ev->getBlock()->getId() == 17){
$this->go17->set($ev->getPlayer()->getName(), ($this->go17->get($ev->getPlayer()->getName()) + 1));
$this->go17->save();
if($this->go17->get($ev->getPlayer()->getName()) > 99) {
$this->go17->set($ev->getPlayer()->getName(), ($this->go17->get($ev->getPlayer()->getName()) - 100));
$this->go17->save();
$this->tc->set($ev->getPlayer()->getName(), ($this->tc->get($ev->getPlayer()->getName()) + 1));
$this->tc->save();
$ev->getPlayer()->sendMessage("§l§c•§ebạn đã nhận được phân bón trung cấp từ nhiệm vụ");
}
}
}
}
    
///Khoán Sản
	
public function onbreak2(BlockBreakEvent $ev) {
	if(!$ev->isCancelled()){
if($ev->getBlock()->getId() == 56){
$this->kc->set($ev->getPlayer()->getName(), ($this->kc->get($ev->getPlayer()->getName()) + 1));
$this->kc->save();
$slkc = $this->kc->get($ev->getPlayer()->getName());
if($this->kc->get($ev->getPlayer()->getName()) > 99) {
$this->kc->set($ev->getPlayer()->getName(), ($this->kc->get($ev->getPlayer()->getName()) - 100));
$this->kc->save();
$this->kc->save();
$this->cc->set($ev->getPlayer()->getName(), ($this->cc->get($ev->getPlayer()->getName()) + 1));
$this->cc->save();
$ev->getPlayer()->sendMessage("§l§c•§ebạn đã nhận được phân bón cao cấp từ nhiệm vụ");
            }
        }
if($ev->getBlock()->getId() == 14){
$this->vang->set($ev->getPlayer()->getName(), ($this->vang->get($ev->getPlayer()->getName()) + 1));
$this->vang->save();
if($this->vang->get($ev->getPlayer()->getName()) > 99) {
$this->vang->set($ev->getPlayer()->getName(), ($this->vang->get($ev->getPlayer()->getName()) - 100));
$this->vang->save();
$this->tc->set($ev->getPlayer()->getName(), ($this->tc->get($ev->getPlayer()->getName()) + 1));
$this->tc->save();
$ev->getPlayer()->sendMessage("§l§c•§ebạn đã nhận được phân bón trung cấp từ nhiệm vụ");
            }
        }
if($ev->getBlock()->getId() == 4){
$this->da->set($ev->getPlayer()->getName(), ($this->da->get($ev->getPlayer()->getName()) + 1));
$this->da->save();
if($this->da->get($ev->getPlayer()->getName()) > 199) {
$this->da->set($ev->getPlayer()->getName(), ($this->da->get($ev->getPlayer()->getName()) - 200));
$this->da->save();
$this->n->set($ev->getPlayer()->getName(), ($this->n->get($ev->getPlayer()->getName()) + 1));
$this->n->save();
$ev->getPlayer()->sendMessage("§l§c•§ebạn đã nhận được nước từ nhiệm vụ");
            }
        }
if($ev->getBlock()->getId() == 15){
$this->sat->set($ev->getPlayer()->getName(), ($this->sat->get($ev->getPlayer()->getName()) + 1));
$this->sat->save();
if($this->sat->get($ev->getPlayer()->getName()) > 99) {
$this->sat->set($ev->getPlayer()->getName(), ($this->sat->get($ev->getPlayer()->getName()) - 100));
$this->sat->save();
$ev->getPlayer()->sendMessage("§l§c•§ebạn đã nhận được phân bón sơ cấp từ nhiệm vụ");
$this->sc->set($ev->getPlayer()->getName(), ($this->sc->get($ev->getPlayer()->getName()) + 1));
$this->sc->save();
            }
        }
        ///Canecell
	}
    }
    
    ////End///

	public function onCommand(CommandSender $sender, Command $command, String $label, array $args) : bool {
        switch($command->getName()){
            case "cay":
            $this->menu($sender);
            return true;
        }
        return true;
	}
	
	 public function menu($sender){
        $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createSimpleForm(function(Player $sender, $data){
          $result = $data;
          if($result === null){
          }
          switch($result){
              case 0:
              break;
              case 1:
              $this->Toplevel($sender);
              break;
              case 2:
              $this->cay($sender);
              break;
              case 3:
              $this->nhiemvu($sender);
              break;
              case 4: 
              $this->shop($sender);
              break;
         }
        });
        $form->setTitle("§b§lcây phát tài");
		$form->addButton("§f•§c§lThoát§r§f•");
		$form->addButton("§f•§b§ltop cây§f•");
		$form->addButton("§f•§b§lXem cây§f•");
		$form->addButton("§f•§b§lnhiệm vụ§f•");
        $form->addButton("§f•§b§lshop§l§f•");
        $form->sendToPlayer($sender);
	}
	
public function lenhcap($sender) {
    $cap = $this->cap->get($sender->getPlayer()->getName());
    $lenhcap = $cap * 500;
if($this->kn->get($sender->getPlayer()->getName()) >= $lenhcap) {
$this->kn->set($sender->getPlayer()->getName(), ($this->kn->get($sender->getPlayer()->getName()) - $lenhcap));
$this->kn->save();
$this->cap->set($sender->getPlayer()->getName(), ($this->cap->get($sender->getPlayer()->getName()) + 1));
$sender->sendMessage("§l§b❖§c Bạn đã lênh cấp §3".$cap." §b ❖");
$this->cap->save();
$this->lenhcap($sender);
}
}

    public function nhiemvu($sender){
        $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createSimpleForm(function(Player $sender, $data){
          $result = $data;
          if($result === null){
          }
          switch($result){
              case 0:
              break;
}
 });
        $name = $sender->getPlayer()->getName();
     $lua = $this->lua->get($sender->getPlayer()->getName());
     $mia = $this->mia->get($sender->getPlayer()->getName());
     $bingo = $this->bingo->get($sender->getPlayer()->getName());
     $dua = $this->dua->get($sender->getPlayer()->getName());
     $go17 = $this->go17->get($sender->getPlayer()->getName());
        $sat = $this->sat->get($sender->getPlayer()->getName());
        $vang = $this->vang->get($sender->getPlayer()->getName());
     $kc = $this->kc->get($sender->getPlayer()->getName());
        $da = $this->da->get($sender->getPlayer()->getName());
        $form->setTitle("§f•§c§lnhiệm vụ§f•");
        $form->setContent("§l§bSắt ".$sat."§a/§f100 §enhận được phân bón sơ cấp\n§bVàng ".$vang."§a/§f100 §eNhận được phân bón trung cấp\n§bKim cương ".$kc."§a/§f100 §eNhận được phân bón cao cấp\n§bđá ".$da."§2/§f200 §eNhận được nước\n§c================================\n§a§lNông Sản\n§blúa ".$lua."§a/§f100 §eđược phân bón sơ cấp\n§bmía ".$mia."§a/§f100 §eđược nhận phân bón sơ cấp\n§bbí ngô ".$bingo."§a/§f100 §enhận được phân bón sơ cấp\n§bdưa ".$dua."§a/§f100 §enhận được phân bón sơ cấp\n§c=====================================\n§a§llâm sản\n§bgỗ ".$go17."§a/§f100 §enhận được phân bón trung cấp");
        $form->addButton("§f•§b§lthoát§f•");
        $form->sendToPlayer($sender);
}

    public function shop($sender){
        $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createSimpleForm(function(Player $sender, $data){
          $result = $data;
          if($result === null){
          }
          switch($result){
              case 0:
              $this->menu($sender);
              break;
              case 1:
            $name = $sender->getName();
            $mymoney = $this->eco->myMoney($sender);
        if($mymoney >= 50000) {
             $sender->sendMessage("§l§b❖§a Bạn Đã Mua phân bón cao cấp  với giá 50.000 xu Thành Công §b❖");
$this->cc->set($sender->getPlayer()->getName(), ($this->cc->get($sender->getPlayer()->getName()) + 1));
$this->cc->save();
             $this->eco->reduceMoney($name, 50000);

  }else{
             $sender->sendMessage("§l§b❖§c Bạn không đủ xu để mua phân bón cao cấp §b ❖");
  }
         break;
              case 2:
            $name = $sender->getName();
            $mymoney = $this->eco->myMoney($sender);
        if($mymoney >= 35000) {
             $sender->sendMessage("§l§b❖§a Bạn Đã Mua phân bón cao cấp với giá 35.000 xu Thành Công §b❖");
$this->tc->set($sender->getPlayer()->getName(), ($this->tc->get($sender->getPlayer()->getName()) + 1));
$this->tc->save();
             $this->eco->reduceMoney($name, 35000);
  }else{
             $sender->sendMessage("§l§b❖§c Bạn không đủ xu để mua phân bón cao cấp §b ❖");
  }
         break;
              case 3:
            $name = $sender->getName();
            $mymoney = $this->eco->myMoney($sender);
        if($mymoney >= 15000) {
             $sender->sendMessage("§l§b❖§a Bạn Đã Mua phân bón sơ cấp với giá 15.000 xu Thành Công §b❖");
$this->sc->set($sender->getPlayer()->getName(), ($this->sc->get($sender->getPlayer()->getName()) + 1));
$this->sc->save();
             $this->eco->reduceMoney($name, 15000);
  }else{
             $sender->sendMessage("§l§b❖§c Bạn không đủ xu để mua phân bón cao cấp §b ❖");
  }
         break;
              
}
 });
            $mymoney = $this->eco->myMoney($sender);
        $name = $sender->getPlayer()->getName();
     $cc = $this->cc->get($sender->getPlayer()->getName());
        $sc = $this->sc->get($sender->getPlayer()->getName());
     $tc = $this->tc->get($sender->getPlayer()->getName());
        $n = $this->n->get($sender->getPlayer()->getName());
        $form->setTitle("§7[ §c§l shop §r§7]");
        $form->setContent("§f§a§oshop bán phân bón\n§6§o§lSố tiền của bạn: §r§f".$mymoney);
        $form->addButton("§f•§c§lQuay lại§r§f•");
       $form->addButton("§f•§b§lPhân bón cao cấp§l§f•\n §2§l§oGiá 50k x1");
       $form->addButton("§f•§b§lPhân bón trung cấp§l§f•\n 2§l§oGiá 50k x1");
       $form->addButton("§f•§b§o§lPhân bón sơ cấp§l§f•\n 2§l§oGiá 50k x1");
        $form->sendToPlayer($sender);
}
	
    public function cay($sender){
        $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createSimpleForm(function(Player $sender, $data){
          $result = $data;
          if($result === null){
          }
          switch($result){
              case 0:
              $this->menu($sender);
              break;
              case 1:
        //phân Bón Cao Cấp
        $cc = $this->cc->get($sender->getPlayer()->getName());
        if($cc >= 1) {
       $cc = $this->cc->get($sender->getPlayer()->getName());
             $sender->sendMessage("§l§b❖§a Bạn Đã bón phân cao cấp x".$cc." thành công §b❖");
             $cckn = $cc * 50;
             $this->kn->set($sender->getPlayer()->getName(), ($this->kn->get($sender->getPlayer()->getName()) + $cckn));
             $this->kn->save();
             $this->cc->set($sender->getPlayer()->getName(), ($this->cc->get($sender->getPlayer()->getName()) - $cc));
             $this->cc->save();
             $this->lenhcap($sender);
  }
  //Phân Bón Sơ Cấp
        $sc = $this->sc->get($sender->getPlayer()->getName());
        if($sc >= 1) {
        $sc = $this->sc->get($sender->getPlayer()->getName());
             $sender->sendMessage("§l§b❖§a Bạn Đã bón phân sơ x".$sc." thành công §b❖");
             $this->sc->set($sender->getPlayer()->getName(), ($this->sc->get($sender->getPlayer()->getName()) - $sc));
             $this->sc->save();
             $sckn = $sc * 20;
             $this->kn->set($sender->getPlayer()->getName(), ($this->kn->get($sender->getPlayer()->getName()) + $sckn));
             $this->kn->save();
             $this->lenhcap($sender);
  }
  //Phân Bón TRung Cấp
        $tc = $this->tc->get($sender->getPlayer()->getName());
        if($tc >= 1) {
        $tc = $this->tc->get($sender->getPlayer()->getName());
             $sender->sendMessage("§l§b❖§a Bạn Đã bón phân Trung cấp x".$tc." thành công §b❖");
             $tckn = $tc * 35;
             $this->kn->set($sender->getPlayer()->getName(), ($this->kn->get($sender->getPlayer()->getName()) + $tckn));
             $this->kn->save();
             $this->tc->set($sender->getPlayer()->getName(), ($this->tc->get($sender->getPlayer()->getName()) - $tc));
             $this->tc->save();
             $this->lenhcap($sender);
  }
  if($cc == 0 || $tc == 0 || $sc == 0){
             $sender->sendMessage("§l§b❖§a Bạn Không Có Phân Bón§b❖");
  }
              break;
              case 2:
                  $this->nuoc($sender);
              break;
              
          }
 });
        $name = $sender->getPlayer()->getName();
     $cc = $this->cc->get($sender->getPlayer()->getName());
        $sc = $this->sc->get($sender->getPlayer()->getName());
     $tc = $this->tc->get($sender->getPlayer()->getName());
        $n = $this->n->get($sender->getPlayer()->getName());
        $cap = $this->cap->get($sender->getPlayer()->getName());
        $kn = $this->kn->get($sender->getPlayer()->getName());
        $maxkn = $cap * 500;
        $form->setTitle("§7[ §c§l cây §e phát tài §r§7]");
        $form->setContent("§2§l§o§mTên người chơi: §r§o§l§d" . $name . "\n§2§l§o§mcấp: §r§o§l" . $cap . "\n§2§l§o§mkinh nghiệm: §r§o§l" . $kn . "§6§l/".$maxkn."\n--------------------------------\n§2§l§o§mPhân Bón\n§f-§2§l§o§mphân bón sơ cấp: §r§o§l" . $sc . "\n§f-§2§l§o§mphân bón trung cấp: §r§o§l" . $tc . "\n§f-§2§l§o§mphân bón cao cấp: §r§o§l" . $cc . "\n§f-§2§l§o§mnước: §r§o§l" . $n . "\n sử dụng phân bón và nước để tăng cấp cây phát tài");
        $form->addButton("§f•§c§lQuay lại§r§f•");
        $form->addButton("§f•§b§o§lbón phân§f•");
        $form->addButton("§f•§b§o§ltưới nước§f•");
        $form->sendToPlayer($sender);
    }
 
    public function nuoc($sender){
        $n = $this->n->get($sender->getPlayer()->getName());
        if($n > 1) {
        $n = $this->n->get($sender->getPlayer()->getName());
        $cap = $this->cap->get($sender->getPlayer()->getName());
      $tien1 = $n * 1000;
      $tien = $cap * $tien1;
       $this->eco->addMoney($sender->getPlayer()->getName(), $tien);
             $sender->sendMessage("§l§b❖§a Bạn Đã Nhận ".$tien." Money Từ Tưới Cây §b❖");
             $this->n->set($sender->getPlayer()->getName(), ($this->n->get($sender->getPlayer()->getName()) - $n));
             $this->n->save();
  }
  if ($n == 0){
             $sender->sendMessage("§l§b❖§a Bạn Không Có Nước§b❖");
  }
    }

	public function Toplevel(Player $sender){
		$levelplot = $this->cap->getAll();
		$message = "";
		$message1 = "";
		if(count($levelplot) > 0){
			arsort($levelplot);
			$i = 1;
			foreach($levelplot as $name => $level){
				$message .= "§l§3TOP " . $i . ": §6" . $name . " §d→ §f" . $level . " §2Cấp\n\n";
				$message1 .= "§l§3TOP " . $i . ": §6" . $name . " §d→ §f" . $level . " §2Cấp\n";
				if($i >= 10){
					break;
				}
				++$i;
			}
		}
		
		$formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $formapi->createSimpleForm(function (Player $sender, ?int $data = null){
			$result = $data;
			switch($result){
				case 0:
				$this->Menu($sender);
				break;
			}
		});
		$form->setTitle("§l§f•§bTop Cây Phát Tài §f•");
		$form->setContent($message);
		$form->addButton("§l§f•§cQuay Lại §f•");
		$form->sendToPlayer($sender);
		return $form;
	}
}