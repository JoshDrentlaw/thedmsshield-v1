<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Debug extends Model
{
    public static function log($var)
    {
        echo '<div class="row"><div class="col-10 offset-1"><pre class="debug-log">';
        var_dump($var);
        echo '</pre></div></div>';
    }
}
