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
	),
	"math" => Array(
		"init" => "",
		"form" => Array(
			'sqrt(%d)',
			'%d',
			'sqrt(%d) * log(n, 2)',
			'%d * log(n, 2)',
			'pow(sqrt(n), %d)',
			'pow(n, %d)',
			'pow(sqrt(n), %d) * log(n, 2)',
			'pow(n, %d) * log(n, 2)',
			'pow(sqrt(n), %d) * pow(log(n, 2), %d)',
			'pow(n, %d) * pow(log(n, 2), %d)'
		)
	),
);

/*
 * solve_get_case
 *
 * Gets the Master Theorem case (1-3) for solving the problem. This is critical
 * in generating a solution as it determines which procedure to do to get the
 * correct answer.
 */

function solve_get_case($p, $form) {
	/*
	 * Recall forms:
	 *   0 - sqrt(%d)
	 *   1 - %d
	 *   2 - sqrt(%d) * lg(n)
	 *   3 - %d * lg(n)
	 *   4 - sqrt(n)^%d
	 *   5 - n^%d
	 *   6 - sqrt(n)^%d * lg(n)
	 *   7 - n^%d * lg(n)
	 *   8 - sqrt(n)^%d * lg(n)^%d
	 *   9 - n^%d * lg(n)^%d
	 */

	//Left-hand side exponent is n^log_b(a)
	$lhs_ex = log($p["a"], $p["b"]);

	//Right-hand side is a bit more... complicated
	$f = $p["f"];
	$is_mlog = ($f == 2 || $f == 3 || $f >= 6);

	//Get n^? exponent
	$rhs_ex = ($f <= 3)
		? 0.0
		: $p["f1"] * (($f % 2) ? 1.0 : 0.5);

	//Determine the limit (0 = zero, -1 = infinity, -2 = undefined, other = ?)
	$limit = -1.0;

	//For simplicity, we are ignoring anything that involves "lg(n)".
	if ($f == 0 || $f == 2) {
		if ($lhs_ex == 0)
			$limit = 1.0 / sqrt($p["f1"]);
		else
			$limit = -1.0; //Infinity
	}
	else
	if ($f == 1 || $f == 3) {
		if ($lhs_ex == 0)
			$limit = 1.0 / $p["f1"];
		else
			$limit = -1.0; //Infinity
	}
	else {
		//If the exponents are the same
		if ($lhs_ex == $rhs_ex) {
			if ($is_mlog)
				$limit = 0.0;
			else
				$limit = 1.0;
		}
		else
		if ($lhs_ex < $rhs_ex)
			$limit = 0.0;
		else
			$limit = -1.0;
	}

	//If zero, it's case 3 as the right side dominates
	if ($limit == 0.0)
		return 3;

	//If infinity, it's case 1 as the left side dominates
	if ($limit == -1.0)
		return 1;

	//If the limit is anything else, it's case 2.
	return 2;
}

function generate_problem() {
	if ((rand() % 4) == 0) {
		//Play a little smart... Try to force Case 2
		$f = rand() % 10;

		//Determine which Case 2 preset to force
		$c = rand() % 4;

		if ($c == 0) {
			//Same a and b. rhs = n^1
			$f = 5;
			$a = $b = 2 + (rand() % 24);
			$f1 = 1;
			$f2 = $f3 = 0;
		}
		else {
			//a = 1, b = any, rhs = constant
			$f  = 1;
			$a  = 1;
			$b  = 2 + (rand() %  9);
			$f1 = 2 + (rand() % 24);
			$f2 = $f3 = 0;
		}
	}
	else {
		//Force either Case 1 or 3.
		//There is still a slight possibility of a Case 2, btw.
		$a  = 2 + (rand() % 24);
		$b  = 2 + (rand() %  9);
		$f1 = 2 + (rand() % 24);
		$f2 = 2 + (rand() %  9);
		$f3 = 2 + (rand() %  9);
		$f  = rand() % 10;
	}

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
			for ($i = 0; $i < sizeof($problems); $i++) {
				echo problem_to_string($problems[$i], $form["html"]) . "&nbsp;&nbsp&nbsp;" . solve_get_case($problems[$i], $form["math"]) . "<br/>";
			}
		?>
	</body>
</html>
