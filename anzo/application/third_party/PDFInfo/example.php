<?php
include 'PDFInfo.php';

$p = new PDFInfo;
$p->load('pdf1.pdf');

echo $p->author;
echo $p->title;
echo $p->pages;