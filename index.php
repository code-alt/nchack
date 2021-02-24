<!doctype html>
<html>
<head>
  <title>Chance of a day with snow</title>
  <link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet"> 
  <style>
  body{
font-family: 'Roboto', sans-serif;
  }
  div.res{
    background-color: black;
    border-radius: 5px;
    backdrop-filter: blur(12.5px);
    width: 30%;
    padding: 40px;
    color: white;
    opacity: 0.7;
    margin-top: 90px;
  }

.inp {
  border-radius: 10px;
  border: 0;
  background: None;
  font-family: 'Roboto', sans-serif;
  color: White;
}

.inp:focus {
  outline: None;
}

  .btn {
    color: White;
    font-family: 'Roboto', sans-serif;
    font-size: 12px;
    padding: 10px;
    cursor: Pointer;
    background: None;
    border: 0;
  }

  .btn:focus {
    outline: None;
  }

  @media (max-width:600px) {
    div.res {
      width: 80%;
      margin-top: 0;
    }
  }
  img {
    left: 10px;
    position: fixed;
    bottom: -10px;
    z-index: -1;
    width: calc(100% - 10px);
  }
  ::selection {
    background:white;
    border-radius: 100px;
  }
  </style>
  </head>

<?php

require __DIR__ . "/vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$weather_key = $_ENV["WEATHER"];

function ktof($k)
{
  $fi = $k * 1.8;
  $fs = $fi - 459.67;
  return $fs;
}

// 

$place = htmlspecialchars($_GET["place"]);

if($place == "minecraft"){
  die("<body><center><br><br><br><div class='res'> </div></center></body></html>");
}

if(! $place)
{
  die("<body><center><br><br><br><div class='res' style='filter:blur(0.2px)'>What are your odds of a day with snow?<br><form>  <br>
  <input type='text' id='place' name='place' placeholder='Lol you need a place' value='New York' class='inp'>
  <input type='submit' value='Find out!' class='btn'>
</form> <br><a style='color:#0f7fff;text-decoration:none;' href='/?place=California'>Example Place</a></div><img src='https://filehoster.codealt1.repl.co/snow3.svg'/></center></body></html>");
}

$headers = array('Accept' => 'application/json');
$request = Requests::get("http://api.openweathermap.org/data/2.5/forecast?q=${place}&appid=${weather_key}", $headers);

$json = json_decode($request->body, true);
$temp = round(ktof($json["list"][1]["main"]["temp"])); // AHAHAHA I HATE JSONOSSONSNONSONSON
$weather = $json["list"][0]['weather'][0]['main'];
if ($weather="Clouds"){$weather="Cloudy";}else if ($weather="Snow"){$weather="Snowy";}
$feelslike = round(ktof($json["list"][1]["main"]["feels_like"]));
$humidity = $json["list"][0]["main"]["humidity"];
$min = round(ktof($json["list"][0]["main"]["temp_min"]));
$max = round(ktof($json["list"][0]["main"]["temp_max"]));
$windSpeed = $json["list"][0]["wind"]["speed"];
$pressure = $json["list"][0]["main"]["pressure"];
$sealevel = $json["list"][0]["main"]["sea_level"];
$visibility = $json["list"][0]["visibility"];
$odds = 0;

/*===========================

Yes, I understand the code under this is bad,
but its sadly the only way to do it.

https://stackoverflow.com/q/20972297/

===========================*/

if($temp < 33)
{
  $odds = $odds + 35;
}else if($temp < 35){
  $odds = $odds + 20;
}else if($temp < 40){
  $odds = $odds + 10;
}else if($temp < 45){
  $odds = $odds + 3;
}else{
  $odds = $odds + 0;
}


if($feelslike < 15)
{
    $odds = $odds + 20;
}

$winter = array("Snowy");

if(in_array($weather, $winter))
{
  $odds = $odds + 30;
}

$imgvar = "<img src='https://filehoster.codealt1.repl.co/snow.svg'/>";

if($odds < 35) {
  $imgvar = "<img src='https://filehoster.codealt1.repl.co/snow.svg'/>";
}else if($odds < 40){
  $imgvar = "<img src='https://filehoster.codealt1.repl.co/snow1.svg'/>";
}else if($odds < 45){
  $imgvar = "<img src='https://filehoster.codealt1.repl.co/snow2.svg'/>";
}else if($odds = 0){
  $imgvar = "<img src='https://filehoster.codealt1.repl.co/snow4.svg'/>";
}else{
  $imgvar = "<img src='https://filehoster.codealt1.repl.co/snow3.svg'/>";
}

echo "<body><br><br><br<br><center><div class='res'>";
if($temp < -300)
{
  echo "Yikes, that isn't a place. Did you spell it wrong?<br><br><a style='color:#0f7fff;text-decoration:none;' href='/'>Try again</a></div><img src='https://filehoster.codealt1.repl.co/snow3.svg'/>";
  die();
}else{
echo "The temperature in ${place} is ${temp} degrees fahrenheit. <br><br> The weather is ${weather}, and it feels like ${feelslike} degrees fahrenheit.";
echo "<br><br> The odds of a day of snow are ${odds}%.<br><br>Other Stats:<br>Humidity: ${humidity}<br>Low: (Fahrenheit) ${min}<br>High: (Fahrenheit) ${max}<br>Wind speed: ${windSpeed} (MPH)<br>Pressure: ${pressure}<br>Sea Level: ${sealevel}<br>Visibility: ${visibility}<br><br>Visibility is calculated out of 10000, so if it is really foggy out, chances are that the Visibility rating would be low.";
echo "<br><br><a style='color:#0f7fff;text-decoration:none;' href='https://discord.com/oauth2/authorize?client_id=793533923824238593&scope=bot'>Get our Discord Bot (WIP)</a>";
echo "</div>";
echo $imgvar;
echo "</center></body></html>";
}
