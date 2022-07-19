<?php

namespace hmmhmmmm\boss\cmd\args;

use hmmhmmmm\boss\libs\CortexPE\Commando\args\FloatArgument;

class FloatArgs extends FloatArgument{

   public function getTypeName(): string{
      return "float";
   }
   
}