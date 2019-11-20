<?php
session_start();
$url =  "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
$request_url = htmlspecialchars( $url, ENT_QUOTES, 'UTF-8' );
$step_now=0;
$channel=isset($_GET['channel'])?($_GET['channel']):('nochannel');
$csvData = file_get_contents($channel.".txt");
$lines = explode(PHP_EOL, $csvData);
$steps = array();
foreach ($lines as $line) {
    $s = str_getcsv($line);
    if(count($s)==2){
        $steps[]=$s;
    }
}
$maxsteps=count($steps)-1;
if(isset($_SESSION["next_step"])){
    $step_now=$_SESSION["next_step"];
}
if($step_now>$maxsteps){
    $step_now=0;
}
$step_next=$step_now+1;
if($step_next>$maxsteps){
    $step_next=0;
}
$_SESSION["next_step"]=$step_next;

$now_url=$steps[$step_now][0];
$time_now=$steps[$step_now][1];

$f_contents = file("news.txt");
$line = $f_contents[array_rand($f_contents)];
$news=explode("#",$line);

?><html>
<head>
<meta http-equiv="refresh" content="<?php echo $time_now;?> url=<?php echo $request_url;?>">
<link rel="stylesheet" href="css.css">
</head>
<body>
    <div class="marquee">
        <div class="title"><?php echo $news[0];?></div>
        <div class="summary"><?php echo $news[1];?>
            <!-- <br>Doing <?php echo $now_url;?> for <?php echo $time_now;?> seconds -->
        </div>
    </div>
    <iframe id="content" name="content" height="100%" width="100%" src="<?php echo $now_url;?>" scrolling="no" seamless="seamless" allow="autoplay;encrypted-media" frameBorder="0" allowfullscreen></iframe>
</body>
</html>