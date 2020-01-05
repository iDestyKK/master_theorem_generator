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

require_once("common.php");

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

		<h1>Master Theorem Worksheet (<?php echo $n; ?> Problem<?php if ($n != 1) echo "s"; ?>, Seed: <?php echo $seed; ?>)</h1>
		<p>
			Evaluate the following recurrences.
			Show all work.
			If you run into case 3, assume the regularity condition is satisfied.
			You may evaluate your answers with the <a href = "answerkey.php?n=<?php echo $n; ?>&seed=<?php echo $seed ?>">answer key</a>.
		</p>

		<ol>
			<?php
				for ($i = 0; $i < sizeof($problems); $i++) {
					echo "<li>";
					echo problem_to_string($problems[$i], $form["html"]);
					echo "<br/><br/><br/><br/><br/><br/><br/>";
					echo "</li>";
				}
			?>
		</ol>
	</body>
</html>
