# Recurrences Worksheet Generator

## Synopsis
This is a webpage-ish project that will generate worksheets for "Recurrence"
problems featured in UTK's COSC 581 course. These serve as additional practice.
In addition to problems being generated, answer keys will also be generated, so
students will know whether their work was correct or not. All problems
generated rely on the [Master Theorem](https://en.wikipedia.org/wiki/Master_theorem_(analysis_of_algorithms))
for an answer.

## How it works
Students can give a seed, as well as the number of problems that they want
generated. The server will generate the problems for the user. The worksheet
will be located at `worksheet.php` with appropriate GET parameters given. An
answer key can be found at `answerkey.php` given the same GET parameters.

## Find an issue?
Contact me or feel free to submit a GitHub issue! I'll fix it if you give me
a case that can be reproduced.
