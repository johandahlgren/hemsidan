<?php
    header("Content-Type: text/html; charset=UTF-8");
    header('Access-Control-Allow-Origin: *');
    ob_start('ob_gzhandler');
    session_start();
    $_SESSION["rootPath"] = $_SERVER["DOCUMENT_ROOT"] . dirname($_SERVER['PHP_SELF']) . "/";
    include_once "cms/core.php";
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
    <head>
        <title><?php print getConfigProperty("siteTitle") ?></title>
        <meta name="description" content="<?php print getConfigProperty("siteDescription") ?>" />
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="msapplication-TileColor" content="#00ff00"/>
        <meta name="msapplication-TileImage" content="style/images/favicon.png"/>
        <link rel="icon" type="image/png" href="style/images/favicon.png" />
        <link rel="stylesheet" type="text/css" href="style/cssMerger.php" media="screen" />
        <script type="text/javascript" src="site/javascript/javascriptMerger.php"></script>
    </head>
    <body>
        <?php 
            $selectedCategory        = $_REQUEST["selectedCategory"];
            if ($selectedCategory != null && $selectedCategory != "")
            {
                $numberOfEntities = countEntities(array("news"), array($selectedCategory), getConfigProperty("siteId"));
            }
            else
            {
                $numberOfEntities = countEntities(array("news"), null, getConfigProperty("siteId"));
            }
        ?>

        <input id="numberOfEntities" type="hidden" value="<?php print $numberOfEntities ?>">
                
        <?php renderCmsComponent("cmsMenu"); ?>
        <div id="containerDiv">
            <?php renderComponent("sliderHeader"); ?>
            <div id="leftCol">
                <?php 
                    renderComponent("categoryList");
                ?>
            </div>
            <div id="middleDiv">
                <?php
                    if ($selectedCategory != null && $selectedCategory != "")
                    {
                        $selectedCategoryName = getCategoryName($selectedCategory);
                        ?>
                            <div class="categorychoice">
                                <div class="categoryChoiceText">
                                    Visar inl&auml;gg fr&aring;n kategorin: <?php print $selectedCategoryName ?> (<?php print $numberOfEntities ?> st)
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                </div>
                                <a class="showAll" href="index.php">Visa alla inl&auml;gg</a>
                            </div>
                        <?php
                    }
                
                    renderComponent("newsListAjax");
                ?>
                
                <div id="loadingIndicator" class="entry news"></div>
            </div>
            <div id="returnToTop" onclick="scrollToTop();">Tillbaka till toppen</div>
        </div>
        <div id="indexIndicator"></div>
        <div id="adminDiv"></div>
        <div id="messageDiv" onclick="hideDiv('messageDiv');"></div>        

        <script type="text/javascript">
            var fbTarget;
            var _gaq = _gaq || [];
            _gaq.push(['_setAccount', 'UA-1571967-8']);
            _gaq.push(['_trackPageview']);

            (function() 
            {
            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
            })();
        
            $(document).ready(function() 
            {
                ajaxLoadNews(true, "<?php print $_REQUEST["searchString"] ?>");
            });
        </script>
    </body>
</html>
<?php
    ob_end_flush();
?>
