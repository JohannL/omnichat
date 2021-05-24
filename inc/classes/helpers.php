<?php

declare(strict_types=1);

class Helpers
{
	static function format_nanoseconds($nanoseconds)
	{
		return round(($nanoseconds / 1000000) * 10) / 10;
	}
	static function stopwatch_start()
	{
		return hrtime(true);
	}
	static function stopwatch_stop($stopwatch_start)
	{
		return hrtime(true) - $stopwatch_start;
	}
}