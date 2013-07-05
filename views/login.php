<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php print $this->registry->titleApplication; ?></title>
        <link href="<?php print $this->registry->UrlSiteCMS . $this->registry->css; ?>style_.css" rel="stylesheet" type="text/css" />
        <script language="JavaScript" type="text/javascript" src="<?php print $this->registry->UrlSiteCMS . $this->registry->js; ?>functions.js"></script>

    </head>

    <body>

        <form id="loginindex" name="login" method="post" action="<?php print $this->registry->UrlSiteCMS . "index.php?rt=auth/login"; ?>">

            <div class="l_left">
                <div class="form_campo" style="width: 350px;">
                    <div class="form_name">Email</div>
                    <div class="form_input"><input type="text" name="user" id="user" value="" onfocus="seleziona(this);" onblur="seleziona();" style="width: 320px"/></div>
                </div>
                <div class="form_campo" style="width: 350px; margin-top: 15px;">
                    <div class="form_name">Password</div>
                    <div class="form_input"><input type="password" name="pass" id="pass" value="" onfocus="seleziona(this);" onblur="seleziona();" style="width: 320px" /></div>
                </div>
                <div class="clearboth"></div>
                <div class="btn_testo_cont" style="width: 345px; margin-top: 15px;">
                    <div class="floatright spazio"><a href="#" onclick="document.login.submit();" title="Entra" class="btn_entra"></a></div>
                </div>
            </div>
            <div class="l_right">
                Inserisci<br /><strong class="color_red">Nome Utente</strong> e <strong class="color_red">Password</strong><br /><br /><br /><span style="font-size: 11px;"></span><br /><?php print $error; ?>
            </div>
            <input type="submit" name="sub" id="sub" style="display: none" />
        </form>



    </body>
</html>
