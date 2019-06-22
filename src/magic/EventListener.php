<?php

namespace magic;

use onebone\economyapi\EconomyAPI;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\network\mcpe\protocol\ModalFormResponsePacket;
use pocketmine\Server;

class EventListener implements Listener
{
    public $Prefix = "§a[§l§6N§eshop§dV§a7§a]§6:§e ";

    /**
     * 玩家点击事件
     * @param PlayerInteractEvent $event
     */
    public function onPlayerInteract(PlayerInteractEvent $event)
    {
        $player = $event->getPlayer();
        $item = $event->getItem();
        if (($item->getCustomName() == "§l§6N§eshop §dV§c7§b商店") || ($item->getId() == 399 && $item->getDamage() == 993)) {
            Nshop::getNshop()->getAPI()->UIAPI(0, $player);
            $event->setCancelled(true);
        }
    }

    /**
     * 玩家加入事件
     * @param PlayerJoinEvent $event
     */
    public function onPlayerJoin(PlayerJoinEvent $event)
    {
        Nshop::getNshop()->getShopMenuItem($event->getPlayer());
    }

    /**
     * 数据接收事件
     * @param DataPacketReceiveEvent $event
     * @return bool|void
     */
    public function onReceive(DataPacketReceiveEvent $event)
    {
        $ShopAll = Nshop::getNshop()->getShopAll();
        $SellAll = Nshop::getNshop()->getSellAll();
        $CmdShopAll = Nshop::getNshop()->getCmdShopAll();
        $ExpAll = Nshop::getNshop()->getExpShopAll();
        $MagicAll = Nshop::getNshop()->getMagicShopAll();
        $SwopAll = Nshop::getNshop()->getSwopShopAll();
        $ShopSwitch = Nshop::getNshop()->getSwitch("出售商店开关");
        $SellSwitch = Nshop::getNshop()->getSwitch("回收商店开关");
        $CmdShopSwitch = Nshop::getNshop()->getSwitch("指令商店开关");
        $ExpShopSwitch = Nshop::getNshop()->getSwitch("经验商店开关");
        $MagicSwitch = Nshop::getNshop()->getSwitch("附魔商店开关");
        $SwopSwitch = Nshop::getNshop()->getSwitch("兑换商店开关");
        $packet = $event->getPacket();
        $player = $event->getPlayer();
        if (!($packet instanceof ModalFormResponsePacket)) return;
        $FormID = $packet->formId;
        $FormData = json_decode($packet->formData);
        switch ($FormID) {
            case 0:
                if ($packet->formData == "null\n") return;
                if ((int)$FormData == 0) {
                    if ($ShopSwitch) {
                        Nshop::getNshop()->getAPI()->UIAPI(1, $player);
                        return;
                    } else {
                        $player->sendMessage(Nshop::getNshop()->PreFix . "§l§4此商店已经关闭");
                        return;
                    }
                }
                if ((int)$FormData == 1) {
                    if ($SellSwitch) {
                        Nshop::getNshop()->getAPI()->UIAPI(2, $player);
                        return;
                    } else {
                        $player->sendMessage(Nshop::getNshop()->PreFix . "§l§4此商店已经关闭");
                        return;
                    }
                }
                if ((int)$FormData == 2) {
                    if ($CmdShopSwitch) {
                        Nshop::getNshop()->getAPI()->UIAPI(3, $player);
                        return;
                    } else {
                        $player->sendMessage(Nshop::getNshop()->PreFix . "§l§4此商店已经关闭");
                        return;
                    }
                }
                if ((int)$FormData == 3) {
                    if ($ExpShopSwitch) {
                        Nshop::getNshop()->getAPI()->UIAPI(4, $player);
                        return;
                    } else {
                        $player->sendMessage(Nshop::getNshop()->PreFix . "§l§4此商店已经关闭");
                        return;
                    }
                }
                if ((int)$FormData == 4) {
                    if ($MagicSwitch) {
                        Nshop::getNshop()->getAPI()->UIAPI(5, $player);
                        return;
                    } else {
                        $player->sendMessage(Nshop::getNshop()->PreFix . "§l§4此商店已经关闭");
                        return;
                    }
                }
                if ((int)$FormData == 5) {
                    if ($SwopSwitch) {
                        Nshop::getNshop()->getAPI()->UIAPI(8, $player);
                        return;
                    } else {
                        $player->sendMessage(Nshop::getNshop()->PreFix . "§l§4此商店已经关闭");
                        return;
                    }
                }
                break;
            case 1:
                if ($packet->formData == "null\n") return;
                $Item = array_keys($ShopAll)[$FormData];
                $Money = Nshop::getNshop()->getShop($Item)["单价"];
                $key = array_keys($ShopAll)[$FormData];
                $Items = explode(":", $key);
                Nshop::getNshop()->o1 = $Money;
                Nshop::getNshop()->o2 = $Items[0];
                Nshop::getNshop()->o3 = $Items[1];
                Nshop::getNshop()->getAPI()->UIAPI(6, $player);
                break;
            case 2:
                if ($packet->formData == "null\n") return;
                $Item = array_keys($SellAll)[$FormData];
                $Money = Nshop::getNshop()->getSell($Item)["单价"];
                $Items = explode(":", $Item);
                Nshop::getNshop()->t3 = $Money;
                Nshop::getNshop()->t1 = $Items[0];
                Nshop::getNshop()->t2 = $Items[1];
                Nshop::getNshop()->getAPI()->UIAPI(7, $player);
                break;
            case 3:
                if ($packet->formData == "null\n") return;
                $Command = array_keys($CmdShopAll)[$FormData];
                $Money = Nshop::getNshop()->getCmdShop($Command)["单价"];
                $PlayerMoney = EconomyAPI::getInstance()->myMoney($player->getName());
                if ($Money > $PlayerMoney) {
                    $player->sendMessage(Nshop::getNshop()->PreFix . "§c购买失败！你的钱不够！");
                } else {
                    EconomyAPI::getInstance()->reduceMoney($player, $Money);
                    $player->sendMessage(Nshop::getNshop()->PreFix . "§l§3恭喜你购买成功,§b本次花费了§e{$Money}");
                    Server::getInstance()->dispatchCommand(new ConsoleCommandSender(), str_replace("@p", $player->getName(), $Command));
                }
                break;
            case 4:
                if ($packet->formData == "null\n") return;
                $Exp = array_keys($ExpAll)[$FormData];
                $Money = Nshop::getNshop()->getExpShop($Exp)["单价"];
                $PlayerMoney = EconomyAPI::getInstance()->myMoney($player->getName());
                if ($Money > $PlayerMoney) {
                    $player->sendMessage(Nshop::getNshop()->PreFix . "§c购买失败！你的钱不够！");
                } else {
                    EconomyAPI::getInstance()->reduceMoney($player, $Money);
                    $player->addXp($Exp);
                    $player->sendMessage(Nshop::getNshop()->PreFix . "§l§3恭喜你购买成功,§b本次花费了§e{$Money}");
                }
                break;
            case 5:
                if ($packet->formData == "null\n") return;
                $Magic = array_keys($MagicAll)[$FormData];
                $Money = Nshop::getNshop()->getMagicShop($Magic)["单价"];
                $PlayerMoney = EconomyAPI::getInstance()->myMoney($player->getName());
                if ($Money > $PlayerMoney) {
                    $player->sendMessage(Nshop::getNshop()->PreFix . "§c购买失败！你的钱不够！");
                } else {
                    $Magics = explode(":", $Magic);
                    $PlayerHotbarSlotItem = $player->getInventory()->getHotbarSlotItem(1);
                    if ($PlayerHotbarSlotItem->getID() == 0) return $player->sendMessage(Nshop::getNshop()->PreFix . "§l§1未发现附魔物品");
                    if ($PlayerHotbarSlotItem->getCount() > 1) return $player->sendMessage(Nshop::getNshop()->PreFix . "§c只能附魔一个物品喔!");
                    $enchid = Enchantment::getEnchantment($Magics[0]);
                    $ench = new EnchantmentInstance($enchid, $Magics[1] + 0);
                    $PlayerHotbarSlotItem->addEnchantment($ench);
                    $player->getInventory()->setItem(1, $PlayerHotbarSlotItem);
                    EconomyAPI::getInstance()->reduceMoney($player, $Money);
                    $player->sendMessage(Nshop::getNshop()->PreFix . "§l§3恭喜你购买成功,§b本次花费了§e{$Money}");
                }
                break;
            case 6:
                if ($FormData[0] <= 0) {
                    $player->sendMessage(Nshop::getNshop()->PreFix . "§3请不要随意输入数值");
                } else {
                    $Money = Nshop::getNshop()->o1 * $FormData[0];
                    $PlayerMoney = EconomyAPI::getInstance()->myMoney($player->getName());
                    if ($Money > $PlayerMoney) {
                        $player->sendMessage(Nshop::getNshop()->PreFix . "§c购买失败！你的钱不够！");
                    } else {
                        EconomyAPI::getInstance()->reduceMoney($player, $Money);
                        $player->getInventory()->addItem(Item::get(Nshop::getNshop()->o2, Nshop::getNshop()->o3, $FormData[0]));
                        $player->sendMessage(Nshop::getNshop()->PreFix . "§l§3恭喜你购买成功,§b本次花费了§e{$Money}");
                    }
                    unset($Money);
                }
                break;
            case 7:
                if ($FormData[0] <= 0) {
                    $player->sendMessage(Nshop::getNshop()->PreFix . "§3请不要随意输入数值");
                } else {
                    $Money = Nshop::getNshop()->t3 * $FormData[0];
                    if (!$player->getInventory()->contains(Item::get(Nshop::getNshop()->t1, Nshop::getNshop()->t2, $FormData[0]))) {
                        $player->sendMessage(Nshop::getNshop()->PreFix . "§l§4您的物品不足");
                    } else {
                        EconomyAPI::getInstance()->addMoney($player, $Money);
                        $player->getInventory()->removeItem(Item::get(Nshop::getNshop()->t1, Nshop::getNshop()->t2, $FormData[0]));
                        $player->sendMessage(Nshop::getNshop()->PreFix . "§e恭喜你出售成功,§b本次获得了§e{$Money}");
                    }
                }
                unset($Money);
                break;
            case 8:
                if ($packet->formData == "null\n") return;
                Nshop::getNshop()->getAPI()->UIAPI(9, $player);
                $i = array_keys($SwopAll)[$FormData];
                $items = Nshop::getNshop()->getSwopShop($i)["需要物品"];
                $Command = Nshop::getNshop()->getSwopShop($i)["执行命令"];
                $Item = explode(":", $i);
                Nshop::getNshop()->y1 = $Item[0];
                Nshop::getNshop()->y2 = $Item[1];
                Nshop::getNshop()->y3 = $items;
                Nshop::getNshop()->y4 = $Command;
                break;
            case 9:
                if ($FormData[0] <= 0) {
                    $player->sendMessage(Nshop::getNshop()->PreFix . "§3请不要随意输入数值");
                } else {
                    $Item = explode(":", Nshop::getNshop()->y3);
                    if (!$player->getInventory()->contains(Item::get($Item[0], $Item[1], $FormData[0] * $Item[2]))) {
                        $player->sendMessage(Nshop::getNshop()->PreFix . "§l§4您的物品不足");
                    } else {
                        $player->getInventory()->addItem(Item::get(Nshop::getNshop()->y1, Nshop::getNshop()->y2, $FormData[0]));
                        $player->getInventory()->removeItem(Item::get($Item[0], $Item[1], $FormData[0] * $Item[2]));
                        Server::getInstance()->dispatchCommand(new ConsoleCommandSender(), str_replace("@p", $player->getName(), (string)Nshop::getNshop()->y4));
                        $player->sendMessage(Nshop::getNshop()->PreFix . "§l§3恭喜你兑换成功");
                    }
                }
                break;
        }
    }
}