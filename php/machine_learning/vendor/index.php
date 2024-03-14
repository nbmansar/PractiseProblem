<?php
require 'vendor/autoload.php';
$data = new \Phpml\Dataset\CsvDataset("/mnt/d/program/php/machine_learning/vendor/data/insurance.csv",1,true);
$dataSet = new \Phpml\CrossValidation\Randomsplit($data,0.8);
/*
$dataSet->getTrainSamples();
$dataSet->getTrainLabels();
$dataSet->getTestSamples();
$dataSet->getTestLabels();

*/
$regression = new \Phpml\Regression\LeastSquares();
$regression->train($dataSet->getTrainSamples(),$dataSet->getTrainLabels());
$predict = $regression->predict($dataSet->getTestSamples());

$score = \Phpml\Metric\Regression::r2Score($dataSet->getTestLabels(),$predict);

echo "r2Score is :".$score;
