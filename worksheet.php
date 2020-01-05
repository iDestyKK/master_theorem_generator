<?php
/*
 * Worksheet Generator
 *
 * Description:
 *     This page will generate "n" questions based on the seed "seed" passed in
 *     via GET.
 *
 * Author:
 *     Clara Nguyen
 */

// ----------------------------------------------------------------------------
// Get data                                                                {{{1
// ----------------------------------------------------------------------------

//Get "n"
if (!isset($_GET['n']))
	die("Fatal: Number of problems not specified.");

if (!ctype_digit($_GET['n']))
	die("Fatal: n is not an integer");

$n = (int) $_GET['n'];

if ($n < 1 || $n > 100)
	die("Fatal: n must be between 1 and 100 (inclusively).");

//Get "seed"
if (!isset($_GET['seed']))
	die("Fatal: Seed not specified.");

if (!ctype_digit($_GET['seed']))
	die("Fatal: seed is not an integer");

$seed = (int) $_GET['seed'];

// ----------------------------------------------------------------------------
// Function Generation                                                     {{{1
// ----------------------------------------------------------------------------

$form = Array(
	"plain" => Array(
		"init" => "T(n) = %dT(n / %d) + ",
		"form" => Array(
			'sqrt(%d)',
			'%d',
			'sqrt(%d) * lg(n)',
			'%d * lg(n)',
			'sqrt(n)^%d',
			'n^%d',
			'sqrt(n)^%d * lg(n)',
			'n^%d * lg(n)',
			'sqrt(n)^%d * lg(n)^%d',
			'n^%d * lg(n)^%d'
		)
	),
	"latex" => Array(
		"init" => "T(N) = %dT(n / %d) + ",
		"form" => Array(
			'\sqrt(%d)',
			'%d',
			'\sqrt(%d) \times lg(n)',
			'%d \times lg(n)',
			'\sqrt(n)^{%d}',
			'n^{%d}',
			'\sqrt(n)^{%d} \times lg(n)',
			'n^{%d} \times lg(n)',
			'\sqrt(n)^{%d} \times lg(n)^{%d}',
			'n^{%d} \times lg(n)^{%d}'
		)
	),
	"html" => Array(
		"init" => "T(N) = %dT(n / %d) + ",
		"form" => Array(
			'&radic;<span class = "radic">%d</span>',
			'%d',
			'&radic;<span class = "radic">%d</span> lg(n)',
			'%d lg(n)',
			'&radic;<span class = "radic">n</span><sup>%d</sup>',
			'n<sup>%d</sup>',
			'&radic;<span class = "radic">n</span><sup>%d</sup> lg(n)',
			'n<sup>%d</sup> lg(n)',
			'&radic;<span class = "radic">n</span><sup>%d</sup> lg(n)<sup>%d</sup>',
			'n<sup>%d</sup> lg(n)<sup>%d</sup>'
		)
	)
);

function generate_problem() {
	$a  = 2 + (rand() % 24);
	$b  = 1 + (rand() % 10);
	$f1 = 2 + (rand() % 24);
	$f2 = 2 + (rand() %  9);
	$f3 = 2 + (rand() %  9);
	$f  = rand() % 10;

	return Array(
		"a"  => $a,
		"b"  => $b,
		"f"  => $f,
		"f1" => $f1,
		"f2" => $f2,
		"f3" => $f3,
	);
}

function problem_to_string($p, $form) {
	return sprintf(
		$form["init"] . $form["form"][$p["f"]],
		$p["a" ],
		$p["b" ],
		$p["f1"],
		$p["f2"],
		$p["f3"]
	);
}

//Set the initial seed
srand($seed);
$problems = Array();

for ($i = 0; $i < $n; $i++)
	array_push($problems, generate_problem());

?>

<html>
	<head>
		<title>Generated Worksheet</title>
	</head>

	<body>
		<style type = "text/css">
			span.radic {
				border-top: 1px solid #000;
			}
		</style>

		<?php
			for ($i = 0; $i < $n; $i++) {
				echo problem_to_string($problems[$i], $form["html"]) . "<br/>";
			}
		?>
	</body>
</html>
