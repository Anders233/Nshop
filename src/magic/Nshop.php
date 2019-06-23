<?php

namespace magic;

use magic\utils\IDAndImgList;
use magic\utils\NshopCommand;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\Item;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use magic\utils\API;

# 写注释是不可能的这辈子都不可能的 - 泥土

# 注释是要写的，别说我了，估计现在你自己都看不懂自己写的啥 - Anders

class Nshop extends PluginBase
{
    /**
     * 插件前缀
     * @var string
     */
    public $PreFix = "§a[§l§6N§eshop§dV§c7§a]§6:§e ";

    /**
     * 插件主类
     * @var
     */
    private static $Nshop = null;

    /**
     * Nshop命令
     * @var
     */
    public $NshopCommand;

    /**
     * 插件主要配置文件
     * @var
     */
    public $MainConfig;

    /**
     * 出售商店
     * @var
     */
    public $ShopConfig;

    /**
     * 回收商店
     * @var
     */
    public $SellConfig;

    /**
     * 指令商店
     * @var
     */
    public $CmdShopConfig;

    /**
     * 经验商店
     * @var
     */
    public $ExpShopConfig;

    /**
     * 附魔商店
     * @var
     */
    public $MagicShopConfig;

    /**
     * 开关配置
     * @var
     */
    public $SwitchConfig;

    /**
     * 交换商店
     * @var
     */
    public $SwopShopConfig;

    /**
     * Get UI API
     * @var
     */
    public $API;

    /**
     * 临时储存数据用的
     * 别动！
     * 动就炸
     * @var
     */
    public $o1;
    public $o2;
    public $o3;
    public $t1;
    public $t2;
    public $t3;
    public $y1;
    public $y2;
    public $y3;
    public $y4;
    public $y5;

    /**
     * 插件加载
     * 加载全部的配置文件
     */
    public function onLoad()
    {
        self::$Nshop = $this;
        $this->NshopCommand = new NshopCommand($this);
        $this->API = new API($this);
        $this->o1 = null;
        $this->o2 = null;
        $this->o3 = null;
        $this->t1 = null;
        $this->t2 = null;
        $this->t3 = null;
        $this->y1 = null;
        $this->y2 = null;
        $this->y3 = null;
        $this->y4 = null;
        $this->y5 = null;
        if (!is_dir($this->getDataFolder())) @mkdir($this->getDataFolder(), 0777, true);
        if (!file_exists($this->getDataFolder() . "config.yml")) $this->saveResource("config.yml");
        $this->MainConfig = new Config($this->getDataFolder() . "config.yml", Config::YAML);
        if (!file_exists($this->getDataFolder() . "购买.yml")) $this->saveResource("购买.yml");
        $this->ShopConfig = new Config($this->getDataFolder() . "购买.yml", Config::YAML);
        if (!file_exists($this->getDataFolder() . "回收.yml")) $this->saveResource("回收.yml");
        $this->SellConfig = new Config($this->getDataFolder() . "回收.yml", Config::YAML);
        if (!file_exists($this->getDataFolder() . "指令.yml")) $this->saveResource("指令.yml");
        $this->CmdShopConfig = new Config($this->getDataFolder() . "指令.yml", Config::YAML);
        if (!file_exists($this->getDataFolder() . "经验.yml")) $this->saveResource("经验.yml");
        $this->ExpShopConfig = new Config($this->getDataFolder() . "经验.yml", Config::YAML);
        if (!file_exists($this->getDataFolder() . "附魔.yml")) $this->saveResource("附魔.yml");
        $this->MagicShopConfig = new Config($this->getDataFolder() . "附魔.yml", Config::YAML);
        if (!file_exists($this->getDataFolder() . "开关.yml")) $this->saveResource("开关.yml");
        $this->SwitchConfig = new Config($this->getDataFolder() . "开关.yml", Config::YAML);
        if (!file_exists($this->getDataFolder() . "兑换.yml")) $this->saveResource("兑换.yml");
        $this->SwopShopConfig = new Config($this->getDataFolder() . "兑换.yml", Config::YAML);
    }

    /**
     * 插件启动
     */
    public function onEnable()
    {
        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
        $this->getLogger()->info("===============================");
        $this->getLogger()->info("本插件由渣渣土乱写出来");
        $this->getLogger()->info("本插件虽然辣鸡但禁止盗卖与乱改版权");
        $this->getLogger()->info("本插件作者渣渣土QQ✑1010340249");
        $this->getLogger()->info("本插件由Anders接坑改写");
        $this->getLogger()->info("渣渣土已弃坑，有BUG联系QQ✑2641541097");
        $this->getLogger()->info("===============================");
    }

    /**
     * Get插件主类
     * @return mixed
     */
    public static function getNshop()
    {
        return self::$Nshop;
    }

    /**
     * Get MainConfig $Key value
     * @param $Key
     * @return mixed
     */
    public function getMainConfig($Key)
    {
        return $this->MainConfig->get($Key);
    }

    /**
     * 获取出售商店配置文件的某个 Key 的 Value
     * @param $Key
     * @return mixed
     */
    public function getShop($Key)
    {
        return $this->ShopConfig->get($Key);
    }

    /**
     * 获取出售商店配置文件全部内容
     * @return mixed
     */
    public function getShopAll()
    {
        return $this->ShopConfig->getAll();
    }

    /**
     * 获取回收商店配置文件的某个 Key 的 Value
     * @param $Key
     * @return mixed
     */
    public function getSell($Key)
    {
        return $this->SellConfig->get($Key);
    }

    /**
     * 获取回收商店配置文件全部内容
     * @return mixed
     */
    public function getSellAll()
    {
        return $this->SellConfig->getAll();
    }

    /**
     * 获取命令商店配置文件的某个 Key 的 Value
     * @param $Key
     * @return mixed
     */
    public function getCmdShop($Key)
    {
        return $this->CmdShopConfig->get($Key);
    }

    /**
     * 获取命令商店配置文件的全部内容
     * @return mixed
     */
    public function getCmdShopAll()
    {
        return $this->CmdShopConfig->getAll();
    }

    /**
     * 获取经验商店配置文件的某个 Key 的 Value
     * @param $Key
     * @return mixed
     */
    public function getExpShop($Key)
    {
        return $this->ExpShopConfig->get($Key);
    }

    /**
     * 获取经验商店配置文件的全部内容
     * @return mixed
     */
    public function getExpShopAll()
    {
        return $this->ExpShopConfig->getAll();
    }

    /**
     * 获取附魔商店配置文件的某个 Key 的 Value
     * @param $Key
     * @return mixed
     */
    public function getMagicShop($Key)
    {
        return $this->MagicShopConfig->get($Key);
    }

    /**
     * 获取附魔商店配置文件的全部内容
     * @return mixed
     */
    public function getMagicShopAll()
    {
        return $this->MagicShopConfig->getAll();
    }

    /**
     * 获取开关配置文件的某个 Key 的 Value
     * @param $Key
     * @return mixed
     */
    public function getSwitch($Key)
    {
        return $this->SwitchConfig->get($Key);
    }

    /**
     * 获取交换商店配置文件的某个 Key 的 Value
     * @param $Key
     * @return mixed
     */
    public function getSwopShop($Key)
    {
        return $this->SwopShopConfig->get($Key);
    }

    /**
     * 获取交换商店配置文件的全部内容
     * @return mixed
     */
    public function getSwopShopAll()
    {
        return $this->SwopShopConfig->getAll();
    }

    /**
     * Get Nshop Main UI Form
     * @param $player
     */
    public function getMainUI($player)
    {
        $this->getAPI()->UIAPI(0, $player);
    }

    /**
     * 重新加载全部配置文件
     */
    public function relaodAllConfig()
    {
        $this->MainConfig->reload();
        $this->ShopConfig->reload();
        $this->SellConfig->reload();
        $this->CmdShopConfig->reload();
        $this->ExpShopConfig->reload();
        $this->MagicShopConfig->reload();
        $this->SwitchConfig->reload();
        $this->SwopShopConfig->reload();
    }

    /**
     * Get UI API
     * @return mixed
     */
    public function getAPI()
    {
        return $this->API;
    }

    /**
     * 获得召唤商店主界面的物品
     * @param Player $player
     */
    public function getShopMenuItem(Player $player)
    {
        $item = new Item(399, 993);
        $item->setCustomName("§l§6N§eshop §dV§c7§b商店");
        $item->setLore([
            '§r§c▬§6▬§e▬§a▬§b▬§9▬§d▬§9▬§b▬§a▬§e▬§6▬§c▬',
            '§aNshop商店',
            '§b使用方法：',
            '§d点击地面即可',
            '§r§c▬§6▬§e▬§a▬§b▬§9▬§d▬§9▬§b▬§a▬§e▬§6▬§c▬',
            '§e若此物品丢失',
            '§6输入指令：§a/nshop',
            '§r§c▬§6▬§e▬§a▬§b▬§9▬§d▬§9▬§b▬§a▬§e▬§6▬§c▬'
        ]);
        foreach ($player->getInventory()->getContents() as $InventoryItem) {
            if (($InventoryItem->getId() == 399 AND $InventoryItem->getDamage() == 993) && $InventoryItem->getCustomName() == $item->getCustomName()) return;
        }
        $player->getInventory()->addItem($item);
        $player->sendMessage($this->PreFix . "§eNshop商店UI召唤工具已经送到您的背包里");
    }

    /**
     * 获得插件前缀
     * @return string
     */
    public function getPreFix()
    {
        return $this->PreFix;
    }

    /**
     * 命令处理器
     * @param CommandSender $sender
     * @param Command $command
     * @param string $label
     * @param array $args
     * @return bool
     */
    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {//命令Command
        return $this->NshopCommand->onCommand($sender, $command, $label, $args);
    }

    /**
     * 获得物品的中文名称
     * @param $String
     * @return false|int|string
     */
    public function getItemName($String)
    {
        if (in_array($String, IDAndImgList::$itemId)) {
            return array_search($String, IDAndImgList::$itemId);
        } else {
            $item = explode(":", (string)$String);
            if (in_array((string)$item[0], IDAndImgList::$itemId)) {
                return array_search((string)$item[0], IDAndImgList::$itemId);
            } else {
                if (in_array($item[0] . ":" . $item[1], IDAndImgList::$itemId)) {
                    return array_search($item[0] . ":" . $item[1], IDAndImgList::$itemId);
                } else {
                    $name = Item::get((int)$item[0], (int)$item[1])->getName();
                    return ($name === "Unknown") ? "未知物品" : $name;
                }
            }
        }
    }

    /**
     * 获得物品的贴图路径
     * @param $String
     * @return false|int|string
     */
    public function getItemImage($String)
    {
        foreach (IDAndImgList::$ImageId2 as $Key => $Value) {
            if ($Key == $String) {
                return $Value;
            }
        }
        return 'textures/ui/cancel.png';
    }
}
# 啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊

# 这写的什么玩意啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊