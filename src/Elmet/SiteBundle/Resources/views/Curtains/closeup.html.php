<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Curtain Range</title>
<link href="/css/elmet.css" rel="stylesheet" type="text/css" />
<link href="/css/nav.css" rel="stylesheet" type="text/css" />
<link href="/css/type.css" rel="stylesheet" type="text/css" />
<link rel="icon" HREF="/img/favicon.ico" type="image/x-icon">
<link rel="shortcut icon" HREF="/img/favicon.ico" type="image/x-icon"> 
<script type="text/javascript" src="/js/swfobject.js"></script>
</head>

<body>
        <div id="top">
                <div id="top-inner">
                        <img SRC="/img/logo.gif" alt="elmet curtains" class="logo"/>
                        <img SRC="/img/tagline.gif" alt="curtains and bedding" class="tagline" />
                </div>
        </div>

        <div id="container">
          <div id="content">
            <div id="popup">
  <img width="500" SRC="/img/products/<?php echo $curtainColour->getSwatchFilePath()?>" alt="<?php echo $curtainDesign->getName()?>"/>
  <p id="swatch-label"><?php echo $curtainDesign->getName()." - ".$curtainColour->getName()?></p>

  

  <a id="popup-close" style="float:left;" href="javascript:close();">Close</a>
</div>
          </div>
        </div>

</body>
</html>
