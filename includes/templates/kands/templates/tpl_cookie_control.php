<?php
if ($request_type == 'NONSSL') { ?>
<script src="http://www.geoplugin.net/javascript.gp" type="text/javascript"></script>
<?php } else { ?>
<script src="https://ssl.geoplugin.net/javascript.gp" type="text/javascript"></script>
<?php } ?>
<script type="text/javascript">//<![CDATA[
  cookieControl({
      introText:'<?php echo COOKIE_CONTROL_INTROTEXT;?>',
      fullText:'<p><?php echo COOKIE_CONTROL_FULLTEXT;?> <a href="<?php echo HTTP_SERVER . DIR_WS_CATALOG;?>index.php?main_page=privacy">Privacy Policy</a>.</p>',
      cookieOnText:'<?php echo COOKIE_CONTROL_COOKIES_ON;?>',
      cookieOffText:'<?php echo COOKIE_CONTROL_COOKIES_OFF;?>',
      position:'<?php echo COOKIE_CONTROL_POSITION;?>',
      shape:'<?php echo COOKIE_CONTROL_SHAPE;?>',
      theme:'<?php echo COOKIE_CONTROL_THEME;?>',
      startOpen:<?php echo COOKIE_CONTROL_STARTOPEN;?>, // true , false
      autoHide:<?php echo COOKIE_CONTROL_AUTOHIDE;?>, // Time in miliseconds
      protectedCookies: [<?php echo COOKIE_CONTROL_PROTECTEDCOOKIES;?>], //list the cookies you do not want deleted ['analytics', 'twitter']
      consentModel:'<?php echo COOKIE_CONTROL_CONSENTMODEL;?>', // information_only , implicit , explicit
      subdomains:<?php echo COOKIE_CONTROL_SUBDOMAINS;?>, // true , false
<?php if ( COOKIE_CONTROL_HIDE == 'true' ) { ?>
      onAccept:function(cc){ccAddAnalytics();cc.setCookie('civicShowCookieIcon', 'no');$('#ccc-icon').hide();},
<?php } else { ?>
      onAccept:function(){ccAddAnalytics();},
<?php } ?>
      onReady:function(){},
      onCookiesAllowed:function(){ccAddAnalytics();},
      onCookiesNotAllowed:function(){},
      countries:'<?php echo COOKIE_CONTROL_COUNTRIES;?>' // Or supply a list ['United Kingdom', 'Greece']
      });
<?php if ($request_type == 'NONSSL') { ?>
      function ccAddAnalytics() {
        jQuery.getScript("http://www.google-analytics.com/ga.js", function() {
          var GATracker = _gat._createTracker('<?php echo COOKIE_CONTROL_GOOGLE_ANALYTICS ; ?>');
          GATracker._trackPageview();
        });
<?php } else { ?>
      function ccAddAnalytics() {
        jQuery.getScript("https://www.google-analytics.com/ga.js", function() {
          var GATracker = _gat._createTracker('<?php echo COOKIE_CONTROL_GOOGLE_ANALYTICS ; ?>');
          GATracker._trackPageview();
        });
<?php } ?> 
      }
   //]]>
</script>