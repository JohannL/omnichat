<?php

declare(strict_types=1);

class Template
{
	var
		$path,
		$templates;
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	function __construct($path)
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	{
		$this->path = $path;
		$this->templates = [];
	}
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	function parse_many($template, $section, $var_arrays, $common_vars = [])
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	{
		// echo '<pre>' . var_export($template, true) . '<br>';
		// echo '<pre>' . var_export($section, true) . '<br>';
		// echo '<pre>' . var_export($var_arrays, true) . '<br>';
		// echo '<pre>' . var_export($common_vars, true) . '<br>';
		$r = '';
		foreach ($var_arrays as $k => $vars)
		{
			$r .= $this->parse($template, $section, array_merge($vars, $common_vars));
		}
		return $r;
	}
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	function parse($template, $section = '', $vars = [])
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	{
		// if this the first time the template is needed, load and prepare it, and cache the results
		if (!isset($this->templates[$template]))
		{
			$path = $this->path . $template . '.html';
//*
			if (!file_exists($path))
			{
				return 'cannot find template "' . $path . '"';
			}
//*/
			$file = file_get_contents($path);
			if ($file !== false)
			{
				$i = preg_match_all('/# (.+?) #[\r\n]+(.+?)[\r\n]+# \/(.+?) #/si', $file, $matches);
				if ($i > 0)
				{
					foreach ($matches[1] as $k => $v)
					{
						$this->templates[$template][$v] = trim($matches[2][$k]);
						// remove the section from the remaining template
						$file = str_replace($matches[0][$k], '', $file);
					}
				}
				// whatever is left over is the main section
				$this->templates[$template][''] = trim($file);
			}
			else
			{
				$this->templates[$template][''] = ' ** NOT FOUND: "' . $path . '" ** ';
			}
		}
		if (!isset($this->templates[$template][$section]))
		{
			return "<span style=color:red;>could not find template section: <b>$template</b> -&gt; <b>$section</b></span>";
		}
		$r = $this->templates[$template][$section];
		$search = [];
		$replace = [];
		foreach ($vars as $k => $v)
		{
			$search[] = '{'.$k.'}';
			$replace[] = $v;
		}
		$r = str_replace($search, $replace, $r);
		return $r;
	}
}