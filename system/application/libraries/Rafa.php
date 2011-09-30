<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
RAFA (Redesign Application For Any Device)
RAFA Library written for PHP 4.x or PHP 5.x
Version 1.2: Start
Version 1.3:
	- Added Form
	- Delete Label
	- Added description, snipset, title and help on List and Form
	- Added advertise on form and label

**/
class Rafa extends XMLWriter
{
	// Constructor
    function __construct() 
    { 
        // Start by calling the XMLWriter constructor... 
        $this->openURI('php://output'); 
        $this->setIndent(4); 
         
		// Start RAFA language
        $this->startElement('rafa'); 
            $this->writeAttribute('version', '1.2'); 
    }

	/**
		Add Rafa Header
		Input:	
			UUID = Application ID
			clear = true if cache cleared false if not
		Output:	RAFA
	**/
	public function addHeading($uuid, $clear=false)
	{
        $this->startElement("head"); 
        $this->writeElement('uuid', $uuid);
		if ($clear) $this->writeElement('clear', '1');
		$this->endELement();
		$this->startElement("body");
	}
     
	/**
		Add List Primitive
		Input:
			method = Method of Application
			title(o) = Title list above position
			help(o) = Help list bellow position
			elements = Array Of
				name = Name of listitem
				value(o) = Value of listitem as input for rafa method
				type(o) = Type of listitem, currently support 'adv' means advertise
				description(o) = Detail description of listitem
				snipset(o) = Snipset on the top right of listitem
		Output: RAFA
	**/
	public function addList($method, $elements, $title="", $help="") {
		$this->startElement("rule");
		$this->writeAttribute("name", 'list');
		$this->writeElement("method", $method);
		if ($title!="") $this->writeElement("title", $title);
		if ($help!="") $this->writeElement("help", $help);
		$this->startElement("elements");
		if (is_array($elements)) {
			foreach ($elements as $element) {
				$this->startElement("item");
				if (isset($element['value'])) {
					$this->writeAttribute("value", $element['value']);
				}
				if (isset($element['type'])) {
					$this->writeAttribute("type", $element['type']);
				}
				$this->writeElement("name", $element['name']);
				if (isset($element['description'])) {
					$this->writeElement("desc", $element['description']);
				}
				if (isset($element['snipset'])) {
					$this->writeElement("snip", $element['snipset']);
				}
				if (isset($element['image'])) {
					$value = $this->base32_encode($element['image']);
					$this->writeElement("img", $value);
				}
				$this->endElement();
			}
		}
		$this->endElement();
		$this->endElement();
	}
     
	/**
		Add Form Promitive
			name = id form
			method = Method of Application
			title(o) = Title list above position
			help(o) = Help list bellow position
			elements = Array Of
				name = Name of input
				type = Type of input
					itext = Textfield
					icheck = Checkbox
					ichoie = Choicebox (just like list but local)
				itext
					value(o) = Default value
					type(o) = Type of textfield, support 'pwd' means password
					label = Label of textfield
				icheck
					value(o) = True/False default value
					label = Label of checkbox
				ichoice
					label = Label of choicebox
					items
						label = Label of choiceitem
						value = value if selected
						selected(o) = default value
		Output: RAFA
	**/
	public function addForm($name, $method, $elements, $title="", $help="") {
		$this->startElement("rule");
		$this->writeAttribute("name", 'form');
		$this->writeElement("method", $method);
		$this->writeElement("name", $name);
		if ($title!="") $this->writeElement("title", $title);
		if ($help!="") $this->writeElement("help", $help);
		$this->startElement("elements");
		foreach ($elements as $element_value) {
			$element_key = $element_value['type'];
			if ($element_key=="itext") {
				$this->startElement("itext");
				$this->writeAttribute("name", $element_value['name']);
				// Value is optional
				if (isset($element['value'])) {
					$this->writeAttribute("value", $element_value['value']);
				}
				// Type of password is optional
				if (isset($element_value['password'])) {
					$this->writeAttribute("type", "pwd");
				}
				$this->text($element_value['label']);
				$this->endElement();				
			} elseif ($element_key=="icheck") {
				$this->startElement("icheck");
				$this->writeAttribute("name", $element_value['name']);
				// Value is optional
				if (isset($element_value['value'])) {
					if ($element_value['value']) {
						$this->writeAttribute("value", "1");
					} else {
						$this->writeAttribute("value", "0");
					}
				}
				$this->text($element_value['label']);
				$this->endElement();
			} elseif ($element_key=="ichoice") {
				$this->startElement("ichoice");
				$this->writeAttribute("name", $element_value['name']);
				$this->writeAttribute("label", $element_value['label']);
				foreach ($element_value['items'] as $choice) {
					$this->startElement("item");
					$this->writeAttribute("value", $choice['value']);
					// Selected is optional
					if (isset($choice['selected'])) {
						$this->writeAttribute("type", "act");
					}
					$this->text($choice['label']);
					$this->endElement();
				}
				$this->endElement();				
			} elseif ($element_key=="adv") {
				$this->startElement("item");
				$this->writeAttribute("value", $element_value['value']);
				$this->writeAttribute("type", $element_value['type']);
				$this->writeElement("name", $element_value['name']);
				if (isset($element_value['description'])) {
					$this->writeElement("desc", $element_value['description']);
				}
				if (isset($element_value['snipset'])) {
					$this->writeElement("snip", $element_value['snipset']);
				}
				$this->endElement();
			}
		}
		$this->endElement();		
		$this->endElement();		
	}

	/**
		Add Alert Primitive
		Input:
			method = Method of application, or 'back' if alert only show information
			value = Value of method
			text = Alert information
			uuid(o) = Application ID for opening if the method aren't back
		Output: RAFA
	**/
	function addAlert($method, $value, $text, $uuid='') {
		$this->startElement("rule");
		$this->writeAttribute("name", "alert");
		$this->startElement("method");
		if (!empty($uuid)) {
			$this->writeAttribute("uuid", $uuid);
		}
		$this->text($method);
		$this->endElement();
		$this->startElement("text");
		$this->writeAttribute("value", $value);
		$this->text($text);
		$this->endElement();
	}
	
	/**
		Add Redirect Primitive
		Input:
			method = Method of application
			uuid(o) = Application ID if redirect cross application
		Output: RAFA
	**/
	function addRedirect($method, $uuid='') {
		$this->startElement("rule");
		$this->writeAttribute("name", "redirect");
		$this->startElement("method");
		if (!empty($uuid)) {
			$this->writeAttribute("uuid", $uuid);
		}
		$this->text($method);
		$this->endElement();
	}
	
	/**
		Add Rafa Footer
		Input: null
		Output: RAFA
	**/
    function endRafa() 
    { 
        // End channel 
        $this->endElement(); 
         
        // End rss 
        $this->endElement(); 
         
        $this->endDocument(); 
         
        $this->flush(); 
    }	

    /**
    * Base32 encode to a binary string
    *
    * @param    $inString   String to base32 encode
    *
    * @return   $outString  Base32 encoded $inString
    *
    * @access   private
    *
    */
    private function base32_encode($inString) 
    { 
	$outString = ""; 
	$compBits = ""; 
	$BASE32_TABLE = array( 
			      '00000' => 0x61, 
			      '00001' => 0x62, 
			      '00010' => 0x63, 
			      '00011' => 0x64, 
			      '00100' => 0x65, 
			      '00101' => 0x66, 
			      '00110' => 0x67, 
			      '00111' => 0x68, 
			      '01000' => 0x69, 
			      '01001' => 0x6a, 
			      '01010' => 0x6b, 
			      '01011' => 0x6c, 
			      '01100' => 0x6d, 
			      '01101' => 0x6e, 
			      '01110' => 0x6f, 
			      '01111' => 0x70, 
			      '10000' => 0x71, 
			      '10001' => 0x72, 
			      '10010' => 0x73, 
			      '10011' => 0x74, 
			      '10100' => 0x75, 
			      '10101' => 0x76, 
			      '10110' => 0x77, 
			      '10111' => 0x78, 
			      '11000' => 0x79, 
			      '11001' => 0x7a, 
			      '11010' => 0x32, 
			      '11011' => 0x33, 
			      '11100' => 0x34, 
			      '11101' => 0x35, 
			      '11110' => 0x36, 
			      '11111' => 0x37, 
			      ); 
	
	/* Turn the compressed string into a string that represents the bits as 0 and 1. */
	for ($i = 0; $i < strlen($inString); $i++) {
	    $compBits .= str_pad(decbin(ord(substr($inString,$i,1))), 8, '0', STR_PAD_LEFT);
	}

	/* Pad the value with enough 0's to make it a multiple of 5 */
	if((strlen($compBits) % 5) != 0) {
	    $compBits = str_pad($compBits, strlen($compBits)+(5-(strlen($compBits)%5)), '0', STR_PAD_RIGHT);
	}
	
	/* Create an array by chunking it every 5 chars */
	$fiveBitsArray = explode("\n",rtrim(chunk_split($compBits, 5, "\n"))); 
	
	/* Look-up each chunk and add it to $outstring */
	foreach($fiveBitsArray as $fiveBitsString) { 
	    $outString .= chr($BASE32_TABLE[$fiveBitsString]); 
	} 
	
	return $outString; 
    } 

}