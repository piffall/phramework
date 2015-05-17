<?php
/**
 * This file is part of phramework.

 * Foobar is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * Foobar is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with phramework.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package Test
 * @filesource tests/test.php
 * @author Cristòfol Torrens <piffall@gmail.com>
 * @version 1
 */

require dirname(__DIR__) . '/vendor/autoload.php';

$c = new PHK\Curl();
$c->setUrl('http://google.com');
$c->get();

print_r($c->getInfo());
?>
