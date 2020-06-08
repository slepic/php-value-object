<?php declare(strict_types=1);

namespace Slepic\ValueObject\Standard\Email;

use Slepic\ValueObject\Strings\StringValue;

class EmailAddress extends StringValue
{
    public function __construct(string $value)
    {
        // copied from nette/utils Nette\Utils\Validators::isEmail()
        // https://github.com/nette/utils/blob/v3.1.1/src/Utils/Validators.php

        $atom = "[-a-z0-9!#$%&'*+/=?^_`{|}~]"; // RFC 5322 unquoted characters in local-part
        $alpha = "a-z\x80-\xFF"; // superset of IDN
        if (1 !== preg_match("(^
			(\"([ !#-[\\]-~]*|\\\\[ -~])+\"|$atom+(\\.$atom+)*)  # quoted or unquoted
			@
			([0-9$alpha]([-0-9$alpha]{0,61}[0-9$alpha])?\\.)+    # domain - RFC 1034
			[$alpha]([-0-9$alpha]{0,17}[$alpha])?                # top domain
		$)Dix", $value)) {
            throw EmailAddressViolation::exception();
        }
        parent::__construct($value);
    }
}
