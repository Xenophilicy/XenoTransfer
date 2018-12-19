<?php
# MADE BY:
# __    __                                          __        __  __  __                     
#/  |  /  |                                        /  |      /  |/  |/  |                    
#$$ |  $$ |  ______   _______    ______    ______  $$ |____  $$/ $$ |$$/   _______  __    __ 
#$$  \/$$/  /      \ /       \  /      \  /      \ $$      \ /  |$$ |/  | /       |/  |  /  |
# $$  $$<  /$$$$$$  |$$$$$$$  |/$$$$$$  |/$$$$$$  |$$$$$$$  |$$ |$$ |$$ |/$$$$$$$/ $$ |  $$ |
#  $$$$  \ $$    $$ |$$ |  $$ |$$ |  $$ |$$ |  $$ |$$ |  $$ |$$ |$$ |$$ |$$ |      $$ |  $$ |
# $$ /$$  |$$$$$$$$/ $$ |  $$ |$$ \__$$ |$$ |__$$ |$$ |  $$ |$$ |$$ |$$ |$$ \_____ $$ \__$$ |
#$$ |  $$ |$$       |$$ |  $$ |$$    $$/ $$    $$/ $$ |  $$ |$$ |$$ |$$ |$$       |$$    $$ |
#$$/   $$/  $$$$$$$/ $$/   $$/  $$$$$$/  $$$$$$$/  $$/   $$/ $$/ $$/ $$/  $$$$$$$/  $$$$$$$ |
#                                        $$ |                                      /  \__$$ |
#                                        $$ |                                      $$    $$/ 
#                                        $$/                                        $$$$$$/           
# Editied By:
#
#           /$$   /$$                                        
#          | $$  /$$/                                        
#  /$$$$$$ | $$ /$$/   /$$$$$$  /$$$$$$$$  /$$$$$$  /$$$$$$$ 
# /$$__  $$| $$$$$/   /$$__  $$|____ /$$/ |____  $$| $$__  $$
#| $$  \ $$| $$  $$  | $$  \ $$   /$$$$/   /$$$$$$$| $$  \ $$
#| $$  | $$| $$\  $$ | $$  | $$  /$$__/   /$$__  $$| $$  | $$
#|  $$$$$$/| $$ \  $$|  $$$$$$$ /$$$$$$$$|  $$$$$$$| $$  | $$
# \______/ |__/  \__/ \____  $$|________/ \_______/|__/  |__/
#                          | $$                              
#                          | $$                              
#                          |__/                              

namespace XenoTransfer;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\item\Item;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\Server;
use pocketmine\Player;

//FormAPI → https://github.com/jojoe77777/FormAPI
use jojoe77777\FormAPI;
use jojoe77777\FormAPI\SimpleForm;

class Main extends PluginBase implements Listener {

    public function onLoad(){
        $this->getLogger()->info("§eXenoTransfer by §6Xenophilicy §eis loading...");
    }

    public function onEnable(){
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        @mkdir($this->getDataFolder());
        $this->saveResource("config.yml");
		$this->getConfig()->getAll();
    }
    
    public function onDisable(){
        $this->getLogger()->info($this->getConfig()->get("Disable_Message"));
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
        if ($sender instanceof Player) {
            $player = $sender->getPlayer();
            switch($command->getName()){
            case'servers':
                $this->serverList($sender);
                break;
            case'xenotransfer':
                $sender->sendMessage("§7-=== §6XenoTransfer §7===-");
                $sender->sendMessage("§eAuthor: §aXenophillicy");
                $sender->sendMessage("§eDescription: §aTransfer to other servers with ease!");
                $sender->sendMessage("§7-====================-");
                break;
            }
            return true;
        }
        else {
            $sender->sendMessage("§cThis command only works in game.");
            return true;
        }
    }

    public function serverList($player){
        $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = new SimpleForm(function (Player $player, $data){
            if($data === null){
                return;
            }
                switch($data){
                    case 0:
                        $this->getServer()->getCommandMap()->dispatch($player, 'transferserver '.$this->getConfig()->get("Server_1_IP").' '.$this->getConfig()->get("Server_1_Port"));
                        break;
                    case 1:
                        $this->getServer()->getCommandMap()->dispatch($player, 'transferserver '.$this->getConfig()->get("Server_2_IP").' '.$this->getConfig()->get("Server_2_Port"));
                        break;
                    case 2:
                        $this->getServer()->getCommandMap()->dispatch($player, 'transferserver '.$this->getConfig()->get("Server_3_IP").' '.$this->getConfig()->get("Server_3_Port"));
                        break;
                    case 3:
                        $this->getServer()->getCommandMap()->dispatch($player, 'transferserver '.$this->getConfig()->get("Server_4_IP").' '.$this->getConfig()->get("Server_4_Port"));
                        break;
                    case 4:
                        $this->getServer()->getCommandMap()->dispatch($player, 'transferserver '.$this->getConfig()->get("Server_5_IP").' '.$this->getConfig()->get("Server_5_Port"));
                        break;
                }
            return true;
        });
        $form->setTitle("§6Server List");
        $form->setContent("§aChoose a server to transfer to!");
        $form->addButton($this->getConfig()->get("Server_1_Label"));
        if ($this->getConfig()->get("Server_2_Label") != false){
            $form->addButton($this->getConfig()->get("Server_2_Label"));
        }
        if ($this->getConfig()->get("Server_3_Label") != false){
            $form->addButton($this->getConfig()->get("Server_3_Label"));
        }
        if ($this->getConfig()->get("Server_4_Label") != false){
            $form->addButton($this->getConfig()->get("Server_4_Label"));
        }
        if ($this->getConfig()->get("Server_5_Label") != false){
            $form->addButton($this->getConfig()->get("Server_5_Label"));
        }
        $form->sendToPlayer($player);
    }

    public function onJoin(PlayerJoinEvent $event){
        $player = $event->getPlayer();
        $compassText = $this->getConfig()->get("Compass-Name");
		$enchantment = Enchantment::getEnchantment(0);
		$enchInstance = new EnchantmentInstance($enchantment, 1);
		$item = Item::get(345);
		$item->setCustomName("§l§a$compassText");
		$item->addEnchantment($enchInstance);
        $player->getInventory()->addItem($item);
    }
    
    public function onInteract(PlayerInteractEvent $event){
        $player = $event->getPlayer();
        $compassText = $this->getConfig()->get("Compass-Name");
        $disableCompHotbar = $this->getConfig()->get("Disable-Compass-Hotbar");
        $item = $player->getInventory()->getItemInHand();
			if($item->getCustomName() == "§l§a$compassText"){
                $event->setCancelled($disableCompHotbar);
                $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = new SimpleForm(function (Player $player, $data){
            if($data === null){
                return;
            }
                switch($data){
                    case 0:
                        $this->getServer()->getCommandMap()->dispatch($player, 'transferserver '.$this->getConfig()->get("Server_1_IP").' '.$this->getConfig()->get("Server_1_Port"));
                        break;
                    case 1:
                        $this->getServer()->getCommandMap()->dispatch($player, 'transferserver '.$this->getConfig()->get("Server_2_IP").' '.$this->getConfig()->get("Server_2_Port"));
                        break;
                    case 2:
                        $this->getServer()->getCommandMap()->dispatch($player, 'transferserver '.$this->getConfig()->get("Server_3_IP").' '.$this->getConfig()->get("Server_3_Port"));
                        break;
                    case 3:
                        $this->getServer()->getCommandMap()->dispatch($player, 'transferserver '.$this->getConfig()->get("Server_4_IP").' '.$this->getConfig()->get("Server_4_Port"));
                        break;
                    case 4:
                        $this->getServer()->getCommandMap()->dispatch($player, 'transferserver '.$this->getConfig()->get("Server_5_IP").' '.$this->getConfig()->get("Server_5_Port"));
                        break;
                }
            return true;
        });
        $form->setTitle("§6Server List");
        $form->setContent("§aChoose a server to transfer to!");
        $form->addButton($this->getConfig()->get("Server_1_Label"));
        if ($this->getConfig()->get("Server_2_Label") != false){
            $form->addButton($this->getConfig()->get("Server_2_Label"));
        }
        if ($this->getConfig()->get("Server_3_Label") != false){
            $form->addButton($this->getConfig()->get("Server_3_Label"));
        }
        if ($this->getConfig()->get("Server_4_Label") != false){
            $form->addButton($this->getConfig()->get("Server_4_Label"));
        }
        if ($this->getConfig()->get("Server_5_Label") != false){
            $form->addButton($this->getConfig()->get("Server_5_Label"));
        }
        $form->sendToPlayer($player);
        }
    }
}
?>
