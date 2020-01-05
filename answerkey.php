<?php
/*
 * Answer Key Generator
 *
 * Description:
 *     This page will generate "n" questions based on the seed "seed" passed in
 *     via GET. It will also generate the solutions to the questions.
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

/*
 * generate_solution_html
 *
 * Generates an HTML-friendly version of the solution to a problem.
 * TODO: Clean this... seriously.
 */

function generate_solution_html($p, $form) {
	$case = solve_get_case($p);
	?>
	<p>
		<table class = "ans-embed" cellpadding = "8px">
			<tr>
				<td>
					a = <?php echo $p["a"]; ?><br/>
					b = <?php echo $p["b"]; ?><br/>
					f(n) = <?php echo sprintf($form["form"][$p["f"]], $p["f1"], $p["f2"], $p["f3"]); ?><br/>
				</td>
				<td>
					This is case <b><?php echo $case; ?></b>,
					as lim<sub>n -&gt; &infin;</sub> n<sup>log<sub><?php echo $p["b"]; ?></sub><?php echo $p["a"]; ?></sup> /
					<?php echo sprintf($form["form"][$p["f"]], $p["f1"], $p["f2"], $p["f3"]); ?>
					is <b><?php
						switch ($case) {
							case 1: echo "&infin;"; break;
							case 2: echo "not zero"; break;
							case 3: echo "0"; break;
						}
					?></b>.
					<br/>
					Thus, the answer is
					<?php
						switch ($case) {
							case 1:
								printf(
									"T(n) = &Theta;(n<sup>log<sub>%d</sub>%d</sup>)",
									$p["b"], $p["a"]
								);
								break;

							case 2:
								$ex = log($p["a"], $p["b"]);

								if ($ex == 0) {
									printf("T(n) = &Theta;(lg(n))");
								}
								else
								if ($ex == 1) {
									printf("T(n) = &Theta;(n lg(n))");
								}
								else {
									printf(
										"T(n) = &Theta;(n<sup>log<sub>%d</sub>%d</sup> lg(n))",
										$p["b"], $p["a"]
									);
								}
								break;

							case 3:
								printf (
									"T(n) = &Theta;(%s)",
									sprintf($form["form"][$p["f"]], $p["f1"], $p["f2"], $p["f3"])
								);
								break;
						}
					?>
				</td>
			</tr>
		</table>
	</p>
	<?php
}

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

			table.ans-embed td {
				vertical-align: top;
			}
		</style>

		<h1>Master Theorem Answer Key</h1>
		<h3><?php echo $n; ?> Problem<?php if ($n != 1) echo "s"; ?>, Seed: <?php echo $seed; ?></h3>
		<p>
			Evaluate the following recurrences.
			Show all work.
			If you run into case 3, assume the regularity condition is satisfied.
			If you want to go back to the blank worksheet, <a href = "worksheet.php?n=<?php echo $n; ?>&seed=<?php echo $seed ?>">click here</a>.
			<br/><br/>
		</p>

		<ol>
			<?php
				for ($i = 0; $i < sizeof($problems); $i++) {
					echo "<li>";
					echo problem_to_string($problems[$i], $form["html"]) . "<br/>";
					echo generate_solution_html($problems[$i], $form["html"]) . "<br/>";
					echo "</li>";
				}
			?>
		</ol>
	</body>
</html>
