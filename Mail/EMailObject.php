<?php

namespace Rad\Mail;

/**
 * Description of EMailObject
 *
 * @author Guillaume Monet
 */
final class EMailObject {

    public $to_mail = "";
    public $from_mail = "";
    public $from_name = "";
    public $head = "";
    public $subject = "";
    public $contenu_html = "";
    public $contenu_texte = "";
    public $pj = "";
    public $pj_name = "";
    public $pj_type = "";
    public $array_styles = array();
    public $template = "";
    public $template_name = "";
    public $bcc = "Bcc: exploit_auto@bebe-au-naturel.com<exploit_auto@bebe-au-naturel.com>\n";

    public function __construct($from_mail, $from_name, $subject = "(sans objet)") {
        $this->from_mail = $from_mail;
        $this->from_name = $from_name;
        $this->subject = $subject;
    }

    public function setText($texte) {
        $this->contenu_texte = html_entity_decode($texte);
    }

    public function setHtml($html) {
        $this->contenu_html = $html;
    }

    public function setSubject($texte) {
        $this->subject = $texte;
    }

    public function setPjByContent($content, $nom_fichier, $content_type = "application/x-unknown-content-type") {
        $this->pj_name = $nom_fichier;
        $this->pj_type = $content_type;
        $this->pj = $content;
    }

    public function setPjByFile($file, $nom_fichier) {
        $this->pj_name = $nom_fichier;
        $this->pj = file_get_contents($file);
    }

    public function loadTemplate($template_name) {
        $tp_smarty = new Smarty;
        $tp_smarty->template_dir = bb::$conf->getConfig()->mail->template->dir . "mails/";
        $tp_smarty->compile_dir = bb::$conf->getConfig()->mail->template->compiled;
        $tp_smarty->config_dir = bb::$conf->getConfig()->mail->template->config;
        $tp_smarty->cache_dir = bb::$conf->getConfig()->mail->template->cache;
        if (bb::$conf->getConfig()->url_rewrite == 1) {
            $tp_smarty->register_outputfilter('filter_template');
        }
        $tp_smarty->language = $_SESSION['langue'];
        $this->template = $tp_smarty;
        $this->template_name = $template_name;
    }

    public function send($to_mail, $reply_to = "") {
        $email = "";
        if ($this->template_name != "" && $this->template != "") {
            $this->contenu_html = $this->template->fetch($this->template_name . ".tpl");
        }
        $this->head = "From: " . mb_encode_mimeheader($this->from_name, "UTF-8", "Q") . "<" . $this->from_mail . ">\n";
        $this->head .= $this->bcc;
        if ($reply_to != "") {
            $this->head .= "Reply-To:" . $reply_to . "\n";
        }
        $this->head .= "Date: " . date("D, j M Y G:i:s O") . "\n";
        $this->head .= "MIME-Version: 1.0 \n";
        if (empty($this->contenu_texte)) {
            if (!empty($this->pj)) {
                $this->head .= "Content-Type: multipart/mixed;boundary=MuLtIpArT_BoUnDaRy\n";
                $email .= "--MuLtIpArT_BoUnDaRy\n";
                $email .= "Content-Type: text/html;charset=ISO-8859-1\n";
            } else {
                $this->head .= "Content-Type: text/html;charset=ISO-8859-1\n";
            }
            $email .= $this->contenu_html;
        } else if (empty($this->contenu_html)) {
            if (!empty($this->pj)) {
                $this->head .= "Content-Type: multipart/mixed;boundary=MuLtIpArT_BoUnDaRy\n";
                $email .= "--MuLtIpArT_BoUnDaRy\n";
                $email .= "Content-Type: text/plain;charset=ISO-8859-1\n";
            } else {
                $this->head .= "Content-Type: text/plain;charset=ISO-8859-1\n";
            }
            $email .= $this->contenu_texte;
        } else {
            $this->head .= "Content-Type: multipart/alternative;boundary=MuLtIpArT_BoUnDaRy\n";
            $email .= "--MuLtIpArT_BoUnDaRy\n";
            $email .= "Content-Type: text/plain;charset=ISO-8859-1\n";
            $email .= $this->contenu_texte;
            $email .= "\n\n--MuLtIpArT_BoUnDaRy\n";
            $email .= "Content-Type: text/html;charset=ISO-8859-1\n";
            $email .= $this->contenu_html;
            if (empty($this->pj)) {
                $email .= "\n--MuLtIpArT_BoUnDaRy--";
            }
        }
        if (!empty($this->pj)) {
            $email .= "\n--MuLtIpArT_BoUnDaRy\n";
            $email .= 'Content-Type: ' . $this->pj_type . ';name="' . $this->pj_name . '"' . "\n";
            $email .= 'Content-Transfer-Encoding: base64' . "\n";
            $email .= 'Content-Disposition:attachement;filename="' . $this->pj_name . '"' . "\n\n";
            $email .= chunk_split(base64_encode($this->pj)) . "\n";
            if (!eregi("\n$", $this->pj)) {
                $email .= "\n";
            }
            $email .= "--MuLtIpArT_BoUnDaRy--\n";
        }
        $this->to_mail = $to_mail;

        if (mail($this->to_mail, mb_encode_mimeheader($this->subject, "UTF-8", "Q", ""), $email, $this->head, "-f" . $this->from_mail)) {
            return true;
        } else {
            return false;
        }
    }

}

?>