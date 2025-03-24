<?$b=fscanf;$b($a=STDIN,"%d%*d%*d%*d%d",$nbFloors,$exitPos);
for(;$i++<$nbFloors-1;)
{
  $b($a,"%d%d",$elevatorFloor,$elevatorPos);
  $elevators[$elevatorFloor]=$elevatorPos;
}
$elevators[$nbFloors-1]=$exitPos;
for(;$b($a,"%d%d%s",$cloneFloor,$clonePos,$cloneDirection);)
{
  echo ($clonePos!=$elevators[$cloneFloor]&&$cloneDirection!=($clonePos<$elevators[$cloneFloor]?"RIGHT":"LEFT")?"BLOCK":"WAIT")."
";
}