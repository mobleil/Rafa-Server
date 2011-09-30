<?php
    $key = "this is a secret key";
    $input = <<<EOT
<rafa version="1.2">
 <head>
  <uuid>advertise</uuid>
 </head>
 <body>
  <rule name="list">
   <method>index</method>
   <title>About Us</title>

   <elements>
    <item>
     <name>Tukuya</name>
     <desc>Tukuya is Indonesia Mobile Market Application. With Tukuya we can find many application that more suitable to the indonesia market.</desc>
    </item>
    <item>
     <name>Startup Guide</name>

     <desc>Just click your [left][button] Start, then select Market. Inside market you can view all application build with rafa, please select on by move your [up] or [down] then click [action][button].</desc>
    </item>
   </elements>
  </rule>
  <rule name="list">
   <method>index</method>
   <title>How To</title>

   <help>Copyright PT. Mobile Solution @2011</help>
   <elements>
    <item>
     <name>Market</name>
     <desc>All application inside Tukuya, like: cineplex, megaplex, lionair, detik, etc..</desc>
    </item>
    <item>

     <name>My Apps</name>
    </item>
    <item>
     <name>My Apps Show</name>
     <desc>List all you application, not all tukuya application.</desc>
    </item>
    <item>

     <name>My Apps Add</name>
     <desc>Add current application to your application.</desc>
    </item>
    <item>
     <name>Run</name>
     <desc>Run directly your desire application, only by typing application name.</desc>
    </item>

    <item>
     <name>Shutdown</name>
     <desc>Quit from tukuya application.</desc>
    </item>
   </elements>
  </rule>
 </body>
</rafa>
EOT;

    $td = mcrypt_module_open('rijndael-256', '', 'ctr', '');
    $iv = mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
    mcrypt_generic_init($td, $key, $iv);
    $encrypted_data = mcrypt_generic($td, $input);
    mcrypt_generic_deinit($td);
    mcrypt_module_close($td);
	
	echo $encrypted_data;
?>
