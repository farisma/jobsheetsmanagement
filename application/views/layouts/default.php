<?php
// Turn off all error reporting
//error_reporting(E_ALL & ~E_NOTICE);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
   <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title><?php echo $template['title'] ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php echo $template['metadata'] ?>
	<!--<link rel="manifest" href="https://theandongroup.com/manifest.json">
  <script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async></script>
  <script>
    var OneSignal = window.OneSignal || [];
    OneSignal.push(["init", {
      appId: "1d60bf43-11bd-4f4d-9aaf-1e735a2fe477",
      autoRegister: false,
      notifyButton: {
        enable: true /* Set to false to hide */
      },	   
	  safari_web_id: 'web.onesignal.auto.4b99c5db-a7c9-461a-8333-facb0838095d'
    }]);
  </script>-->
  </head>

  <body class="<?php echo $body_class ?>">
    <?php echo $template['partials']['header'] ?>

    <div class="container">

      <?php echo $template['partials']['flash_messages'] ?>

      <?php echo $template['body'] ?>

      <?php echo $template['partials']['footer'] ?>

    </div> <!-- /container -->
    
  </body>
</html>