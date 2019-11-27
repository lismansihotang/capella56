<?php
class Formatter extends CFormatter
{
  public $numFormat = array('decimals' => 3, 'decimalSeparator' => ',', 'thousandSeparator' => '.');
  public $numberFormat = array('decimals' => 3, 'decimalSeparator' => ',', 'thousandSeparator' => '.');
  public $currencyFormat = array('decimals' => 2, 'decimalSeparator' => ',', 'thousandSeparator' => '.');
  public $USNumberFormat = array('decimals' => 4, 'decimalSeparator' => '.', 'thousandSeparator' => ',');
  public $USCurrencyFormat = array('decimals' => 2, 'decimalSeparator' => '.', 'thousandSeparator' => ',');
  public $USNumFormat = array('decimals' => 0, 'decimalSeparator' => '.', 'thousandSeparator' => ',');
  public function formatNum($value)
  {
    if ($value === null)
      return null; // new
    if ($value === '')
      return ''; // new
    return number_format($value, $this->numFormat['decimals'], $this->numFormat['decimalSeparator'], $this->numFormat['thousandSeparator']);
  }
  public function formatNumber($value)
  {
    if ($value === null)
      return null; // new
    if ($value === '')
      return ''; // new
    return number_format($value, $this->numberFormat['decimals'], $this->numberFormat['decimalSeparator'], $this->numberFormat['thousandSeparator']);
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
	public function formatNumberUS($value)
  {
    if ($value === null)
      return null; // new
    if ($value === '')
      return ''; // new
    return number_format($value, $this->USNumberFormat['decimals'], $this->USNumberFormat['decimalSeparator'], $this->USNumberFormat['thousandSeparator']);
  }
  public function formatCurrencyUS($value)
  {
    if ($value === null)
      return null; // new
    if ($value === '')
      return ''; // new
    return number_format($value, $this->USCurrencyFormat['decimals'], $this->USCurrencyFormat['decimalSeparator'], $this->USCurrencyFormat['thousandSeparator']);
  }
  public function unformatNumber($formatted_number)
  {
    if ($formatted_number === null)
      return null;
    if ($formatted_number === '')
      return '';
    if (is_float($formatted_number))
      return $formatted_number; // only 'unformat' if parameter is not float already
    
    $value = str_replace($this->numberFormat['thousandSeparator'], '', $formatted_number);
    $value = str_replace($this->numberFormat['decimalSeparator'], '.', $value);
    return (float) $value;
  }
}