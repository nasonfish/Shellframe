<?php

class Handler {

    public $redis;

    public function __construct(){
        require 'Predis/Autoloader.php';
        Predis\Autoloader::register();
        $predis = new Predis\Client(array('port'=>6381));
        $this->redis = new Redis($predis);
    }

    public function entry($id){
        return new Entry($id, $this->redis);
    }
}

class Redis {

    /*
     * Data Saved:
     *
     * id, date, title, text, tags. (Comments?)
     */

    public $predis;
    public $md;

    public function __construct(Predis\Client $predis){
        $this->predis = $predis;
        require 'Markdown/Michelf/MarkdownExtra.php';
        $this->md = new \Michelf\MarkdownExtra;
    }

    public function page_get($id, $data){
        if(strtolower($data) === 'tags'){
            $cmd = new Predis\Command\SetMembers;
        } else {
            $cmd = new Predis\Command\StringGet;
        }
        $cmd->setRawArguments(array('entry:' . $id . ':' . $data));
        return $this->predis->executeCommand($cmd);
    }

    public function create($title, $text, $tags){
        $cmd = new Predis\Command\KeyExists;
        $cmd->setRawArguments(array('next'));
        if(!$this->predis->executeCommand($cmd)){
            $cmd = new Predis\Command\StringSet;
            $cmd->setRawArguments(array('next', 1));
            $this->predis->executeCommand($cmd);
            $id = 0;
        } else {
            $cmd = new Predis\Command\StringGet;
            $cmd->setRawArguments(array('next'));
            $id = $this->predis->executeCommand($cmd);
            $cmd = new Predis\Command\StringIncrement;
            $cmd->setRawArguments(array('next'));
            $this->predis->executeCommand($cmd);
        }

        $cmd = new Predis\Command\StringSet;
        $cmd->setRawArguments(array('entry:' . $id . ':title', $title));
        $this->predis->executeCommand($cmd);
        $cmd->setRawArguments(array('entry:' . $id . ':text', $text));
        $this->predis->executeCommand($cmd);
        $cmd->setRawArguments(array('entry:' . $id . ':date', date('F j, Y, g:i a')));
        $this->predis->executeCommand($cmd);
        $cmd = new Predis\Command\SetAdd;
        $this->tag_edit($id, $tags);
        $cmd->setRawArguments(array('entries', $id));
        $this->predis->executeCommand($cmd);
        return $id;
    }

    public function edit($id, $title, $text, $tags){
        $cmd = new Predis\Command\StringSet;
        $cmd->setRawArguments(array('entry:' . $id . ':title', $title));
        $this->predis->executeCommand($cmd);
        $cmd->setRawArguments(array('entry:' . $id . ':text', $text));
        $this->predis->executeCommand($cmd);
        //$cmd->setRawArguments(array('entry:' . $id . ':date', date("F j, Y, g:i a")));
        $this->tag_edit($id, $tags);
    }

    public function delete($id){
        $cmd = new Predis\Command\KeyDelete;
        $cmd->setRawArguments(array('entry:' . $id . ':title'));
        $this->predis->executeCommand($cmd);
        $cmd->setRawArguments(array('entry:' . $id . ':text'));
        $this->predis->executeCommand($cmd);
        $cmd->setRawArguments(array('entry:' . $id . ':date'));
        $this->predis->executeCommand($cmd);
        $this->tag_edit($id, array());
        $cmd = new Predis\Command\SetRemove;
        $cmd->setRawArguments(array('entries', $id));
        $this->predis->executeCommand($cmd);
    }

    private function tag_edit($id, $new = array()){
        // tags = (tag, tag, tag)
        // tag:<tag> = (1, 2, 3)
        // entry:<id>:tags = (tag, tag, tag)
        $cmd = new Predis\Command\SetMembers;
        $cmd->setRawArguments(array('entry:' . $id . ':tags'));
        $before = $this->predis->executeCommand($cmd);
        $cmd = new Predis\Command\SetRemove;
        foreach($before as $tag){
            $cmd->setRawArguments(array('tags', $tag));
            $this->predis->executeCommand($cmd);
            $cmd->setRawArguments(array('tag:' . $tag, $id));
            $this->predis->executeCommand($cmd);
        }
        $cmd = new Predis\Command\SetAdd;
        foreach($new as $tag){
            $cmd->setRawArguments(array('tags', $tag));
            $this->predis->executeCommand($cmd);
            $cmd->setRawArguments(array('tag:' . $tag, $id));
            $this->predis->executeCommand($cmd);
        }
        $cmd = new Predis\Command\KeyDelete;
        $cmd->setRawArguments(array('entry:' . $id  . ':tags'));
        $this->predis->executeCommand($cmd);
        $cmd = new Predis\Command\SetAdd;
        foreach($new as $id=>$val){
            if($val === ''){
                unset($new[$id]);
            }
        }
        sort($new);
        foreach($new as $n){
            $cmd->setRawArguments(array('entry:' . $id . ':tags', $n));
            $this->predis->executeCommand($cmd);
        }
    }

    public function all($amount = false){
        if($amount) return $this->all_amount();
        $cmd = new Predis\Command\SetMembers();
        $cmd->setRawArguments(array('entries'));
        return array_reverse($this->predis->executeCommand($cmd));
    }
    private function all_amount(){
        $cmd = new Predis\Command\SetCardinality;
        $cmd->setRawArguments(array('entries'));
        return $this->predis->executeCommand($cmd);
    }
    public function random(){
        $cmd = new Predis\Command\SetRandomMember();
        $cmd->setRawArguments(array('entries'));
        return new Entry($this->predis->executeCommand($cmd), $this);
    }
    public function tagged($tag, $amt = false){
        if($amt) return $this->tagged_amt($tag);
        $cmd = new Predis\Command\SetMembers();
        $cmd->setRawArguments(array('tag:' . $tag));
        $ret = array();
        foreach($this->predis->executeCommand($cmd) as $entry){
            $ret[] = new Entry($entry, $this);
        }
        return $ret;
    }
    private function tagged_amt($tag){
        $cmd = new Predis\Command\SetCardinality;
        $cmd->setRawArguments(array('tag:' . $tag));
        return $this->predis->executeCommand($cmd);
    }
    public function tags(){
        $cmd = new Predis\Command\SetMembers();
        $cmd->setRawArguments(array('tags'));
        return $this->predis->executeCommand($cmd);
    }
}

class Entry {

    public $id;
    public $redis;

    public function __construct($id, Redis $redis){
        $this->id = $id;
        $this->redis = $redis;
    }


    public function format($text){
        //if(!class_exists('Michelf\MarkdownExtra')){
        //    require 'Markdown/Michelf/MarkdownExtra.php';
        //}
        $text = $this->custom_formats($text);
        return $this->redis->md->defaultTransform($text);
    }

    private function custom_formats($text){
        $langs = array('c', 'shell', 'java', 'd',
            'coffeescript', 'generic', 'scheme', 'javascript',
            'r', 'haskell', 'python', 'html',
            'smalltalk', 'csharp', 'go', 'php',
            'ruby', 'lua', 'css', 'terminal'
        );
        foreach($langs as $lang){
            $text = str_replace('{'.$lang.'}', '<pre data-language="'.$lang.'">', $text);
            $text = str_replace('{/'.$lang.'}', '</pre>', $text); // bad :/
        }
        return $text;
    }

    public function getID(){
        return $this->id;
    }

    public function getTitle(){
        return $this->redis->page_get($this->id, 'title');
    }
    public function getText(){
        return $this->format($this->redis->page_get($this->id, 'text'));
    }

    public function getDate(){
        return $this->redis->page_get($this->id, 'date');
    }
    public function getTags(){
        return $this->redis->page_get($this->id, 'tags');
    }

    public function __toString(){
        return json_encode(
            array(
                'id' => $this->id,
                'title' => $this->getTitle(),
                'text' => $this->getText(),
                'date' => $this->getDate(),
                'tags' => $this->getTags()
            )
        );
    }

    public function show($short = false){
        $ret = '<div class="entry">';
        $ret .= '<h2 class="title"><a href="/v/%s/">%s</a></h2>';
        $ret .= '<div class="text">%s</div>';
        $ret .= '<hr/>';
        $ret .= '<p class="sign">By %s, on %s</p>';
        $ret .= '<ul class="tags blue">';
        foreach($this->getTags() as $tag){
            $ret .= sprintf('<li><a href="/tag/%s/">%s <span>%s</span></a></li>', $tag, $tag, $this->redis->tagged($tag, true));
        }
        $ret .= '</ul>';
        $ret .= '</div>';
        $text = $this->getText();
        if($short){
            if(strlen($text) > 300){
                $text = substr($text, 0, 300) . '<a href="/v/'.$this->id.'/">(Read more)</a>';
            }
        }
        return sprintf($ret, $this->id, $this->getTitle(), $text, get('author'), $this->getDate());
    }
}
