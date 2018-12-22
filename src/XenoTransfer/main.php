<?php
# MADE BY:
#  __    __                                          __        __  __  __                     
# /  |  /  |                                        /  |      /  |/  |/  |                    
# $$ |  $$ |  ______   _______    ______    ______  $$ |____  $$/ $$ |$$/   _______  __    __ 
# $$  \/$$/  /      \ /       \  /      \  /      \ $$      \ /  |$$ |/  | /       |/  |  /  |
#  $$  $$<  /$$$$$$  |$$$$$$$  |/$$$$$$  |/$$$$$$  |$$$$$$$  |$$ |$$ |$$ |/$$$$$$$/ $$ |  $$ |
#   $$$$  \ $$    $$ |$$ |  $$ |$$ |  $$ |$$ |  $$ |$$ |  $$ |$$ |$$ |$$ |$$ |      $$ |  $$ |
#  $$ /$$  |$$$$$$$$/ $$ |  $$ |$$ \__$$ |$$ |__$$ |$$ |  $$ |$$ |$$ |$$ |$$ \_____ $$ \__$$ |
# $$ |  $$ |$$       |$$ |  $$ |$$    $$/ $$    $$/ $$ |  $$ |$$ |$$ |$$ |$$       |$$    $$ |
# $$/   $$/  $$$$$$$/ $$/   $$/  $$$$$$/  $$$$$$$/  $$/   $$/ $$/ $$/ $$/  $$$$$$$/  $$$$$$$ |
#                                         $$ |                                      /  \__$$ |
#                                         $$ |                                      $$    $$/ 
#                                         $$/                                        $$$$$$/           
# Editied By:
#
#            /$$   /$$                                        
#           | $$  /$$/                                        
#   /$$$$$$ | $$ /$$/   /$$$$$$  /$$$$$$$$  /$$$$$$  /$$$$$$$ 
#  /$$__  $$| $$$$$/   /$$__  $$|____ /$$/ |____  $$| $$__  $$
# | $$  \ $$| $$  $$  | $$  \ $$   /$$$$/   /$$$$$$$| $$  \ $$
# | $$  | $$| $$\  $$ | $$  | $$  /$$__/   /$$__  $$| $$  | $$
# |  $$$$$$/| $$ \  $$|  $$$$$$$ /$$$$$$$$|  $$$$$$$| $$  | $$
#  \______/ |__/  \__/ \____  $$|________/ \_______/|__/  |__/
#                           | $$                              
#                           | $$                              
#                           |__/                              

namespace XenoTransfer;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\Server;
use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\item\Item;

//FormAPI → https://github.com/jojoe77777/FormAPI
use jojoe77777\FormAPI;
use jojoe77777\FormAPI\SimpleForm;

class Main extends PluginBase implements Listener {

    private $config;

    public function onLoad(){
        $this->saveDefaultConfig();
        $this->config = new Config($this->getDataFolder()."config.yml", Config::YAML);
        $this->config->getAll();
        $this->getLogger()->info("§eXenoTransfer by §6Xenophilicy §eis loading...");
    }

    public function onEnable(){
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getLogger()->info("§6XenoTransfer§a has been enabled!");
    }
    
    public function onDisable(){
        $this->getLogger()->info($this->config->get("§6XenoTransfer§c has been disabled!"));
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
                    $this->getServer()->getCommandMap()->dispatch($player, 'transferserver '.$this->config->getNested("Server-1.IP").' '.$this->config->getNested("Server-1.Port"));
                    break;
                case 1:
                    $this->getServer()->getCommandMap()->dispatch($player, 'transferserver '.$this->config->getNested("Server-2.IP").' '.$this->config->getNested("Server-2.Port"));
                    break;
                case 2:
                    $this->getServer()->getCommandMap()->dispatch($player, 'transferserver '.$this->config->getNested("Server-3.IP").' '.$this->config->getNested("Server-3.Port"));
                    break;
                case 3:
                    $this->getServer()->getCommandMap()->dispatch($player, 'transferserver '.$this->config->getNested("Server-4.IP").' '.$this->config->getNested("Server-4.Port"));
                    break;
                case 4:
                    $this->getServer()->getCommandMap()->dispatch($player, 'transferserver '.$this->config->getNested("Server-5.IP").' '.$this->config->getNested("Server-5.Port"));
                    break;
            }
            return true;
        });
        $form->setTitle($this->config->getNested("UI-Title"));
        $form->setContent($this->config->getNested("UI-Message"));
        $label1 = $this->config->getNested("Server-1.Label");
        $label2 = $this->config->getNested("Server-2.Label");
        $label3 = $this->config->getNested("Server-3.Label");
        $label4 = $this->config->getNested("Server-4.Label");
        $label5 = $this->config->getNested("Server-5.Label");
        $form->addButton("$label1");
        if ($label2 != false){
            $form->addButton("$label2");
        }
        if ($label3 != false){
            $form->addButton("$label3");
        }
        if ($label4 != false){
            $form->addButton("$label4");
        }
        if ($label5 != false){
            $form->addButton("$label5");
        }
        $form->sendToPlayer($player);
    }

    public function onJoin(PlayerJoinEvent $event){
        $compEnable = $this->config->get("Compass-Hotbar");
        if ($compEnable == true) {
            $player = $event->getPlayer();
            $compassText = $this->config->get("Compass-Name");
            $enchantment = Enchantment::getEnchantment(0);
            $enchInstance = new EnchantmentInstance($enchantment, 1);
            $item = Item::get(345);
            $item->setCustomName("§o$compassText");
            $item->addEnchantment($enchInstance);
            $player->getInventory()->addItem($item);
        }
        else {
            $this->getLogger()->notice("§eCompass support turned off in conig! Disabling compass...");
        }
    }

    public function onInteract(PlayerInteractEvent $event){
        $compEnable = $this->config->get("Compass-Hotbar");
        if ($compEnable == true) {
            $player = $event->getPlayer();
            $compassText = $this->config->get("Compass-Name");
            $item = $player->getInventory()->getItemInHand();
            if($item->getCustomName() == "§o$compassText"){
                $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
                $form = new SimpleForm(function (Player $player, $data){
                    if($data === null){
                        return;
                    }
                    switch($data){
                        case 0:
                            $this->getServer()->getCommandMap()->dispatch($player, 'transferserver '.$this->config->getNested("Server-1.IP").' '.$this->config->getNested("Server-1.Port"));
                            break;
                        case 1:
                            $this->getServer()->getCommandMap()->dispatch($player, 'transferserver '.$this->config->getNested("Server-2.IP").' '.$this->config->getNested("Server-2.Port"));
                            break;
                        case 2:
                            $this->getServer()->getCommandMap()->dispatch($player, 'transferserver '.$this->config->getNested("Server-3.IP").' '.$this->config->getNested("Server-3.Port"));
                            break;
                        case 3:
                            $this->getServer()->getCommandMap()->dispatch($player, 'transferserver '.$this->config->getNested("Server-4.IP").' '.$this->config->getNested("Server-4.Port"));
                            break;
                        case 4:
                            $this->getServer()->getCommandMap()->dispatch($player, 'transferserver '.$this->config->getNested("Server-5.IP").' '.$this->config->getNested("Server-5.Port"));
                            break;
                    }
                    return true;
            });
            $form->setTitle($this->config->getNested("UI-Title"));
            $form->setContent($this->config->getNested("UI-Message"));
            $label1 = $this->config->getNested("Server-1.Label");
            $label2 = $this->config->getNested("Server-2.Label");
            $label3 = $this->config->getNested("Server-3.Label");
            $label4 = $this->config->getNested("Server-4.Label");
            $label5 = $this->config->getNested("Server-5.Label");
            $form->addButton("$label1");
            if ($label2 != false){
                $form->addButton("$label2");
            }
            if ($label3 != false){
                $form->addButton("$label3");
            }
            if ($label4 != false){
                $form->addButton("$label4");
            }
            if ($label5 != false){
                $form->addButton("$label5");
            }
            $form->sendToPlayer($player);
            }
        }
        else{
        }
    }
}
?>
