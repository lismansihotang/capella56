<?php
class Formatter extends CFormatter
{
  public $numberFormatQty = array('decimals' => 4, 'decimalSeparator' => ',', 'thousandSeparator' => '.');
  public $numberFormat = array('decimals' => 2, 'decimalSeparator' => ',', 'thousandSeparator' => '.');
  public $currencyFormat = array('decimals' => 2, 'decimalSeparator' => ',', 'thousandSeparator' => '.');
  public function formatNumber($value)
  {
    if ($value === null)
      return null; // new
    if ($value === '')
      return ''; // new
    return number_format($value, $this->numberFormat['decimals'], $this->numberFormat['decimalSeparator'], $this->numberFormat['thousandSeparator']);
  }
  public function formatNumberQty($value)
  {
    if ($value === null)
      return null; // new
    if ($value === '')
      return ''; // new
    return number_format($value, $this->numberFormatQty['decimals'], $this->numberFormatQty['decimalSeparator'], $this->numberFormat['thousandSeparator']);
  }
	public function formatNumberWODecimal($value)
  {
    if ($value === null)
      return null; // new
    if ($value === '')
      return ''; // new
    return number_format($value, 0, $this->numberFormat['decimalSeparator'], $this->numberFormat['thousandSeparator']);
  }
  public function formatCurrency($value,$symbol='')
  {
    if ($value === null)
      return null; // new
    if ($value === '')
      return ''; // new
    if ($value < 0) {
      return '(' . number_format($value * -1, $this->currencyFormat['decimals'], $this->currencyFormat['decimalSeparator'], $this->currencyFormat['thousandSeparator']) . ')';
    } else {
      return $symbol.' '.number_format($value, $this->currencyFormat['decimals'], $this->currencyFormat['decimalSeparator'], $this->currencyFormat['thousandSeparator']);
    }
  }
  public function unformatNumber($formatted_number)
  {
    if ($formatted_number === null)
      return null;
    if ($formatted_number === '')
      return '';
    if (is_float($formatted_number))
      return $formatted_number; // only 'unformat' if parameter is not float already
    
    $value = strtr($formatted_number,array($this->numberFormat['thousandSeparator']=>''));
    $value = strtr($value,array($this->numberFormat['decimalSeparator']=>'.'));
    return (float) $value;
  }
}