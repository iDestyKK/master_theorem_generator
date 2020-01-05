<html>
	<head>
		<title>Master Theorem Worksheet Generator</title>
	</head>

	<body>
		<h1>Master Theorem Worksheet Generator</h1>
		<p>
			Generator for practicing using the "Master Theorem" to find time
			complexities in recurrence relations. Generation is based on seed
			and the number of problems specified. Worksheets come with answer
			keys linked on the page so you can evaluate your answers.
		</p>

		<h3>Generator:</h3>
		<form action = "worksheet.php" method = "get">
			<table>
				<tr>
					<td>
						Number of Problems:
					</td>
					<td>
						<input type = "text" name = "n">
					</td>
				</tr>
				<tr>
					<td>
						Seed:
					</td>
					<td>
						<input type = "text" name = "seed">
					</td>
				</tr>
				<tr>
					<td>
						Generate:
					</td>
					<td>
						<input type = "submit" value = "Worksheet">
					</td>
				</tr>
			</table>
		</form>
	</body>
</html>
