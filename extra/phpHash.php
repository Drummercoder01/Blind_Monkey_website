<?php
echo'<h1> PHP hash functies<br><p>hash(algo, &quot;webontwikkeling&quot;);</p></h1>
<table width="600" border="1">
<tr><td><strong>algo</strong></td><td><strong>lengte</strong></td><td><strong>resultaat</strong>(tot op 40 chars)</td><td><strong>tijd</strong> in micro sec</td>
  </tr>';
foreach (hash_algos() as $v) {
	      $time = microtime(true);
        $r = hash($v, "webontwikkeling", false);
				$time = round((microtime(true) - $time)* 1000000000, 2);
        
        echo("<tr><td>$v</td><td>".strlen($r)."</td><td>".substr ( $r , 0 ,40 )."</td><td>$time</td></tr>");
}
echo("</table>");
?>
