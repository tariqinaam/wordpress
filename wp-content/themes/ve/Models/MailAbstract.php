<?php
/**
 * Build an email message using a template, and some variables, and send it to someone
 */
class sendTplMail
{
    private $temp_dir;
    private $subject;
    private $recipient;
    private $sender;
    private $fields;
    private $template;
    
    public function __construct()
    {
        $this->temp_dir = abs_packages.'/alert/_blocks/';
        return $this;
    }
    
    public function setParam($key,$val){
        $this->{$key} = $val;
        return $this;
    }
    
    /**
     * Include a template email
     * @param string $text
     * @param array $data
     * @return string 
     */
    
    private function get_template($file){
        ob_start();
        foreach($this->fields as $key => $var){
            $this->{$key} = $var;
        }
        if(file_exists($file))
        include $file;
        
        return ob_get_clean();
    }

    /**
     * Get email text from options, and put in data
     * @return string 
     */
    public function content()
    {
	// Get message
	return $this->get_template($this->temp_dir.$this->template);
    }
    
    /**
     * Build the message and send it to this email.
     * @param string $email 
     */
    public function send()
    {
        
	// Make the message
        $email = new KM_Mailer(MAIL_HOSTNAME, '25');
        //$email->contentType ='text';
        $sent = $email->send($this->sender,$this->recipient,$this->subject,$this->content());

        //log successfully sent email
        if(!$sent){
            return false;
            //$log->log("Email not sent to: $this->recipient",$this->subject,3);
        }else return true;
    }
    
}

?>