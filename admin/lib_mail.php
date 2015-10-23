<?php
/*
Library for work with mail. V1.4. Copyright (C) 2003 - 2008 Richter
Additional information: http://wpdom.com
*/

class Mail_sender {
  var $from_addr;	// Sender address
  var $from_name;	// Sender name
  var $to;		// Address of a recipient or array of these addresses
  var $headers;
  var $body;		// Main message
  var $body_type;	// Main message encoding (text/plain or text/html)
  var $priority;	// Letter priority;

  function Mail_sender()
  {
    $this -> from_addr = "";
    $this -> from_name = "";
    $this -> to = "";
    $this -> body = "";
    $this -> body_type = 'text/plain';
    $this -> headers = Array();
    $this -> subj = "";
    $this -> priority = 3;
  }

  // Attachs some content
  function attach_content($file_name = '', $file_content, $encoding_type = 'application/octet-stream', $in_letter_id = FALSE)
  {
    $this -> headers[] = array(
    "name" => $file_name,
    "content" => $file_content,
    "encode" => $encoding_type,
    "cid" => $in_letter_id
    );
  }

  // Builds a part of letter
  function build_part($header)
  {
    $cnt = $header["content"];
    $cnt = chunk_split(base64_encode($cnt));
    $encoding = "base64";
    if ($header["encode"]=="text/html" or $header["encode"]=="text/plain") {
      $charset = '; charset="UTF-8"';
    }
    return "Content-Type: ".$header["encode"].
    ($header["name"]? "; name = \"".$header["name"]."\"" : "")."$charset\nContent-Transfer-Encoding: $encoding".($header['cid']?"\nContent-ID: <".$header['cid'].">":"")."\n\n$cnt\n";
  }

  // Attachs a file (Use it for make attachment)
  // $in_letter_id: ID in body of letter or FALSE
  function attach_file($filename, $filename_in_letter, $enc_type, $in_letter_id = FALSE)
  {
    if ($filename_in_letter == '') $filename_in_letter = $filename;
    $fp = fopen($filename,"r");
    $data = fread($fp, filesize($filename));
    fclose($fp);
    $this->attach_content($filename_in_letter, $data, $enc_type, $in_letter_id);
  }

  // Prepare letter
  function prepare_letter()
  {
    $mime = '';

    // In some cases, when there is several addresses
    if (is_array($this->from_addr)) $this->from_addr = $this->from_addr[0];

    if (!empty($this->from_addr)) {
      if ($this->from_name != '') $from = $this->from_name.' <'.$this->from_addr.'>';
      else $from = $this->from_addr;
      $from = "From: $from\nReply-To: $from\n";
      $mime .= $from;
    }
    $mime .= "X-Priority: ".$this->priority."\nMIME-Version: 1.0\n";
    if (count($this->headers) > 0) {	// Multipart letter
      // Main body as part of letter
      $this->attach_content('', $this->body, $this->body_type);
      // Compiling a parts
      $boundary = 'b'.md5(uniqid(time())); // Boundary
      $multipart = "Content-Type: multipart/mixed; boundary =$boundary\n\nThis is a MIME encoded letter\n\n--$boundary";
      for ($I = sizeof($this->headers)-1; $I >= 0; $I--) {
        $multipart .= "\n".$this->build_part($this->headers[$I])."--$boundary"; // Inserting part of letter
      }
      $multipart .= "--\n";
      $mime .= $multipart;
    } elseif ($this->body != '') {	// Simple letter
      $this->body = chunk_split(base64_encode($this->body));
      $mime .= "Content-Type: ".$this->body_type."; charset=\"UTF-8\"\nContent-Transfer-Encoding: base64\n\n".$this->body;
    }

    if (!is_array($this->to)) $this->to = array($this->to);
    $this->mime = $mime;
  }

  // Sends letter
  function send_letter()
  {
    foreach ($this->to as $to) {
      @mail($to, $this->subj, "", $this->mime);
    }
  }
}
?>