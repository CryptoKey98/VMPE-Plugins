<?php

namespace bansystem\listener;

use bansystem\util\date\Countdown;
use DateTime;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\utils\TextFormat;
use pocketmine\Player;

class PlayerPreLoginListener implements Listener {
    
    public function onPlayerPreLogin(PlayerPreLoginEvent $event) {
        $player = $event->getPlayer();
        $banList = $player->getServer()->getNameBans();
        if ($banList->isBanned(strtolower($player->getName()))) {
            $kickMessage = "";
            $banEntry = $banList->getEntries();
            $entry = $banEntry[strtolower($player->getName())];
            if ($entry->getExpires() == null) {
                $reason = $entry->getReason();
                if ($reason != null || $reason != "") {
                    $kickMessage = TextFormat::RED . "§cYou are still banned from this server.\n§cReason: " . TextFormat::AQUA . $reason . TextFormat::RED . ".";
                } else {
                    $kickMessage = TextFormat::RED . "§cYou are currently banned by §4Staff";
                }
            } else {
                $expiry = Countdown::expirationTimerToString($entry->getExpires(), new DateTime());
                if ($entry->hasExpired()) {
                    $banList->remove($entry->getName());
                    return;
                }
                $banReason = $entry->getReason();
                if ($banReason != null || $banReason != "") {
                    $kickMessage = TextFormat::RED . "§cYou are still banned from this server.\n§cReason: " . TextFormat::LIGHT_PURPLE . $banReason . TextFormat::RED . " §bYou will be able to play again in " . TextFormat::AQUA . $expiry . TextFormat::RED . ".";
                } else {
                    $kickMessage = TextFormat::RED . "§cYou are still banned from this server.\n§bYou will be able to play again in " . TextFormat::LIGHT_PURPLE . $expiry . TextFormat::RED . ".";
                }
            }
            $player->close("", $kickMessage);
        }
    }
    
    public function onPlayerPreLogin2(PlayerPreLoginEvent $event) {
        $player = $event->getPlayer();
        $banList = $player->getServer()->getIPBans();
        if ($banList->isBanned(strtolower($player->getAddress()))) {
            $kickMessage = "";
            $banEntry = $banList->getEntries();
            $entry = $banEntry[strtolower($player->getAddress())];
            if ($entry->getExpires() == null) {
                $reason = $entry->getReason();
                if ($reason != null || $reason != "") {
                    $kickMessage = TextFormat::RED . "You are currently IP banned by Staff §aReason: " . TextFormat::AQUA . $reason . TextFormat::RED . ".";
                } else {
                    $kickMessage = TextFormat::RED . "You are currently IP banned by §bStaff";
                }
            } else {
                $expiry = Countdown::expirationTimerToString($entry->getExpires(), new DateTime());
                if ($entry->hasExpired()) {
                    $banList->remove($entry->getName());
                    return;
                }
                $banReason = $entry->getReason();
                if ($banReason != null || $banReason != "") {
                    $kickMessage = TextFormat::RED . "You are currently IP banned by §bStaff §aReason: " . TextFormat::AQUA . $banReason . TextFormat::RED . " until " . TextFormat::AQUA . $expiry . TextFormat::RED . ".";
                } else {
                    $kickMessage = TextFormat::RED . "You are currently IP banned by §bStaff §auntil " . TextFormat::AQUA . $expiry . TextFormat::RED . ".";
                }
            }
            $player->close("", $kickMessage);
        }
    }
}
