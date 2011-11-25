<?php
/**
 * @package         WebDig
 * @author          Emerson Rocha Luiz - emerson at webdesign.eng.br - http://fititnt.org
 * @copyright       Copyright (C) 2011 Webdesign Assessoria em Tecniligia da Informacao. All rights reserved.
 * @license         GNU General Public License version 3. See license-gpl3.txt
 * @license         Massachusetts Institute of Technology. See license-mit.txt
 * @version         0.1alpha
 * 
 */
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE); //better debug
include_once 'WebserviceDebug.php';

$wd = new WebserviceDebug();
$wd->initialize($_POST);
?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>WebserviceDebug</title>
    </head>
    <body>
        <script>
            /* Show and Hide element
             * 
             */
            function sh(target){
                if (document.getElementById(target).style.display == "none"){
                    document.getElementById(target).style.display = "inherit";
                } else {
                    document.getElementById(target).style.display = "none";
                }        
            }    
        </script>
        <?php if (isset($wd->result) && $wd->result != 0) : ?>
            <fieldset>
                <legend>Excecucao</legend>
                URL: <a href="<?php echo $wd->data->url; ?>" target="_blank"><?php echo $wd->data->url; ?></a>
                <h3><a href="#" onclick="sh('data-info')"> Informacoes gerais</a></h3>
                <div id="data-info" style="display:none">
                    <?php
                    foreach ($wd->data->info AS $key => $item) {
                        echo '<strong>' . $key . '</strong>: ' . $item . '<br />';
                    }
                    ?>
                </div>
                <fieldset>
                    <legend>Enviado</legend>
                    <h3>Cabeçalho</h3>
                    <pre><?php echo $wd->data->request->header; ?></pre>
                    <h3>Conteudo</h3>
                    <pre><?php echo $wd->data->request->content; ?></pre>
                </fieldset>
                <fieldset>
                    <legend>Recebido</legend>
                    <h3>Cabeçalho</h3>
                    <pre><?php echo $wd->data->response->header; ?></pre>
                    <h3>Conteudo</h3>
                    <pre><?php echo $wd->data->response->content; ?></pre>
                    <h3><a href="#" onclick="sh('data-contentraw')">Conteudo (RAW)</a></h3>
                    <pre id="data-contentraw"><?php echo $wd->data->response->contentraw; ?></pre>
                </fieldset>
            </fieldset>
        <?php endif; ?>    

        <form action="index.php" method="POST">
            <h3>URL</h3>
            <input type="text" name="url" size="100" value="<?php echo isset($wd->data->url) ? $wd->data->url : ''; ?>"/>
            <h3>Header</h3>
            <textarea name="header" cols="100" rows="3"><?php echo isset($wd->data->header) ? $wd->data->header : ''; ?></textarea>
            <h3>Content</h3>
            <textarea name="content" cols="100" rows="20"><?php echo isset($wd->data->content) ? $wd->data->content : ''; ?></textarea>
            <br />
            <input type="submit" value="send" />
        </form>
        <?php print_r($wd->data->header);
        var_dump( $wd->getLinesToArray( $wd->data->header ) );?>
    </body>
</html>