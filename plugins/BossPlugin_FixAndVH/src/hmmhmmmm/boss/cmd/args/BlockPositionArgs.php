<?php

namespace hmmhmmmm\boss\cmd\args;

use hmmhmmmm\boss\libs\CortexPE\Commando\args\BlockPositionArgument;

class BlockPositionArgs extends BlockPositionArgument{

   public function getTypeName(): string{
      return "block-position";
   }
   
}