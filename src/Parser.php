<?php

namespace Kosatyi\Localize;

class Parser {

    private $type    = 'LC_MESSAGES';
    private $domain  = 'messages';

    private $locales = array();
    private $output  = array();
    private $files   = array();

    private $options = array(
        'target'  => '.locales',
        'sources' => array('.'),
        'locales' => array('en')
    );
    public function __construct( $options = array() ){
        $this->options = array_merge($this->options,$options);
    }
    public function initialize(){
        foreach($this->options['locales'] as $locale)
        {
            $this->locales[$locale] = $this->options['target'].'/'.$locale.'/'.$this->type;
            $this->createDirectory($this->locales[$locale]);
        }
        foreach($this->options['sources'] as $dir) {
            $this->findFiles($dir);
        }
        return $this->generate();
    }
    protected function findFiles($directory){
        $directory = new RecursiveDirectoryIterator($directory);
        $iterator  = new RecursiveIteratorIterator($directory);
        $match     = RecursiveRegexIterator::GET_MATCH;
        $cursor    = new RegexIterator($iterator, '/^.+\.php$/i', $match);
        foreach($cursor as $item){
            $this->files[] = $item[0];
        }
    }
    protected function createDirectory($structure){
        if(is_dir($structure)) return(TRUE);
        if(!mkdir($structure, 0777, true))
            die('directory create fail');
    }
    public function generate(){
        $domain = $this->domain;
        $this->output = array();
        $this->execute('pwd');
        $this->execute('whoami');
        foreach($this->locales as $locale=>$path)
        {
            $potfile = sprintf('%s/%s.pot',$path,$domain);
            $pofile  = sprintf('%s/%s.po',$path,$domain);
            $mofile  = sprintf('%s/%s.mo',$path,$domain);
            $this->createPotFile($potfile);
            $this->mergePoFile($pofile,$potfile);
            $this->compileMoFile($pofile,$mofile);
            $this->removePotFile($potfile);
        }
        return $this->output;
    }
    protected function createPotFile($potfile){
        $files   = join(' ',$this->files);
        $command = sprintf('xgettext --force-po --from-code=UTF-8 -o %s %s',$potfile,$files);
        $this->execute($command);
    }
    protected function mergePoFile($pofile,$potfile){
        if(!file_exists($pofile)){
            $command = sprintf('cp %s %s',$potfile,$pofile);
        } else {
            $command = sprintf('msgmerge -q -U --no-fuzzy-matching --previous %s %s',$pofile,$potfile);
        }
        $this->execute($command);
    }
    protected function compileMoFile($pofile,$mofile){
        $command = sprintf('msgfmt %s -o %s',$pofile,$mofile);
        $this->execute($command);
    }
    protected function removePotFile($potfile){
        $command = sprintf('rm -f %s',$potfile);
        $this->execute($command);
    }
    protected function execute( $command ){
        $output = shell_exec( $command );
        $this->output[] = array($command,$output);
    }
}
