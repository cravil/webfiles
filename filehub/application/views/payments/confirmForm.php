<form method="post" action="https://payeer.com/merchant/">
<input type="hidden" name="m_shop" value="<?=$m_shop?>">
<input type="hidden" name="m_orderid" value="<?=$m_orderid?>">
<input type="hidden" name="m_amount" value="<?=$m_amount?>">
<input type="hidden" name="m_curr" value="<?=$m_curr?>">
<input type="hidden" name="m_desc" value="<?=$m_desc?>">
<input type="hidden" name="m_sign" value="<?=$sign?>">
<?php /*
<input type="hidden" name="form[ps]" value="2609">
<input type="hidden" name="form[curr[2609]]" value="USD">
*/ ?>
<?php /*
<input type="hidden" name="m_params" value="<?=$m_params?>">
<input type="hidden" name="m_cipher_method" value="AES-256-CBC">
*/ ?>
<input type="submit" name="m_process" value="send" />
</form>