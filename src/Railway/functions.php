<?php
/**
 *  (c) José Luis Martínez de la Riva <martinezdelariva@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE file
 *  that was distributed with this source code.
 */

declare(strict_types=1);

namespace Martinezdelariva\Railway;
use Martinezdelariva\Railway\Either\Either;

function bind(callable $switch): callable {
    return (new Bind())($switch);
};

function unite(callable $one, callable $other): callable {
    return (new Unite())($one, $other);
};

function lift(callable $oneTrack): callable {
    return (new Lift())($oneTrack);
};

function map(callable $oneTrack): callable {
    return (new Map())($oneTrack);
};

function tee(callable $callable): callable {
    return (new Tee())($callable);
};

function tryCatch(callable $oneTrack): callable {
    return (new TryCatch())($oneTrack);
};

function doubleMap(callable $oneTrackRight, callable $oneTrackLeft): callable {
    return (new DoubleMap())($oneTrackRight, $oneTrackLeft);
};

function plus(callable $addRight, callable $addLeft, callable $switch1, callable $switch2): callable {
    return (new Plus())($addRight, $addLeft, $switch1, $switch2);
};

function pipeCalc($initial, $operations) {
	$result = $initial;
	foreach ($operations as $k => $operation) {
		$result = $operation($result);
	}

	return $result;
};

function pipe(callable ...$operations): callable {
	return fn ($initial) => pipeCalc($initial, $operations);
};

/**
 * Iterate an array or other foreach-able without making a copy of it.
 *
 * @param array|\Traversable $iterable
 * @return Generator
 */
function iter_reverse($iterable) {
    for (end($iterable); ($key=key($iterable))!==null; prev($iterable)){
        yield $key => current($iterable);
    }
}

function compose(callable ...$operations): callable {
	return fn ($initial) => pipeCalc($initial, iter_reverse($operations));
};
