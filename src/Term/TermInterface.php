<?php
namespace PComm\WPUtils\Term;

interface TermInterface {
    public function getTermID();
    public function getTermName();
    public function getTermSlug();
    public function getTermTaxonomy();
    public function getTermDescription();
    public function getTermMeta();
    public function getTermParent();
    public function getTermOrder();
}