<?php
/**
 * @author Nikolai Kordulla
 */
class PBString extends PBScalar
{
    var $wired_type = PBMessage::WIRED_STRING;

    /**
     * Parses the message for this type
     *
     * @param array
     */
    public function ParseFromArray()
    {
        $this->value = '';
        // first byte is length
        $length = $this->reader->next();
		
		// just extract the string
		$pointer = $this->reader->get_pointer();
		$this->reader->add_pointer($length);
		$this->value = $this->reader->get_message_from($pointer);
    }

    /**
     * Serializes type
     */
   public function SerializeToString($rec = -1)
   {
        $string = '';

        if ($rec > -1)
            $string .= $this->base128->set_value($rec << 3 | $this->wired_type);
		
        // now the string
        $value = ($this->value);
        
        $add = mb_convert_encoding($this->value, "UTF-8");
        $string .= $this->base128->set_value(strlen($add));
		$string .= $add;
		
        return $string;
   }
}
?>