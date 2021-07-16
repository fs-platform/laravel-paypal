<?php

namespace Smbear\Paypal\Enums;

class PaypalEnums
{
    const CMD = 'cmd=_notify-validate';
	
	const VALID = 'VERIFIED';
	
	const VERIFY_URI = 'https://ipnpb.paypal.com/cgi-bin/webscr';

    const SANDBOX_VERIFY_URI = 'https://ipnpb.sandbox.paypal.com/cgi-bin/webscr';
}