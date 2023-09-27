<?php

class Imaps
{
    private $mbox = '';
    private $htmlmsg = '';
    private $plainmsg = '';
    private $charset = '';
    private $attachments = array();
    private $unread;
    public
    function __get($name)
    {
        if($name == 'mail') return $this->unread;
        else return null;
    }
    public
    function getmail()
    {
        return $this->unread;
    } /* backwards compatibility for php4*/
    public
    function imaps($host, $login, $pwd)
    {
        /* backwards compatibility for php 4, __constructor*/
        $messages = array();
        // $folder = "/imap/ssl";
        // $folder = "{imap/ssl}INBOX";
        $folder = "INBOX";
        // $folder = "[Gmail]/&BCEEPwQwBDw - ";
        $this->mbox = imap_open("{$host}{$folder}", $login,$pwd) or die(imap_last_error());
        // $this->mbox = imap_open("{imap.rambler.ru:993/imap/ssl}INBOX", "register.inc.by.email@rambler.ru", "Xnjpfgfhjkm!!!11") or die(imap_last_error());
        $arr = imap_search($this->mbox, 'UNSEEN');
        if($arr !== false)
        {
            foreach($arr as $i)
            {
                $attachments = array();
                $headerArr = imap_headerinfo($this->mbox,$i);
                $msghead   = imap_header($this->mbox, $i, 255, 255);
                $elements  = imap_mime_header_decode($msghead->fetchsubject);
                $subj      = '';
                $cc        = array();
                setlocale(LC_CTYPE, 'POSIX');
                for($p = 0; $p < count($elements); $p++)
                    //$subj .= $elements[$p]->text;
                    $subj .= $elements[0]->charset!=='default'?iconv($elements[0]->charset, 'UTF-8//IGNORE', $elements[$p]->text):$elements[$p]->text;
                $sender = $headerArr->from[0]->mailbox . "@" . $headerArr->from[0]->host;
                if(isset($headerArr->cc) AND !empty($headerArr->cc)){
                    foreach ($headerArr->cc as $ccarr){
                        $cc[] = $ccarr->mailbox . "@" . $ccarr->host;
                    }
                    $cc = implode(',' , $cc);
                }
                $mailArr =
                    array(
                        'sender' => $headerArr->from[0]->mailbox . "@" . $headerArr->from[0]->host,
                        'to'     => $headerArr->to[0]->mailbox . "@" . $headerArr->to[0]->host,
                        'date'   => $headerArr->date,
                        'size'   => $headerArr->Size,
                        'subject'=> $headerArr->subject,
                        'id'     => $i,
                    );
                $this->getmsg($i);
                imap_setflag_full($this->mbox, $i, "\\Seen");
                $messages[] = array('id'     => $mailArr['id'],'sender'=>$sender,'subject'=>$subj, 'cc' => $cc, 'charset'=>$this->charset,'plain'  =>$this->plainmsg,'html'   =>$this->htmlmsg, 'attach'=>$this->attachments);
            }
            $this->unread = $messages;
            unset($messages);
        }
        else
        {
            $this->unread = false;
        }
        imap_close($this->mbox);
    }

    private
    function decode($enc)
    {
        $parts = imap_mime_header_decode($enc);
        $str   = '';
        for($p = 0; $p < count($parts); $p++)
        {
            $ch   = $parts[$p]->charset;
            $part = $parts[$p]->text;
            if($ch !== 'default') $str .= mb_convert_encoding($part,'UTF-8',$ch);
            else $str .= $part;
        }
        return $str;
    }
    private
    function returnHeaderInfoObj($mid)
    {
        return @imap_headerinfo($this->mbox,$mid);
    }
    public
    function deleteMail($host, $login, $pwd, $mid)
    {
        $folder = "INBOX";
        //$folder = "[Gmail]/&BCEEPwQwBDw - ";
        $this->mbox = @imap_open("{$host}{$folder}", $login,$pwd) or die(imap_last_error());
        imap_delete($this->mbox, $mid);
        imap_expunge($this->mbox);
    }

    private
    function getmsg($mid)
    {
        $this->htmlmsg = $this->plainmsg = $this->charset = '';
        $this->attachments = array();

        $s = imap_fetchstructure($this->mbox,$mid);
        if(!isset($s->parts)){
            $this->getpart($mid,$s,0);
        }else{
            foreach($s->parts as $partno0=>$p)

                $this->getpart($mid,$p,$partno0+1);
        }
    }

    private
    function getpart($mid,$p,$partno)
    {
        $data = ($partno)? imap_fetchbody($this->mbox,$mid,$partno): imap_body($this->mbox,$mid);
        if($p->encoding == 4){
            $data = quoted_printable_decode($data);
        } elseif($p->encoding == 3){
            $data   = base64_decode($data);
        }
        $params = array();
        if($p->parameters)
            foreach($p->parameters as $x)
                $params[ strtolower( $x->attribute ) ] = $x->value;
        if(isset($p->dparameters))
            foreach($p->dparameters as $x)
                $params[ strtolower( $x->attribute ) ] = $x->value;

        if((isset($params['filename']) AND !empty($params['filename'])) || isset($params['name']))
        {
            $filename = (isset($params['filename']))? $params['filename'] : $params['name'];
            $f = $this->decode($filename);
            $this->attachments[] = array('id' => $p->id, 'filename' => $f, 'data' => $data);  // если 2 файла c одним именем - тут баг. TODO
        }
        elseif($p->type == 0 && $data)
        {
            if(strtolower($p->subtype) == 'plain')
                $this->plainmsg .= trim($data) ."\n\n";
            else
                $this->htmlmsg .= $data ."<br><br>";
            $this->charset = (isset($params['charset']))?$params['charset']:'utf-8';
        }
        elseif($p->type == 2 && $data)
        {
            $this->plainmsg .= trim($data) ."\n\n";
        }

        if(isset($p->parts))
        {
            foreach($p->parts as $partno0=>$p2)
                $this->getpart($mid,$p2,$partno.'.'.($partno0 + 1));
        }
    }
}