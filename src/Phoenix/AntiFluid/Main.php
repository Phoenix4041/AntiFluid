<?php

declare(strict_types=1);

namespace Phoenix4041\AntiFluid;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\block\BlockSpreadEvent;
use pocketmine\event\block\BlockFormEvent;
use pocketmine\event\block\LiquidFlowEvent;
use pocketmine\block\Water;
use pocketmine\block\Lava;
use pocketmine\block\VanillaBlocks;

class Main extends PluginBase implements Listener {

    public function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getLogger()->info("§aAntiFluid Plugin enabled - Fluids will not spread");
    }

    public function onDisable(): void {
        $this->getLogger()->info("§cAntiFluid Plugin disabled");
    }

    /**
     * Cancels liquid flow
     */
    public function onLiquidFlow(LiquidFlowEvent $event): void {
        $block = $event->getBlock();
        
        // Check if it's water or lava
        if ($block instanceof Water || $block instanceof Lava) {
            $event->cancel();
        }
    }

    /**
     * Cancels fluid block spreading
     */
    public function onBlockSpread(BlockSpreadEvent $event): void {
        $source = $event->getSource();
        
        // Check if the source block is a fluid
        if ($source instanceof Water || $source instanceof Lava) {
            $event->cancel();
        }
    }

    /**
     * Cancels block formation by fluids (like obsidian)
     */
    public function onBlockForm(BlockFormEvent $event): void {
        $block = $event->getBlock();
        
        // Check if there are nearby fluids that could cause formation
        $world = $block->getPosition()->getWorld();
        $pos = $block->getPosition();
        
        // Check adjacent blocks
        $adjacentPositions = [
            $pos->add(1, 0, 0),
            $pos->add(-1, 0, 0),
            $pos->add(0, 1, 0),
            $pos->add(0, -1, 0),
            $pos->add(0, 0, 1),
            $pos->add(0, 0, -1)
        ];
        
        foreach ($adjacentPositions as $adjacentPos) {
            $adjacentBlock = $world->getBlock($adjacentPos);
            if ($adjacentBlock instanceof Water || $adjacentBlock instanceof Lava) {
                $event->cancel();
                return;
            }
        }
    }
}