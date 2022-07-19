<?php

namespace hmmhmmmm\boss\cmd\args;

use hmmhmmmm\boss\libs\CortexPE\Commando\args\BooleanArgument;

class BooleanArgs extends BooleanArgument{

   public function getTypeName(): string{
      return "bool";
   }
   
}