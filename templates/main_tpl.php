<!DOCTYPE html>
<html>
<head>
    <title><?=$this->title()?> - <?=get('main:title');?></title>
    <link href='http://fonts.googleapis.com/css?family=Share+Tech' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Rokkitt' rel='stylesheet' type='text/css'>
    <link type="text/css" rel="stylesheet" href="/libs/Rainbow/my_rainbows.css"/>
    <link type="text/css" rel="stylesheet" href="/libs/Rainbow/github.css"/>
    <link type="text/css" rel="stylesheet" href="/libs/sliding-tags/12-sliding-tags/css/style.css"/>
    <link type="text/css" rel="stylesheet" href="/app.css"/>
    <?php $this->css(); ?>
</head>
<body>
<div class="content">
    <div class="head">
        <table class="tabletop">
            <tr>
                <td>
                    <h1 class="i"><a class="title" href="/">Blog</a> </h1><span>by nasonfish</span>
                </td>
                <td><a href="/all/">Recent Posts</a></td>
                <td><a href="/random/">Random Post</a></td>
                <td><a href="/tags/">View tags</a></td>
            </tr>
        </table>
        <hr/>
        <?php $this->head(); ?>
    </div>
    <div class="body">
         <?php $this->page(); ?>
    </div>
    <div class="foot">
         <?php $this->foot(); ?>
    </div>
</div>
<footer>
    <?php $this->js(); ?>
    <script src="/app.js"></script>
    <script src="/libs/Rainbow/rainbow-custom.min.js"></script>
    <script src="/libs/Rainbow/my_rainbows.js"></script>
</footer>
</body>
</html>
