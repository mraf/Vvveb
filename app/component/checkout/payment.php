<?php

/**
 * Vvveb
 *
 * Copyright (C) 2022  Ziadin Givan
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 */

namespace Vvveb\Component\Checkout;

use function Vvveb\session as sess;
use Vvveb\System\Component\ComponentBase;
use Vvveb\System\Event;
use Vvveb\System\Payment as PaymentApi;

class Payment extends ComponentBase {
	public static $defaultOptions = [
		'checkout' => null,
	];

	function cacheKey() {
		//disable caching
		return false;
	}

	function results() {
		$checkoutInfo = [];

		if ($this->options['checkout']) {
			$checkoutInfo = sess('checkout', []) ?? [];
		}

		$results            = [];
		$payment            = PaymentApi::getInstance();
		$results['payment'] = $payment->getMethods($checkoutInfo);
		$results['count']   = $results['payment'] ? count($results['payment']) : 0;

		list($results) = Event :: trigger(__CLASS__,__FUNCTION__, $results);

		return $results;
	}
}
