<?php

namespace hmmhmmmm\boss\cmd\args;

use hmmhmmmm\boss\libs\CortexPE\Commando\args\TextArgument;

class TextArgs extends TextArgument{

   public function getTypeName(): string{
      return "text";
   }
   
}